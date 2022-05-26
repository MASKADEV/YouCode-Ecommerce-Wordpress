<?php

namespace wpautoterms;

use wpautoterms\admin\Menu;
use wpautoterms\admin\Notices;
use wpautoterms\admin\Options;
use wpautoterms\cpt\CPT;
use wpautoterms\frontend\Container_Constants;
use wpautoterms\frontend\Endorsements;
use wpautoterms\frontend\Links;
use wpautoterms\frontend\notice\Cookies_Notice;
use wpautoterms\frontend\notice\Update_Notice;
use wpautoterms\frontend\Pages_Widget_Extend;

abstract class Frontend {
	const OB_TEST_TOTAL = 5;
	const OB_PASSED_LIMIT = 0.2;
	const CACHE_PLUGIN_NOTICE_ID = 'cache_plugin_usage';
	protected static $_body_top = '';
	/**
	 * @var Links
	 */
	protected static $_links;
	protected static $_body_applied = false;
	protected static $_compat;

	public static function init( $license ) {
		global $pagenow;
		if ( in_array( $pagenow, array( 'wp-login.php', 'wp-register.php' ) ) ) {
			return;
		}
		static::$_compat = Options::get_option( Options::CACHE_PLUGINS_COMPAT );
		if ( ! static::$_compat ) {
			// NOTE: modify buffer on teardown.
			$detection_mode = ! Options::get_option( Options::CACHE_PLUGINS_SUPPRESS_WARNING ) &&
			                  Options::get_option( Options::CACHE_PLUGINS_DETECTION );
			if ( $detection_mode ) {
				add_action( 'init', array( __CLASS__, '_init' ) );
				$body_handler = array( __CLASS__, '_out_head_supported' );
			} else {
				$body_handler = array( __CLASS__, '_out_head' );
			}
			add_filter( 'wp_cache_ob_callback_filter', $body_handler );
			add_filter( 'cache_enabler_before_store', $body_handler );
			ob_start( array( __CLASS__, '_out_head' ) );
		}
		add_action( 'wp', array( __CLASS__, 'action_wp' ), 20 );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( __CLASS__, 'footer' ), 100002 );
		$a = Update_Notice::create();
		$a->init();
		$a = Cookies_Notice::create( $license );
		$a->init();
		new Endorsements( $license );
		static::$_links = new Links();
		new Pages_Widget_Extend();
	}

	public static function _init() {
		if ( ! \is_user_logged_in() ) {
			$total = Options::get_option( Options::OB_TOTAL );
			if ( $total > static::OB_TEST_TOTAL ) {
				$passed = Options::get_option( Options::OB_NOT_INTERCEPTED );
				Options::set_option( Options::OB_NOT_INTERCEPTED, 0 );
				Options::set_option( Options::OB_TOTAL, 1 );
				if ( $passed > $total * static::OB_PASSED_LIMIT ) {
					Options::set_option( Options::CACHE_PLUGINS_DETECTION, false );
					Options::set_option( Options::CACHE_PLUGINS_DETECTED, true );
//					Notices::$instance->add(
//						__( 'Cache plugins detected. Please review ' .
//						    '<a href="' . admin_url( 'edit.php?post_type=' . CPT::type() . '&page=' . WPAUTOTERMS_SLUG .
//						                             '_' . Menu::PAGE_SETTINGS_ADVANCED ) .
//						    '">WPAutoTerms settings</a> and enable the Caching Plugins compatibility mode.',
//							WPAUTOTERMS_SLUG ), Notices::CLASS_ERROR, true, static::CACHE_PLUGIN_NOTICE_ID );
				}
			} elseif ( static::_is_html_content() ) {
				Options::set_option( Options::OB_TOTAL, $total + 1 );
			}
		}
	}

	public static function action_wp() {
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			static::$_compat = true;
		}
		if ( ! static::$_compat ) {
			static::$_body_top = static::top_container( true );
		}
	}

	public static function enqueue_scripts() {
		wp_register_style( WPAUTOTERMS_SLUG . '_css', WPAUTOTERMS_PLUGIN_URL . 'css/wpautoterms.css', WPAUTOTERMS_VERSION );
		wp_enqueue_style( WPAUTOTERMS_SLUG . '_css' );
	}

	protected static function _is_html_content() {
		$ct = 'content-type';
		$ct_len = strlen( $ct );
		foreach ( headers_list() as $h ) {
			$h = ltrim( $h );
			if ( strncasecmp( $h, $ct, $ct_len ) === 0 ) {
				if ( 1 !== preg_match( '/^content-type:\s*(text\/html|application\/xhtml\+xml)([^[:alnum:]]+.*|)$/i', $h ) ) {
					return false;
				}
			}
		}

		return true;
	}

	public static function _out_head_supported( $buf ) {
		Options::set_option( Options::CACHE_PLUGINS_DETECTION, false );
		Options::set_option( Options::CACHE_PLUGINS_DETECTED, false );
		Options::set_option( Options::OB_NOT_INTERCEPTED, 0 );
		Options::set_option( Options::OB_TOTAL, 0 );

		return static::_out_head( $buf );
	}

	public static function _out_head( $buf ) {
		if ( ! static::_is_html_content() ) {
			return $buf;
		}
		if ( function_exists( '\is_user_logged_in' ) && ! \is_user_logged_in() &&
		     ! Options::get_option( Options::CACHE_PLUGINS_SUPPRESS_WARNING ) &&
		     Options::get_option( Options::CACHE_PLUGINS_DETECTION ) ) {
			Options::set_option( Options::OB_NOT_INTERCEPTED, Options::get_option( Options::OB_NOT_INTERCEPTED ) + 1 );
		}
		if ( static::$_body_applied ) {
			return $buf;
		}
		static::$_body_applied = true;
		$m = array();
		preg_match( '/(.*<\s*body[^>]*>)(.*)/is', $buf, $m );
		$ret = '';
		if ( count( $m ) < 3 ) {
			// NOTE: HTML is not well formed, we can only detect a closing body
			$ret .= $buf;
			$ret .= static::$_body_top;
		} else {
			$ret .= $m[1];
			$ret .= static::$_body_top;
			$ret .= $m[2];
		}

		return $ret;
	}

	public static function footer() {
		static::$_links->links_box();
		static::bottom_container();
	}

	public static function container_id(
		$where = Container_Constants::LOCATION_TOP,
		$type = Container_Constants::TYPE_STATIC
	) {
		return 'wpautoterms-' . $where . '-' . $type . '-container';
	}

	protected static function container( $where, $type, $return = false ) {
		ob_start();
		do_action( WPAUTOTERMS_SLUG . '_container', $where, $type );
		$c = ob_get_contents();
		ob_end_clean();
		if ( ! empty( $c ) ) {
			$c = '<div id="' . static::container_id( $where, $type ) . '">' . $c . '</div>';
		}
		if ( $return ) {
			return $c;
		}
		echo $c;

		return '';
	}

	protected static function top_container( $return = false ) {
		$c = static::container( Container_Constants::LOCATION_TOP, Container_Constants::TYPE_STATIC, $return );
		$c .= static::container( Container_Constants::LOCATION_TOP, Container_Constants::TYPE_FIXED, $return );
		if ( $return ) {
			return $c;
		}
		echo $c;

		return '';
	}

	protected static function bottom_container() {
		if ( static::$_compat ) {
			self::top_container();
		}
		static::container( Container_Constants::LOCATION_BOTTOM, Container_Constants::TYPE_FIXED );
		static::container( Container_Constants::LOCATION_BOTTOM, Container_Constants::TYPE_STATIC );
	}
}
