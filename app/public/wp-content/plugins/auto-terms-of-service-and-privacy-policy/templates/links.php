<?php

use wpautoterms\frontend\Links;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( empty( $posts ) ) {
	return;
}
$links = array();
$target = $new_page ? ' target="_blank"' : '';
foreach ( $posts as $post ) {
		$links[] = '<a href="' . esc_url( get_post_permalink( $post->ID ) ) . '"' . $target . '>' .
		           esc_html( $post->post_title ) . '</a>';
}
if ( empty( $links ) ) {
	return;
}
?>
<div class="<?php echo esc_attr( Links::FOOTER_CLASS ); ?>"><p>
		<?php
		echo join( '<span class="' . esc_attr( Links::SEPARATOR_CLASS ) . '"> ' .
		           get_option( WPAUTOTERMS_OPTION_PREFIX . 'links_separator' ) . ' </span>', $links );
		?></p>
</div>