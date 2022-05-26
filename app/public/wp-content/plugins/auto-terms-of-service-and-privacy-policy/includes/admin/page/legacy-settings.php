<?php

namespace wpautoterms\admin\page;

use wpautoterms\admin\Menu;
use wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Legacy_Settings extends Settings_Base {
	const SECTION_ID = 'legacy_section';

	const VALUE_ON = 'atospp_on';
	const VALUE_OFF = 'atospp_off';

	const ON_OFF = 'atospp_onoff';
	const HEADING = 'atospp_tos_heading';
	const PP_HEADING = 'atospp_pp_heading';
	const NAME_FULL = 'atospp_namefull';
	const NAME = 'atospp_name';
	const NAME_POSSESSIVE = 'atospp_namepossessive';
	const DOMAIN_NAME = 'atospp_domainname';
	const WEBSITE_URL = 'atospp_websiteurl';
	const MIN_AGE = 'atospp_minage';
	const TIME_FEES_NOTIFICATIONS = 'atospp_time_feesnotifications';
	const TIME_REPLY_TO_PRIORITY_EMAIL = 'atospp_time_replytopriorityemail';
	const TIME_DETERMINING_MAX_DAMAGES = 'atospp_time_determiningmaxdamages';
	const DMCA_NOTICE_URL = 'atospp_dmcanoticeurl';
	const VENUE = 'atospp_venue';
	const COURT_LOCATION = 'atospp_courtlocation';
	const ARBITRATION_LOCATION = 'atospp_arbitrationlocation';

	public static function all_options() {
		return array(
			static::ON_OFF => array(
				__( 'On/Off', WPAUTOTERMS_SLUG ),
				__( '', WPAUTOTERMS_SLUG )
			),
			static::HEADING => array(
				__( 'TOS Heading', WPAUTOTERMS_SLUG ),
				__( 'e.g. Terms of Service, Terms of Use', WPAUTOTERMS_SLUG )
			),
			static::PP_HEADING => array(
				__( 'PP Heading', WPAUTOTERMS_SLUG ),
				__( 'e.g. Privacy Policy', WPAUTOTERMS_SLUG )
			),
			static::NAME_FULL => array(
				__( 'Full Name', WPAUTOTERMS_SLUG ),
				__( 'e.g. Automattic Inc.', WPAUTOTERMS_SLUG )
			),
			static::NAME => array(
				__( 'Name', WPAUTOTERMS_SLUG ),
				__( 'e.g. Automattic', WPAUTOTERMS_SLUG )
			),
			static::NAME_POSSESSIVE => array(
				__( 'Possessive Name', WPAUTOTERMS_SLUG ),
				__( 'e.g. Automattic\'s', WPAUTOTERMS_SLUG )
			),
			static::DOMAIN_NAME => array(
				__( 'Domain Name', WPAUTOTERMS_SLUG ),
				__( 'e.g. Automattic.com', WPAUTOTERMS_SLUG )
			),
			static::WEBSITE_URL => array(
				__( 'Official Website URL', WPAUTOTERMS_SLUG ),
				__( 'e.g. http://www.wordpress.com/', WPAUTOTERMS_SLUG )
			),
			static::MIN_AGE => array(
				__( 'Minimum Age', WPAUTOTERMS_SLUG ),
				__( 'e.g. 13', WPAUTOTERMS_SLUG )
			),
			static::TIME_FEES_NOTIFICATIONS => array(
				__( 'Time Period for changing fees and for notifications', WPAUTOTERMS_SLUG ),
				__( 'e.g. thirty (30) days', WPAUTOTERMS_SLUG )
			),
			static::TIME_REPLY_TO_PRIORITY_EMAIL => array(
				__( 'Time Period for replying to priority email', WPAUTOTERMS_SLUG ),
				__( 'e.g. one business day', WPAUTOTERMS_SLUG )
			),
			static::TIME_DETERMINING_MAX_DAMAGES => array(
				__( 'Time Period for determining maximum damages', WPAUTOTERMS_SLUG ),
				__( 'e.g. twelve (12) month. Notice no "s" on "month"', WPAUTOTERMS_SLUG )
			),
			static::DMCA_NOTICE_URL => array(
				__( 'DMCA Notice URL', WPAUTOTERMS_SLUG ),
				__( 'e.g. http://automattic.com/dmca-notice/ or blank', WPAUTOTERMS_SLUG )
			),
			static::VENUE => array(
				__( 'Venue', WPAUTOTERMS_SLUG ),
				__( 'e.g. state of California, U.S.A.', WPAUTOTERMS_SLUG )
			),
			static::COURT_LOCATION => array(
				__( 'Court Location', WPAUTOTERMS_SLUG ),
				__( 'e.g. San Francisco County, California', WPAUTOTERMS_SLUG )
			),
			static::ARBITRATION_LOCATION => array(
				__( 'Arbitration Location', WPAUTOTERMS_SLUG ),
				__( 'e.g. San Francisco, California', WPAUTOTERMS_SLUG )
			),
		);
	}

	public function define_options() {
		parent::define_options();
		$options = static::all_options();
		$a = new option\Choices_Option( static::ON_OFF, $options[ static::ON_OFF ][0], '', $this->id(), static::SECTION_ID );
		$a->set_values( array(
			static::VALUE_OFF => __( 'Off / Coming soon', WPAUTOTERMS_SLUG ),
			static::VALUE_ON => __( 'On / Displaying', WPAUTOTERMS_SLUG ),
		) );
		$required = array( 'data-required' => '1' );
		$regular = array();
		foreach ( $options as $k => $v ) {
			if ( $k == static::ON_OFF ) {
				continue;
			}
			$attrs = $k == static::DMCA_NOTICE_URL ? $regular : $required;
			new option\Text_Option( $k, $v[0], $v[1], $this->id(), static::SECTION_ID, false, $attrs );
		}
	}

	public function defaults() {
		return array();
	}

	public function register_menu() {
		if ( get_option( WPAUTOTERMS_OPTION_PREFIX . Menu::LEGACY_OPTIONS, false ) ) {
			parent::register_menu();
		}
	}

	public function enqueue_scripts() {
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_legacy_on_off', WPAUTOTERMS_PLUGIN_URL . 'js/legacy-on-off.js',
			array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
		wp_localize_script( WPAUTOTERMS_SLUG . '_legacy_on_off', 'wpautotermsLegacy', array(
			'required' => __( 'required', WPAUTOTERMS_SLUG ),
			'onOffNotice' => __( 'please, fill all required fields to enable', WPAUTOTERMS_SLUG ),
			'onOffName' => WPAUTOTERMS_OPTION_PREFIX . static::ON_OFF,
			'onOffValueOff' => static::VALUE_OFF,
		) );
	}
}
