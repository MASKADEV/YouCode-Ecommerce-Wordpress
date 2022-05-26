<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Last_Updated extends Post_Base {

	protected function handle_post( $post, $values, $content ) {
		return esc_html( get_post_modified_time( get_option( 'date_format' ), false, $post, true ) );
	}
}
