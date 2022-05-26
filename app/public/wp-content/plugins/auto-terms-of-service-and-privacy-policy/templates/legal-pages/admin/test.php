<?php
// Direct execution guard, just keep it on the top
if (!defined( 'ABSPATH' )) {
	exit;
}

// Export helpers

use wpautoterms\admin\form\Controls;
use wpautoterms\admin\form\Section;

// Define page header, do not forget make it translatable
?>

<h1><?php _e( 'Test page', WPAUTOTERMS_SLUG ); ?></h1>

<hr />

<label for="name">Your name:</label>
<input type="text" name="name" class="" value="" placeholder="Enter name"/>

<hr />

<h4>Checkboxes:</h4>
<?php

Controls::checkbox_group( 'collects_data_list', array (
	'personalize' => __( 'Personalize', WPAUTOTERMS_SLUG ),
	'improve' => __( 'Improve', WPAUTOTERMS_SLUG ),
	'transactions' => __( 'Transactions', WPAUTOTERMS_SLUG ),
	'promotions' => __( 'Promotions', WPAUTOTERMS_SLUG ),
	'email' => __( 'Email', WPAUTOTERMS_SLUG ),
) );

?>

<hr />

<h4>Checkboxes:</h4>

<?php

Controls::radio( 'pet_type', array (
	'cat' => __( 'Cat', WPAUTOTERMS_SLUG ),
	'dog' => __( 'Big dog', WPAUTOTERMS_SLUG ),
	'small dog' => __( 'Small dog', WPAUTOTERMS_SLUG ),
	'other' => __( 'Other', WPAUTOTERMS_SLUG ),
) );

?>

<?php

Section::begin( 'pet_type_other_section', false );
Section::show_if( 'pet_type_other' );

?>

<label for="pet_type_other">Other pet type:</label>
<input type="text" name="pet_type_other" class="" value="" placeholder="Enter pet type"/>

<?php

Section::end();
include __DIR__ . DIRECTORY_SEPARATOR . 'submit.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'script.php';
