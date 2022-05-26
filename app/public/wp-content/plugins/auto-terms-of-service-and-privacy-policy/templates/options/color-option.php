<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<input type="text" name="' . $name . '" id="' . $name . '" value="' . esc_attr($value) .
     '" class="wpautoterms-color-selector ' . $classes . '" ' . $attrs . '/>';
include __DIR__ . DIRECTORY_SEPARATOR . 'option-suffix.php';