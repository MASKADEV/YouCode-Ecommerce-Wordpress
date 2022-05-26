<?php

namespace wpautoterms;

class Action_Base {
	const NAME = '';

	protected $_capability;
	protected $_name;
	protected $_args;
	protected $_admin_post;
	/**
	 * @var callable|string|null
	 */
	protected $_fail_handler;
	/**
	 * @var callable|null
	 */
	protected $_handler;

	protected static $_actions = array();
	protected $_skip_nonce;

	public function __construct(
		$capability, $name = '', $handler = null, $args = null, $fail_handler = null, $admin_post = false,
		$nopriv = false, $skip_nonce = false
	) {
		$this->_name = empty( $name ) ? static::NAME : $name;
		$this->_args = $args;
		$this->_handler = $handler;
		$this->_fail_handler = $fail_handler;
		$this->_capability = $capability;
		$this->_skip_nonce = $skip_nonce;
		if ( $admin_post ) {
			add_action( 'admin_post_' . $this->name(), array( $this, 'handle_post' ) );
		}
		add_action( 'wp_ajax_' . $this->name(), array( $this, 'handle' ) );
		if ( $nopriv ) {
			add_action( 'wp_ajax_nopriv_' . $this->name(), array( $this, 'handle_nopriv' ) );
		}
		static::$_actions[] = $this;
	}

	public static function actions() {
		return self::$_actions;
	}

	public function name() {
		return $this->_name;
	}

	public function capability() {
		return $this->_capability;
	}

	public function nonce() {
		return wp_create_nonce( $this->name() );
	}

	protected function _fail() {
		if ( $this->_fail_handler ) {
			if ( is_callable( $this->_fail_handler ) ) {
				$fn = $this->_fail_handler;
				$fn( $this->_args );

				return;
			}
			$msg = $this->_fail_handler;
		} else {
			$msg = '';
		}
		wp_die( $msg );
	}

	protected function _handle( $admin_post ) {
		if ( $this->_handler !== false ) {
			$fn = $this->_handler;
			$fn( $admin_post, $this->_args );
		}
	}

	public function handle_post() {
		$this->handle( true );
	}

	public function handle( $admin_post = false ) {
		$cap = $this->capability();
		if ( ! empty( $cap ) && ! current_user_can( $cap ) ) {
			$this->_fail();
		}
		$fn = $admin_post ? 'check_admin_referer' : 'check_ajax_referer';
		if ( ! $this->_skip_nonce && ! $fn( $this->name(), 'nonce', false ) ) {
			$this->_fail();
		}
		$this->_handle( $admin_post );
	}

	public function handle_nopriv() {
		$cap = $this->capability();
		if ( ! empty( $cap ) && ! current_user_can( $cap ) ) {
			$this->_fail();
		}
		if ( ! $this->_skip_nonce && ! check_ajax_referer( $this->name(), 'nonce', false ) ) {
			$this->_fail();
		}
		$this->_handle( false );
	}

	protected static function _request_var( $name ) {
		if ( isset( $_REQUEST[ $name ] ) ) {
			return wp_slash( $_REQUEST[ $name ] );
		}

		return false;
	}
}
