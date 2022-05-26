<?php

namespace wpautoterms\admin\action;


use wpautoterms\Action_Base;
use wpautoterms\api\License_Transfer;
use wpautoterms\api\Query;

class Transfer_License extends Action_Base {
	const NAME = 'wpautoterms_transfer_license';
	/**
	 * @var Query
	 */
	protected $_query;

	public function set_query( Query $query ) {
		$this->_query = $query;
	}

	protected function _handle( $admin_post ) {
		if ( $admin_post ) {
			wp_die( 'Not supported.' );
		}
		$site = static::_request_var( 'site' );
		$email = static::_request_var( 'email' );
		$key = static::_request_var( 'key' );
		$lt = new License_Transfer( $this->_query );
		$res = $lt->execute( $site, $email, $key );
		wp_send_json( array( 'result' => $res ) );
	}
}
