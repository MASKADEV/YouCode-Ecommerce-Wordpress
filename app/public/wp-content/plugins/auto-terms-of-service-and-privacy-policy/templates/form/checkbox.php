<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$full_id = trim($control_id, '[]') . '_' . esc_attr($value);
?><input type="checkbox" name="<?php echo $control_id; ?>" id="<?php echo $full_id; ?>" value="<?php echo esc_attr($value); ?>" />
<label for="<?php echo $full_id; ?>"><?php echo esc_html($label); ?></label>