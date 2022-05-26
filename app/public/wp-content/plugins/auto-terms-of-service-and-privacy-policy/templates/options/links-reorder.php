<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use wpautoterms\cpt\CPT;

$hide_attr = ' style="display:none"';

?>
<table class="form-table" role="presentation">
    <tbody>
    <tr>
        <th>Links Order</th>
        <td>
            <p>Order how the links of any published Legal Pages appear by dragging them up & down in the list below.</p>
            <br/>

            <ol id="links_order" class="wpautoterms-sortable-list">
				<?php
				/**
				 * @var $post \WP_Post
				 */
				foreach ( $posts as $post ) {
					$id_esc = esc_attr( $post->ID );
					?>
                    <li class="active" data-id="<?php echo $id_esc; ?>" data-control="line">
						<?php _e( $post->post_title, WPAUTOTERMS_SLUG ); ?>
                    </li>
					<?php
				}
				?>
            </ol>
        </td>
    </tr>
    </tbody>
</table>