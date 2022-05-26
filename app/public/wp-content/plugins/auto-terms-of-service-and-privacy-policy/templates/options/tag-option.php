<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><p><?php _e( 'Choose a tag from your current list of tags:', WPAUTOTERMS_SLUG ); ?></p>
<select class="wpautoterms-options-select-tag <?php echo $classes; ?>" name="<?php echo $name; ?>[]"
        id="<?php echo $name; ?>[]">
	<?php
	$found = false;
	if ( ! empty( $values ) ) {
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
	}
	?>
    <option value="0"><?php _e( 'new tag...', WPAUTOTERMS_SLUG ); ?></option>
</select>
<div class="wpautoterms-options-new-tag<?php if ( ! empty( $values ) ) {
	echo ' wpautoterms-hidden';
} ?>" id="new-tag-<?php echo $name; ?>[]"><p><?php _e( 'Add a new tag:', WPAUTOTERMS_SLUG ); ?></p>
    <p><input type="text" name="<?php echo $name; ?>[]" id="<?php echo $name; ?>[]" value=""
              placeholder="<?php _e( 'Enter new tag', WPAUTOTERMS_SLUG ); ?>"/></p>
</div>