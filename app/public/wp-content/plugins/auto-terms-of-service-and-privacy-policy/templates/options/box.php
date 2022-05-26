<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \wpautoterms\cpt\CPT;

/**
 * @var $box \wpautoterms\box\Box
 */

/**
 * @var $enabled bool
 */

/**
 * @var $status_text string
 */

/**
 * @var $enable_button_text string
 */
?>
<div class="postbox wpautoterms-options-box">
    <h3><?php echo $box->title(); ?>
    </h3>
    <div class="inside">
        <p class="box-infotip"><?php echo $box->infotip(); ?></p>
        <p class="box-status <?php echo $enabled ? 'enabled' : 'disabled'; ?>"
           id="status_<?php echo $box->enable_action_id(); ?>"><?php echo $status_text; ?></p>
    </div>
    <div class="wpautoterms-box-enable-button">
		<?php if ( ! isset( $license_paid ) || ( isset( $license_paid ) && $license_paid ) ) { ?>
            <a class="button" data-type="enable" href="javascript:void(0);"
               id="<?php echo $box->enable_action_id(); ?>">
				<?php echo $enable_button_text; ?>
            </a>
		<?php } else { ?>
            <a class="button" href="<?php echo esc_url( WPAUTOTERMS_PURCHASE_URL ); ?>" target="wpautotermsGetLicense">
				<?php _e( 'Purchase License', WPAUTOTERMS_SLUG ); ?>
            </a>
		<?php } ?>
    </div>
    <div class="wpautoterms-box-configure-button">
        <a class="button button-primary"
           href="edit.php?post_type=<?php echo CPT::type(); ?>&page=wpautoterms_compliancekits&box=<?php echo $box->id(); ?>">
			<?php _e( 'Configure', WPAUTOTERMS_SLUG ); ?>
        </a>
    </div>
</div>