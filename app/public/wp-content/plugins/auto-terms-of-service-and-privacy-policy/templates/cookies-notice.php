<?php

use \wpautoterms\frontend\notice\Cookies_Notice;

?><div class="<?php echo $class_escaped; ?>" style="display:none">
	<?php echo $message; ?>
    <a href="javascript:void(0);" class="<?php echo esc_attr( Cookies_Notice::CLASS_CLOSE_BUTTON ); ?>"
       data-value="1" data-cookie="<?php echo esc_attr( $cookie_name ); ?>">
		<?php echo $close; ?></a>
</div>