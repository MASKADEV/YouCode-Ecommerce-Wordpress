<?php

namespace wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Color_Option extends Option {
	const TYPE_GENERIC = 'color-option';

	protected static function _default_template() {
		return static::TYPE_GENERIC;
	}

	function sanitize( $input ) {
		if ( ! preg_match( '/(\#[\dabcdef]{1,6})/i', $input, $matches ) ) {
			return '';
		}

		return $matches[0];
	}
}
