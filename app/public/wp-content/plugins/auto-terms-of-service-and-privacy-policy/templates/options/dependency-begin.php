<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><div class="wpautoterms-option-dependent<?php if($dep_type!='hide'){ echo ' wpautoterms-hidden'; } ?>"
	 data-source="<?php echo esc_attr($dep_source); ?>"
	 data-value="<?php echo esc_attr($dep_value); ?>"
	 data-type="<?php echo esc_attr($dep_type); ?>">