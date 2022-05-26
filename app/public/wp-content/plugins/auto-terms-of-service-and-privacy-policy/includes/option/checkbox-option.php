<?php

namespace wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Checkbox_Option extends Option {
	const TYPE_GENERIC = 'checkbox-option';

	protected static function _default_template() {
		return static::TYPE_GENERIC;
	}

	public function sanitize( $input ) {
		return (bool) $input;
	}
}