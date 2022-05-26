<?php

namespace wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hidden_Option extends Option {
	const TYPE_GENERIC = 'hidden-option';

	/**
	 * @var callable
	 */
	public $custom_sanitize;

	protected static function _default_template() {
		return static::TYPE_GENERIC;
	}

	public function sanitize( $input ) {
		if ( $this->custom_sanitize != null ) {
			return call_user_func( $this->custom_sanitize, $input );
		}

		return $input;
	}
}
