<?php

namespace wpautoterms\shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface I_Shortcode_Handler {
	function handle( $values, $content );
}