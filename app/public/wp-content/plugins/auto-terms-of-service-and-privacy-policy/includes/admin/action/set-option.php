<?php

namespace wpautoterms\admin\action;


use wpautoterms\Action_Base;

class Set_Option extends Action_Base {

	protected $_option_name;

	public function set_option_name( $option_name ) {
		$this->_option_name = $option_name;
	}

	protected function _handle( $admin_post ) {
		if ( $admin_post ) {
			wp_die( 'Not supported.' );
		}
		if ( isset( $_REQUEST['state'] ) ) {
			$state = wp_slash( $_REQUEST['state'] );
			update_option( WPAUTOTERMS_OPTION_PREFIX . $this->_option_name, $state );
		} else {
			$state = get_option( WPAUTOTERMS_OPTION_PREFIX . $this->_option_name );
		}
		wp_send_json( array( 'state' => $state, ) );
	}
}
