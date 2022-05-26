<?php

namespace wpautoterms;

use ReflectionClass;
use ReflectionProperty;

abstract class Vo {
	protected static $_fields;
	protected static $_defaults = array();

	/**
	 * Vo constructor.
	 *
	 * @param $values array
	 */
	public function __construct( array $values ) {
		if ( static::$_fields == null ) {
			$reflect = new ReflectionClass( $this );
			$props = $reflect->getProperties( ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED |
			                                  ReflectionProperty::IS_PRIVATE );
			static::$_fields = array();
			foreach ( $props as $x ) {
				if ( static::_filter_fields( $x->name ) ) {
					static::$_fields[] = $x->name;
				}
			}
		}
		foreach ( static::$_fields as $k ) {
			if ( isset( $values[ $k ] ) ) {
				$this->$k = $values[ $k ];
			} elseif ( isset( static::$_defaults[ $k ] ) ) {
				$this->$k = static::$_defaults[ $k ];
			}
		}
	}

	/**
	 * @param $field_name string
	 *
	 * @return bool
	 */
	protected static function _filter_fields( $field_name ) {
		return $field_name[0] !== '_';
	}

	/**
	 * @return array
	 */
	public static function defaults() {
		return static::$_defaults;
	}

	/**
	 * @return array
	 */
	public function to_array() {
		$res = array();
		foreach ( static::$_fields as $k ) {
			$res[ $k ] = $this->$k;
		}

		return $res;
	}
}
