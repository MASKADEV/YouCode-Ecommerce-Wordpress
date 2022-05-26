<?php

namespace wpautoterms\box;

use wpautoterms\admin\action\Toggle_Action;
use wpautoterms\admin\page;
use wpautoterms\cpt\CPT;
use wpautoterms\Frontend;
use wpautoterms\frontend\Container_Constants;
use wpautoterms\option\Text_Option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Box {
	protected $_id;
	protected $_title;
	protected $_infotip;
	protected $_action;

	public function __construct( $id, $title, $infotip ) {
		$this->_id = $id;
		$this->_action = new Toggle_Action( CPT::edit_cap(), $this->enable_action_id() );
		$this->_action->set_option_name( $this->_enabled_option() );
		$this->_title = $title;
		$this->_infotip = $infotip;
		// Do not uncomment, called by Compliancekits class.
//		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function action() {
		return $this->_action;
	}

	public function enable_action_id() {
		return WPAUTOTERMS_SLUG . '_enable_' . $this->id() . '_toggle';
	}

	public function id() {
		return $this->_id;
	}

	public function title() {
		return $this->_title;
	}

	public function infotip() {
		return $this->_infotip;
	}

	protected function _toggle_button_text( $value ) {
		return $value ? __( 'Disable', WPAUTOTERMS_SLUG ) : __( 'Enable', WPAUTOTERMS_SLUG );
	}

	protected function _box_args() {
		$v = get_option( $this->_enabled_option(), false );

		return array(
			'box' => $this,
			'enabled' => $v,
			'enable_button_text' => $this->_toggle_button_text( $v ),
			'status_text' => $v ? __( 'Enabled', WPAUTOTERMS_SLUG ) : __( 'Disabled', WPAUTOTERMS_SLUG ),
		);
	}

	public function render() {
		\wpautoterms\print_template( 'options/box', $this->_box_args() );
	}

	protected function _page_args( page\Base $page ) {
		return array(
			'title' => $this->title(),
			'page_id' => $page->id(),
			'box_id' => $this->id(),
		);
	}

	protected function _class_hints() {
		return array();
	}

	protected static function _container_classes() {
		return array(
			'#' . Frontend::container_id( Container_Constants::LOCATION_TOP, Container_Constants::TYPE_STATIC ),
			'#' . Frontend::container_id( Container_Constants::LOCATION_TOP, Container_Constants::TYPE_FIXED ),
			'#' . Frontend::container_id( Container_Constants::LOCATION_BOTTOM, Container_Constants::TYPE_STATIC ),
			'#' . Frontend::container_id( Container_Constants::LOCATION_BOTTOM, Container_Constants::TYPE_FIXED )
		);
	}

	public function render_page( page\Base $page ) {
		\wpautoterms\print_template( 'options/box-page', $this->_page_args( $page ) );
	}

	protected function _enabled_option() {
		return WPAUTOTERMS_OPTION_PREFIX . $this->id();
	}

	abstract public function defaults();

	abstract public function define_options( $page_id, $section_id );

	protected function _custom_css_options( $page_id, $section_id ) {
		$to = new Text_Option( $this->id() . '_custom_css', __( 'Additional CSS', WPAUTOTERMS_SLUG ), '',
			$page_id, $section_id, 'css-textarea-option', array( 'data-codemirror' => null ),
			array( 'wpautoterms-resize-both' ) );
		$to->additional_template_args['class_hints'] = $this->_class_hints();
		$to->additional_template_args['container_classes'] = static::_container_classes();
	}

	public function enqueue_scripts() {
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/codemirror.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_css', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/css.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_hint', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/hint/show-hint.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_css_hint', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/hint/css-hint.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_matchbrackets', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/edit/matchbrackets.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_closebrackets', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/edit/closebrackets.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_active_line', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/selection/active-line.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_annotatescrollbar', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/scroll/annotatescrollbar.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_matchesonscrollbar', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/search/matchesonscrollbar.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_search_cursor', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/search/searchcursor.js', false, false, true );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_codemirror_match_highlight', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/search/match-highlighter.js', false, false, true );
		wp_enqueue_style( WPAUTOTERMS_SLUG . '_codemirror', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/codemirror.css' );
		wp_enqueue_style( WPAUTOTERMS_SLUG . '_codemirror_hint', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/hint/show-hint.css' );
		wp_enqueue_style( WPAUTOTERMS_SLUG . '_codemirror_matchesonscrollbar', WPAUTOTERMS_PLUGIN_URL . 'js/codemirror-5.42.0/addon/search/matchesonscrollbar.css' );

		wp_enqueue_script( WPAUTOTERMS_SLUG . '_css_hint', WPAUTOTERMS_PLUGIN_URL . 'js/css-hints.js', array( WPAUTOTERMS_JS_BASE ),
			WPAUTOTERMS_VERSION, true );
	}

	public function _render_revert_message($option) {
		\wpautoterms\print_template( 'options/revert-message', array(
			'option' => $option,
			'box' => $this
		) );
	}
}
