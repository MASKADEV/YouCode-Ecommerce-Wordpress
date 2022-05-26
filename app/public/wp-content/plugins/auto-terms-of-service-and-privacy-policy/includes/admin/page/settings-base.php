<?php

namespace wpautoterms\admin\page;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Settings_Base extends Base {
	const SECTION_ID = 'section';

	protected $_options;
	protected $_section_title = false;

	public function define_options() {
		// NOTE: PHP<5.5 compliance
		$value = static::SECTION_ID;
		if ( empty( $value ) ) {
			return;
		}
		add_settings_section( static::SECTION_ID,
			$this->_section_title,
			false,
			$this->id() );
	}

	abstract public function defaults();
}
