<?php
if (!defined( 'ABSPATH' )) {
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

	<?php Section::begin( 'kind_personal_information_section', __( 'What kind of personal information you collect from users?', WPAUTOTERMS_SLUG ) ); ?>
	<?php
	Controls::checkbox_group( 'kind_personal_information', array (
	'name' => __( 'Name (first and last name)', WPAUTOTERMS_SLUG ),
	'email' => __( 'Email address', WPAUTOTERMS_SLUG ),
	'telephone' => __( 'Telephone number', WPAUTOTERMS_SLUG ),
	'address' => __( 'Address (postal address)', WPAUTOTERMS_SLUG ),
	) );
	?>
	<?php Section::end(); ?>

	<?php Section::begin('show_ads_google_asense_section', __('Do you show ads with Google AdSense on your website?', WPAUTOTERMS_SLUG)); ?>
	<?php
	Controls::radio( 'show_ads_google_adsense', array (
	'Yes' => __( 'Yes, ads are being served on our website with Google AdSense', WPAUTOTERMS_SLUG ),
	'No' => __( 'No', WPAUTOTERMS_SLUG ),
	));
	?>
	<?php Section::end(); ?>

	<?php Section::begin('disclose_personal_information_law_section', __('If required by law or subpoena, will you disclose personal information of users to law enforcement agents?', WPAUTOTERMS_SLUG)); ?>
	<?php
	Controls::radio( 'disclose_personal_information_law', array (
	'Yes' => __( 'Yes, if required by law or by a subpoena', WPAUTOTERMS_SLUG ),
	'No' => __( 'No', WPAUTOTERMS_SLUG ),
	));
	?>
	<?php Section::end(); ?>

</div>
<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'submit.php';
