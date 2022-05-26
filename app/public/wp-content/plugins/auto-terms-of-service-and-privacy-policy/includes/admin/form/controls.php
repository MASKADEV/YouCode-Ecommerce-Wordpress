<?php

namespace wpautoterms\admin\form;

abstract class Controls {
	public static function yes_no() {
		return array(
			'yes' => __( 'Yes', WPAUTOTERMS_SLUG ),
			'no' => __( 'No', WPAUTOTERMS_SLUG ),
		);
	}

	public static function checkbox( $control_id, $label, $value ) {
		$control_id = esc_attr( $control_id );
		\wpautoterms\print_template( 'form/checkbox', compact( 'control_id', 'label', 'value' ) );
	}

	public static function checkbox_group( $control_id, $values ) {
		$control_id = esc_attr( $control_id );
		\wpautoterms\print_template( 'form/checkbox-group', compact( 'control_id', 'values' ) );
	}

	public static function radio( $control_id, $values ) {
		$control_id = esc_attr( $control_id );
		\wpautoterms\print_template( 'form/radio', compact( 'control_id', 'values' ) );
	}

}
