<?php

use wpautoterms\admin\action\Send_Message;
use wpautoterms\admin\Options;
use wpautoterms\cpt\CPT;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @var $page \wpautoterms\admin\page\Help
 */
$current_user = wp_get_current_user();
$data = $page->action->get_data();
$site_name = Options::get_option( Options::SITE_NAME );
$site_url = Options::get_option( Options::SITE_URL );
$email = $current_user->user_email;
$site_info = Send_Message::DEFAULT_SITE_INFO;
$text = '';
$is_error = isset( $_GET['error'] ) ? (bool) $_GET['error'] : false;
$message = isset( $_GET['message'] ) ? esc_html( $_GET['message'] ) : '';
if ( ! empty( $data ) ) {
	if ( isset( $data['site_name'] ) ) {
		$site_name = $data['site_name'];
	}
	if ( isset( $data['site_url'] ) ) {
		$site_url = $data['site_url'];
	}
	if ( isset( $data['email'] ) ) {
		$email = $data['email'];
	}
	if ( isset( $data['text'] ) ) {
		$text = $data['text'];
	}
	if ( isset( $data['site_info'] ) ) {
		$site_info = $data['site_info'];
	}
}
?>
<div class="wrap">
    <h2><?php echo $page->title(); ?></h2>

    <div id="wpautoterms_notice">
		<?php
		if ( ! empty( $message ) ) {
			echo '<div class="updated ' . ( $is_error ? 'error' : 'notice' ) .
			     ' is-dismissible"><p><strong>' . $message . '</strong></p></div>';
		} ?>
    </div>

    <div id="poststuff">
        <div class="wpautoterms-help-page-container">
            <div data-type="accordion" class="wpautoterms-help-page-help">
				<?php
				include "help-q-a.php";
				?>
            </div>

            <div class="wpautoterms-help-page-form-button">
                <span class="wpautoterms-help-page-no-answer-text"><?php _e( 'Couldn\'t find your answer?', WPAUTOTERMS_SLUG ); ?></span>
                <input type="button" id="wpautoterms_contact_button" class="button button-primary"
                       value="<?php _e( 'Send us a message', WPAUTOTERMS_SLUG ); ?>">
            </div>

            <div id="wpautoterms_form_container" class="wpautoterms-help-page-form">
                <h3>
					<?php _e( 'Send Us a Message', WPAUTOTERMS_SLUG ); ?>
                    <a href="#" id="wpautoterms_form_container_hide">
                        <small class="wpautoterms-small"><?php esc_html_e( 'hide', WPAUTOTERMS_SLUG ); ?></small>
                    </a>
                </h3>
                <form action="<?php echo esc_url( $page->api_endpoint() ); ?>" method="post"
                      id="wpautoterms_contact">
                    <input type="hidden" name="site_info" value=""/>
                    <input type="hidden" name="locale" value="<?php echo esc_attr( get_locale() ); ?>"/>
                    <input type="hidden" name="ret_url" value="<?php
					echo esc_url( admin_url( 'edit.php?post_type=' . CPT::type() . '&page=' . $page->id() ) ); ?>">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th scope="row"><label for="site_name">Site Name</label></th>
                            <td><input data-pending="1" type="text" name="site_name" id="site_name" value="<?php
								echo esc_attr( $site_name );
								?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="site_url">Site URL</label></th>
                            <td><input data-pending="1" type="text" name="site_url" id="site_url" value="<?php
								echo esc_attr( $site_url );
								?>"/>
                                <span class="wpautoterms-hidden wpautoterms-option-required" data-name="site_url"
                                      data-type="notice"><?php _e( 'Wrong URL', WPAUTOTERMS_SLUG ); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="email">Email Address</label></th>
                            <td><input data-pending="1" type="text" name="email" id="email" value="<?php
								echo esc_attr( $email );
								?>"/>
                                <span class="wpautoterms-hidden wpautoterms-option-required" data-name="email"
                                      data-type="notice"><?php _e( 'Wrong email address', WPAUTOTERMS_SLUG ); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="site_info_preview">Site Information</label></th>
                            <td><select data-pending="1" name="site_info_preview" id="site_info_preview"><?php
									foreach ( $page->action->site_info_options() as $option => $label ) {
										?>
                                        <option value="<?php echo $option; ?>"<?php
										if ( $site_info === $option ) {
											echo ' selected';
										}
										?>><?php echo ucwords( $label ); ?></option>
										<?php
									}
									?>
                                </select>
                                <p class="wpautoterms-help-page-site-info-notice" data-type="notice">
									<?php _e( 'Please choose Extended if you are submitting a bug so we can troubleshoot it.', WPAUTOTERMS_SLUG ); ?>
                                </p>
                                <div class="wpautoterms-hidden wpautoterms-option-info" data-name="site_info_preview"
                                     data-type="info"></div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="text">Message</label></th>
                            <td>
                                <textarea name="text" id="text"
                                          data-pending="1"><?php echo esc_html( $text ); ?></textarea>
                                <div>
                                    <div class="wpautoterms-hidden wpautoterms-option-info wpautoterms-pull-right wpautoterms-clear"
                                         data-target="[name='text']" data-type="char-counter" data-max="<?php
									echo $page->action->max_text_length();
									?>"></div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="wpautoterms-hidden wpautoterms-form-errors" data-name="form"
                         data-type="notice"><?php _e( 'Please fill in the required information in order to send the message.', WPAUTOTERMS_SLUG ); ?></div>
                    <div><?php _e( 'You will be redirected to WPAutoTerms website to complete this form submission.', WPAUTOTERMS_SLUG ); ?></div>

                    <p class="submit">
                        <input name="submit_button" data-pending="1" class="button button-primary"
                               value="<?php _e( 'Send Message', WPAUTOTERMS_SLUG ); ?>" type="submit">
                        <span class="wpautoterms-hidden" id="wpautoterms_sending">
                        <?php _e( 'Sending...', WPAUTOTERMS_SLUG ); ?>
                    </span>
                    </p>
                </form>
            </div>
        </div>
    </div>

</div>
<script type="text/html" id="tmpl-wpautoterms-site-info">
	<?php _e( 'What will be sent:', WPAUTOTERMS_SLUG ); ?>
    <strong id="wpautoterms_short_info">{{ data.preview }}</strong>
    <# if (data.full!==null){ #>
    <a href="#" data-type="expander"
       data-target="#wpautoterms_full_info,#wpautoterms_short_info,#wpautoterms_hide_title,#wpautoterms_show_title">
        <span id="wpautoterms_show_title"><?php _e( 'Show all', WPAUTOTERMS_SLUG ); ?></span>
        <span class="wpautoterms-hidden"
              id="wpautoterms_hide_title"><?php _e( '(hide)', WPAUTOTERMS_SLUG ); ?></span>
    </a>
    <div class="wpautoterms-hidden" id="wpautoterms_full_info">
        <pre id="site_info_text">{{ data.full }}</pre>
    </div>
    <# } #>
</script>