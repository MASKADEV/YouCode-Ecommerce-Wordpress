<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><select name="<?php echo $name; ?>" class="<?php echo $classes; ?>" id="<?php echo $name; ?>" <?php echo $attrs; ?>>
	<?php
	foreach ( $values as $k => $v ) {
		$k = trim( $k );
		if ( trim( $k ) == $value ) {
			$selected = ' selected="selected"';
		} else {
			$selected = '';
		}
		?>
        <option value="<?php echo esc_attr( $k ); ?>"<?php echo $selected; ?>><?php echo esc_html( $v ); ?></option>
		<?php
	}
	?>
</select>
<span class="wpautoterms-hidden" data-name="<?php echo $name; ?>" data-type="notice"></span>
<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'option-suffix.php';
