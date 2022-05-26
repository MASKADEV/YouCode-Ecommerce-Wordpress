<?php
if (!defined ('ABSPATH')) {
	exit;
}

?><h2>Demo</h2>

Shortcode for document last updated date: [wpautoterms last_updated_date].

Your name is <?php echo esc_html($name); ?>.

<?php if ($collects_data_list) { ?>
##Contact Data and Other Identifiable Information

This site collects certain user information, which may include a username and password, contact information, or any other data that you type in to the site.

It may also identify your IP address to help identify you on future visits to the site.

At our discretion, the Site may use this data to:

<?php if (in_array( 'personalize', $collects_data_list )) { ?>- Personalize the user experience and/or customer service<?php } ?>
<?php if (in_array( 'improve', $collects_data_list )) { ?>- Improve the site<?php } ?>
<?php if (in_array( 'transactions', $collects_data_list )) { ?>- To process transactions<?php } ?>
<?php if (in_array( 'promotions', $collects_data_list )) { ?>- Administer a contest, promotion, survey or other site feature or function<?php } ?>
<?php if (in_array( 'email', $collects_data_list )) { ?>- Send email to users<?php } ?>
<?php } // end collects_data ?>

Yor pet name is: <?php echo esc_html($pet_type_other); ?>.