<?php

namespace wpautoterms\option;

use wpautoterms\api\License;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Checkbox_Option_Ex extends Checkbox_Option {
	/**
	 * @var License
	 */
	protected $_license;

	public function set_license( $license ) {
		$this->_license = $license;
	}

	public function sanitize( $input ) {
		if ( ! $this->_license->is_paid() ) {
			return false;
		}

		return (bool) $input ;
	}

	public function get_value() {
		if ( !$this->_license->is_paid() ) {
			return false;
		}

		return parent::get_value();
	}
}