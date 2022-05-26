<?php

use wpautoterms\frontend\notice\Update_Notice;

?>
<div id="wpautoterms-update-notice-placeholder" style="display:none"></div>
<script id="tmpl-wpautoterms-update-notice" type="text/html">
    <div class="<?php echo $class_escaped; ?>">
        {{{data.message}}}
        <a href="javascript:void(0);" class="<?php echo esc_attr( Update_Notice::CLOSE_CLASS ) ?>"
           data-type="closeButton" data-cookie="{{{data.cookies}}}" data-value="{{{data.values}}}">
			<?php echo $close; ?>
        </a></div>
</script>
