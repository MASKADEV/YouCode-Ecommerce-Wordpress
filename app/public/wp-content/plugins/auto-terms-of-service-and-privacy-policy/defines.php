<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPAUTOTERMS_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'WPAUTOTERMS_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'WPAUTOTERMS_TAG', 'wpautoterms' );
define( 'WPAUTOTERMS_SLUG', 'wpautoterms' );
define( 'WPAUTOTERMS_OPTION_PREFIX', WPAUTOTERMS_SLUG . '_' );
define( 'WPAUTOTERMS_LEGAL_PAGES_DIR', 'legal-pages' . DIRECTORY_SEPARATOR );
define( 'WPAUTOTERMS_OPTION_ACTIVATED', 'activated' );
define( 'WPAUTOTERMS_LICENSE_RECHECK_TIME', 24 * 60 * 60 );
define( 'WPAUTOTERMS_JS_BASE', WPAUTOTERMS_SLUG . '_base' );
