<?php

namespace wpautoterms\frontend;

use wpautoterms\admin\Options;
use wpautoterms\cpt\CPT;

class Pages_Widget_Extend {
	const MARKER = 'wpautoterms_pages_widget_extend_marker';

	public function __construct() {
		add_filter( 'widget_pages_args', array( $this, 'widget_pages_args' ), 10 );
		add_filter( 'wp_list_pages', array( $this, 'wp_list_pages' ), 10, 2 );
	}

	public function widget_pages_args( $args ) {
		$args[ static::MARKER ] = true;

		return $args;
	}

	public function wp_list_pages( $output, $r ) {
		if ( ! isset( $r[ static::MARKER ] ) || ! Options::get_option( Options::SHOW_IN_PAGES_WIDGET ) ) {
			return $output;
		}
		$args = $r;
		unset( $args[ static::MARKER ] );
		$args['post_type'] = CPT::type();
		$output .= wp_list_pages( $args );

		return $output;
	}
}
