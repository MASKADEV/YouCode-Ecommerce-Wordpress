<?php

namespace wpautoterms\box;

use wpautoterms\admin\Menu;
use wpautoterms\admin\Options;
use wpautoterms\frontend\Links;
use wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Links_Box extends Box {

	public function __construct( $id, $title, $infotip ) {
		parent::__construct( $id, $title, $infotip );
	}

	public function define_options( $page_id, $section_id ) {
		new option\Checkbox_Option( $this->id(), __( 'Enabled', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		new option\Color_Option( $this->id() . '_bg_color', __( 'Background color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a = new option\Choices_Combo_Option( $this->id() . '_font', __( 'Font', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a->set_values( Menu::fonts() );
		$a = new option\Choices_Combo_Option( $this->id() . '_font_size', __( 'Font size', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a->set_values( Menu::font_sizes() );
		new option\Color_Option( $this->id() . '_text_color', __( 'Text color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		$a = new option\Choices_Option( $this->id() . '_text_align', __( 'Text alignment', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$a->set_values( array(
			' ' => __( 'default', WPAUTOTERMS_SLUG ),
			'center' => __( 'center', WPAUTOTERMS_SLUG ),
			'right' => __( 'right', WPAUTOTERMS_SLUG ),
			'left' => __( 'left', WPAUTOTERMS_SLUG ),
		) );
		new option\Color_Option( $this->id() . '_links_color', __( 'Links color', WPAUTOTERMS_SLUG ), '', $page_id, $section_id );
		new option\Text_Option( $this->id() . '_separator', __( 'Links separator', WPAUTOTERMS_SLUG ), '',
			$page_id, $section_id );
		new option\Checkbox_Option( $this->id() . '_target_blank', __( 'Links to open in a new page', WPAUTOTERMS_SLUG ),
			'', $page_id, $section_id );
		$so = new option\Hidden_Option( Options::LINKS_ORDER, '', '', $page_id, $section_id );
		$so->custom_sanitize = array( $this, '_sanitize_sorting' );
		$this->_custom_css_options( $page_id, $section_id );
	}

	public function _sanitize_sorting( $input ) {
		$input = explode( ',', $input );
		$input = array_map( 'trim', $input );
		$input = array_filter( $input, 'is_numeric' );

		return join( ',', $input );
	}

	public function defaults() {
		return array(
			$this->id() => true,
			$this->id() . '_bg_color' => '#ffffff',
			$this->id() . '_font' => 'Arial, sans-serif',
			$this->id() . '_font_size' => '14px',
			$this->id() . '_text_color' => '#cccccc',
			$this->id() . '_text_align' => 'center',
			$this->id() . '_links_color' => '#000000',
			$this->id() . '_separator' => '-',
			$this->id() . '_target_blank' => false,
		);
	}

	protected function _class_hints() {
		return array(
			__( 'Links bar class:', WPAUTOTERMS_SLUG ) => '.' . Links::FOOTER_CLASS,
			__( 'Separator class:', WPAUTOTERMS_SLUG ) => '.' . Links::FOOTER_CLASS . ' .' . Links::SEPARATOR_CLASS,
			__( 'Link class:', WPAUTOTERMS_SLUG ) => '.' . Links::FOOTER_CLASS . ' a',
		);
	}

	public function enqueue_scripts() {
		parent::enqueue_scripts();
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_links_box_page', WPAUTOTERMS_PLUGIN_URL . 'js/links-box-page.js',
			array( WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
	}

	protected function _page_args( \wpautoterms\admin\page\Base $page ) {
		$args = parent::_page_args( $page );
		$args['after_section'] = \wpautoterms\print_template( 'options/links-reorder',
			array(
				'posts' => Links::link_posts(),
			), true );

		return $args;
	}
}
