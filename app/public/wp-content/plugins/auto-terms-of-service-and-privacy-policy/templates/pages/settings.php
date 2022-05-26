<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use wpautoterms\admin\Menu;

$page_prefix = WPAUTOTERMS_SLUG . '_';

if ( isset( $_GET['page'] ) ) {
	$active_page = substr( $_GET['page'], strlen( $page_prefix ) );
} else {
	$active_page = Menu::PAGE_SETTINGS;
}

if ( false === array_search( $active_page, array(
		Menu::PAGE_SETTINGS,
		Menu::PAGE_SETTINGS_ADVANCED
	), true ) ) {
	die( 'Bad page.' );
}

$link_prefix = '?post_type=' . \wpautoterms\cpt\CPT::type() . '&page=' . $page_prefix;

?>
<div class="wrap">
    <h2><?php echo esc_html( $page->title() ); ?></h2>
	  <?php settings_errors(); ?>

    <h2 class="nav-tab-wrapper">
        <a href="<?php echo $link_prefix . Menu::PAGE_SETTINGS; ?>"
           class="nav-tab <?php echo $active_page == Menu::PAGE_SETTINGS ? 'nav-tab-active' : ''; ?>">
			<?php _e( 'General', WPAUTOTERMS_SLUG ); ?>
        </a>
        <a href="<?php echo $link_prefix . Menu::PAGE_SETTINGS_ADVANCED; ?>"
           class="nav-tab <?php echo $active_page == Menu::PAGE_SETTINGS_ADVANCED ? 'nav-tab-active' : ''; ?>">
			<?php _e( 'Advanced', WPAUTOTERMS_SLUG ); ?>
        </a>
    </h2>

    <form method="post" action="options.php"><?php
		settings_fields( $page_prefix . $active_page );
		do_settings_sections( $page_prefix . $active_page );

		submit_button();
		?>
    </form>
</div>
