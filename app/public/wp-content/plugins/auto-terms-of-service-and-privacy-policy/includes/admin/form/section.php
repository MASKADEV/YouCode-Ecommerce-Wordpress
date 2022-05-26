<?php

namespace wpautoterms\admin\form;

// TODO: split into page and section
abstract class Section {
	protected static $_section_id = null;
	protected static $_header = null;
	protected static $_dependencies;
	protected static $_start_hidden;
	protected static $_inputs_only;

	const SHOW = "show";
	const HIDE = "hide";

	static function init() {
		static::$_dependencies = array();
		static::$_start_hidden = array();
	}

	static function begin( $section_id, $header ) {
		if ( ( static::$_section_id != null ) || ( static::$_header != null ) ) {
			throw new \ErrorException( 'Unexpected section begin, use section::end to end current section: : ' . static::$_section_id );
		}
		if ( array_key_exists( $section_id, static::$_dependencies ) ) {
			throw new \ErrorException( 'Duplicate section id: ' . static::$_section_id );
		}
		static::$_dependencies[ $section_id ] = false;
		static::$_section_id = $section_id;
		static::$_header = $header;
		\wpautoterms\print_template( 'form/section-begin', compact( 'section_id', 'header' ) );
	}

	static function end() {
		$section_id = static::$_section_id;
		$header = static::$_header;
		\wpautoterms\print_template( 'form/section-end', compact( 'section_id', 'header' ) );
		static::$_section_id = null;
		static::$_header = null;
	}

	static protected function check_dependency() {
		if ( isset( static::$_section_id[ static::$_section_id ] ) && static::$_section_id[ static::$_section_id ] ) {
			throw new \ErrorException( 'Dependency already defined for section: ' . static::$_section_id );
		}
	}

	static function show_if( $control_id, $value = true ) {
		static::check_dependency();
		if ( ! in_array( static::$_section_id, static::$_start_hidden ) ) {
			static::$_start_hidden[] = static::$_section_id;
		}
		static::$_dependencies[ static::$_section_id ] = array( $control_id, $value, static::SHOW );
	}

	static function hide_if( $control_id, $value = true ) {
		static::check_dependency();
		static::$_dependencies[ static::$_section_id ] = array( $control_id, $value, static::HIDE );
	}

	static function get_start_hidden() {
		return static::$_start_hidden;
	}

	static function get_dependencies() {
		// NOTE: strip unconditional (persistent) sections using default predicate,
		// empty arrays are stripped out
		return array_filter( static::$_dependencies );
	}
}
