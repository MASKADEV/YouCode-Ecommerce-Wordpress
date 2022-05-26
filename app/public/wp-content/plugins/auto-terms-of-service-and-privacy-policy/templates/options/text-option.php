<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<input type="text"  class="' . $classes . '" name="' . $name . '" id="' . $name . '" value="' . esc_attr($value) . '" ' . $attrs . '/>';
include __DIR__ . DIRECTORY_SEPARATOR . 'option-suffix.php';