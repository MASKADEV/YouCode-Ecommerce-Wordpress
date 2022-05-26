<?php

namespace wpautoterms\admin\page;

use wpautoterms\admin\Notices;
use wpautoterms\admin\Options;
use wpautoterms\api\License;
use wpautoterms\Countries;
use wpautoterms\option\Checkbox_Option;
use wpautoterms\option\Choices_Option;
use wpautoterms\option\CPT_Slug_Option;
use wpautoterms\option\Text_Option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings_Page extends Settings_Base {

	const SHORTCODE_OPTION_TEMPLATE = 'shortcode-entry-option';
	const SHORTCODE_SELECT_TEMPLATE = 'shortcode-select-option';
	/**
	 * @var License
	 */
	protected $_license;

	public function set_license( License $license ) {
		$this->_license = $license;
	}

	public function define_options() {
		parent::define_options();
		new Text_Option( Options::SITE_NAME,
			__( 'Website name', WPAUTOTERMS_SLUG ),
			'',
			$this->id(), static::SECTION_ID,
			static::SHORTCODE_OPTION_TEMPLATE );
		new Text_Option( Options::SITE_URL,
			__( 'Website URL', WPAUTOTERMS_SLUG ),
			'',
			$this->id(), static::SECTION_ID,
			static::SHORTCODE_OPTION_TEMPLATE );
		new Text_Option( Options::COMPANY_NAME,
			__( 'Company name', WPAUTOTERMS_SLUG ),
			'',
			$this->id(), static::SECTION_ID,
			static::SHORTCODE_OPTION_TEMPLATE );
		$country = new Choices_Option( Options::COUNTRY,
			__( 'Country', WPAUTOTERMS_SLUG ),
			'',
			$this->id(), static::SECTION_ID,
			static::SHORTCODE_SELECT_TEMPLATE, array(
				'data-type' => 'country-selector',
			),
			array( 'wpautoterms-hidden' ) );
		$countries = Countries::get();
		$country->set_values( array_combine( $countries, $countries ) );

		$state = new Choices_Option( Options::STATE,
			__( 'State', WPAUTOTERMS_SLUG ),
			'',
			$this->id(), static::SECTION_ID,
			static::SHORTCODE_SELECT_TEMPLATE, array(
				'data-type' => 'state-selector',
			),
			array( 'wpautoterms-hidden' ) );
		$states = array_keys( Countries::translations() );
		$states = array_diff( $states, $countries );
		$state->set_values( array_combine( $states, $states ) );

		$is_free = !$this->_license->is_paid();
		global $wp_rewrite;
		$does_rewrite = $wp_rewrite->using_permalinks();
		$attrs = ( $is_free || ! $does_rewrite ) ? array( 'disabled' => null ) : array();
		$tooltip = $is_free ? '<a href="' . esc_url( WPAUTOTERMS_PURCHASE_URL ) .
		                      '" target="wpautotermsGetLicense" class="wpautoterms-legal-page-purchase">' .
		                      __( 'Purchase license to enable this option', WPAUTOTERMS_SLUG ) . '</a><br>' : '';
		if ( ! $does_rewrite ) {
			$tooltip .= __( 'Your Permalinks structure is set to plain. For this option to work, you need to change your Permalinks structure to post name.',
				WPAUTOTERMS_SLUG );
		} else {
			$tooltip .= __( 'Please keep in mind that if you set your Permalinks structure to plain, this option will not work.',
				WPAUTOTERMS_SLUG );
		}
		$slug = new CPT_Slug_Option( Options::LEGAL_PAGES_SLUG,
			__( 'Legal pages slug', WPAUTOTERMS_SLUG ),
			$tooltip,
			$this->id(), static::SECTION_ID, false, $attrs );
		$slug->set_fallback( array( $this, 'slug_fail' ) );

		new Checkbox_Option( Options::SHOW_IN_PAGES_WIDGET, __( 'Show legal pages in Pages Widget', WPAUTOTERMS_SLUG ),
			'', $this->id(), static::SECTION_ID );
	}

	protected function _default_slug() {
		$value = Options::get_option( Options::LEGAL_PAGES_SLUG );
		if ( empty( $value ) ) {
			$value = Options::default_value( Options::LEGAL_PAGES_SLUG );
		}

		return $value;
	}

	public function slug_fail( $x ) {
		global $wp_rewrite;
		$value = $this->_default_slug();
		if ( !$this->_license->is_paid() ) {
			$dv = Options::default_value( Options::LEGAL_PAGES_SLUG );
			if ( $value !== $dv ) {
				Notices::$instance->add( __( 'Using free license, set pages slug to default.', WPAUTOTERMS_SLUG ),
					Notices::CLASS_ERROR );
				$value = $dv;
			}
		} elseif ( $wp_rewrite->using_permalinks() ) {
			Notices::$instance->add( sprintf( __( 'Invalid slug value, set to %s.', WPAUTOTERMS_SLUG ), $value ),
				Notices::CLASS_ERROR );
		}

		return $value;
	}

	public function defaults() {
		return array_reduce( Options::all_options(), function ( $acc, $x ) {
			$acc[ $x ] = Options::default_value( $x );

			return $acc;
		}, array() );
	}

	public function enqueue_scripts() {
		parent::enqueue_scripts();
		Countries::enqueue_scripts();
	}
}
