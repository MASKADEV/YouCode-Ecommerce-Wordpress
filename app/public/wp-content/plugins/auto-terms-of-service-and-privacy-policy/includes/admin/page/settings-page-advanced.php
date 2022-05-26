<?php

namespace wpautoterms\admin\page;

use wpautoterms\admin\Notices;
use wpautoterms\admin\Options;
use wpautoterms\cpt\CPT;
use wpautoterms\Frontend;
use wpautoterms\option\Checkbox_Option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings_Page_Advanced extends Settings_Base {
	public function define_options() {
		parent::define_options();
		$cache_reminder = __( 'Clean/delete cache after enabling or disabling this option.', WPAUTOTERMS_SLUG );
		$s = array(
			__( 'Enable this option only if you experience problems with the notification banners. Most popular caching plugins should handle the notification banners properly with this option disabled.',
				WPAUTOTERMS_SLUG )
		);
		if ( Options::get_option( Options::CACHE_PLUGINS_DETECTED ) ) {
			$s[] = __( '<strong>Unsupported caching plugins detected. We recommend enabling this option.</strong>' );
		}
		$s[] = $cache_reminder;
		if ( count( $s ) == 2 ) {
			$cache_hint_format = _x( '%s<br/>%s', 'Cache hint format', WPAUTOTERMS_SLUG );
		} else {
			$cache_hint_format = _x( '%s<br/>%s<br/>%s', 'Cache hint format', WPAUTOTERMS_SLUG );
		}
		array_unshift( $s, $cache_hint_format );
		$compat_hint = call_user_func_array( 'sprintf', $s );
		new Checkbox_Option( Options::CACHE_PLUGINS_COMPAT, __( 'Caching plugins compatibility mode', WPAUTOTERMS_SLUG ),
			$compat_hint, $this->id(), static::SECTION_ID );
	}

	public function render() {
		parent::render();
		Notices::$instance->delete_persistent( Notices::CLASS_ERROR, Frontend::CACHE_PLUGIN_NOTICE_ID );
		if ( Options::get_option( Options::CACHE_PLUGINS_COMPAT ) ) {
			Options::set_option( Options::CACHE_PLUGINS_DETECTION, false );
		}
	}

	public function defaults() {
		return array_reduce( array(
			Options::CACHE_PLUGINS_COMPAT
		), function ( $acc, $x ) {
			$acc[ $x ] = Options::default_value( $x );

			return $acc;
		}, array() );
	}

	public function register_menu() {
		add_submenu_page( false,
			$this->title(),
			false,
			CPT::edit_cap(),
			$this->id(),
			array( $this, 'render' )
		);
	}
}
