<?php

namespace wpautoterms\api;

use wpautoterms\admin\Options;
use wpautoterms\Countries;
use wpautoterms\Util;

class Agreement {
	const TRANSIENT_ERROR_NAME = 'agreement_error';
	const TRANSIENT_DATA_NAME = 'agreement_data';
	const TRANSIENT_EXPIRATION = 90;
	/**
	 * @var Query
	 */
	protected $_query;

	/**
	 * @var License
	 */
	protected $_license;

	const _EP_AGREEMENT = 'agreement/v1/text/';
	const _EP_WIZARD = 'agreement/v1/wizard/%s/%s';

	const _RESP_TEXT = 'html';

	public function __construct( Query $query, License $license ) {
		$this->_query = $query;
		$this->_license = $license;
	}

	public function generate( $agreement_id, $data ) {
		$new_data = array();
		if ( isset( $data['wizardUseValues'] ) ) {
			$allowed = array_map( function ( $x ) {
				$len = strlen( $x );
				if ( $len < 3 ) {
					return $x;
				}
				if ( substr( $x, - 2 ) == '[]' ) {
					return substr( $x, 0, $len - 2 );
				}

				return $x;
			}, explode( ',', $data['wizardUseValues'] ) );
			foreach ( $data as $k => $v ) {
				if ( in_array( $k, $allowed, true ) ) {
					$new_data[ $k ] = stripslashes_deep($v);
				}
			}
		} else {
			foreach ( $data as $k => $v ) {
				$lk = strtolower( $k );
				if ( false === strstr( $lk, 'nonce' ) && false === strstr( $lk, 'password' ) ) {
					$new_data[ $k ] = stripslashes_deep($v);
				}
			}
		}
		$data = $new_data;
		$args = array(
			'wizard_data' => $data,
			'lang' => 'en',
		);
		if ( isset( $data['country'] ) && Countries::exists( $data['country'] ) ) {
			$args['country'] = $data['country'];
			if ( isset( $data['state'] ) && 0 == strncasecmp( $args['country'], $data['state'], strlen( $args['country'] ) ) ) {
				$args['state'] = $data['state'];
			} else {
				$args['state'] = '';
			}
		} else {
			$args['country'] = Options::get_option( Options::COUNTRY );
			$args['state'] = Options::get_option( Options::STATE );
		}
		$headers = array( WPAUTOTERMS_API_KEY_HEADER => $this->_license->api_key() );
		$resp = $this->_query->post_json( static::_EP_AGREEMENT . $agreement_id . '/', $args, $headers );
		$json = $resp->json();
		if ( ! $resp->has_error() && isset( $json[ static::_RESP_TEXT ] ) ) {
			return $json[ static::_RESP_TEXT ];
		}
		set_transient( WPAUTOTERMS_SLUG . static::TRANSIENT_ERROR_NAME, $resp->format_error( WP_DEBUG ),
			static::TRANSIENT_EXPIRATION );
		set_transient( WPAUTOTERMS_SLUG . static::TRANSIENT_DATA_NAME, array( $agreement_id, $data ),
			static::TRANSIENT_EXPIRATION );

		return false;
	}

	public function wizard( $agreement_id ) {
		$params = array();
		foreach ( Options::all_options() as $k ) {
			$v = Options::get_option( $k );
			if ( ! empty( $v ) ) {
				$params[ $k ] = $v;
			}
		}
		$headers = array( WPAUTOTERMS_API_KEY_HEADER => $this->_license->api_key() );
		$data = array(
			'name' => $agreement_id,
			'country' => Options::get_option( Options::COUNTRY ),
			'state' => Options::get_option( Options::STATE ),
			'lang' => 'en',
		);
		$resp = $this->_query->get( sprintf( static::_EP_WIZARD, $data['name'], $data['lang'] ), $params, $headers );
		$json = $resp->json();
		if ( ! $resp->has_error() && isset( $json[ static::_RESP_TEXT ] ) ) {
			return $json[ static::_RESP_TEXT ];
		}

		return $resp->format_error( WP_DEBUG );
	}
}
