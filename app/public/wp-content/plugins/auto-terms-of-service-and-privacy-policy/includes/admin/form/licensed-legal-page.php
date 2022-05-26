<?php

namespace wpautoterms\admin\form;

use wpautoterms\api\Agreement;
use wpautoterms\api\Query;
use wpautoterms\api\License;

class Licensed_Legal_Page extends Legal_Page {
	/**
	 * @var License
	 */
	protected $_license;

	/**
	 * @var Query
	 */
	protected $_query;

	public function set_params( $license, $query ) {
		$this->_license = $license;
		$this->_query = $query;
	}

	protected function _get_content( $args ) {
		if ( !$this->_license->is_paid() ) {
			return _( 'Invalid license', WPAUTOTERMS_SLUG );
		}
		$agreement = new Agreement( $this->_query, $this->_license );

		return $agreement->generate( $this->id(), $args );
	}

	protected function _wizard_text() {
		if ( !$this->_license->is_paid() ) {
			return __( 'Invalid license', WPAUTOTERMS_SLUG );
		}
		$agreement = new Agreement( $this->_query, $this->_license );

		return $agreement->wizard( $this->id() );
	}

	public function availability() {
		if ( $this->_license->is_paid() ) {
			return true;
		}

		# TODO: move to a template
		return '<a href="' . esc_url( WPAUTOTERMS_PURCHASE_URL ) .
		       '" target="wpautotermsGetLicense" class="wpautoterms-legal-page-purchase">' .
		       __( 'Purchase license to generate', WPAUTOTERMS_SLUG ) . '</a>';
	}
}
