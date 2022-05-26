<?php

namespace wpautoterms\api;

class License {
	const OPTION_KEY = 'license';
	const OPTION_INFO = 'license_info';

	const _EP_STATUS = 'license/v2/status/';

	const _FIELD_STATUS = 'status';
	const _FIELD_SITES = 'sites';

	protected $_query;
	/**
	 * @var License_Info_Vo
	 */
	protected $_info;

	public function __construct( Query $query ) {
		$this->_query = $query;
		$this->_info = new License_Info_Vo( get_option( WPAUTOTERMS_OPTION_PREFIX . static::OPTION_INFO,
			License_Info_Vo::defaults() ) );
		add_action( 'update_option_' . WPAUTOTERMS_OPTION_PREFIX . static::OPTION_KEY, array(
			$this,
			'on_update_key'
		), 10, 2 );
    add_filter('https_ssl_verify', '__return_false');
	}

	public function on_update_key( $old_value, $value ) {
		if ( $old_value == $value ) {
			return;
		}
		if ( empty( $value ) ) {
			$this->_info->status = License_Info_Vo::STATUS_FREE;
			$this->_info->max_sites = 0;
			$this->_info->available_sites = 0;
			$this->_save_status();

			return;
		}
		$this->check( true );
	}

	protected function _set_status( $json ) {
		$this->_info->status = $json[ static::_FIELD_STATUS ];
		if ( isset( $json[ static::_FIELD_SITES ] ) ) {
			$sites = $json[ static::_FIELD_SITES ];
			$this->_info->max_sites = isset( $sites['total'] ) ? $sites['total'] : 0;
			$this->_info->available_sites = isset( $sites['available'] ) ? $sites['available'] : 0;
		} else {
			$this->_info->max_sites = 0;
			$this->_info->available_sites = 0;
		}
		$this->_info->site_name = isset( $json['site_name'] ) ? $json['site_name'] : '';
		$this->_info->ip = isset( $json['ip'] ) ? $json['ip'] : '';
	}

	protected function _save_status() {
		$this->_info->timestamp = time();
		update_option( WPAUTOTERMS_OPTION_PREFIX . static::OPTION_INFO, $this->_info->to_array() );
	}

	public function check( $force = false ) {
		$last_check = $this->_info->timestamp;
		if ( ! $force && ( time() - $last_check ) < WPAUTOTERMS_LICENSE_RECHECK_TIME ) {
			return;
		}
		$key = $this->api_key();
		if ( empty( $key ) ) {
			$this->_info->status = License_Info_Vo::STATUS_FREE;
			$this->_info->error = '';

			return;
		}
		$headers = array( WPAUTOTERMS_API_KEY_HEADER => $key );
		$resp = $this->_query->get( static::_EP_STATUS, array(), $headers );
		$json = $resp->json();
		$this->_info->error = $resp->format_error( WP_DEBUG );
		if ( ! $resp->has_error() && isset( $json[ static::_FIELD_STATUS ] ) ) {
			if ( in_array( $json[ static::_FIELD_STATUS ], License_Info_Vo::valid_statuses() ) ) {
				$this->_set_status( $json );
			} else {
				$this->_info->status = License_Info_Vo::STATUS_BAD_RESPONSE;
			}
		} else {
			$this->_info->status = License_Info_Vo::STATUS_QUERY_FAILED;
		}
		$this->_save_status();
	}

	public function api_key() {
		return get_option( WPAUTOTERMS_OPTION_PREFIX . static::OPTION_KEY, '' );
	}

	/**
	 * @return License_Info_Vo
	 */
	public function info() {
		return $this->_info;
	}

	public function is_paid() {
		return $this->_info->is_paid();
	}
}
