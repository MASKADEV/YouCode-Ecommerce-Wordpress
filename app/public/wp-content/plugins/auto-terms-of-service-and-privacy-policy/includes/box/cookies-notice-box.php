<?php

namespace wpautoterms\box;

use wpautoterms\admin\Menu;
use wpautoterms\cpt\CPT;
use wpautoterms\frontend\Container_Constants;
use wpautoterms\frontend\notice\Cookies_Notice;
use wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cookies_Notice_Box extends Licensed_Box {

	public function empty_buttons( $buttons ) {
		return array();
	}

	public function limited_buttons( $buttons ) {
		return array(
			'bold',
			'italic',
			'underline',
			'bullist',
			'numlist',
			'link',
			'unlink',
		);
	}

	public function shortcodes( $option ) {
		\wpautoterms\print_template( 'shortcodes', array(
			'shortcodes' => array(
				__( 'site name', WPAUTOTERMS_SLUG ) => '[wpautoterms site_name]',
				__( 'website URL', WPAUTOTERMS_SLUG ) => '[wpautoterms site_url]',
				__( 'company name', WPAUTOTERMS_SLUG ) => '[wpautoterms company_name]',
				__( 'country', WPAUTOTERMS_SLUG ) => '[wpautoterms country]',
				__( 'state', WPAUTOTERMS_SLUG ) => '[wpautoterms state]',
			),
			'option' => $option,
		) );
	}

	/**
	 * @param $page_id
	 * @param $section_id
	 */
	public function define_options( $page_id, $section_id ) {
		parent::define_options( $page_id, $section_id );

		if ( current_user_can( CPT::edit_cap() ) ) {
//			new option\Checkbox_Option( $this->id() . '_test_mode', __( 'Test mode', WPAUTOTERMS_SLUG ),
//				__( 'Show sample box to admin', WPAUTOTERMS_SLUG ), $page_id, $section_id );
		}

		$a = new option\Choices_Option( $this->id() . '_bar_position', __( 'Announcement bar position', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_values( array(
			Container_Constants::LOCATION_TOP => __( 'top', WPAUTOTERMS_SLUG ),
			Container_Constants::LOCATION_BOTTOM => __( 'bottom', WPAUTOTERMS_SLUG ),
		) );
		$a = new option\Choices_Option( $this->id() . '_bar_type', __( 'Announcement bar type', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_values( array(
			Container_Constants::TYPE_STATIC => __( 'static', WPAUTOTERMS_SLUG ),
			Container_Constants::TYPE_FIXED => __( 'fixed', WPAUTOTERMS_SLUG ),
		) );
		/*
		new option\Choices_Combo_Option($this->id().'_offset', __( 'Announcement bar offset', WPAUTOTERMS_SLUG ),
			array(
				' ' => __('default', WPAUTOTERMS_SLUG),
				'5px' => '5px',
				'10px' => '10px',
				'15px' => '15px',
				'20px' => '20px',
				'25px' => '25px',
			), option\Choices_Combo_Option::TYPE_SELECT, $page_id, $section_id);
		*/
		$a = new option\Choices_Option( $this->id() . '_disable_logged', __( 'Disable for logged-in users', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_values( array(
			'yes' => __( 'yes', WPAUTOTERMS_SLUG ),
			'no' => __( 'no', WPAUTOTERMS_SLUG ),
		) );
		$a = new option\Editor_Option( $this->id() . '_message', __( 'Message', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a->set_settings( array(
			'drag_drop_upload' => false,
			'media_buttons' => false,
			'editor_height' => 150,
			'filters' => array(
				array( 'mce_buttons', array( $this, 'limited_buttons' ) ),
				array( 'mce_buttons_2', array( $this, 'empty_buttons' ) ),
				array( 'mce_buttons_3', array( $this, 'empty_buttons' ) ),
				array( 'mce_buttons_4', array( $this, 'empty_buttons' ) ),
				array( 'wpautoterms_post_editor', array( $this, 'shortcodes' ) ),
				array( 'wpautoterms_post_editor', array( $this, '_render_revert_message' ) ),
			),
			'tinymce' => array(
				'resize' => false,
			),
		) );
		new option\Text_Option( $this->id() . '_close_message', __( 'Message for close button', WPAUTOTERMS_SLUG ), '',
			$page_id, $section_id );
		new option\Color_Option( $this->id() . '_bg_color', __( 'Background color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a = new option\Choices_Combo_Option( $this->id() . '_font', __( 'Font', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a->set_values( Menu::fonts() );
		$a = new option\Choices_Combo_Option( $this->id() . '_font_size', __( 'Font size', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a->set_values( Menu::font_sizes() );
		new option\Color_Option( $this->id() . '_text_color', __( 'Text color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		new option\Color_Option( $this->id() . '_links_color', __( 'Links color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$this->_custom_css_options( $page_id, $section_id );
	}

	public function defaults() {
		$ret = parent::defaults();

		return array_merge( $ret, array(
			$this->id() . '_bar_position' => Container_Constants::LOCATION_TOP,
			$this->id() . '_bar_type' => Container_Constants::TYPE_STATIC,
			$this->id() . '_disable_logged' => 'yes',
			$this->id() . '_message' => __( 'We use cookies to ensure that we give you the best experience on our website', WPAUTOTERMS_SLUG ),
			$this->id() . '_close_message' => __( 'Close', WPAUTOTERMS_SLUG ),
			$this->id() . '_bg_color' => '',
			$this->id() . '_font' => '',
			$this->id() . '_font_size' => '',
			$this->id() . '_text_color' => '',
			$this->id() . '_links_color' => '',
		) );
	}

	protected function _class_hints() {
		return array(
			__( 'Cookies notice bar class:', WPAUTOTERMS_SLUG ) => '.' . Cookies_Notice::CLASS_COOKIES_NOTICE,
			__( 'Close button class:', WPAUTOTERMS_SLUG ) => '.' . Cookies_Notice::CLASS_CLOSE_BUTTON,
		);
	}
}
