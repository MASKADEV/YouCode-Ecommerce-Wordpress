<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
    <h2><?php echo esc_html( $page->title() ); ?></h2>
	<?php settings_errors(); ?>

    <p><?php _e( 'This settings page is only to disable the old Auto ToS & PP plugin (up to version 1.8.2).
        Please note that since our 2.0.0 version we\'re no longer support these settings.', WPAUTOTERMS_SLUG ); ?></p>

    <form method="post" action="options.php"><?php
		settings_fields( $page->id() );
		do_settings_sections( $page->id() );
		submit_button();
		?>
    </form>
</div>