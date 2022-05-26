<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Link extends Post_Data {

	public function __construct( $name ) {
		parent::__construct( $name, 'ID' );
	}

	protected function handle_post( $post, $values, $content ) {
		$res = parent::handle_post( $post, $values, $content );
		if ( empty( $res ) ) {
			return $res;
		}

		return esc_url( get_post_permalink( $res ) );
	}

}
