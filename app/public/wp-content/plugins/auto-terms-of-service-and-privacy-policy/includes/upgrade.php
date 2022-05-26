<?php

namespace wpautoterms;

use wpautoterms\admin\Menu;
use wpautoterms\admin\Options;
use wpautoterms\admin\page\Legacy_Settings;
use wpautoterms\admin\page\Settings_Base;
use wpautoterms\cpt\CPT;

class Upgrade {
	protected $_activate = false;
	protected $_update_legacy = false;

	public function __construct() {
		add_action( 'init', array( $this, 'run' ), 0 );
	}

	public function run() {
		if ( ! get_option( WPAUTOTERMS_OPTION_PREFIX . WPAUTOTERMS_OPTION_ACTIVATED ) ) {
			flush_rewrite_rules();
			update_option( WPAUTOTERMS_OPTION_PREFIX . WPAUTOTERMS_OPTION_ACTIVATED, true );
			CPT::register_roles();
			$activated = true;
			$this->_activate = true;
		} else {
			$activated = false;
		}
		$version = get_option( WPAUTOTERMS_OPTION_PREFIX . Menu::VERSION, false );
		if ( $version !== WPAUTOTERMS_VERSION ) {
			if ( $version === false ) {
				$this->_update_legacy = true;
			}
			$this->_add_slug_option();
			if ( ! $activated ) {
				CPT::register_roles();
			}
			update_option( WPAUTOTERMS_OPTION_PREFIX . Menu::VERSION, WPAUTOTERMS_VERSION );
		}
		if ( did_action( 'admin_init' ) ) {
			$this->run_translated();
		} else {
			add_action( 'admin_init', array( $this, 'run_translated' ) );
		}
	}

	public function run_translated() {
		if ( $this->_activate ) {
			/**
			 * @var $page \wpautoterms\admin\page\Base
			 */
			foreach ( Menu::$pages as $page ) {
				if ( $page instanceof Settings_Base ) {
					$d = $page->defaults();
					if ( ! empty( $d ) ) {
						foreach ( $d as $k => $v ) {
							add_option( WPAUTOTERMS_OPTION_PREFIX . $k, $v );
						}
					}
				}
			}
		}
		if ( $this->_update_legacy ) {
			$this->_upgrade_from_tos_pp();
		}
	}

	protected function _upgrade_from_tos_pp() {
		$options = get_option( Menu::AUTO_TOS_OPTIONS, false );
		update_option( WPAUTOTERMS_OPTION_PREFIX . Menu::LEGACY_OPTIONS, $options !== false );
		if ( $options === false ) {
			return;
		}
		$transform = array_keys( Legacy_Settings::all_options() );
		foreach ( $transform as $k ) {
			if ( isset( $options[ $k ] ) ) {
				$v = $options[ $k ];
			} else {
				$v = '';
			}
			update_option( WPAUTOTERMS_OPTION_PREFIX . $k, $v );
		}
	}

	protected function _add_slug_option() {
		$slug = Options::get_option( Options::LEGAL_PAGES_SLUG, true );
		if ( empty( $slug ) ) {
			Options::set_option( Options::LEGAL_PAGES_SLUG, Options::default_value( Options::LEGAL_PAGES_SLUG ) );
		}
	}
}
