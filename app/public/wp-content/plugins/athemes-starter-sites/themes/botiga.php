<?php

/**
 * Starter Register Demos
 */

function botiga_demos_list() {

	$plugins = array();

	$plugins[] = array(
		'name'     => 'WooCommerce',
		'slug'     => 'woocommerce',
		'path'     => 'woocommerce/woocommerce.php',
		'required' => true
	);

	$plugins[] = array(
		'name'     => 'aThemes Blocks',
		'slug'     => 'athemes-blocks',
		'path'     => 'athemes-blocks/athemes-blocks.php',
		'required' => true
	);	

	$demos = array(
		'beauty'      => array(
			'name'       => esc_html__( 'Beauty', 'botiga' ),
			'type'       => 'free',
			'categories' => array( 'ecommerce' ),
			'builders'   => array(
				'gutenberg',
			),
			'preview'    => 'https://demo.athemes.com/botiga/',
			'thumbnail'  => 'https://athemes.com/themes-demo-content/botiga/beauty/thumb.png',
			'plugins'    => array_merge(
				array(
					array(
						'name'     => 'WPForms',
						'slug'     => 'wpforms-lite',
						'path'     => 'wpforms-lite/wpforms.php',
						'required' => false
					)					
				),
				$plugins
			),
			'import'     => array(
				'content'    => 'https://athemes.com/themes-demo-content/botiga/beauty/botiga-dc-beauty.xml',
				'widgets'    => 'https://athemes.com/themes-demo-content/botiga/beauty/botiga-w-beauty.wie',
				'customizer' => 'https://athemes.com/themes-demo-content/botiga/beauty/botiga-c-beauty.dat'
			),
		),
		'apparel'   => array(
			'name'       => esc_html__( 'Apparel', 'athemes-starter-sites' ),
			'type'       => 'pro',
			'categories' => array( 'ecommerce' ),
			'builders'   => array(
				'gutenberg',
			),
			'preview'    => 'https://demo.athemes.com/botiga-apparel/',
			'thumbnail'  => 'https://athemes.com/themes-demo-content/botiga/apparel/thumb.png',
			'plugins'    => array_merge(
				array(
					array(
						'name'     => 'WPForms',
						'slug'     => 'wpforms-lite',
						'path'     => 'wpforms-lite/wpforms.php',
						'required' => false
					)					
				),
				$plugins
			),
			'import'     => array(
				'content'    => 'https://athemes.com/themes-demo-content/botiga/apparel/botiga-dc-apparel.xml',
				'widgets'    => 'https://athemes.com/themes-demo-content/botiga/apparel/botiga-w-apparel.wie',
				'customizer' => 'https://athemes.com/themes-demo-content/botiga/apparel/botiga-c-apparel.dat'
			),
		),
		'furniture'   => array(
			'name'       => esc_html__( 'Furniture', 'athemes-starter-sites' ),
			'type'       => 'pro',
			'categories' => array( 'ecommerce' ),
			'builders'   => array(
				'gutenberg',
			),
			'preview'    => 'https://demo.athemes.com/botiga-furniture/',
			'thumbnail'  => 'https://athemes.com/themes-demo-content/botiga/furniture/thumb.png',
			'plugins'    => array_merge(
				array(
					array(
						'name'     => 'WPForms',
						'slug'     => 'wpforms-lite',
						'path'     => 'wpforms-lite/wpforms.php',
						'required' => false
					)					
				),
				$plugins
			),
			'import'     => array(
				'content'    => 'https://athemes.com/themes-demo-content/botiga/furniture/botiga-dc-furniture.xml',
				'widgets'    => 'https://athemes.com/themes-demo-content/botiga/furniture/botiga-w-furniture.wie',
				'customizer' => 'https://athemes.com/themes-demo-content/botiga/furniture/botiga-c-furniture.dat'
			),
		),
	);

	return $demos;
}
add_filter( 'atss_register_demos_list', 'botiga_demos_list' );

/**
 * Define actions that happen after import
 */
function botiga_setup_after_import() {
	
	// Get demo id
	$demo_id = get_transient( 'atss_importer_demo_id' );

	//Update custom css file
	$css_class = new Botiga_Custom_CSS();

	$css_class->update_custom_css_file();

	// Assign the menu.
	$main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
	set_theme_mod(
		'nav_menu_locations',
		array(
			'primary' => $main_menu->term_id,
		)
	);

	if( $demo_id == 'apparel' ) {
		
		// Assign footer copyright menu
		$copyright_menu = get_term_by( 'name', 'Footer Copyright', 'nav_menu' );
		set_theme_mod(
			'nav_menu_locations',
			array(
				'footer-copyright-menu' => $copyright_menu->term_id,
			)
		);

	}

	// "Footer" menu (menu name from import)
	$footer_menu_one = get_term_by( 'name', 'Footer', 'nav_menu' );
	if( $footer_menu_one ) {
		$nav_menu_widget = get_option( 'widget_nav_menu' );
		foreach( $nav_menu_widget as $key => $widget ) {
			if( $key != '_multiwidget' ) {
				if( in_array( $nav_menu_widget[ $key ]['title'], array( 'Quick links', 'Quick Links' ) ) ) {
					$nav_menu_widget[ $key ]['nav_menu'] = $footer_menu_one->term_id;
					update_option( 'widget_nav_menu', $nav_menu_widget );
				}
			}
		}
	}

	// "Footer 2" menu (menu name from import)
	$footer_menu_two = get_term_by( 'name', 'Footer 2', 'nav_menu' );
	if( $footer_menu_two ) {
		$nav_menu_widget = get_option( 'widget_nav_menu' );
		foreach( $nav_menu_widget as $key => $widget ) {
			if( $key != '_multiwidget' ) {
				if( in_array( $nav_menu_widget[ $key ]['title'], array( 'About' ) ) ) {
					$nav_menu_widget[ $key ]['nav_menu'] = $footer_menu_two->term_id;
					update_option( 'widget_nav_menu', $nav_menu_widget );
				}
			}
		}
	}

	// Asign the static front page and the blog page.
	$front_page = get_page_by_title( 'Home' );
	$blog_page  = get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page->ID );
	update_option( 'page_for_posts', $blog_page->ID );

	// My wishlist page
	$wishlist_page = get_page_by_title( 'My Wishlist' );
	if( $wishlist_page ) {
		update_option( 'botiga_wishlist_page_id', $wishlist_page->ID );
	}

	// Create/assign WooCommerce pages
	$shop_page 		= get_page_by_title( 'Shop' );
	$cart_page 		= get_page_by_title( 'Cart' );
	$checkout_page  = get_page_by_title( 'Checkout' );
	$myaccount_page = get_page_by_title( 'My Account' );

	update_option( 'woocommerce_shop_page_id', $shop_page->ID );
	update_option( 'woocommerce_cart_page_id', $cart_page->ID );
	update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
	update_option( 'woocommerce_myaccount_page_id', $myaccount_page->ID );

	atss_import_helper( 'botiga', $demo_id );

	// Delete the transient for demo id
	delete_transient( 'atss_importer_demo_id' );
}
add_action( 'atss_finish_import', 'botiga_setup_after_import' );

// Do not create default WooCommerce pages when plugin is activated
// The condition avoid the filter being applied in others pages
// Eg: Woo > Status > Tools > Create default pages
if( isset( $_POST['action'] ) && $_POST['action'] === 'atss_import_plugin' ) {
	add_filter( 'woocommerce_create_pages', function(){
		return array();
	} );
}