<?php

namespace wpautoterms\box;

use wpautoterms\api\License;
use wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Licensed_Box extends Box {
	/**
	 * @var License
	 */
	protected $_license;

	public function set_license( License $license ) {
		$this->_license = $license;
		$this->_action->set_license( $this->_license );
	}

	protected function _box_args() {
		$arr = parent::_box_args();
		$arr['license_paid'] = $this->_license->is_paid();
		if ( ! $arr['license_paid'] ) {
			$arr['status_text'] = __( 'Disabled', WPAUTOTERMS_SLUG );
			$arr['enabled'] = false;
		}

		return $arr;
	}

	public function define_options( $page_id, $section_id ) {
		$attrs = array();
		if ( ! $this->_license->is_paid() ) {
			$attrs['disabled'] = null;
			$tooltip = __( '<a href="' . esc_url( WPAUTOTERMS_PURCHASE_URL ) . '" target="wpautotermsGetLicense">
Purchase license</a> to enable',
				WPAUTOTERMS_SLUG );
		} else {
			$tooltip = '';
		}
		$a = new option\Checkbox_Option_Ex( $this->id(), __( 'Enabled', WPAUTOTERMS_SLUG ), $tooltip, $page_id,
			$section_id, false, $attrs );
		$a->set_license( $this->_license );
	}

	public function defaults() {
		return array(
			$this->id() => false,
		);
	}
}
