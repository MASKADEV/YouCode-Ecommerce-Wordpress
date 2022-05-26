<?php

namespace wpautoterms\cpt;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Admin_Columns {
	const COL_CB = 'cb';
	const COL_TITLE = 'title';
	const COL_STATUS = 'status';
	const COL_SHORTCODE = 'shortcode';
	const COL_LAST_DATE = 'last_date';
	const COL_DATE = 'date';

	static function init() {
		add_filter( 'manage_edit-' . CPT::type() . '_columns', array( __CLASS__, 'edit_columns' ) );
		add_filter( 'manage_edit-' . CPT::type() . '_sortable_columns', array(
			__CLASS__,
			'sortable_columns'
		) );
		add_filter( 'display_post_states', array( __CLASS__, 'display_post_states' ), 10, 2 );
		add_action( 'manage_' . CPT::type() . '_posts_custom_column',
			array( __CLASS__, 'manage_columns' ),
			10,
			2 );
	}

	static function edit_columns( $columns ) {
		return array(
			static::COL_CB => '<input type="checkbox" />',
			static::COL_TITLE => __( 'Title', WPAUTOTERMS_SLUG ),
			static::COL_STATUS => __( 'Status', WPAUTOTERMS_SLUG ),
			static::COL_SHORTCODE => __( 'Shortcode', WPAUTOTERMS_SLUG ),
			static::COL_LAST_DATE => __( 'Last Effective Date', WPAUTOTERMS_SLUG ),
			static::COL_DATE => __( 'Date', WPAUTOTERMS_SLUG )
		);
	}

	static function sortable_columns( $columns ) {
		$columns[ static::COL_STATUS ] = static::COL_STATUS;
		$columns[ static::COL_LAST_DATE ] = static::COL_LAST_DATE;
		$columns[ static::COL_SHORTCODE ] = static::COL_SHORTCODE;

		return $columns;
	}

	static function manage_columns( $column, $post_id ) {
		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			return;
		}
		switch ( $column ) {
			case static::COL_LAST_DATE:
				if ( $post->post_status == 'publish' ) {
					echo esc_html( get_post_modified_time( get_option( 'date_format' ), false, $post, true ) );
				}
				break;
			case static::COL_SHORTCODE:
				if ( $post->post_status == 'publish' ) {
					echo esc_html( '[' . WPAUTOTERMS_SLUG . ' page="' . $post->post_name . '"]' );
				} else {
					echo '<abbr title="' .
					     __( 'Publish page to get shortcode.', WPAUTOTERMS_SLUG ) . '">' .
					     _x( 'N/A', 'Legal pages list', WPAUTOTERMS_SLUG )
					     . '</abbr>';
				}
				break;
			case static::COL_STATUS:
				echo esc_html( $post->post_status );
				break;
		}
	}

	static function display_post_states( $post_states, $post ) {
		if ( $post->post_type == CPT::type() ) {
			return array();
		}

		return $post_states;
	}
}
