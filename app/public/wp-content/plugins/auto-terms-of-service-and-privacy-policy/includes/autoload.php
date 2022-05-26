<?php

namespace wpautoterms;

spl_autoload_register( function ( $class ) {
	if ( substr_compare( $class, __NAMESPACE__ . '\\', 0, strlen( __NAMESPACE__ ) + 1 ) === 0 ) {
		$class_data = substr( $class, strlen( __NAMESPACE__ ) + 1 );
		$class_data = explode( '\\', str_replace( '_', '-', $class_data ) );
		$path = __DIR__;
		$c = count( $class_data );
		if ( $c > 1 ) {
			$path .= DIRECTORY_SEPARATOR . str_replace( '_', '-', join( DIRECTORY_SEPARATOR, array_slice( $class_data, 0, - 1 ) ) );
		}
		$class_name = str_replace( '_', '-', strtolower( $class_data[ $c - 1 ] ) );
		$path .= DIRECTORY_SEPARATOR . $class_name . '.php';
		$real_path = realpath( $path );
		if ( $real_path !== false ) {
			include_once $real_path;
		} else {
			throw new \UnexpectedValueException( 'Class not found: ' . $class . ' at path: ' . $path );
		}
	}
} );
