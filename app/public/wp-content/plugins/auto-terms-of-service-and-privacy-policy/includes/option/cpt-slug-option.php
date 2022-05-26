<?php

namespace wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPT_Slug_Option extends Text_Option {
	protected $_fail_handler;

	public function set_fallback( $fail_handler = null ) {
		$this->_fail_handler = $fail_handler;
	}

	public function sanitize( $input ) {
		$value = sanitize_title( parent::sanitize( $input ) );
		if ( empty( $value ) ) {
			$value = call_user_func( $this->_fail_handler, $input );
		}

		return $value;
	}
}
