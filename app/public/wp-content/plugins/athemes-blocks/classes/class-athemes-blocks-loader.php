<?php 
/**
 * aThemes Blocks - Class Loader
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'ATBLOCKS_Loader' ) ) {

    class ATBLOCKS_Loader {

        public function __construct() {

            // Define Constants
            $this->define_constants();

            // Load translation textdomain
            $this->load_textdomain();

            // Start plugin
            add_action( 'plugins_loaded', array( $this, 'start_plugin' ) );
            
        }

        /**
         * Define Constants
         * 
         */
        public function define_constants() {
            define( 'ATBLOCKS_VERSION', '1.0.0' );
            define( 'ATBLOCKS_URL', plugin_dir_url( ATBLOCKS_FILE ) );
            define( 'ATBLOCKS_DIR', plugin_dir_path( ATBLOCKS_FILE ) );
        }

        /**
         * Load translation textdomain
         * 
         */
        public function load_textdomain() {
            load_plugin_textdomain( 'athemes-blocks', false, dirname( plugin_basename( ATBLOCKS_FILE ) ) . '/languages' );
        }

        /**
         *  Start Plugin
         * 
         */
        public function start_plugin() {
            require_once ATBLOCKS_DIR . 'classes/class-athemes-blocks-helpers.php';
            require_once ATBLOCKS_DIR . 'classes/class-athemes-blocks-css.php';
            require_once ATBLOCKS_DIR . 'classes/class-athemes-blocks-init.php';
        }

    }

    new ATBLOCKS_Loader;
}