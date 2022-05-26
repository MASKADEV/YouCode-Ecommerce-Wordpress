<?php

namespace wpautoterms\cpt;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


abstract class CPT {
	const ROLE = 'manage_wpautoterms_pages';
	const ROLE_EDITOR = 'manage_wpautoterms_pages_editor';
	const BASE_ROLE = 'editor';

	protected static $_taxonomies = array( 'category' );

	public static function init() {
		add_filter( 'theme_' . static::type() . '_templates', array( __CLASS__, '_filter_templates' ), 10, 2 );
		add_filter( 'map_meta_cap', array( __CLASS__, '_map_meta_cap' ), 10, 4 );
		add_action( 'admin_menu', array( __CLASS__, '_remove_taxonomies' ) );
	}

	public static function edit_cap() {
		return 'edit_' . static::cap_plural();
	}

	public static function type() {
		return WPAUTOTERMS_SLUG . '_page';
	}

	public static function cap_singular() {
		return WPAUTOTERMS_SLUG . '_page';
	}

	public static function cap_plural() {
		return WPAUTOTERMS_SLUG . '_pages';
	}

	public static function caps() {
		$p = static::cap_plural();

		return array(
			'edit_' . $p => true,
			'edit_others_' . $p => true,
			'edit_private_' . $p => true,
			'edit_published_' . $p => true,
			'read_private_' . $p => true,
			'delete_' . $p => true,
			'delete_others_' . $p => true,
			'delete_private_' . $p => true,
			'delete_published_' . $p => true,
			'publish_' . $p => true,
		);
	}

	public static function register( $slug ) {
		$labels = array(
			'name' => __( 'Legal Pages', WPAUTOTERMS_SLUG ),
			'all_items' => __( 'All Legal Pages', WPAUTOTERMS_SLUG ),
			'singular_name' => __( 'Legal Page', WPAUTOTERMS_SLUG ),
			'add_new' => __( 'Add Legal Pages', WPAUTOTERMS_SLUG ),
			'add_new_item' => __( 'Add Legal Page', WPAUTOTERMS_SLUG ),
			'edit' => __( 'Edit', WPAUTOTERMS_SLUG ),
			'edit_item' => __( 'Edit Legal Page', WPAUTOTERMS_SLUG ),
			'new_item' => __( 'New Legal Page', WPAUTOTERMS_SLUG ),
			'view' => __( 'View', WPAUTOTERMS_SLUG ),
			'view_item' => __( 'View Legal Page', WPAUTOTERMS_SLUG ),
			'search_items' => __( 'Search Legal Pages', WPAUTOTERMS_SLUG ),
			'not_found' => __( 'No legal pages exist.', WPAUTOTERMS_SLUG ),
			'not_found_in_trash' => __( 'No legal pages found in Trash', WPAUTOTERMS_SLUG ),
			'parent' => __( 'Parent Legal Pages', WPAUTOTERMS_SLUG ),
			'plugin_listing_table_title_cell_link' => __( 'Wpautoterms', WPAUTOTERMS_SLUG ),
			'menu_name' => __( 'WP AutoTerms', WPAUTOTERMS_SLUG ),
		);

		$args = array(
			'labels' => $labels,
			'hierarchical' => true,
			'supports' => array( 'title', 'editor', 'revisions', 'page-attributes', 'custom-fields', 'excerpt' ),
			'public' => true,
			'show_ui' => true,
			//'show_in_nav_menus'   => false,
			'show_in_menu' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => array( 'slug' => $slug ),
			'map_meta_cap' => true,
			'capability_type' => array( static::cap_singular(), static::cap_plural() ),
			'menu_icon' => WPAUTOTERMS_PLUGIN_URL . 'images/icon.png',
			'show_admin_column' => true,
			'taxonomies' => static::$_taxonomies
		);

		register_post_type( static::type(), $args );
	}

	public static function register_roles() {
		add_role( static::ROLE, __( 'WPAutoTerms Pages Editor (additional)' ), static::caps() );
		$role = get_role( static::BASE_ROLE );
		if ( ! empty( $role ) ) {
			add_role( static::ROLE_EDITOR,
				__( 'Editor + WPAutoTerms Pages Editor' ),
				array_merge( $role->capabilities, static::caps() ) );
		}
	}

	protected static function _remove_role_caps( $role_name ) {
		$role = get_role( $role_name );
		if ( $role != null ) {
			foreach ( static::caps() as $k => $v ) {
				$role->remove_cap( $k );
			}
		}
	}

	public static function unregister_roles() {
		static::_remove_role_caps( static::ROLE );
		static::_remove_role_caps( static::ROLE_EDITOR );
		remove_role( static::ROLE );
		$users = get_users( array( 'role' => static::ROLE_EDITOR ) );
		if ( ! empty( $users ) ) {
			/**
			 * @var $user \WP_User
			 */
			foreach ( $users as $user ) {
				$user->add_role( static::BASE_ROLE );

				$user->remove_role( static::ROLE_EDITOR );
			}
		}
		static::_remove_role_caps( static::ROLE_EDITOR );
		remove_role( static::ROLE_EDITOR );
		static::_remove_role_caps( 'administrator' );
	}

	/**
	 * @param [] $post_templates
	 * @param \WP_Theme $theme
	 *
	 * @return array
	 */
	public static function _filter_templates( $post_templates, $theme ) {
		return array_merge( $post_templates, $theme->get_page_templates() );
	}

	public static function endswith( $haystack, $needle ) {
		return $needle === substr( $haystack, - strlen( $needle ) );
	}

	protected static function is_current_cap( $cap ) {
		return static::endswith( $cap, static::cap_singular() ) || static::endswith( $cap, static::cap_plural() );
	}

	public static function _map_meta_cap( $caps, $cap, $user_id, $args ) {
		if ( isset( $args[0] ) ) {
			$ok = false;
			foreach ( $caps as $c ) {
				if ( static::is_current_cap( $caps[0] ) ) {
					$ok = true;
					break;
				}
			}
			if ( ! $ok ) {
				return $caps;
			}
		} elseif ( ! static::is_current_cap( $cap ) ) {
			return $caps;
		}
		if ( is_super_admin( $user_id ) ) {
			return array();
		}

		return $caps;
	}

	public static function _remove_taxonomies() {
		foreach ( static::$_taxonomies as $t ) {
			remove_submenu_page( 'edit.php?post_type=' . static::type(),
				'edit-tags.php?taxonomy=' . $t . '&amp;post_type=' . static::type() );
		}
	}
}
