<p class="wpautoterms-shortcodes-source"><?php
	_e( 'Insert shortcode:', WPAUTOTERMS_SLUG );
	echo ' ';
	$s = array ();
	foreach ($shortcodes as $k => $v) {
		$s[] = '<a href="javascript:void(0);" data-data="' .
			esc_attr( $v ) . '" data-editor="'.esc_attr($option->name()).'">' . esc_html( $k ) . '</a>';
	}
	echo join( ', ', $s );
	?>.
</p>