<?php

namespace wpautoterms\box;

use wpautoterms\admin\Menu;
use wpautoterms\frontend\Container_Constants;
use wpautoterms\frontend\Endorsements;
use wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Endorsements_Box extends Licensed_Box {

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
				__( 'site name', WPAUTOTERMS_SLUG ) => '[wpautoterms site_name]',
				__( 'website URL', WPAUTOTERMS_SLUG ) => '[wpautoterms site_url]',
				__( 'company name', WPAUTOTERMS_SLUG ) => '[wpautoterms company_name]',
				__( 'country', WPAUTOTERMS_SLUG ) => '[wpautoterms country]',
			),
			'option' => $option,
		) );
	}


	function define_options( $page_id, $section_id ) {
		parent::define_options( $page_id, $section_id );
		$a = new option\Editor_Option( $this->id() . '_message', __( 'Disclaimer message', WPAUTOTERMS_SLUG ),
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
				array( 'wpautoterms_post_editor', array( $this, 'shortcodes' ) ),
				array( 'wpautoterms_post_editor', array( $this, '_render_revert_message' ) ),
			),
			'tinymce' => array(
				'resize' => false,
			),
		) );
		$a = new option\Choices_Option( $this->id() . '_when', __( 'When to insert the disclaimer note', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_values( array(
			' ' => __( 'always', WPAUTOTERMS_SLUG ),
			'if_tag' => __( 'if tag exists on a post', WPAUTOTERMS_SLUG ),
		) );
		$t = new option\Tag_Option( $this->id() . '_tag', '', '', $page_id, $section_id );
		$t->set_dependency( $this->id() . '_when', 'if_tag', 'show' );

		$a = new option\Choices_Option( $this->id() . '_where', __( 'Where to insert the disclaimer note', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_values( array(
			' ' => __( 'at the top of the post (before post content)', WPAUTOTERMS_SLUG ),
			Container_Constants::LOCATION_BOTTOM => __( 'at the bottom of the post (after post content)', WPAUTOTERMS_SLUG ),
		) );

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
			$this->id() . '_message' => '<p>Some of the links in this article are "affiliate links", a link with a special tracking code. This means if you click on an affiliate link and purchase the item, we will receive an affiliate commission.</p> <p>The price of the item is the same whether it is an affiliate link or not. Regardless, we only recommend products or services we believe will add value to our readers.</p> <p>By using the affiliate links, you are helping support our Website, and we genuinely appreciate your support.</p>',
			$this->id() . '_when' => '',
			$this->id() . '_tag' => '',
			//$this->id().'_where'=>'',
		) );
	}

	protected static function _container_classes() {
		return array();

	}

	protected function _class_hints() {
		return array(
			__( 'Endorsement block class:', WPAUTOTERMS_SLUG ) => '.' . Endorsements::css_class_id(),
		);
	}
}
