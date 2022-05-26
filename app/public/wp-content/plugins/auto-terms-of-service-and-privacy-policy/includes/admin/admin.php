<?php

namespace wpautoterms\admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use wpautoterms\Action_Base;
use wpautoterms\admin\action\Check_Updates;
use wpautoterms\admin\action\Dismiss_Notice;
use wpautoterms\admin\action\Recheck_License;
use wpautoterms\admin\action\Set_Option;
use wpautoterms\admin\action\Transfer_License;
use wpautoterms\admin\form\Legal_Page;
use wpautoterms\api\License;
use wpautoterms\api\Query;
use wpautoterms\Countries;
use wpautoterms\cpt\Admin_Columns;
use wpautoterms\cpt\CPT;
use wpautoterms\frontend\notice\Update_Notice;
use wpautoterms\Upgrade;
use wpautoterms\Wpautoterms;

define( 'WPAUTOTERMS_API_KEY_HEADER', 'X-WpAutoTerms-ApiKey' );

abstract class Admin {
	/**
	 * @var  License
	 */
	protected static $_license;
	/**
	 * @var Query
	 */
	protected static $_query;
	/**
	 * @var Set_Option
	 */
	protected static $_warning_action;
	/**
	 * @var Review_Banner
	 */
	protected static $_review_banner;

	public static function init( License $license, Query $query ) {
		static::$_license = $license;
		static::$_query = $query;
		add_action( 'init', array( __CLASS__, 'action_init' ) );
		new Slug_Helper();
		new Upgrade();
		static::$_review_banner = new Review_Banner();
	}

	public static function action_init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ), 100 );
		add_filter( 'post_row_actions', array( __CLASS__, 'row_actions' ), 10, 2 );
		add_filter( 'pre_update_option', array( __CLASS__, 'fix_update' ), 10, 3 );
		add_action( 'edit_form_top', array( __CLASS__, 'edit_form_top' ) );
		add_filter( 'get_pages', array( __CLASS__, 'update_wp_builtin_pp' ), 10, 2 );
		add_action( 'activated_plugin', array( __CLASS__, 'on_activated_plugin' ), 10, 2 );
		add_action( WPAUTOTERMS_SLUG . Dismiss_Notice::DISMISSED_ACTION_SUFFIX, array(
			__CLASS__,
			'_on_dismiss_notice'
		), 10, 3 );

		$recheck_action = new Recheck_License( CPT::edit_cap(), '', null, null, __( 'Access denied', WPAUTOTERMS_SLUG ) );
		$recheck_action->set_license( static::$_license );

		$transfer_action = new Transfer_License( CPT::edit_cap(), '', null, null, __( 'Access denied', WPAUTOTERMS_SLUG ) );
		$transfer_action->set_query( static::$_query );

		// TODO: extract warnings class
		static::$_warning_action = new Set_Option( CPT::edit_cap(), 'settings_warning_disable' );
		static::$_warning_action->set_option_name( 'settings_warning_disable' );

		$cu = new Check_Updates( '', WPAUTOTERMS_SLUG . Update_Notice::ACTION_NAME, null, null,
			__( 'Updated posts error', WPAUTOTERMS_SLUG ), false, true, true );
		$cu->duration = intval( get_option( WPAUTOTERMS_OPTION_PREFIX . Update_Notice::ID . '_duration' ) );
		$cu->message_multiple = get_option( WPAUTOTERMS_OPTION_PREFIX . Update_Notice::ID . '_message_multiple' );
		$cu->message = get_option( WPAUTOTERMS_OPTION_PREFIX . Update_Notice::ID . '_message' );
		$cu->cookie_prefix = Update_Notice::COOKIE_PREFIX;

		Admin_Columns::init();
		Menu::init( static::$_license );
		static::$_license->check();
	}

	public static function _on_dismiss_notice( $class, $id, $success ) {
		Options::set_option( Options::CACHE_PLUGINS_SUPPRESS_WARNING, true );
	}

	public static function on_activated_plugin( $plugin, $network_wide ) {
		Options::set_option( Options::CACHE_PLUGINS_DETECTION, true );
		Options::set_option( Options::CACHE_PLUGINS_DETECTED, false );
		Options::set_option( Options::OB_TOTAL, 0 );
		Options::set_option( Options::OB_NOT_INTERCEPTED, 0 );
	}

	public static function update_wp_builtin_pp( $pages, $r ) {
		$res = isset( $r['name'] ) && in_array( $r['name'], array(
				'wp_page_for_privacy_policy',
				'page_for_privacy_policy',
				'woocommerce_terms_page_id'
			) );
		if ( ! $res && function_exists( 'wc_get_page_id' ) && isset( $r['exclude'] ) ) {
			$cmp = array(
				wc_get_page_id( 'cart' ),
				wc_get_page_id( 'checkout' ),
				wc_get_page_id( 'myaccount' ),
			);
			$res = $cmp === $r['exclude'];
		}
		if ( ! $res ) {
			return $pages;
		}
		$r['post_type'] = CPT::type();
		$r['name'] = WPAUTOTERMS_SLUG . '_page_for_privacy_policy';
		unset( $r['exclude'] );
		$autoterms_pages = get_pages( $r );

		return array_merge( $pages, $autoterms_pages );
	}

	public static function edit_form_top( $post ) {
		if ( $post->post_type != CPT::type() ) {
			return;
		}

		if ( $post->post_status == 'auto-draft' ) {
			$page_id = isset( $_REQUEST['page_name'] ) ? sanitize_text_field( $_REQUEST['page_name'] ) : '';
			$page = false;
			if ( $page_id !== 'custom' ) {
				if ( ! empty( $page_id ) ) {
					$page = Wpautoterms::get_legal_page( $page_id );
					if ( $page->availability() !== true ) {
						$page = false;
					}
				}
				if ( $page === false ) {
					global $wpdb;
					$cpt = CPT::type();
					$cases = array();
					foreach ( Wpautoterms::get_legal_pages() as $page ) {
						$id = $page->id();
						$cases[] = "SUM(CASE WHEN $wpdb->posts.post_name LIKE '$id%' THEN 1 ELSE 0 END) as '$id'";
					}
					$cases = join( ',', $cases );
					$query = "SELECT $cases FROM $wpdb->posts WHERE ($wpdb->posts.post_type = '$cpt' AND $wpdb->posts.post_status<>'trash')";
					$pages_by_type = $wpdb->get_results( $query, ARRAY_A );
					$pages_by_type = $pages_by_type[0];
					\wpautoterms\print_template( 'auto-draft', compact( 'pages_by_type' ) );
				} else {
					\wpautoterms\print_template( 'auto-draft-page', compact( 'page' ) );
				}
			}
		}
	}

	public static function fix_update( $value, $name, $old_value ) {
		if ( $name !== WPAUTOTERMS_OPTION_PREFIX . Options::LEGAL_PAGES_SLUG ) {
			return $value;
		}

		return static::$_license->is_paid() ? $value : Options::default_value( Options::LEGAL_PAGES_SLUG );
	}

	public static function row_actions( $actions, $post ) {
		if ( ( CPT::type() == get_post_type( $post ) ) && ( $post->post_status == 'publish' ) ) {
			$link = get_post_permalink( $post->ID );
			$short_link = preg_replace( '/https?:\/\//i', '', trim( $link, '/' ) );
			$info = '<a href="' . $link . '">' . $short_link . '</a>';
			array_unshift( $actions, '<div class="inline-row-action-summary">' . $info . '</div>' );
		}

		return $actions;
	}

	public static function enqueue_scripts( $page ) {
		if ( ! isset ( $_REQUEST['post_type'] ) || $_REQUEST['post_type'] !== CPT::type() ) {
			return;
		}
		global $post;
		if ( ! empty( $post ) ) {
			// NOTE: load media scripts in case 3-rd party plugin fails to enqueue them properly.
			$scripts = wp_scripts();
			if ( ! empty( $scripts->queue ) ) {
				$cmp = 'media-';
				$cmp_len = strlen( $cmp );
				foreach ( $scripts->queue as $item ) {
					if ( strncasecmp( $item, $cmp, $cmp_len ) ) {
						wp_enqueue_media();
						break;
					}
				}
			}
			if ( $page == 'edit.php' ) {
				wp_enqueue_script( WPAUTOTERMS_SLUG . '_row_actions', WPAUTOTERMS_PLUGIN_URL . 'js/row-actions.js',
					array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
			}
			if ( $page == 'post-new.php' && $post->post_status == 'auto-draft' ) {
				wp_enqueue_script( WPAUTOTERMS_SLUG . '_post_new', WPAUTOTERMS_PLUGIN_URL . 'js/post-new.js',
					array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
				$hidden = array();
				$dependencies = array();
				/**
				 * @var $v Legal_Page
				 */
				foreach ( Wpautoterms::get_legal_pages() as $v ) {
					$hidden[ $v->id() ] = $v->hidden();
					$dependencies[ $v->id() ] = $v->dependencies();
				}
				$page_id = isset( $_REQUEST['page_name'] ) ? sanitize_text_field( $_REQUEST['page_name'] ) : '';
				wp_localize_script( WPAUTOTERMS_SLUG . '_post_new', 'wpautotermsPostNew', array(
					'hidden' => $hidden,
					'dependencies' => $dependencies,
					'page_id' => $page_id
				) );
				wp_register_style( WPAUTOTERMS_SLUG . '_post_new_css', WPAUTOTERMS_PLUGIN_URL . 'css/post-new.css',
					WPAUTOTERMS_VERSION );
				wp_enqueue_style( WPAUTOTERMS_SLUG . '_post_new_css', array(), WPAUTOTERMS_VERSION );
			}

		}
		wp_register_style( WPAUTOTERMS_SLUG . '_admin_css', WPAUTOTERMS_PLUGIN_URL . 'css/admin.css', WPAUTOTERMS_VERSION );
		wp_enqueue_style( WPAUTOTERMS_SLUG . '_admin_css', array(), WPAUTOTERMS_VERSION );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_common', WPAUTOTERMS_PLUGIN_URL . 'js/common.js', array( WPAUTOTERMS_JS_BASE ),
			WPAUTOTERMS_VERSION, true );
		$nonce = array();
		/**
		 * @var Action_Base $action
		 */
		foreach ( Action_Base::actions() as $action ) {
			$nonce[ $action->name() ] = $action->nonce();
		}
		wp_localize_script( WPAUTOTERMS_SLUG . '_common', 'wpautotermsCommon', array(
			'nonce' => $nonce,
		) );
		$prefix = WPAUTOTERMS_SLUG . '_';
		if ( strncmp( $page, $prefix, strlen( $prefix ) ) === 0 ) {
			Countries::enqueue_scripts();
			wp_enqueue_script( WPAUTOTERMS_SLUG . '_admin', WPAUTOTERMS_PLUGIN_URL . 'js/kits.js', array( WPAUTOTERMS_JS_BASE ),
				WPAUTOTERMS_VERSION, true );
		}
	}
}
