<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Titles extends Sub_Shortcode {

	public function handle( $values, $content ) {
		global $wpautoterms_posts;
		if ( empty( $wpautoterms_posts ) ) {
			return '';
		}

		$titles = array();
		foreach ( $wpautoterms_posts as $post ) {
			$titles[] = esc_html( $post->post_title );
		}

		return join( ', ', $titles );
	}

}
