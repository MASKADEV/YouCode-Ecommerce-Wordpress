<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
    <h2><?php echo $title; ?></h2>
	<?php settings_errors(); ?>

    <form method="post" action="options.php">
        <input type="hidden" name="box" value="<?php echo esc_attr( $box_id ); ?>"/><?php
		settings_fields( $page_id );
		do_settings_sections( $page_id );
		if ( isset( $after_section ) ) {
			echo $after_section;
		}
		?>
        <div class="wpautoterms-box-page-submit">
            <input type="submit" name="submit" id="submit" class="button button-primary"
                   value="<?php _e( 'Save Changes' ); ?>"/>
        </div>
        <div class="wpautoterms-box-page-back">
            <a href="edit.php?post_type=<?php echo \wpautoterms\cpt\CPT::type(); ?>&page=wpautoterms_compliancekits">
				<?php _e( 'Back to Compliance Kits', WPAUTOTERMS_SLUG ); ?>
            </a>
        </div>
    </form>
</div>
