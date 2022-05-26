<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><textarea name="<?php echo $name; ?>" class="<?php echo $classes; ?>" id="<?php echo $name; ?>"<?php
echo empty( $attrs ) ? '' : ' ' . $attrs;
?>>
<?php echo esc_html($value); ?>
</textarea><?php
include __DIR__ . DIRECTORY_SEPARATOR . 'option-suffix.php';
