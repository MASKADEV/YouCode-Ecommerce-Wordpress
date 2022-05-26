<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?><ul class="legal-pages-form-radio">
    <?php
    foreach ($values as $k => $v) {
        $full_id = $control_id . '_' . esc_attr($k);
        ?>
        <li><input type="radio" name="<?php echo $control_id; ?>" id="<?php echo $full_id; ?>"
                   value="<?php echo esc_attr($k); ?>" />
            <label for="<?php echo $full_id; ?>"><?php echo esc_html($v); ?></label></li>
        <?php
    }
    ?>
</ul>