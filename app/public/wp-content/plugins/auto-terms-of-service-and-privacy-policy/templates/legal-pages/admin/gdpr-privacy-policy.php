<?php
if (!defined( 'ABSPATH' )) {
	exit;
}
?>
<?php
use wpautoterms\admin\form\Controls;
use wpautoterms\admin\form\Section;
?>

<div class="legal-page-inner">

	<h1><?php _e( 'GDPR Privacy Policy', WPAUTOTERMS_SLUG ); ?></h1>
    <input type="text" name="country" readonly class="regular-text" value="<?php echo do_shortcode('[wpautoterms country]'); ?>" required="required" />

	<?php Section::begin('website_url_section', __('What is your website URL?', WPAUTOTERMS_SLUG)); ?>
		<input type="text" name="website_url" class="regular-text" value="<?php echo do_shortcode('[wpautoterms site_url]'); ?>" placeholder="Enter your website URL" required="required" />
		<p class="text-muted text-small text-note">e.g. http://www.MyWordPress-Site.com</p>
	<?php Section::end(); ?>


	<?php Section::begin('website_name_section', __('What is your website name?', WPAUTOTERMS_SLUG)); ?>
		<input type="text" name="website_name" class="regular-text" value="<?php echo do_shortcode('[wpautoterms site_name]'); ?>" placeholder="Enter your website name" required="required" />
		<p class="text-muted text-small text-note">e.g. My WordPress Site</p>
	<?php Section::end(); ?>

	<?php Section::begin('country_name_section', __('Your country', WPAUTOTERMS_SLUG)); ?>
		<input type="text" name="country" readonly class="regular-text" value="<?php echo do_shortcode('[wpautoterms country]'); ?>" required="required" />
        <input type="hidden" name="country_code" value="<?php echo esc_attr($country_code); ?>"/>
        <input type="hidden" name="state_code" value="<?php echo esc_attr($state_code); ?>"/>
	<?php Section::end(); ?>

    <?php
        $state = do_shortcode('[wpautoterms state]');
        if(!empty($state)) {
	        Section::begin('state_name_section', __('Your state', WPAUTOTERMS_SLUG));
	    ?>
        <input type="text" name="state" readonly class="regular-text" value="<?php echo $state ?>" required="required"/>
	    <?php
            Section::end();
        }
    ?>

	<?php Section::begin('company_name_section', __('What is your company name?', WPAUTOTERMS_SLUG)); ?>
		<p class="text-muted text-small text-note">If this WordPress website is not operated/owned by a registered company or entity, please enter your website name instead.</p>
		<input type="text" name="company_name" class="regular-text" value="<?php echo do_shortcode('[wpautoterms company_name]'); ?>" placeholder="Enter your company name" required="required" />
	<?php Section::end(); ?>


	<?php Section::begin('types_of_data_collected_section', __('What kind of personal information you ask users to provide you?', WPAUTOTERMS_SLUG)); ?>
		<?php
			Controls::checkbox_group( 'types_of_data_collected', array (
				'email' => __( 'Email address', WPAUTOTERMS_SLUG ),
				'name' => __( 'First name and last name', WPAUTOTERMS_SLUG ),
				'phone_number' => __( 'Phone number', WPAUTOTERMS_SLUG ),
				'address' => __( 'Address, State, Province, ZIP/Postal code, City', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>


	<?php Section::begin('clause_communications_section', __('Do you email users with promotional emails (newsletters, marketing or promotional materials)', WPAUTOTERMS_SLUG)); ?>
	<?php
		Controls::radio( 'clause_communications', array (
			'yes' => __( 'Yes, we send out email newsletters to users', WPAUTOTERMS_SLUG ),
			'no' => __( 'No', WPAUTOTERMS_SLUG ),
		));
	?>
	<?php Section::end(); ?>
	<?php
		Section::begin('clause_communications_unsubscribe_method_section', __('How can users unsubscribe from your newsletters?', WPAUTOTERMS_SLUG));
		Section::show_if( 'clause_communications_yes' );
	?>
		<?php
			Controls::checkbox_group( 'clause_communications_unsubscribe_method', array (
				'By clicking the Unsubscribe link' => __( 'By clicking the Unsubscribe link or by following the instructions from each email we send', WPAUTOTERMS_SLUG ),
				'By contacting us' => __( 'By contacting us', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>


	<?php Section::begin('service_providers_analytics_section', __('Do you use analytics tools (such as Google Analytics)?', WPAUTOTERMS_SLUG)); ?>
		<?php
			Controls::radio( 'service_providers_analytics', array (
				'yes' => __( 'Yes, we use Google Analytics or other related tools', WPAUTOTERMS_SLUG ),
				'no' => __( 'No', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>
	<?php
		Section::begin('service_providers_analytics_list_section', __('Select the tools you use for tracking and/or analytics)?', WPAUTOTERMS_SLUG));
		Section::show_if( 'service_providers_analytics_yes' );
	?>
		<?php
			Controls::checkbox_group( 'service_providers_analytics_list', array (
				'Google Analytics' => __( 'Google Analytics', WPAUTOTERMS_SLUG ),
				'Piwik or Matomo' => __( 'Piwik or Matomo', WPAUTOTERMS_SLUG ),
				'Clicky' => __( 'Clicky', WPAUTOTERMS_SLUG ),
				'Statcounter' => __( 'Statcounter', WPAUTOTERMS_SLUG ),
				'Mixpanel' => __( 'Mixpanel', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>


	<?php Section::begin('service_providers_payments_section', __('Can users pay for your products/services?', WPAUTOTERMS_SLUG)); ?>
		<?php
			Controls::radio( 'service_providers_payments', array (
				'yes' => __( 'Yes, users can pay for our products/services on website', WPAUTOTERMS_SLUG ),
				'no' => __( 'No', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>
	<?php
		Section::begin('service_providers_payments_list_section', __('Select the payment processors', WPAUTOTERMS_SLUG));
		Section::show_if( 'service_providers_payments_yes' );
	?>
		<?php
			Controls::checkbox_group( 'service_providers_payments_list', array (
				'Apple' => __( 'Apple Store In-App Payments', WPAUTOTERMS_SLUG ),
				'Google' => __( 'Google Play In-App Payments', WPAUTOTERMS_SLUG ),
				'Stripe' => __( 'WePay', WPAUTOTERMS_SLUG ),
				'WePay' => __( 'Stripe', WPAUTOTERMS_SLUG ),
				'WorldPay' => __( 'WorldPay', WPAUTOTERMS_SLUG ),
				'PayPal or Braintree' => __( 'PayPal or Braintree', WPAUTOTERMS_SLUG ),
				'FastSpring' => __( 'FastSpring', WPAUTOTERMS_SLUG ),
				'Authorize' => __( 'Authorize.net', WPAUTOTERMS_SLUG ),
				'2Checkout' => __( '2Checkout', WPAUTOTERMS_SLUG ),
				'Sage Pay' => __( 'Sage Pay', WPAUTOTERMS_SLUG ),
				'Square' => __( 'Square', WPAUTOTERMS_SLUG ),
				'Go Cardless' => __( 'Go Cardless', WPAUTOTERMS_SLUG ),
				'Elavon' => __( 'Elavon', WPAUTOTERMS_SLUG ),
				'Verifone' => __( 'Verifone', WPAUTOTERMS_SLUG ),
				'Moneris' => __( 'Moneris', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>


	<?php Section::begin('service_providers_advertising_section', __('Do you show ads on your website?', WPAUTOTERMS_SLUG)); ?>
		<?php
			Controls::radio( 'service_providers_advertising', array (
				'yes' => __( 'Yes, we show ads', WPAUTOTERMS_SLUG ),
				'no' => __( 'No', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>
	<?php
		Section::begin('service_providers_advertising_list_section', __('Select the platforms you use to show ads on your website', WPAUTOTERMS_SLUG));
		Section::show_if( 'service_providers_advertising_yes' );
	?>
		<?php
			Controls::checkbox_group( 'service_providers_advertising_list', array (
				'Google AdSense' => __( 'Google AdSense', WPAUTOTERMS_SLUG ),
				'Bing Ads' => __( 'Bing Ads', WPAUTOTERMS_SLUG ),
				'StartApp' => __( 'StartApp', WPAUTOTERMS_SLUG ),
				'AdButler' => __( 'AdButler', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>


	<?php Section::begin('service_providers_remarkering_section', __('Do you use remarketing services for marketing & advertising purposes?', WPAUTOTERMS_SLUG)); ?>
		<?php
			Controls::radio( 'service_providers_remarketing', array (
				'yes' => __( 'Yes, we use remarketing services to advertise our website', WPAUTOTERMS_SLUG ),
				'no' => __( 'No', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>
	<?php
		Section::begin('service_providers_remarketing_list_section', __('Select the platforms you use to show ads on your website', WPAUTOTERMS_SLUG));
		Section::show_if( 'service_providers_remarketing_yes' );
	?>
		<?php
			Controls::checkbox_group( 'service_providers_remarketing_list', array (
				'Google AdWords' => __( 'Google AdWords', WPAUTOTERMS_SLUG ),
				'Twitter' => __( 'Twitter', WPAUTOTERMS_SLUG ),
				'Facebook' => __( 'Facebook', WPAUTOTERMS_SLUG ),
				'Pinterest' => __( 'Pinterest', WPAUTOTERMS_SLUG ),
				'AdRoll' => __( 'AdRoll', WPAUTOTERMS_SLUG ),
				'Perfect Audience' => __( 'Perfect Audience', WPAUTOTERMS_SLUG ),
				'AppNexus' => __( 'AppNexus', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>

	<?php Section::begin('company_contact_section', __('How can users contact you if they have any questions about your Privacy Policy?', WPAUTOTERMS_SLUG)); ?>
		<?php
			Controls::checkbox_group( 'company_contact', array (
				'email' => __( 'By email', WPAUTOTERMS_SLUG ),
				'link' => __( 'By visiting a page on our website', WPAUTOTERMS_SLUG ),
				'phone' => __( 'By phone', WPAUTOTERMS_SLUG ),
				'address' => __( 'By mailing address', WPAUTOTERMS_SLUG ),
			));
		?>
	<?php Section::end(); ?>
	<?php
		Section::begin('company_contact_email_section', __('Enter the email', WPAUTOTERMS_SLUG));
		Section::show_if( 'company_contact_email' );
	?>
		<input type="text" name="company_contact_email" class="regular-text" value="" placeholder="Enter your email address" />
	<?php Section::end(); ?>
	<?php
		Section::begin('company_contact_link_section', __('Enter the link', WPAUTOTERMS_SLUG));
		Section::show_if( 'company_contact_link' );
	?>
		<input type="text" name="company_contact_link" class="regular-text" value="" placeholder="Enter the link"  />
	<?php Section::end(); ?>
	<?php
		Section::begin('company_contact_phone_section', __('Enter the phone number', WPAUTOTERMS_SLUG));
		Section::show_if( 'company_contact_phone' );
	?>
		<input type="text" name="company_phone_link" class="regular-text" value="" placeholder="Enter the phone number" />
	<?php Section::end(); ?>
	<?php
		Section::begin('company_contact_address_section', __('Enter the mailing address', WPAUTOTERMS_SLUG));
		Section::show_if( 'company_contact_address' );
	?>
		<input type="text" name="company_contact_address" class="regular-text" value="" placeholder="Enter the mailing address"/>
	<?php Section::end(); ?>


</div>
<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'submit.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'script.php';
