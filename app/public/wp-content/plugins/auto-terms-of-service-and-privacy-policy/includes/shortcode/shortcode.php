<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shortcode implements I_Shortcode_Handler {
	protected $_name;
	/**
	 * @var I_Shortcode_Handler[]
	 */
	protected $_subs = array();
	/**
	 * @var false|I_Shortcode_Handler
	 */
	protected $_default_handler;

	public function __construct( $name, $default_handler = false ) {
		$this->_name = $name;
		$this->_default_handler = $default_handler;
		add_shortcode( $this->name(), array( $this, 'handle' ) );
	}

	public static function expand_keys( $a ) {
		$keys = array_keys( $a );
		$ret = array();
		foreach ( $keys as $k ) {
			if ( is_int( $k ) ) {
				$ret[ $a[ $k ] ] = $a[ $k ];
			} else {
				$ret[ $k ] = $a[ $k ];
			}
		}

		return $ret;
	}

	public function handle( $values, $content ) {
		$k = array_keys( static::expand_keys( $values ) );
		if ( isset( $k[0] ) && isset( $this->_subs[ $k[0] ] ) ) {
			return $this->_subs[ $k[0] ]->handle( $values, $content );
		}
		if ( $this->_default_handler ) {
			return $this->_default_handler->handle( $values, $content );
		}

		return '';
	}

	public function add_subshortcode( Sub_Shortcode $sub ) {
		$this->_subs[ $sub->name() ] = $sub;
	}

	public function remove_subshortcode( Sub_Shortcode $sub ) {
		unset( $this->_subs[ $sub->name() ] );
	}

	public function name() {
		return $this->_name;
	}
}
