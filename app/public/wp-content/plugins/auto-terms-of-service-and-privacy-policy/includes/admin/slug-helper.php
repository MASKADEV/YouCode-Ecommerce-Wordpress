<?php

namespace wpautoterms\admin;

class Slug_Helper {
	const TRANSIENT_NAME = 'slug_helper';

	public function __construct() {
		add_action( 'updated_option', array(
			$this,
			'update_slugs'
		), 10, 3 );
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	public function init() {
		if ( ! get_transient( $this->_transient_name() ) ) {
			return;
		}
		delete_transient( $this->_transient_name() );
		flush_rewrite_rules();
	}

	public function update_slugs( $name, $old_value, $value ) {
		if ( $name === WPAUTOTERMS_OPTION_PREFIX . Options::LEGAL_PAGES_SLUG ) {
			set_transient( $this->_transient_name(), true );
		}
	}

	protected function _transient_name() {
		return WPAUTOTERMS_OPTION_PREFIX . static::TRANSIENT_NAME;
	}
}