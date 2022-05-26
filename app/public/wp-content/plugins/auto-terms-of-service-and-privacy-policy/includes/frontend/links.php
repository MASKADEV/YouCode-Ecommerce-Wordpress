<?php

namespace wpautoterms\frontend;

use wpautoterms\admin\Options;
use wpautoterms\cpt\CPT;
use wpautoterms\gen_css\Attr;
use wpautoterms\gen_css\Document;
use wpautoterms\gen_css\Record;

class Links {
	const MODULE_ID = 'links';
	const FOOTER_CLASS = 'wpautoterms-footer';
	const SEPARATOR_CLASS = 'separator';

	static $_posts;

	public function __construct() {
		add_action( 'wp_print_styles', array( $this, 'print_styles' ) );
	}

	protected static function _option_prefix() {
		return WPAUTOTERMS_OPTION_PREFIX . static::MODULE_ID;
	}

	public static function links_order() {
		$order = explode( ',', Options::get_option( Options::LINKS_ORDER ) );

		return array_map( 'intval', $order );
	}

	public static function link_posts() {
		if ( static::$_posts == null ) {
			$wp_type = CPT::type();
			$args = array(
				'post_type' => $wp_type,
				'post_status' => 'publish',
				'numberposts' => - 1,
				'suppress_filters' => false
			);

			$posts = get_posts( $args );
			// Filter out by post type, category page adds "post" in filter.
			$posts = array_filter( $posts, function ( \WP_Post $x ) use ( $wp_type ) {
				return $x->post_type == $wp_type;
			} );
			$posts = array_reduce( $posts, function ( $acc, \WP_Post $x ) {
				$acc[ $x->ID ] = $x;

				return $acc;
			}, array() );
			static::$_posts = array();
			$order = static::links_order();
			if ( ! empty( $order ) ) {
				foreach ( $order as $id ) {
					if ( isset( $posts[ $id ] ) ) {
						static::$_posts[] = $posts[ $id ];
						unset( $posts[ $id ] );
					}
				}
			}
			static::$_posts = array_merge( static::$_posts, array_values( $posts ) );
		}

		return static::$_posts;
	}

	public function links_box() {
		if ( ! get_option( WPAUTOTERMS_OPTION_PREFIX . static::MODULE_ID ) ) {
			return;
		}
		$posts = static::link_posts();
		$new_page = $custom = get_option( static::_option_prefix() . '_target_blank' );
		\wpautoterms\print_template( static::MODULE_ID, compact( 'posts', 'new_page' ) );
	}

	public function print_styles() {
		$option_prefix = static::_option_prefix();
		if ( ! get_option( $option_prefix ) ) {
			return;
		}

		$d = new Document( array(
			new Record( '.' . static::FOOTER_CLASS, array(
				new Attr( $option_prefix, Attr::TYPE_BG_COLOR ),
				new Attr( $option_prefix, Attr::TYPE_TEXT_ALIGN ),
			) ),
			new Record( '.' . static::FOOTER_CLASS . ' a', array(
				new Attr( $option_prefix, Attr::TYPE_LINKS_COLOR ),
				new Attr( $option_prefix, Attr::TYPE_FONT ),
				new Attr( $option_prefix, Attr::TYPE_FONT_SIZE ),
			) ),
			new Record( '.' . static::FOOTER_CLASS . ' .' . static::SEPARATOR_CLASS, array(
				new Attr( $option_prefix, Attr::TYPE_TEXT_COLOR ),
				new Attr( $option_prefix, Attr::TYPE_FONT ),
				new Attr( $option_prefix, Attr::TYPE_FONT_SIZE ),
			) ),
		) );
		$text = $d->text();
		$custom = get_option( $option_prefix . '_custom_css' );
		if ( ! empty( $custom ) ) {
			$text .= "\n" . strip_tags( $custom );
		}
		echo Document::style( $text ) . "\n";
	}

}