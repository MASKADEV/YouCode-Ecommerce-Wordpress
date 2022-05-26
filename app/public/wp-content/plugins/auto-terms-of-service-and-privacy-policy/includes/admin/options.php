<?php

namespace wpautoterms\admin;

class Options {
	const SITE_NAME = 'site_name';
	const SITE_URL = 'site_url';
	const COMPANY_NAME = 'company_name';
	const COUNTRY = 'country';
	const STATE = 'state';
	const LEGAL_PAGES_SLUG = 'legal_pages_slug';
	const SHOW_IN_PAGES_WIDGET = 'show_in_pages_widget';
	const CACHE_PLUGINS_COMPAT = 'cache_plugins_compat';
	const OB_NOT_INTERCEPTED = 'ob_not_intercepted';
	const OB_TOTAL = 'ob_total';
	const CACHE_PLUGINS_DETECTED = 'cache_plugins_detected';
	const CACHE_PLUGINS_DETECTION = 'cache_plugins_detection';
	const CACHE_PLUGINS_SUPPRESS_WARNING = 'cache_plugins_suppress_warning';
	const LINKS_ORDER = 'links_order';
	protected static $_defaults;

	public static function all_options() {
		return array(
			static::SITE_NAME,
			static::SITE_URL,
			static::COMPANY_NAME,
			static::COUNTRY,
			static::STATE,
			static::LEGAL_PAGES_SLUG,
			static::SHOW_IN_PAGES_WIDGET,
		);
	}

	public static function default_value( $name ) {
		if ( static::$_defaults === null ) {
			$blogname = get_option( 'blogname' );
			static::$_defaults = array(
				Options::SITE_NAME => $blogname,
				Options::SITE_URL => get_option( 'siteurl' ),
				Options::COMPANY_NAME => $blogname,
				Options::COUNTRY => '',
				Options::STATE => '',
				Options::LEGAL_PAGES_SLUG => 'wpautoterms',
				Options::SHOW_IN_PAGES_WIDGET => false,
				Options::CACHE_PLUGINS_COMPAT => false,
				Options::OB_NOT_INTERCEPTED => 0,
				Options::OB_TOTAL => 0,
				Options::CACHE_PLUGINS_DETECTION => true,
				Options::CACHE_PLUGINS_DETECTED => false,
				Options::CACHE_PLUGINS_SUPPRESS_WARNING => false,
				Options::LINKS_ORDER => '',
			);
		}

		return static::$_defaults[ $name ];
	}

	public static function get_option( $name, $no_default = false ) {
		$default = $no_default ? null : static::default_value( $name );

		return get_option( WPAUTOTERMS_OPTION_PREFIX . $name, $default );
	}

	public static function set_option( $name, $value, $autoload = null ) {
		return update_option( WPAUTOTERMS_OPTION_PREFIX . $name, $value, $autoload );
	}
}
