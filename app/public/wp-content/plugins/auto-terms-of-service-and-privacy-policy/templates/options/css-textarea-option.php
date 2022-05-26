<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><div class="wpautoterms-flex">
    <div class="wpautoterms-w-3">
        <textarea name="<?php echo $name; ?>" class="<?php echo $classes; ?>" id="<?php echo $name; ?>"<?php
        echo empty( $attrs ) ? '' : ' ' . $attrs;
        ?>><?php echo esc_html( $value ); ?></textarea><?php
            include __DIR__ . DIRECTORY_SEPARATOR . 'option-suffix.php';
            ?>

        <div class="wpautoterms-custom-css-available-selectors">
            <p class="wpautoterms-title"><strong><?php _e( 'Available CSS selectors:', WPAUTOTERMS_SLUG ); ?></strong></p>
            <ul class="wpautoterms-list">
            <?php if ( ! empty( $container_classes ) ) { ?>
                <li>
                    <span class="wpautoterms-selector-type"><?php _e( 'Container IDs:', WPAUTOTERMS_SLUG ); ?></span>
                    <?php
                    foreach ( $container_classes as $cc ) {
                        ?><a class="wpautoterms-selector" href="#" data-control="css-hint" data-value="<?php echo esc_attr( $cc ); ?>"
                             data-target="<?php echo $name; ?>"><?php
                        echo $cc;
                        ?></a>
                        <?php
                    }
                    ?>
                </li>
                <?php
            }
            if ( ! empty( $class_hints ) ) {
                foreach ( $class_hints as $ch_key => $ch_val ) {
                    ?>
                    <li>
                        <span class="wpautoterms-selector-type"><?php echo $ch_key; ?></span>
                        <a href="#" data-control="css-hint" class="wpautoterms-selector" data-value="<?php echo esc_attr( $ch_val ); ?>"
                           data-target="<?php echo $name; ?>"><?php
                            echo $ch_val;
                            ?></a>
                    </li>
                    <?php
                }
            }
            ?>
            </ul>
        </div>
    </div>
</div>