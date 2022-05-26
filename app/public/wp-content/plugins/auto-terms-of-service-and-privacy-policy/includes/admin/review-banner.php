<?php

namespace wpautoterms\admin;

use wpautoterms\admin\action\Set_Option;
use wpautoterms\cpt\CPT;

class Review_Banner {
	const ACTION_ID = 'review_banner_disable';

	/**
	 * @var Set_Option
	 */
	public $action;

	public function __construct() {
		if ( Options::get_option( static::ACTION_ID, true ) ) {
			return;
		}
		add_action( 'admin_notices', array( $this, 'print_notice' ) );
		$this->action = new Set_Option( CPT::edit_cap(), static::ACTION_ID );
		$this->action->set_option_name( static::ACTION_ID );
	}

	public function print_notice() {
		if ( ! isset ( $_REQUEST['post_type'] ) || $_REQUEST['post_type'] !== CPT::type() ) {
			return;
		}
		$c = (array) wp_count_posts( CPT::type() );
		$c = array_sum( array_values( $c ) );
		if ( $c < 1 ) {
			return;
		}
		\wpautoterms\print_template( "review-banner", array( 'action_id' => static::ACTION_ID ) );
	}
}
