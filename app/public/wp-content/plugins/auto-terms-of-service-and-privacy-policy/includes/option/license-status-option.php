<?php

namespace wpautoterms\option;

use wpautoterms\api\License;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class License_Status_Option extends Option {
	const TYPE_GENERIC = 'license-status-option';
	/**
	 * @var License
	 */
	protected $_license;

	public function set_license( $license ) {
		$this->_license = $license;
	}

	protected function _template_args() {
		return array_merge( parent::_template_args(), array(
			'status' => $this->_license->status(),
			'error_message' => $this->_license->error_message()
		) );
	}

	protected static function _default_template() {
		return static::TYPE_GENERIC;
	}

	public function sanitize( $input ) {
		return $input;
	}

	protected function _register() {
	}
}
