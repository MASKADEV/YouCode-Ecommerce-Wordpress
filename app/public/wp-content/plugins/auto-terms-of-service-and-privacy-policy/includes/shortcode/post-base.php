<?php

namespace wpautoterms\shortcode;

use wpautoterms\cpt\CPT;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Post_Base extends Sub_Shortcode {

	public function handle( $values, $content ) {
		$ex_values = Shortcode::expand_keys( $values );
		$k = array_keys( $ex_values );

		if ( ! empty( $k ) && isset( $ex_values[ $k[0] ] ) ) {
			$args = array(
				'name' => $ex_values[ $k[0] ],
				'post_status' => 'publish',
				'numberposts' => 1,
				'post_type' => CPT::type()
			);
			$posts = get_posts( $args );
			if ( ! empty( $posts ) ) {
				return $this->handle_post( $posts[0], $values, $content );
			}
		}
		global $wpautoterms_post;
		if ( ! empty( $wpautoterms_post ) ) {
			return $this->handle_post( $wpautoterms_post, $values, $content );
		}
		global $post;
		if ( empty( $post ) ) {
			return '';
		}

		return $this->handle_post( $post, $values, $content );
	}

	/**
	 * @param \WP_Post $post
	 * @param array $values
	 * @param $content
	 *
	 * @return string
	 */
	abstract protected function handle_post( $post, $values, $content );
}
