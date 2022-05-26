<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Content extends Post_Base {

	protected static $_lock = false;

	protected function handle_post( $post, $values, $content ) {
		if ( static::$_lock ) {
			return '';
		}
		if ( post_password_required( $post ) ) {
			return __( 'Password required.', WPAUTOTERMS_SLUG );
		}
		static::$_lock = true;
		$ret = do_shortcode( apply_filters( 'the_content', $post->post_content ) );

		static::$_lock = false;

		return $ret;
	}
}
