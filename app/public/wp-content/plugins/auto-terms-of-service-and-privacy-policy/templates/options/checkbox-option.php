<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$value = $value ? 'checked="checked"' : '';
echo '<input type="checkbox" class="' . $classes . '" name="' . $name . '" id="' . $name . '" ' . $value . ' ' . $attrs . '/>';
include __DIR__ . DIRECTORY_SEPARATOR . 'option-suffix.php';