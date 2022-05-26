<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Sub_Shortcode implements I_Shortcode_Handler {
	protected $_name;

	public function __construct( $name ) {
		$this->_name = $name;
	}

	public function name() {
		return $this->_name;
	}

	abstract public function handle( $values, $content );
}
