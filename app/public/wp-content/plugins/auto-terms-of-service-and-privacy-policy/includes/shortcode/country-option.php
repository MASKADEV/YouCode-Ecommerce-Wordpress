<?php

namespace wpautoterms\shortcode;

use wpautoterms\Countries;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Country_Option extends Option {
	const TYPE_COUNTRY = 'country';
	const TYPE_STATE = 'state';

	public function __construct( $options, $option_name = false, $name = false ) {
		if ( ! in_array( $option_name, array( static::TYPE_COUNTRY, static::TYPE_STATE ) ) ) {
			throw new \RuntimeException( 'Option name should be one of the declared types (TYPE_xxx)' );
		}
		parent::__construct( $options, $option_name, $name );
	}

	public function handle( $values, $content ) {
		$name = $this->_name;
		$value = call_user_func( $this->_options, $name );
		$locale = Countries::DEFAULT_LOCALE;
		$value = Countries::translate( $value, $locale );

		return esc_html( $value );
	}
}
