<?php

namespace wpautoterms\admin\action;


use wpautoterms\Action_Base;
use wpautoterms\admin\Notices;

class Dismiss_Notice extends Action_Base {
	const DISMISSED_ACTION_SUFFIX = '_dismissed_admin_notice';
	/**
	 * @var Notices
	 */
	protected $_notices;

	public function set_notices( $notices ) {
		$this->_notices = $notices;
	}

	protected function _handle( $admin_post ) {
		if ( $admin_post ) {
			wp_die( 'Not supported.' );
		}
		if ( isset( $_REQUEST['c'] ) && isset( $_REQUEST['id'] ) ) {
			$class = sanitize_html_class( $_REQUEST['c'] );
			$id = sanitize_key( isset( $_REQUEST['id'] ) );
			$success = true;
			$removed = $this->_notices->delete_persistent( $class, $id );
			do_action( WPAUTOTERMS_SLUG . static::DISMISSED_ACTION_SUFFIX, $class, $id, $removed );
		} else {
			$success = false;
			$removed = false;
		}
		wp_send_json( array( 'success' => $success, 'removed' => $removed ) );
	}
}
