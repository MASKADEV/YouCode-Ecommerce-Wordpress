<?php

namespace wpautoterms\frontend\notice;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use wpautoterms\frontend\Styles;

abstract class Base_Notice {
	protected $_where;
	protected $_type;
	protected $_message;
	protected $_close_message;
	protected $_id;
	protected $_tag;
	protected $_container;
	protected $_element;

	public function __construct( $id, $container_class, $element_class ) {
		$this->_id        = $id;
		$this->_tag       = str_replace( '_', '-', $this->_id );
		$this->_container = $container_class;
		$this->_element   = $element_class;
	}

	public static function is_amp() {
		return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
	}

	public function init() {
		if ( ! $this->_is_enabled() ) {
			return;
		}
		add_action( 'wp_print_styles', array( $this, 'print_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( WPAUTOTERMS_SLUG . '_container', array( $this, 'container' ), 10, 2 );

		$this->_type          = get_option( WPAUTOTERMS_OPTION_PREFIX . $this->_id . '_bar_type' );
		$this->_where         = get_option( WPAUTOTERMS_OPTION_PREFIX . $this->_id . '_bar_position' );
		$this->_message       = get_option( WPAUTOTERMS_OPTION_PREFIX . $this->_id . '_message' );
		$this->_close_message = get_option( WPAUTOTERMS_OPTION_PREFIX . $this->_id . '_close_message',
			__( 'Close', WPAUTOTERMS_SLUG ) );
	}

	public function id() {
		return $this->_id;
	}

	protected function _is_enabled() {
		return get_option( WPAUTOTERMS_OPTION_PREFIX . $this->_id );
	}

	public function enqueue_scripts() {
		if ( static::is_amp() ) {
			return;
		}
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_js_' . $this->id(), WPAUTOTERMS_PLUGIN_URL . 'js/wpautoterms.js',
			array( 'jquery', 'wp-util', WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
		wp_localize_script( WPAUTOTERMS_SLUG . '_js_' . $this->id(), 'wpautoterms_js_' . $this->id(),
			$this->_localize_args() );
	}

	protected function _localize_args() {
		return array(
			'disable' => $this->_is_disabled_logged()
		);
	}

	protected function _is_disabled_logged() {
		$disable_logged = get_option( WPAUTOTERMS_OPTION_PREFIX . $this->_id . '_disable_logged' );

		return \is_user_logged_in() && $disable_logged == 'yes';
	}

	public function print_styles() {
		if ( static::is_amp() ) {
			return;
		}
		Styles::print_styles( $this->_id, $this->_element );
	}

	public function container( $where, $type ) {
		if ( ! static::is_amp() && ( $this->_where == $where ) && ( $this->_type == $type ) ) {
			$this->_print_box();
		}
	}

	abstract protected function _print_box();
}
