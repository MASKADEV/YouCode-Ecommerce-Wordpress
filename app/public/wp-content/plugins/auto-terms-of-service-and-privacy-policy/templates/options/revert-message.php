<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><p class="wpautoterms-replace-source"><?php
$d = $box->defaults();
$message = $d[ substr( $option->name(), strlen( WPAUTOTERMS_OPTION_PREFIX ) ) ];
echo _x( 'You can also', 'Revert message', WPAUTOTERMS_SLUG );
?> <a href="javascript:void(0);" data-data="<?php esc_attr_e( $message ); ?>"
      data-editor="<?php esc_attr_e( $option->name() ); ?>"><?php _e( 'revert message to the default one', WPAUTOTERMS_SLUG ); ?></a>.
</p>