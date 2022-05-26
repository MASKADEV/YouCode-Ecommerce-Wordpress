<?php

namespace wpautoterms\admin\action;


use wpautoterms\Action_Base;
use wpautoterms\api\License;

class Recheck_License extends Action_Base {
	const NAME = 'wpautoterms_recheck_license';

	/**
	 * @var License
	 */
	protected $_license;

	public function set_license( License $license_query ) {
		$this->_license = $license_query;
	}

	protected function _handle( $admin_post ) {
		if ( $admin_post ) {
			wp_die( 'Not supported.' );
		}
		// NOTE: check before update_option() call to avoid double recheck
		$this->_license->check( true );
		update_option( WPAUTOTERMS_OPTION_PREFIX . License::OPTION_KEY, $_REQUEST['apiKey'] );
		$info = $this->_license->info();
		wp_send_json( array(
			'status' => $info->status,
			'shouldShowWebsites' => $info->should_show_websites(),
			'licenseType' => $info->license_type_string(),
			'summary' => $info->summary(),
			'websites' => $info->websites_info(),
			'maxSites' => $info->max_sites,
		) );
	}
}
