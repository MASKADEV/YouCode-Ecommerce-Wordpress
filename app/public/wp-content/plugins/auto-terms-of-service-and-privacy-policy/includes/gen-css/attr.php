<?php

namespace wpautoterms\gen_css;

class Attr {
	const TYPE_BG_COLOR = 'bg_color';
	const TYPE_FONT = 'font';
	const TYPE_FONT_SIZE = 'font_size';
	const TYPE_LINKS_COLOR = 'links_color';
	const TYPE_TEXT_ALIGN = 'text_align';
	const TYPE_TEXT_COLOR = 'text_color';

	protected static $_attrs = array(
		Attr::TYPE_BG_COLOR => 'background-color',
		Attr::TYPE_FONT => 'font-family',
		Attr::TYPE_FONT_SIZE => 'font-size',
		Attr::TYPE_LINKS_COLOR => 'color',
		Attr::TYPE_TEXT_ALIGN => 'text-align',
		Attr::TYPE_TEXT_COLOR => 'color',
	);

	protected $_important;
	protected $_prefix;
	protected $_type;

	public function __construct( $prefix, $type, $important = false ) {
		$this->_prefix = $prefix;
		$this->_type = $type;
		$this->_important = $important;
	}

	public function text() {
		$value = get_option( $this->_prefix . '_' . $this->_type );
		if ( empty( $value ) ) {
			return '';
		}

		return static::$_attrs[ $this->_type ] . ':' . $value . ( $this->_important ? ' !important' : '' ) . ';';
	}
}
