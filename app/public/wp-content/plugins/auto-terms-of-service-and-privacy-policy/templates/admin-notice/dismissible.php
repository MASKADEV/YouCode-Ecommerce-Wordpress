<div class="notice wpautoterms-is-dismissible <?php echo esc_attr( $class ); ?>"
     data-wpautoterms-dismissible="<?php
     esc_attr_e( 'Don\'t show this again', WPAUTOTERMS_SLUG ); ?>"
     data-wpautoterms-action-id="<?php echo esc_attr( $action->name() ); ?>"
     data-wpautoterms-action-data="<?php echo esc_attr( json_encode( array(
	     'c' => $class,
	     'id' => $id
     ) ) ) ?>">
    <p><?php echo $message; ?></p>
</div>
