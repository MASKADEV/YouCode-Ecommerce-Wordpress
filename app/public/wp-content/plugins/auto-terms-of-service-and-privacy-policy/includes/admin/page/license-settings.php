<?php

namespace wpautoterms\admin\page;

use wpautoterms\admin\action\Recheck_License;
use wpautoterms\admin\action\Transfer_License;
use wpautoterms\api\License;
use wpautoterms\option\Text_Option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class License_Settings extends Settings_Base {

	/**
	 * @var License
	 */
	protected $_license;

	public function set_license( License $license ) {
		$this->_license = $license;
	}

	public function define_options() {
		parent::define_options();
		new Text_Option( License::OPTION_KEY, __( 'License key', WPAUTOTERMS_SLUG ), '',
			$this->id(), static::SECTION_ID, Text_Option::TYPE_GENERIC, array(), array( 'wpautoterms-license-key' ) );
	}

	public function defaults() {
		return array(
			License::OPTION_KEY => '',
		);
	}

	protected function _render_args() {
		$res = parent::_render_args();
		$res['info'] = $this->_license->info();

		return $res;
	}

	public function enqueue_scripts() {
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_license_settings', WPAUTOTERMS_PLUGIN_URL . 'js/license-settings.js',
			array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
		wp_localize_script( WPAUTOTERMS_SLUG . '_license_settings', 'wpautotermsLicenseSettings', array(
			'nonce' => wp_create_nonce( Recheck_License::NAME ),
			'action' => Recheck_License::NAME,
			'keyId' => WPAUTOTERMS_OPTION_PREFIX . License::OPTION_KEY,
			'status' => $this->_license->info()->status,
		) );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_license_transfer', WPAUTOTERMS_PLUGIN_URL . 'js/license-transfer.js',
			array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
		wp_localize_script( WPAUTOTERMS_SLUG . '_license_transfer', 'wpautotermsLicenseTransfer', array(
			'nonce' => wp_create_nonce( Transfer_License::NAME ),
			'action' => Transfer_License::NAME,
		) );
	}
}
