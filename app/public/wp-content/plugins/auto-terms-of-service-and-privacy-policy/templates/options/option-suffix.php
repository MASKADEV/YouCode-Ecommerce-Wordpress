<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo ' <span class="wpautoterms-hidden" data-name="' . $name . '" data-type="notice"></span>';
if ( ! empty( $tooltip ) ) {
	echo '<p class="wpautoterms-option-tooltip"><small>' . $tooltip . '</small></p>';
}
