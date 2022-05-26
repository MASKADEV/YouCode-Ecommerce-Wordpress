<?php

namespace wpautoterms;

abstract class Util {

	public static function first_existing( $files ) {
		foreach ( $files as $file ) {
			if ( file_exists( $file ) ) {
				return $file;
			}
		}

		return false;
	}
}