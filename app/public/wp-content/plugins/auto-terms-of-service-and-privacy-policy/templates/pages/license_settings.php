<?php

use wpautoterms\admin\action\Recheck_License;
use wpautoterms\admin\action\Transfer_License;
use wpautoterms\admin\Options;
use wpautoterms\api\License;
use wpautoterms\api\License_Info_Vo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @var $page \wpautoterms\admin\page\Base
 */

/**
 * @var $info \wpautoterms\api\License_Info_Vo
 */

function url_base( $s ) {
	$ssl = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
	$sp = strtolower( $s['SERVER_PROTOCOL'] );
	$protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
	$port = $s['SERVER_PORT'];
	$port = ( ( ! $ssl && $port == '80' ) || ( $ssl && $port == '443' ) ) ? '' : ':' . $port;
	$host = isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null;
	$host = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

	return $protocol . '://' . $host;
}

$ret_url = url_base( $_SERVER ) . $_SERVER['REQUEST_URI'];

?>
<div class="wrap">
    <h2><?php echo esc_html( $page->title() ); ?></h2>

	<?php settings_errors(); ?>

    <form method="post" action="options.php" class="license-page">

    		<?php
    		settings_fields( $page->id() );
    		do_settings_sections( $page->id() );
    		?>

        <table class="form-table license-key-save-recheck">
            <tbody>
              <tr>
                  <th scope="row">
                      &nbsp;
                  </th>
                  <td>
                      <p class="submit">
            						<?php
            						submit_button( null, 'primary', 'submit', false );
            						?>
                        <input type="button" name="wpautoterms_recheck" id="wpautoterms_recheck"
                                 class="button button-large"
                                 value="<?php _ex( 'Recheck', 'license settings page', WPAUTOTERMS_SLUG ); ?>"
                                 data-action="<?php esc_attr_e( Recheck_License::NAME ); ?>"/>
                      </p>
                  </td>
              </tr>
            </tbody>
        </table>

    </form>
    
    <hr/>
    
    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row"><?php _e( 'License type', WPAUTOTERMS_SLUG ); ?></th>
            <td>
                <div>
  	                <span id="wpautoterms_license_status"><?php echo $info->license_type_string(); ?></span>
                </div>
            </td>
        </tr>
        <tr id="wpautoterms_websites_limit_row"<?php echo $info->should_show_websites() ? '' : ' class="wpautoterms-hidden"'; ?>>
            <th scope="row"><?php _e( 'Allowed websites', WPAUTOTERMS_SLUG ); ?></th>
            <td>
                <div>
  	                <span id="wpautoterms_websites_limit"><?php echo $info->websites_info(); ?></span>
                </div>
                <div class="wpautoterms-license-upgrade" id="wpautoterms_license_upgrade"
					          <?php echo $info->max_sites != 0 ? '' : 'style="display:none"'; ?>>
                    <form method="post" action="<?php esc_attr_e( WPAUTOTERMS_UPGRADE_URL ); ?>">
                        <input type="hidden" name="key"
                               value="<?php esc_attr_e( Options::get_option( License::OPTION_KEY, true ) ); ?>"/>
                        <input type="hidden" name="ret_url" value="<?php esc_attr_e( $ret_url ); ?>"/>
                        <input type="submit" class="button button-large"
                               value="<?php _e( 'Upgrade License key to allow more websites', WPAUTOTERMS_SLUG ); ?>"/>
                    </form>
                </div>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e( 'Summary', WPAUTOTERMS_SLUG ); ?></th>
            <td>
                <div>
  	                <p id="wpautoterms_license_summary"><?php echo $info->summary(); ?></p>
                    <?php if($info->license_type_string() == "free") { ?>
                      <p>
                        <br />
                        Most features of our plugin work without a License key.
                        <br />If you're interested in having access to all plugin features,
                        <br />consider purchasing a License key.
                        <br />
                        <br />
                        <a class="button" href="<?php echo esc_url( WPAUTOTERMS_PURCHASE_URL ); ?>" target="wpautotermsGetLicense">
                          <?php _e( 'Purchase License', WPAUTOTERMS_SLUG ); ?>
                        </a>
                      </p>
                      <br />
                      <p>
                        If you already have a License key and the "License type" is "free" above,
                        <br />
                        contact us to troubleshoot this.
                      </p>
                    <?php } ?>
                </div>
                
                <!-- Transfer to New Website -->
                <div id="wpautoterms_transfer" class="wpautoterms-hidden">
                    <small>
                        <a href="#" id="wpautoterms_transfer_open">
							            <?php _e( 'You can request to transfer the License key to this new website', WPAUTOTERMS_SLUG ); ?>.
                        </a>
                    </small>
                </div>
                <div id="wpautoterms_request_transfer" class="wpautoterms-hidden ">
                    <div class="wpautoterms-transfer-entry">
                        <label for="wpautoterms_transfer_key"><?php _e( 'License key', WPAUTOTERMS_SLUG ); ?></label>
                        <input type="text" class="wpautoterms-license-key" name="wpautoterms_transfer_key"
                               data-control="wpautoterms_transfer_key" disabled/>
                    </div>
                    <div class="wpautoterms-transfer-entry">
                        <label for="wpautoterms_transfer_email"><?php _e( 'Confirm email', WPAUTOTERMS_SLUG ); ?></label>
                        <input type="text" class="wpautoterms-transfer-email" name="wpautoterms_transfer_email"
                               data-control="wpautoterms_transfer_email" placeholder="me@domain.com"/>
                    </div>
                    <div class="wpautoterms-transfer-entry">
                        <label for="wpautoterms_transfer_site"><?php _e( 'Old website address', WPAUTOTERMS_SLUG ); ?></label>
                        <input type="text" class="wpautoterms-transfer-site" name="wpautoterms_transfer_site"
                               data-control="wpautoterms_transfer_site" placeholder="website.com"/>
                    </div>
                    <div class="wpautoterms-hidden wpautoterms-form-errors wpautoterms-transfer-entry"
                         data-control="wpautoterms_transfer_error">
                        <br/>
						            <?php _e( 'License key transfer to a new site address failed. Please contact us from WP AutoTerms > Help > Send us a message.', WPAUTOTERMS_SLUG ); ?>
                    </div>
                    <div class="wpautoterms-transfer-entry">
                        <br />
                        <input type="button" class="button button-primary" data-control="wpautoterms_transfer_button"
                               value="<?php _e( 'Transfer License key', WPAUTOTERMS_SLUG ); ?>"
                               data-action="<?php esc_attr_e( Transfer_License::NAME ); ?>"/>
                    </div>
                    <br/>
                    <hr/>
                    <br/>
                    <p>The <strong>old website address</strong> and <strong>email address</strong> must match the
                        website address and email address assigned to the License key.</p>
                    <p>For example: If you purchased the License key for "domain1.com" and you wish to move it to
                        "domain2.com", your old website address is "domain1.com".</p>
                </div>

                <!-- Transfer IP Address -->
                <div id="wpautoterms_transfer_address" class="wpautoterms-transfer-address wpautoterms-hidden">
                    If your website server's IP address has changed, 
                    <a href="#" id="wpautoterms_transfer_address_open" class=""><?php _e( 'transfer the License key of your website to this new IP address', WPAUTOTERMS_SLUG ); ?></a>.
                </div>
                <br />
                <div id="wpautoterms_request_transfer_address" class="wpautoterms-request-transfer-address wpautoterms-hidden">
                    <div class="wpautoterms-transfer-entry">
                        <label for="wpautoterms_transfer_key"><?php _e( 'License key', WPAUTOTERMS_SLUG ); ?></label>
                        <input type="text" class="wpautoterms-license-key" name="wpautoterms_transfer_key"
                               data-control="wpautoterms_transfer_key" disabled/>
                    </div>
                    <div class="wpautoterms-transfer-entry">
                        <label for="wpautoterms_transfer_email"><?php _e( 'Confirm email', WPAUTOTERMS_SLUG ); ?></label>
                        <input type="text" class="wpautoterms-transfer-email" name="wpautoterms_transfer_email"
                               data-control="wpautoterms_transfer_email" placeholder="me@domain.com"/>
                    </div>
                    <div class="wpautoterms-hidden wpautoterms-form-errors wpautoterms-transfer-entry"
                         data-control="wpautoterms_transfer_error">
                        <br/>
						            <?php _e( 'License key transfer to a new IP address failed. Please contact us from WP AutoTerms > Help > Send us a message.', WPAUTOTERMS_SLUG ); ?>
                    </div>
                    <div class="wpautoterms-transfer-entry">
                        <br/>
                        <input type="button" class="button button-primary" data-control="wpautoterms_transfer_button"
                               value="<?php _e( 'Transfer License key', WPAUTOTERMS_SLUG ); ?>"
                               data-action="<?php esc_attr_e( Transfer_License::NAME ); ?>"/>
                    </div>
                    <br/>
                    <hr/>
                    <br/>
                    <p>The <strong>email address</strong> must match the email address assigned to the License key.</p>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>