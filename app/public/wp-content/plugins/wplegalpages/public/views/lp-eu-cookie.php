<?php
/**
 * Provide php for cookie feature.
 *
 * This file is used to markup the cookie feature.
 *
 *  @package    Wplegalpages
 *  @subpackage Wplegalpages/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div id="lp_eu_container">
	<table id="lp_eu_table" class="lp_eu_table" style="border:none;">
		<tr>
			<td width="90%">
			<?php
			if ( ! empty( $lp_eu_title ) ) {
				?>
			<b id="lp_eu_title"><?php echo esc_html( $lp_eu_title ); ?></b>
				<?php
			}
			?>
			<p id="lp_eu_body"><?php echo esc_html( stripslashes( html_entity_decode( $lp_eu_message ) ) ); ?></p>
				<a id="lp_eu_link" target="_blank" href="<?php echo esc_attr( $lp_eu_link_url ); ?>"><?php echo esc_html( $lp_eu_link_text ); ?></a>
			</p>
			</td>
			<td width="10%" >
				<div id="lp_eu_right_container">
					<p id="lp_eu_close_button"></p>
					<p style="min-height:50%"></p>
					<p id="lp_eu_btnContainer">
						<button type="button" id="lp_eu_btn_agree"><?php echo esc_html( $lp_eu_button_text ); ?></button>
					</p>
				</div>
			</td>
		</tr>
	</table>
</div>
