<?php
/**
 * Plugin Name:       aThemes Blocks
 * Description:       aThemes Blocks is a Gutenberg plugin extending the WordPress editor with awesome blocks.
 * Version:           1.0.4
 * Author:            aThemes
 * Author URI:        https://athemes.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       athemes-blocks
 * Domain Path:       /languages
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ATBLOCKS_FILE',  __FILE__ );

// Plugin Loader
if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
	add_action( 'admin_notices', 'athemes_blocks_uncompatible_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '5.5', '>=' ) ) {
	add_action( 'admin_notices', 'athemes_blocks_uncompatible_wp_version' );
} else {
	require_once 'classes/class-athemes-blocks-loader.php';
}

/**
 * Uncompatible PHP version notice
 * 
 */
function athemes_blocks_uncompatible_php_version() {
	$message        = sprintf( esc_html__( 'aThemes Blocks plugin requires PHP version %s+. Please update your server PHP version to get the plugin working.', 'athemes-blocks' ), '5.6' );
	$message_output = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $message_output );
}

/**
 * Uncompatible WP version notice
 * 
 */
function athemes_blocks_uncompatible_wp_version() {
	$message        = sprintf( esc_html__( 'aThemes Blocks plugin requires WordPress version %s+. Please update the WordPress version to get the plugin working.', 'athemes-blocks' ), '5.5' );
	$message_output = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $message_output );
}