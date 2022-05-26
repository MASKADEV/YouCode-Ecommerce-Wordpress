<?php

namespace wpautoterms\admin\action;

use wpautoterms\Action_Base;
use wpautoterms\api\License;

class Toggle_Action extends Action_Base {
	protected $_option_name;
	/**
	 * @var License|boolean
	 */
	protected $_license = false;

	public function set_license( License $license ) {
		$this->_license = $license;
	}

	public function set_option_name( $name ) {
		$this->_option_name = $name;
	}

	protected function _handle( $admin_post ) {
		$option = ! (bool) get_option( $this->_option_name, false );
		if ( ! empty( $this->_license ) ) {
			if ( !$this->_license->is_paid() ) {
				$option = false;
			}
		}
		update_option( $this->_option_name, $option );
		wp_send_json( array(
				'enabled' => $option ? 1 : 0
			)
		);
	}
}