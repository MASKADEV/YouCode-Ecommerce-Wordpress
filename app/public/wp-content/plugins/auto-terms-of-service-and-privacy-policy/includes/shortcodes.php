<?php

namespace wpautoterms;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use wpautoterms\admin\Options;
use wpautoterms\shortcode\Country_Option;

abstract class Shortcodes {

	/**
	 * @var shortcode\Shortcode
	 */
	protected static $_root;

	public static function init() {
		$option_fn = array( __CLASS__, 'get_option' );
		static::$_root = new shortcode\Shortcode( WPAUTOTERMS_SLUG );
		static::$_root->add_subshortcode( new shortcode\Last_Updated( 'last_updated_date' ) );
		static::$_root->add_subshortcode( new shortcode\Post_Data( 'page_title', 'post_title' ) );
		static::$_root->add_subshortcode( new shortcode\Post_Content( 'page' ) );
		static::$_root->add_subshortcode( new shortcode\Post_Link( 'page_link' ) );
		static::$_root->add_subshortcode( new shortcode\Post_Titles( 'page_titles' ) );
		static::$_root->add_subshortcode( new shortcode\Post_Links( 'page_links' ) );
		static::$_root->add_subshortcode( new shortcode\Option( $option_fn, Options::COMPANY_NAME ) );
		static::$_root->add_subshortcode( new shortcode\Option( $option_fn, Options::SITE_NAME ) );
		static::$_root->add_subshortcode( new shortcode\Option( $option_fn, Options::SITE_URL ) );
		static::$_root->add_subshortcode( new Country_Option( $option_fn, Country_Option::TYPE_STATE ) );
		static::$_root->add_subshortcode( new Country_Option( $option_fn, Country_Option::TYPE_COUNTRY ) );
	}

	public static function get_option( $name ) {
		return Options::get_option( $name );
	}
}
