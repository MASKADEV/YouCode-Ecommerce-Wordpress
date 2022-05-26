<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use wpautoterms\admin\form\Controls;
use wpautoterms\admin\form\Legal_Page;
use wpautoterms\admin\form\Section;

/**
 * @var $page Legal_Page
 */
?>

<div class="legal-page-inner">

	<h1><?php echo esc_html($page->page_title()); ?></h1>

	<?php Section::begin('website_url_section', __('What is your website URL?', WPAUTOTERMS_SLUG)); ?>
	<input type="text" name="website_url" class="regular-text" value="<?php echo do_shortcode('[wpautoterms site_url]'); ?>" placeholder="Enter your website URL" />
	<?php Section::end(); ?>

	<?php Section::begin('website_name_section', __('What is your website name?', WPAUTOTERMS_SLUG)); ?>
	<input type="text" name="website_name" class="regular-text" value="<?php echo do_shortcode('[wpautoterms site_name]'); ?>" placeholder="Enter your website name" />
	<?php Section::end(); ?>

	<?php
	    include __DIR__ . DIRECTORY_SEPARATOR . 'country.php';
	?>

	<?php Section::begin('company_name_section', __('What is your company name?', WPAUTOTERMS_SLUG)); ?>
		<p class="text-muted text-small text-note">If this WordPress website is not operated/owned by a registered company or entity, please enter the website name instead.</p>
		<input type="text" name="company_name" class="regular-text" value="<?php echo do_shortcode('[wpautoterms company_name]'); ?>" placeholder="Enter your company name" />
	<?php Section::end(); ?>

	<?php Section::begin('user_accounts_section', __('Can users create an account on your website?', WPAUTOTERMS_SLUG)); ?>
	<?php
	Controls::radio( 'user_accounts', array (
	'Yes' => __( 'Yes, users can create an account', WPAUTOTERMS_SLUG ),
	'No' => __( 'No', WPAUTOTERMS_SLUG ),
	));
	?>
	<?php Section::end(); ?>

	<?php Section::begin('ip_rights_section', __('Do you want to make it clear that your own content and trademarks are your exclusive property?', WPAUTOTERMS_SLUG)); ?>
	<?php
	Controls::radio( 'ip_rights', array (
	'Yes' => __( 'Yes, our own content (logo, visual design etc.) and trademarks is our exclusive property', WPAUTOTERMS_SLUG ),
	'No' => __( 'No', WPAUTOTERMS_SLUG ),
	));
	?>
	<?php Section::end(); ?>

	<?php Section::begin('terminate_access_section', __('Do you want to be able to terminate access to certain users, if these users abuse your website?', WPAUTOTERMS_SLUG)); ?>
	<?php
	Controls::radio( 'terminate_access', array (
	'Yes' => __( 'Yes', WPAUTOTERMS_SLUG ),
	'No' => __( 'No', WPAUTOTERMS_SLUG ),
	));
	?>
	<?php Section::end(); ?>

	<?php Section::begin('effective_notice_section', __('For any material changes to the Terms and Conditions, you should notify users in advance. How many days notice you will provide before the new Terms and Conditions become effective?', WPAUTOTERMS_SLUG)); ?>
	<?php
	Controls::radio( 'effective_notice', array (
	'15' => __( '15 days notice', WPAUTOTERMS_SLUG ),
	'30' => __( '30 days notice (recommended)', WPAUTOTERMS_SLUG ),
	'60' => __( '60 days notice', WPAUTOTERMS_SLUG ),
	));
	?>
	<?php Section::end(); ?>

	<?php Section::begin('limit_liability_section', __('Do you want to limit your liability by providing your website on an "AS IS" and "AS AVAILABLE" basis?', WPAUTOTERMS_SLUG)); ?>
	<?php
	Controls::radio( 'limit_liability', array (
	'Yes' => __( 'Yes, please include a "Disclaimer" disclosure in the legal page', WPAUTOTERMS_SLUG ),
	'No' => __( 'No', WPAUTOTERMS_SLUG ),
	));
	?>
	<?php Section::end(); ?>

</div>
<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'submit.php';
