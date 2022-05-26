<?php

namespace wpautoterms;

use wpautoterms\admin\Menu;
use wpautoterms\admin\page\Legacy_Settings;
use wpautoterms\cpt\CPT;

abstract class Legacy_Shortcodes {
	public static function my_terms_of_service_and_privacy_policy() {
		$ret = static::_prologue( 'my_terms_of_service_and_privacy_policy' );
		if ( ! static::_should_show() ) {
			return $ret . static::_coming_soon( __( 'Terms and Privacy Policy are coming soon.', WPAUTOTERMS_SLUG ) );
		}

		return $ret . \wpautoterms\print_template( 'legacy/tos-and-pp', static::_get_vars(), true );
	}

	protected static function _get_vars() {
		$args = Legacy_Settings::all_options();
		foreach ( $args as $k => $v ) {
			$args[ $k ] = get_option( WPAUTOTERMS_OPTION_PREFIX . $k );
		}

		return $args;
	}

	protected static function _should_show() {
		$options = Legacy_Settings::all_options();
		if ( Legacy_Settings::VALUE_OFF == get_option( WPAUTOTERMS_OPTION_PREFIX . Legacy_Settings::ON_OFF,
				Legacy_Settings::VALUE_OFF ) ) {
			return false;
		}
		unset( $options[ Legacy_Settings::ON_OFF ] );
		unset( $options[ Legacy_Settings::DMCA_NOTICE_URL ] );
		foreach ( array_keys( $options ) as $k ) {
			$v = get_option( WPAUTOTERMS_OPTION_PREFIX . $k, false );
			if ( empty( $v ) ) {
				return false;
			}
		}

		return true;
	}

	protected static function _coming_soon( $text ) {
		$args = array( 'text' => $text );
		if ( current_user_can( 'edit_plugins' ) ) {
			$args['settings_url'] =
				admin_url( 'edit.php?post_type=' . CPT::type() . '&page=' . WPAUTOTERMS_SLUG . '_' . Menu::PAGE_LEGACY_SETTINGS );

			return \wpautoterms\print_template( 'legacy/coming-soon-admin', $args, true );
		}

		return \wpautoterms\print_template( 'legacy/coming-soon', $args, true );
	}

	public static function _prologue( $shortcode ) {
		return \wpautoterms\print_template( 'legacy/shortcode-prologue', array( 'shortcode' => $shortcode ), true );
	}

	public static function my_terms_of_service() {
		$ret = static::_prologue( 'my_terms_of_service' );
		if ( ! static::_should_show() ) {
			return $ret . static::_coming_soon( __( 'Terms are coming soon.', WPAUTOTERMS_SLUG ) );
		}

		return $ret . \wpautoterms\print_template( 'legacy/terms-of-service', static::_get_vars(), true );
	}


	public static function my_privacy_policy() {
		$ret = static::_prologue( 'my_privacy_policy' );
		if ( ! static::_should_show() ) {
			return $ret . static::_coming_soon( __( 'Privacy Policy is coming soon.', WPAUTOTERMS_SLUG ) );
		}

		return $ret . \wpautoterms\print_template( 'legacy/privacy-policy', static::_get_vars(), true );
	}

	public static function init() {
		add_shortcode( 'my_terms_of_service_and_privacy_policy', array(
			__CLASS__,
			'my_terms_of_service_and_privacy_policy'
		) );
		add_shortcode( 'my_terms_of_service', array( __CLASS__, 'my_terms_of_service' ) );
		add_shortcode( 'my_privacy_policy', array( __CLASS__, 'my_privacy_policy' ) );
	}
}