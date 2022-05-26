<?php

namespace wpautoterms;

use wpautoterms\cpt\CPT;

class Updated_Posts {
	protected $_duration;
	protected $_posts;
	protected $_cookie_prefix;
	protected $_message;
	protected $_message_multiple;

	public function __construct( $duration, $cookie_prefix, $message, $message_multiple ) {
		$this->_duration = $duration;
		$this->_cookie_prefix = $cookie_prefix;
		$this->_message = $message;
		$this->_message_multiple = $message_multiple;
	}

	public function fetch_posts() {
		$args = array(
			'post_type' => CPT::type(),
			'post_status' => 'publish',
			'orderby' => 'post_modified',
			'date_query' => array(
				'column' => 'post_modified',
				'after' => '-' . $this->_duration . ' days',
			),
		);

		$posts = get_posts( $args );
		$this->_posts = array();
		if ( count( $posts ) ) {
			foreach ( $posts as $post ) {
				if ( $post->post_modified == $post->post_date ) {
					continue;
				}
				$t = get_post_modified_time( get_option( 'date_format' ), false, $post, true );
				if ( ! isset( $this->_posts[ $t ] ) ) {
					$this->_posts[ $t ] = array();
				}
				$this->_posts[ $t ][] = $post;
			}
		}
	}

	public function transform() {
		$post_data = array();
		if ( empty( $this->_posts ) ) {
			return $post_data;
		}
		global $wpautoterms_post;
		global $wpautoterms_posts;
		foreach ( $this->_posts as $posts ) {
			$wpautoterms_posts = array();
			$wpautoterms_post = null;
			$cookies = array();
			$values = array();
			foreach ( $posts as $post ) {
				$cookie_name = $this->_cookie_prefix . $post->ID;
				$modified = strtotime( $post->post_modified );
				if ( ! isset( $_COOKIE[ $cookie_name ] ) || $_COOKIE[ $cookie_name ] != $modified ) {
					if ( $wpautoterms_post == null ) {
						$wpautoterms_post = $post;
					}
					$wpautoterms_posts[] = $post;
					$cookies[] = $cookie_name;
					$values[] = $modified;
				}
			}
			if ( count( $wpautoterms_posts ) > 0 ) {
				$post_data[] = array(
					'message' => count( $wpautoterms_posts ) > 1 ? do_shortcode( $this->_message_multiple ) :
						do_shortcode( $this->_message ),
					'cookies' => $cookies,
					'values' => $values
				);
			}
		}

		return $post_data;
	}

	public function posts() {
		$this->_posts;
	}

}