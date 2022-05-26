<?php

namespace wpautoterms\frontend;

use wpautoterms\api\License;

class Endorsements {
	const ID = 'endorsements';
	/**
	 * @var License
	 */
	protected $_license;

	public function __construct( License $license ) {
		$this->_license = $license;
		add_action( 'the_content', array( $this, 'append_disclaimer' ) );
	}

	public function append_disclaimer( $content ) {
		if ( ! is_singular( 'post' ) ) {
			return $content;
		}
		$post = get_post();
		if ( empty( $post ) ) {
			return $content;
		}
		if ( ! get_option( WPAUTOTERMS_OPTION_PREFIX . static::ID ) || !$this->_license->is_paid() ) {
			return $content;
		}
		$when = get_option( WPAUTOTERMS_OPTION_PREFIX . static::ID . '_when' );
		if ( $when == 'never' ) {
			return $content;
		}
		$where = get_option( WPAUTOTERMS_OPTION_PREFIX . static::ID . '_where' );
		if ( $when == 'if_tag' ) {
			$tag = get_option( WPAUTOTERMS_OPTION_PREFIX . static::ID . '_tag' );
			$tags = wp_get_object_terms( $post->ID, 'post_tag' );
			$tags = array_map( function ( $x ) {
				return $x->term_id;
			}, $tags );
			if ( ! in_array( $tag, $tags ) ) {
				return $content;
			}
		}
		$message = get_option( WPAUTOTERMS_OPTION_PREFIX . static::ID . '_message' );
		$message = \wpautoterms\print_template( static::ID, array(
			'message' => do_shortcode( $message )
		), true );
		$message = Styles::print_styles( static::ID, static::css_class_id(), true ) . $message;
		if ( $where == Container_Constants::LOCATION_BOTTOM ) {
			return $content . $message;
		}

		return $message . $content;
	}

	public static function css_class_id() {
		return 'wpautoterms-' . static::ID;
	}
}