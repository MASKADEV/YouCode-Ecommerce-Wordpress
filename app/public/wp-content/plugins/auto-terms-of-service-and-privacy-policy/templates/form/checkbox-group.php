<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?><ul class="legal-pages-form-checkbox-group">
    <?php
    $control_id .= '[]';
    foreach ($values as $value => $label) {
        ?><li><?php \wpautoterms\print_template('form/checkbox',
            compact('control_id', 'label', 'value')); ?></li>
        <?php
    }
    ?>
</ul>