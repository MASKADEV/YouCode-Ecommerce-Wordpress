<?php

namespace wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Choices_Combo_Option extends Choices_Option {

	const TYPE_SELECT = 'select-combo-option';

	function sanitize( $input ) {
		if ( ! isset( $this->_values[ $input ] ) ) {
			return strip_tags( strval( $input ) );
		}

		return $input;
	}
}
