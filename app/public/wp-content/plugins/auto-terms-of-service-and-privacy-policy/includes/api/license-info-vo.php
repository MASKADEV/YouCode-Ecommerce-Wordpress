<?php

namespace wpautoterms\api;

use wpautoterms\Vo;

define( 'WPAUTOTERMS_LICENSE_INFO_VO_STATUS_FREE', 'free' );

class License_Info_Vo extends Vo {
	const STATUS_FREE = WPAUTOTERMS_LICENSE_INFO_VO_STATUS_FREE;
	const STATUS_PAID = 'paid';
	const STATUS_IP_MISMATCH = 'ip_mismatch';
	const STATUS_MAX_SITES = 'max_sites';
	const STATUS_BAD_RESPONSE = 'bad_response';
	const STATUS_QUERY_FAILED = 'query_failed';

	public $status;
	public $timestamp;
	public $error;
	public $max_sites;
	public $available_sites;
	public $site_name;
	public $ip;

	protected static $_defaults = array(
		'status' => WPAUTOTERMS_LICENSE_INFO_VO_STATUS_FREE,
		'timestamp' => 0,
		'error' => '',
		'max_sites' => 0,
		'available_sites' => 0,
		'site_name' => '',
		'ip' => '',
	);

	public static function valid_statuses() {
		return array(
			static::STATUS_FREE,
			static::STATUS_PAID,
			static::STATUS_IP_MISMATCH,
			static::STATUS_MAX_SITES,
		);
	}

	public function license_type_string() {
		return $this->is_paid() ? _x( 'premium', 'license type', WPAUTOTERMS_SLUG ) :
			_x( 'free', 'license type', WPAUTOTERMS_SLUG );
	}

	public function is_paid() {
		return $this->status === static::STATUS_PAID;
	}

	public function should_show_websites() {
		return in_array( $this->status, array(
			static::STATUS_MAX_SITES,
			static::STATUS_PAID,
			static::STATUS_IP_MISMATCH
		) );
	}

	public function websites_info() {
		$used = $this->max_sites - $this->available_sites;
		if ( $this->max_sites == 0 ) {
			return _x( 'unlimited', 'license websites limit', WPAUTOTERMS_SLUG );
		}

		return sprintf( _x( '%d / %d', 'license websites limit', WPAUTOTERMS_SLUG ), $used, $this->max_sites );
	}

	public function summary() {
		$query_failed_str = 'Server query failed. Please contact us from Help > Send us a message.';
		$used = $this->max_sites - $this->available_sites;
		$status_str = array(
			License_Info_Vo::STATUS_FREE => __( 'Free license or invalid premium license key.', WPAUTOTERMS_SLUG ),
			License_Info_Vo::STATUS_PAID => sprintf( __( 'License key is registered to %s (%s).', WPAUTOTERMS_SLUG ),
				$this->site_name, $this->ip ),
			License_Info_Vo::STATUS_IP_MISMATCH => sprintf( __( 'IP address mismatch (%s).', WPAUTOTERMS_SLUG ),
				$this->ip ),
			License_Info_Vo::STATUS_MAX_SITES => sprintf( __( 'Reached maximum number of allowed websites for this License key (%d of %d).', WPAUTOTERMS_SLUG ),
				$used, $this->max_sites ),
			License_Info_Vo::STATUS_QUERY_FAILED => __( $query_failed_str, WPAUTOTERMS_SLUG ),
			License_Info_Vo::STATUS_BAD_RESPONSE => __( $query_failed_str, WPAUTOTERMS_SLUG ),
		);

		return $status_str[ $this->status ];
	}
}
