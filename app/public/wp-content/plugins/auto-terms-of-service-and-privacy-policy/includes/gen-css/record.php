<?php

namespace wpautoterms\gen_css;

class Record {
	protected $_selector;
	/**
	 * @var Attr[]
	 */
	protected $_attrs;

	/**
	 * Css_Class constructor.
	 *
	 * @param $selector
	 * @param Attr[] $attrs
	 */
	public function __construct( $selector, $attrs ) {
		$this->_attrs = $attrs;
		$this->_selector = $selector;
	}

	public function text() {
		$text = array_reduce( $this->_attrs, function ( $acc, Attr $x ) {
			return $acc . $x->text();
		}, '' );
		if ( empty( $text ) ) {
			return $text;
		}

		return esc_attr( $this->_selector ) . '{' . esc_js( $text ) . '}';
	}
}
