<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'defines.php';
require_once join( DIRECTORY_SEPARATOR, array( __DIR__, 'includes', 'cpt', 'cpt.php' ) );

wpautoterms\cpt\CPT::unregister_roles();

global $wpdb;

$wpdb->query( $wpdb->prepare( "DELETE FROM $wpdb->options WHERE option_name LIKE %s", WPAUTOTERMS_OPTION_PREFIX . '%' ) );

flush_rewrite_rules();
