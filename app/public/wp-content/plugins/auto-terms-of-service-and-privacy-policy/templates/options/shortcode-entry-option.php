<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include __DIR__ . DIRECTORY_SEPARATOR . 'text-option.php';
echo '<p class="wpautoterms-shortcode-option ' . $classes . '">' . __( 'Short code:', WPAUTOTERMS_SLUG ) .
     ' [wpautoterms ' . esc_html( substr( $name, strlen( WPAUTOTERMS_OPTION_PREFIX ) ) ) . ']</p>';