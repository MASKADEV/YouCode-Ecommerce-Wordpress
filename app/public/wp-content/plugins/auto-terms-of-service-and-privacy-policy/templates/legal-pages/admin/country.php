<?php

use wpautoterms\admin\form\Section;

Section::begin( 'country_name_section', __( 'Your country', WPAUTOTERMS_SLUG ) ); ?>
<select name="country" data-type="country-selector" class="wpautoterms-hidden"></select>
<?php Section::end(); ?>

<div data-type="state-row">
	<?php Section::begin( 'state_name_section', __( 'Your state', WPAUTOTERMS_SLUG ) ); ?>
    <select name="state" data-type="state-selector" class="wpautoterms-hidden"></select>
	<?php Section::end(); ?>
</div>