<?php

namespace wpautoterms\admin\page;

use wpautoterms\api\License;
use wpautoterms\box\Box;
use wpautoterms\box\Cookies_Notice_Box;
use wpautoterms\box\Endorsements_Box;
use wpautoterms\box\Links_Box;
use wpautoterms\box\Update_Notice_Box;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Compliancekits extends Settings_Base {
	const KIT_COOKIES_NOTICE = 'cookies_notice';
	const KIT_ENDORSEMENTS = 'endorsements';
	const KIT_LINKS = 'links';
	const KIT_UPDATE_NOTICE = 'update_notice';

	protected $_boxes;
	/**
	 * @var bool|Box
	 */
	protected $_box = false;

	function __construct( $id, $title, License $license, $menu_title = null ) {
		parent::__construct( $id, $title, $menu_title );
		$cookies = new Cookies_Notice_Box( static::KIT_COOKIES_NOTICE, __( 'Cookies Notice', WPAUTOTERMS_SLUG ),
			__( 'Inform users that you are using cookies through your website.', WPAUTOTERMS_SLUG )
		);
		$cookies->set_license( $license );
		$endorsements = new Endorsements_Box( static::KIT_ENDORSEMENTS, __( 'Endorsements', WPAUTOTERMS_SLUG ),
			__( 'Inform users that your website may contain endorsements through disclaimers.', WPAUTOTERMS_SLUG )
		);
		$endorsements->set_license( $license );
		$this->_boxes = array(
			new Links_Box( static::KIT_LINKS, __( 'Links to Legal Pages', WPAUTOTERMS_SLUG ),
				__( 'Append links to your legal pages in the footer section of your website.', WPAUTOTERMS_SLUG )
			),
			new Update_Notice_Box( static::KIT_UPDATE_NOTICE, __( 'Update Notices of Legal Pages', WPAUTOTERMS_SLUG ),
				__( 'Inform users when your legal pages have been updated.', WPAUTOTERMS_SLUG )
			),
			$cookies,
			$endorsements,
		);
		if ( isset( $_REQUEST['box'] ) ) {
			foreach ( $this->boxes() as $box ) {
				if ( $box->id() == $_REQUEST['box'] ) {
					$this->_box = $box;
				}
			}
		}
	}

	public function defaults() {
		$ret = array();
		foreach ( $this->_boxes as $box ) {
			$ret = array_merge( $ret, $box->defaults() );
		}

		return $ret;
	}

	function enqueue_scripts() {
		if ( ! $this->_box ) {
			wp_enqueue_script( WPAUTOTERMS_SLUG . '_compliancekits_page', WPAUTOTERMS_PLUGIN_URL . 'js/compliancekits-page.js',
				array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
			wp_localize_script( WPAUTOTERMS_SLUG . '_compliancekits_page', 'wpautotermsComplianceKits', array(
				'boxData' => array_reduce( $this->_boxes, function ( $acc, Box $x ) {
					$acc[ $x->enable_action_id() ] = array(
						'noticeText' => array(
							$x->title() . ' ' . __( 'disabled.', WPAUTOTERMS_SLUG ),
							$x->title() . ' ' . __( 'enabled.', WPAUTOTERMS_SLUG ),
						)
					);

					return $acc;
				}, array() ),
				'buttonText' => array( __( 'Enable', WPAUTOTERMS_SLUG ), __( 'Disable', WPAUTOTERMS_SLUG ) ),
				'statusText' => array( __( 'Disabled', WPAUTOTERMS_SLUG ), __( 'Enabled', WPAUTOTERMS_SLUG ) )
			) );
		} else {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( WPAUTOTERMS_SLUG . '_box_page', WPAUTOTERMS_PLUGIN_URL . 'js/box-page.js',
				array( 'wp-color-picker', WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
			$this->_box->enqueue_scripts();
		}
	}

	function render() {
		if ( $this->_box ) {
			$this->_box->render_page( $this );
		} else {
			parent::render();
		}
	}

	function boxes() {
		return $this->_boxes;
	}

	function define_options() {
		parent::define_options();
		if ( $this->_box ) {
			$this->_box->define_options( $this->id(), static::SECTION_ID );
		}
	}
}
