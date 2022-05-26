<?php
if (!defined ('ABSPATH')) {
	exit;
}

?><h3>Demo</h3>
<?php
if($country_code==='US'){
    echo 'Text for USA based website.';
} else {
	echo "Text for non-USA based website ($country, $country_code).";
}
?>

Shortcode for document last updated date: [wpautoterms last_updated_date]

You have to check if a checkbox variable set.

Controls values, note that $_POST is not needed:
<ul>
	<li>section_2_visible: <?php print_r ($section_2_visible); ?></li>
	<li>section_3_show: <?php print_r ($section_3_show); ?></li>
	<li>lonely_checkbox: <?php if (isset($lonely_checkbox)) {
			print_r ($lonely_checkbox);
		} else {
			echo 'not set';
		}
		?></li>
	<li>checkbox_group: <?php if (isset($checkbox_group)) {
			print_r ($checkbox_group);
		} else {
			echo 'not set';
		}
		?></li>
	<li>show_section_checkbox: <?php if (isset($show_section_checkbox)) {
			print_r ($show_section_checkbox);
		} else {
			echo 'not set';
		}
		?></li>
	<li>pet_name: <?php print_r ($pet_name); ?></li>
	<li>pet_type: <?php print_r ($pet_type); ?></li>
	<li>pet_type_other: <?php print_r ($pet_type_other); ?></li>
</ul>

Yor pet name is: <?php echo esc_html($pet_name); ?>, it is a <?php
if($pet_type == 'other'){
	echo esc_html($pet_type_other);
} else {
	echo esc_html($pet_type);
}
?>.
