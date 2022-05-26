<?php

namespace wpautoterms\legal_pages;

abstract class Conf {
	const GROUP_TEST = 'test';
	const GROUP_PRIVACY_POLICY = 'privacy_policy';
	const GROUP_TERMS = 'terms';

	protected static $_pages;
	protected static $_groups;

	protected static function _create_groups() {
		$arr = array(
			new Group( static::GROUP_TEST, __( 'Test Agreements', WPAUTOTERMS_SLUG ) ),
			new Group( static::GROUP_PRIVACY_POLICY, __( 'Privacy Policy', WPAUTOTERMS_SLUG ) ),
			new Group( static::GROUP_TERMS, __( 'Terms & Conditions', WPAUTOTERMS_SLUG ) ),
		);
		static::$_groups = array_combine( array_map( function ( $x ) {
			return $x->id;
		}, $arr ), $arr );
	}

	protected static function _create_pages() {
		static::$_pages = array(
			new Page( 'professional-privacy-policy',
				static::get_group( static::GROUP_PRIVACY_POLICY ),
				__( 'Professional Privacy Policy (CCPA & GPDR)', WPAUTOTERMS_SLUG ),
				__( 'Create a Professional Privacy Policy for your WordPress website. Include CCPA & GDPR.', WPAUTOTERMS_SLUG ),
				true,
				__( 'Privacy Policy', WPAUTOTERMS_SLUG )
			),
			new Page( 'privacy-policy',
				static::get_group( static::GROUP_PRIVACY_POLICY ),
				__( 'Simple Privacy Policy', WPAUTOTERMS_SLUG ),
				__( 'Create a simple Privacy Policy for your WordPress website.', WPAUTOTERMS_SLUG ),
				false,
				__( 'Privacy Policy', WPAUTOTERMS_SLUG )
			),
			new Page( 'terms-and-conditions',
				static::get_group( static::GROUP_TERMS ),
				__( 'Terms and Conditions', WPAUTOTERMS_SLUG ),
				__( 'Create a simple Terms and Conditions agreement for your WordPress website.', WPAUTOTERMS_SLUG ),
				false
			),
		);
	}

	/**
	 * @param $id
	 *
	 * @return Group
	 */
	protected static function get_group( $id ) {
		$g = static::get_groups();

		return $g[ $id ];
	}

	/**
	 * @return Group[]
	 */
	public static function get_groups() {
		if ( static::$_groups == null ) {
			static::_create_groups();
		}

		return static::$_groups;
	}

	/**
	 * @param null|string $group_id
	 *
	 * @return Page[]
	 */
	public static function get_legal_pages( $group_id = null ) {
		if ( static::$_pages == null ) {
			static::_create_pages();
		}
		if ( $group_id == null ) {
			return static::$_pages;
		}

		return array_filter( static::$_pages, function ( $x ) use ( $group_id ) {
			return $x->group->id == $group_id;
		} );
	}
}
