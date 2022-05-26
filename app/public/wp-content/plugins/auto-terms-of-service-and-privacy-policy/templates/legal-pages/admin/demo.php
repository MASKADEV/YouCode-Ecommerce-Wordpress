<?php
// Direct execution guard, just keep it on the top
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Export helpers
use wpautoterms\admin\form\Controls;
use wpautoterms\admin\form\Section;

// Define page header, do not forget make it translatable
?><h1><?php _e( 'Demo page', WPAUTOTERMS_SLUG ); ?></h1>


<?php Section::begin( 'country_name_section', __( 'Your country', WPAUTOTERMS_SLUG ) ); ?>
    <input type="text" name="country" readonly class="regular-text"
           value="<?php echo do_shortcode( '[wpautoterms country]' ); ?>" required="required"/>
    <input type="hidden" name="country_code" value="<?php echo esc_attr( $country_code ); ?>"/>
    <input type="hidden" name="state_code" value="<?php echo esc_attr( $state_code ); ?>"/>
<?php Section::end(); ?>

<?php
$state = do_shortcode( '[wpautoterms state]' );
if ( ! empty( $state ) ) {
	Section::begin( 'state_name_section', __( 'Your state', WPAUTOTERMS_SLUG ) );
	?>
    <input type="text" name="state" readonly class="regular-text" value="<?php echo $state ?>" required="required"/>
	<?php
	Section::end();
}
?>


<?php

/*
 * Sections are (almost) logical elements that organize one or several groups of radio or checkboxes
 * Almost logical as currently I use header for the section, maybe will change in future.
 * The main use of section is to support visibility change, depending on user input.
 * But you can use sections just to organize elements.
 * All visible radio boxes are automatically required.
 * You cannot have nested sections for now.
 * To avoid naming conflicts, append '_section' suffix to all section ids.
 *
 * How to use:
 * 1. Open section with Section::begin, pass unique section id as the first parameter,
 *    pass section header as the second, do not forget to translate. Section header is <h2> tag.
 * 2. Show elements, either with helpers (Controls::radio, Controls::checkbox, Controls::checkbox_group,
 *    or with some html
 * 3. Close section by Section::end() call
 */
Section::begin( 'always_visible_section', __( 'Section 1:', WPAUTOTERMS_SLUG ) );
/*
 * Define radio group. Pass unique name (will be used to pass form data to the backend as input name),
 * pass values - key-value array, key is an input value, value - is an input label.
 * Control value is built of this unique name and the key, in this case, id for Show option will be:
 * section_2_visible_yes and we'll use it below.
 */
Controls::radio( 'section_2_visible', array(
	'yes' => __( 'Show section 2', WPAUTOTERMS_SLUG ),
	'no' => __( 'Hide section 2', WPAUTOTERMS_SLUG ),
) );
?>
    <h4>Keep showing Section 3:</h4>
<?php
/*
 * Use yes/no helper to show Yes/No options, note, that yes/no values (not depending on labels,
 * this will work for section 2 visible param as well) are special values that converted to boolean
 * type in the backend, so you can just use if($section_3_show) {} check w/o string comparison.
 */
Controls::radio( 'section_3_show', Controls::yes_no() );
?>
    <h4>Checkbox:</h4>
<?php
/*
 * Define checkbox. Pass unique name (will be used to pass form data to the backend as an
 * input name), pass value that will be used if checkbox is ticked.
 */
Controls::checkbox( 'lonely_checkbox', __( 'Lonely checkbox', WPAUTOTERMS_SLUG ), 'ticked' );
Section::end();

Section::begin( 'section_2', __( 'Section 2:', WPAUTOTERMS_SLUG ) );
/*
 * Use show_if to hide this section initially, use control state id to tell what to listen
 * for show decision:
 */
Section::show_if( 'section_2_visible_yes' );
/*
 * Define checkbox group. Pass unique name (will be used to pass form data to the backend as an array
 * input name), pass values - key-value array, key is an input value, value - is an input label.
 * Control value is built of this unique name and the key.
 */
Controls::checkbox_group( 'checkbox_group', array(
	'option_1' => __( 'Option 1', WPAUTOTERMS_SLUG ),
	'option_2' => __( 'Option 2', WPAUTOTERMS_SLUG ),
) );
?>
    <h4>Special checkbox:</h4>
<?php
/*
 * Define another checkbox, change section visibility below (section_additional_info.
 */
Controls::checkbox( 'show_section_checkbox', __( 'Show additional info', WPAUTOTERMS_SLUG ), 'show' );
Section::end();

Section::begin( 'section_additional_info', __( 'Additional info:', WPAUTOTERMS_SLUG ) );
/*
 * show_if works also with checkboxes, just use checkbox id concatenated with its value using '_'
 */
Section::show_if( 'show_section_checkbox_show' );
?>
    Some additional info
<?php
Section::end();

Section::begin( 'section_3', __( 'Section 3:', WPAUTOTERMS_SLUG ) );
/*
 * Use hide_if to hide this section upon certain control state:
 */
Section::hide_if( 'section_3_show_no' );
?>
    Just some info in the section.
<?php
Section::end();

?><h2>Some basic info:</h2>
    <div>
        <label for="pet_name">Your pet name:</label> <input type="text" name="pet_name" class="" value=""
                                                            placeholder="Enter pet name"/>
        <h4>Pet type:</h4>
		<?php
		/*
		 * Note, here we use radio outside of the section with custom labels.
		 */
		Controls::radio( 'pet_type', array(
			'cat' => __( 'Cat', WPAUTOTERMS_SLUG ),
			'dog' => __( 'Big dog', WPAUTOTERMS_SLUG ),
			'small dog' => __( 'Small dog', WPAUTOTERMS_SLUG ),
			'other' => __( 'Other', WPAUTOTERMS_SLUG ),
		) );

		/*
		 * Here we specify section without a header to appear right below radio group:
		 */
		Section::begin( 'pet_type_other_section', false );
		Section::show_if( 'pet_type_other' );
		?>
        <label for="pet_type_other">Other pet type:</label> <input type="text" name="pet_type_other" class="" value=""
                                                                   placeholder="Enter pet type"/>
		<?php
		Section::end();
		?></div>
<?php
include __DIR__ . DIRECTORY_SEPARATOR . 'submit.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'script.php';
