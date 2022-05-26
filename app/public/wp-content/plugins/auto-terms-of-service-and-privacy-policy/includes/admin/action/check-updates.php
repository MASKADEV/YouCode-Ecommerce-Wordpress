<?php

namespace wpautoterms\admin\action;

use wpautoterms\Action_Base;
use wpautoterms\Updated_Posts;

class Check_Updates extends Action_Base {
	public $message;
	public $message_multiple;
	public $cookie_prefix;
	public $duration;

	protected function _handle( $admin_post ) {
		if ( $admin_post ) {
			wp_die( 'Not supported.' );
		}
		$posts = new Updated_Posts( $this->duration, $this->cookie_prefix, $this->message, $this->message_multiple );
		$posts->fetch_posts();
		$ret = array( 'data' => $posts->transform() );
		wp_send_json( $ret );
	}
}
