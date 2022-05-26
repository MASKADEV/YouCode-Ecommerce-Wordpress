<?php

namespace wpautoterms\box;

use wpautoterms\admin\Menu;
use wpautoterms\cpt\CPT;
use wpautoterms\frontend\Container_Constants;
use wpautoterms\frontend\notice\Update_Notice;
use wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Update_Notice_Box extends Box {

	function empty_buttons( $buttons ) {
		return array();
	}

	function limited_buttons( $buttons ) {
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

	function shortcodes( $option ) {
		\wpautoterms\print_template( 'shortcodes', array(
			'shortcodes' => array(
				__( 'title', WPAUTOTERMS_SLUG ) => '[wpautoterms page_title]',
				__( 'link', WPAUTOTERMS_SLUG ) => '<a href="[wpautoterms page_link]">[wpautoterms page_title]</a>',
				__( 'href', WPAUTOTERMS_SLUG ) => '[wpautoterms page_link]',
				__( 'last effective date', WPAUTOTERMS_SLUG ) => '[wpautoterms last_updated_date]',
			),
			'option' => $option,
		) );
	}

	function shortcodes_multiple( $option ) {
		\wpautoterms\print_template( 'shortcodes', array(
			'shortcodes' => array(
				__( 'titles', WPAUTOTERMS_SLUG ) => '[wpautoterms page_titles]',
				__( 'links', WPAUTOTERMS_SLUG ) => '[wpautoterms page_links]',
				__( 'last effective date', WPAUTOTERMS_SLUG ) => '[wpautoterms last_updated_date]',
			),
			'option' => $option,
		) );
	}

	function define_options( $page_id, $section_id ) {
		new option\Checkbox_Option( $this->id(), __( 'Enabled', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );

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
		new Choices_Combo_Option($this->id().'_offset', __( 'Announcement bar offset', WPAUTOTERMS_SLUG ),
			array(
				' ' => __('default', WPAUTOTERMS_SLUG),
				'5px' => '5px',
				'10px' => '10px',
				'15px' => '15px',
				'20px' => '20px',
				'25px' => '25px',
			), Choices_Combo_Option::TYPE_SELECT, $page_id, $section_id);
		*/
		$a = new option\Choices_Option( $this->id() . '_disable_logged', __( 'Disable for logged-in users', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_values( array(
			'yes' => __( 'yes', WPAUTOTERMS_SLUG ),
			'no' => __( 'no', WPAUTOTERMS_SLUG ),
		) );
		$a = new option\Choices_Option( $this->id() . '_duration', __( 'How long to keep the announcement bar', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_values( array(
			'1' => __( '24 hours', WPAUTOTERMS_SLUG ),
			'3' => __( '3 days', WPAUTOTERMS_SLUG ),
			'10' => __( '10 days', WPAUTOTERMS_SLUG ),
			'30' => __( '30 days', WPAUTOTERMS_SLUG ),
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
		$a = new option\Editor_Option( $this->id() . '_message_multiple', __( 'Message for multiple updated pages', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_settings( array(
			'drag_drop_upload' => false,
			'media_buttons' => false,
			'editor_height' => 150,
			'filters' => array(
				array( 'mce_buttons', array( $this, 'limited_buttons' ) ),
				array( 'mce_buttons_2', array( $this, 'empty_buttons' ) ),
				array( 'mce_buttons_3', array( $this, 'empty_buttons' ) ),
				array( 'mce_buttons_4', array( $this, 'empty_buttons' ) ),
				array( 'wpautoterms_post_editor', array( $this, 'shortcodes_multiple' ) ),
				array( 'wpautoterms_post_editor', array( $this, '_render_revert_message' ) ),
			),
			'tinymce' => array(
				'resize' => false,
			),
		) );
		new option\Text_Option( $this->id() . '_close_message', __( 'Message for close button', WPAUTOTERMS_SLUG ), '',
			$page_id, $section_id, option\Text_Option::TYPE_GENERIC );
		new option\Color_Option( $this->id() . '_bg_color', __( 'Background color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a = new option\Choices_Combo_Option( $this->id() . '_notice_font', __( 'Font', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a->set_values( Menu::fonts() );
		$a = new option\Choices_Combo_Option( $this->id() . '_font_size', __( 'Font size', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a->set_values( Menu::font_sizes() );
		new option\Color_Option( $this->id() . '_text_color', __( 'Text color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		new option\Color_Option( $this->id() . '_links_color', __( 'Links color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$this->_custom_css_options( $page_id, $section_id );
	}

	public function defaults() {
		return array(
			$this->id() => false,
			$this->id() . '_bar_position' => Container_Constants::LOCATION_TOP,
			$this->id() . '_bar_type' => Container_Constants::TYPE_STATIC,
			$this->id() . '_disable_logged' => 'yes',
			$this->id() . '_duration' => '3',
			$this->id() . '_message' => __( 'Our <a href="[wpautoterms page_link]">[wpautoterms page_title]</a> has been updated on [wpautoterms last_updated_date].', WPAUTOTERMS_SLUG ),
			$this->id() . '_message_multiple' => __( 'Our [wpautoterms page_links] have been updated on [wpautoterms last_updated_date].', WPAUTOTERMS_SLUG ),
			$this->id() . '_close_message' => __( 'Close', WPAUTOTERMS_SLUG ),
			$this->id() . '_bg_color' => '',
			$this->id() . '_font' => '',
			$this->id() . '_font_size' => '',
			$this->id() . '_text_color' => '',
			$this->id() . '_links_color' => '',
		);
	}

	protected function _class_hints() {
		return array(
			__( 'Update notice class:', WPAUTOTERMS_SLUG ) => '.' . Update_Notice::BLOCK_CLASS,
			__( 'Close button class:', WPAUTOTERMS_SLUG ) => '.' . Update_Notice::CLOSE_CLASS,
		);
	}
}
