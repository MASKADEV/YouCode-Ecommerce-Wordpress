<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?><div id="<?php echo esc_attr($section_id); ?>" class="legal-pages-form-section">
    <?php
    if($header !== false) {
    ?>
    <h2 class="legal-pages-form-section-header"><?php echo esc_html($header); ?></h2><?php
}
