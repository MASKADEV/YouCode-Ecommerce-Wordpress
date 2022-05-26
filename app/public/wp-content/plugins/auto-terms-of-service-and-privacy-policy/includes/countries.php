<?php

namespace wpautoterms;

use wpautoterms\admin\Options;

include_once WPAUTOTERMS_PLUGIN_DIR . 'data/countries.php';

abstract class Countries {
	const LOCALE_PATH = 'js/data/translations/%s/strings.js';
	const LOCALE_PATH_PHP = 'data/translations/%s/countries.php';

	const DEFAULT_LOCALE = 'en';

	protected static $_countries;
	protected static $_translations = array();

	public static function select_locale( $template, $locale = null ) {
		if ( $locale === null ) {
			$locale = get_locale();
		}
		$lang = explode( '_', $locale );
		$lang = $lang[0];
		$locales = array( $locale, static::DEFAULT_LOCALE );
		if ( $lang !== $locale ) {
			array_splice( $locales, 1, 0, $lang );
		}
		$template = WPAUTOTERMS_PLUGIN_DIR . $template;
		$files = array_map( function ( $x ) use ( $template ) {
			return sprintf( $template, $x );
		}, $locales );

		$file = Util::first_existing( $files );
		if ( $file === false ) {
			return false;
		}

		return array( $file, $locales[ array_search( $file, $files ) ] );
	}

	protected static function _init( $locale = null ) {
		if ( $locale !== null ) {
			if ( ! isset( static::$_translations[ $locale ] ) ) {
				$trans_info = static::select_locale( static::LOCALE_PATH_PHP, $locale );
				if ( $trans_info !== false ) {
					include_once $trans_info[0];
					$fn = 'wpautoterms_country_translations_' . strtolower( $trans_info[1] );
					static::$_translations[ $locale ] = $fn();
				}
			}
		}
		if ( ! empty( static::$_countries ) ) {
			return;
		}
		static::$_countries = wpautoterms_countries();
	}

	public static function get() {
		static::_init();

		return static::$_countries;
	}

	public static function exists( $country ) {
		static::_init();

		return in_array( strtoupper( $country ), static::$_countries );
	}

	public static function translations( $locale = null ) {
		if ( $locale === null ) {
			$locale = get_locale();
		}
		static::_init( $locale );
		if ( isset( static::$_translations[ $locale ] ) ) {
			return static::$_translations[ $locale ];
		}

		return array();
	}

	public static function translate( $country_code, $locale = null ) {
		$t = static::translations( $locale );
		if ( isset( $t[ $country_code ] ) ) {
			return $t[ $country_code ];
		}

		return $country_code;
	}

	public static function enqueue_scripts() {
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_countries', WPAUTOTERMS_PLUGIN_URL . 'js/countries.js',
			array( 'underscore', 'wp-util', WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
		$ret = static::select_locale( static::LOCALE_PATH );
		if ( $ret !== false ) {
			$lang = $ret[1];
			$locale = $ret[0];
			$locale = WPAUTOTERMS_PLUGIN_URL . substr( $locale, strlen( WPAUTOTERMS_PLUGIN_DIR ) );
			wp_enqueue_script( WPAUTOTERMS_SLUG . '_countries_locale', $locale, array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
		} else {
			$lang = static::DEFAULT_LOCALE;
		}
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_states_js', WPAUTOTERMS_PLUGIN_URL . 'js/data/states.js',
			array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );

		wp_localize_script( WPAUTOTERMS_SLUG . '_countries', 'wpautotermsCountry', array(
			'country' => Options::get_option( Options::COUNTRY ),
			'state' => Options::get_option( Options::STATE ),
			'locale' => $lang
		) );
	}

}
