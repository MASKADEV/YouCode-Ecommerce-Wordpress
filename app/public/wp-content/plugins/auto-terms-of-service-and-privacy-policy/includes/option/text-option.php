<?php

namespace wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Text_Option extends Option {
	const TYPE_GENERIC = 'text-option';
	const TYPE_TEXTAREA = 'textarea-option';

	protected static function _default_template() {
		return static::TYPE_GENERIC;
	}

	public function sanitize( $input ) {
		return trim( strval( $input ) );
	}
}
