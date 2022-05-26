<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><div class="wpautoterms-options-select-combo <?php echo $classes; ?>">
    <select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
		<?php
		$found = false;
		foreach ( $values as $k => $v ) {
			$k = trim( $k );
			if ( trim( $k ) == $value ) {
				$selected = ' selected="selected"';
				$found = true;
			} else {
				$selected = '';
			}
			?>
            <option value="<?php echo esc_attr( $k ); ?>"<?php echo $selected; ?>><?php echo esc_html( $v ); ?></option>
			<?php
		}
		?>
        <option value="" class="wpautoterms-custom-value-<?php echo $name; ?>"<?php if ( ! $found ) {
			echo ' selected="selected"';
		} ?>><?php _e( 'custom...', WPAUTOTERMS_SLUG ); ?></option>
    </select>
    <p><input class="wpautoterms-hidden" type="text" name="custom_<?php echo $name; ?>" value="<?php if ( ! $found ) {
			echo esc_attr($value);
		} ?>" placeholder="<?php _e( 'Enter a custom value', WPAUTOTERMS_SLUG ); ?>" <?php echo $attrs; ?>/>
        <span class="wpautoterms-hidden" data-name="<?php echo $name; ?> " data-type="notice"></span></p><?php
	if ( ! empty( $tooltip ) ) {
		echo '<p class="wpautoterms-option-tooltip"><small>' . esc_html($tooltip) . '</small></p>';
	}
	?></div>