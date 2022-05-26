<?php

namespace wpautoterms;

use wpautoterms\admin\form\Legal_Page;
use wpautoterms\admin\form\Licensed_Legal_Page;
use wpautoterms\admin\Notices;
use wpautoterms\admin\Options;
use wpautoterms\cpt\CPT;
use wpautoterms\frontend\Widget;
use wpautoterms\legal_pages;

abstract class Wpautoterms {
	protected static $_legal_pages;

	public static function init( $license, $query ) {
		static::$_legal_pages = array();
		foreach ( legal_pages\Conf::get_legal_pages() as $page ) {
			if ( $page->is_paid ) {
				$c = '\wpautoterms\admin\form\Licensed_Legal_Page';
			} else {
				$c = '\wpautoterms\admin\form\Legal_Page';
			}
			$p = new $c( $page->id, $page->title, $page->description, $page->page_title );
			if ( $page->is_paid ) {
				/**
				 * @var $p Licensed_Legal_Page
				 */
				$p->set_params( $license, $query );
			}
			static::$_legal_pages[ $page->id ] = $p;
		}
		add_action( 'init', array( __CLASS__, 'action_init' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, '_enqueue_scripts' ), 1 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, '_enqueue_scripts' ), 1 );
		add_action( 'plugins_loaded', array( __CLASS__, 'init_translations' ), 5 );
		CPT::init();
		Shortcodes::init();
		Legacy_Shortcodes::init();
		Widget::init();
		Notices::$instance = new Notices( WPAUTOTERMS_OPTION_PREFIX . 'notices' );
	}

	public static function _enqueue_scripts() {
		wp_enqueue_script( WPAUTOTERMS_JS_BASE, WPAUTOTERMS_PLUGIN_URL . 'js/base.js', array(
			'jquery',
			'wp-dom-ready'
		), WPAUTOTERMS_VERSION, false );
	}

	/**
	 * @return Legal_Page[]
	 */
	public static function get_legal_pages() {
		return static::$_legal_pages;
	}

	/**
	 * @param $id string
	 *
	 * @return Legal_Page|false
	 */
	public static function get_legal_page( $id ) {
		if ( ! isset( static::$_legal_pages[ $id ] ) ) {
			return false;
		}

		return static::$_legal_pages[ $id ];
	}

	public static function init_translations() {
		load_plugin_textdomain( WPAUTOTERMS_SLUG,
			false,
			WPAUTOTERMS_PLUGIN_DIR . 'languages/' );
	}

	public static function action_init() {
		CPT::register( Options::get_option( Options::LEGAL_PAGES_SLUG ) );
		do_action( WPAUTOTERMS_SLUG . '_registered_cpt' );
	}
}
