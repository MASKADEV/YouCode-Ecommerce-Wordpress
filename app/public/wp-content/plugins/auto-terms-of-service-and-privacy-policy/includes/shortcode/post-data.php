<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Data extends Post_Base {
	protected $_data;

	public function __construct( $name, $data ) {
		parent::__construct( $name );
		$this->_data = $data;
	}

	protected function handle_post( $post, $values, $content ) {
		$name = $this->_data;
		if ( ! isset( $post->$name ) ) {
			return '';
		}

		return do_shortcode( $post->$name );
	}

}
