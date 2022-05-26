<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WPAUTOTERMS_API_URL' ) ) {
  define( 'WPAUTOTERMS_API_URL', 'https://app.wpautoterms.com/' );
  // define( 'WPAUTOTERMS_API_URL', 'https://app.staging.wpautoterms.com/' );
  // define( 'WPAUTOTERMS_API_URL', 'http://127.0.0.1:5000/' );
}

define( 'WPAUTOTERMS_PURCHASE_URL', WPAUTOTERMS_API_URL . 'product/v1/buy' );
define( 'WPAUTOTERMS_UPGRADE_URL', WPAUTOTERMS_API_URL . 'product/v1/upgrade' );
