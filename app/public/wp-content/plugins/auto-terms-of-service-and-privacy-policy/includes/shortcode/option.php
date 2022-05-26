<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Option extends Sub_Shortcode {

	protected $_options;
	protected $_option_name;

	public function __construct(
		$options,
		$option_name = false,
		$name = false
	) {
		$this->_options = $options;
		$this->_option_name = $option_name;
		if ( $name === false ) {
			$name = $this->_option_name;
		}
		parent::__construct( $name );
	}

	protected function _get_name() {
		if ( $this->_option_name === false ) {
			return reset( $values );
		}

		return $this->_option_name;
	}

	public function handle( $values, $content ) {
		$name = $this->name();

		return esc_html( call_user_func( $this->_options, $name ) );
	}
}
