<?php

namespace wpautoterms\gen_css;

class Document {
	/**
	 * @var Record[]
	 */
	protected $_records;

	/**
	 * Document constructor.
	 *
	 * @param Record[] $records
	 */
	public function __construct( $records ) {
		$this->_records = $records;
	}

	public function text() {
		$text = array_filter( array_map( function ( Record $x ) {
			return $x->text();
		}, $this->_records ), function ( $x ) {
			return ! empty( $x );
		} );
		if ( empty( $text ) ) {
			return '';
		}

		return join( "\n", $text );
	}

	public static function style( $text ) {
		$text = trim( $text );
		if ( empty( $text ) ) {
			return $text;
		}

		return "<style type=\"text/css\" media=\"all\">\n" . $text . "</style>";

	}
}
