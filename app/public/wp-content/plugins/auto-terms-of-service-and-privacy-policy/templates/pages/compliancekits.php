<?php

if (!defined( 'ABSPATH' )) {
	exit;
}
?>
<div class="wrap">
	<h2><?php echo $page->title(); ?></h2>
	<?php settings_errors(); ?>
	<div id="wpautoterms_notice"></div>
	<div id="poststuff">

		<div class="postbox-container">
			<?php foreach ($page->boxes() as $box) {
				$box->render();
			}
			?>
		</div>
	</div>
</div>