<?php

namespace wpautoterms\option;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Option {
	protected $_name;
	protected $_section_id;
	protected $_page_id;
	protected $_label;
	protected $_template;
	protected $_dep_source = false;
	protected $_dep_value = false;
	protected $_dep_type = false;
	protected $_tooltip;
	protected $_attrs;
	protected $_classes;
	public $additional_template_args = array();

	protected function _prepare_attrs() {
		$ret = array();
		if ( ! empty( $this->_attrs ) ) {
			foreach ( $this->_attrs as $k => $attr ) {
				if ( $attr === null ) {
					$ret[] = esc_attr( $k );
				} else {
					$ret[] = esc_attr( $k ) . '="' . esc_attr( $attr ) . '"';
				}
			}
		}

		return join( ' ', $ret );
	}

	protected static function _default_template() {
		throw new \InvalidArgumentException( 'Default template is undefined' );
	}

	protected function _get_template() {
		if ( $this->_template === false ) {
			return static::_default_template();
		}

		return $this->_template;
	}

	public function __construct(
		$name, $label, $tooltip, $page_id, $section_id, $type = false, $attrs = array(),
		$classes = array()
	) {
		$this->_name = WPAUTOTERMS_OPTION_PREFIX . $name;
		$this->_section_id = $section_id;
		$this->_page_id = $page_id;
		$this->_label = $label;
		$this->_tooltip = $tooltip;
		$this->_attrs = $attrs;
		$this->_template = $type;
		$this->_classes = $classes;

		$this->_add_field();
		$this->_register();
	}

	protected function _add_field() {
		add_settings_field( $this->_name,
			$this->_label,
			array( $this, 'render' ),
			$this->_page_id,
			$this->_section_id );
	}

	protected function _register() {
		register_setting( $this->_page_id, $this->_name, array( $this, 'sanitize' ) );
	}

	public function get_value() {
		return get_option( $this->_name );
	}

	public function render() {
		if ( ! empty( $this->_dep_source ) ) {
			$this->_handle_render( 'options/dependency-begin', array(
				'dep_source' => $this->_dep_source,
				'dep_value' => $this->_dep_value,
				'dep_type' => $this->_dep_type,
			) );
		}
		$this->_handle_render( 'options/' . $this->_get_template(), $this->_template_args() );
		if ( ! empty( $this->_dep_source ) ) {
			$this->_handle_render( 'options/dependency-end', array() );
		}
	}

	protected function _template_args() {
		return array_merge( array(
			'name' => esc_attr( $this->_name ),
			'value' => $this->get_value(),
			'tooltip' => $this->_tooltip,
			'attrs' => $this->_prepare_attrs(),
			'classes' => join( ' ', array_map( 'esc_attr', $this->_classes ) )
		), $this->additional_template_args );
	}

	protected function _handle_render( $template, $args ) {
		\wpautoterms\print_template( $template, $args );
	}

	abstract public function sanitize( $input );

	public function set_dependency( $source, $value, $type ) {
		$this->_dep_source = WPAUTOTERMS_OPTION_PREFIX . $source;
		$this->_dep_value = $value;
		$this->_dep_type = $type;
	}

	public function name() {
		return $this->_name;
	}
}