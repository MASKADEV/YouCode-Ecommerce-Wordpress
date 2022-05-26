<?php

namespace wpautoterms\frontend;

use wpautoterms\gen_css\Attr;
use wpautoterms\gen_css\Document;
use wpautoterms\gen_css\Record;

abstract class Styles {

	public static function print_styles( $id, $class, $return = false ) {
		$option_prefix = WPAUTOTERMS_OPTION_PREFIX . $id;
		$d = new Document( array(
			new Record( '.' . esc_attr( $class ) . 'a', array(
				new Attr( $option_prefix, Attr::TYPE_FONT ),
				new Attr( $option_prefix, Attr::TYPE_FONT_SIZE ),
				new Attr( $option_prefix, Attr::TYPE_LINKS_COLOR ),
			) ),
			new Record( '.' . esc_attr( $class ), array(
				new Attr( $option_prefix, Attr::TYPE_FONT ),
				new Attr( $option_prefix, Attr::TYPE_FONT_SIZE ),
				new Attr( $option_prefix, Attr::TYPE_TEXT_COLOR ),
				new Attr( $option_prefix, Attr::TYPE_BG_COLOR ),
			) ),
		) );
		$text = $d->text();
		$custom = get_option( $option_prefix . '_custom_css' );
		if ( ! empty( $custom ) ) {
			$text .= "\n" . strip_tags( $custom );
		}
		$text = Document::style( $text ) . "\n";
		if ( $return ) {
			return $text;
		}
		echo $text;

		return true;
	}
}