<?php

namespace wpautoterms\api;

class Response {
	const HTTP_OK = 200;
	const HTTP_LIMIT = 429;

	const HEADER_RETRY_AFTER = 'retry-after';

	const MESSAGE_KEY = 'message';

	public $code;
	public $error;
	public $error_info = '';
	public $url;
	public $delay;
	/**
	 * @var array|\WP_Error
	 */
	protected $_response;
	protected $_verbose;
	protected $_vs;
	public $headers = array();
	protected $_json;
	protected $_request_headers;
	protected $_request_body;

	public function __construct( $response, $url, $headers, $body, $verbose = false ) {
		$this->_response = $response;
		$this->_verbose = $verbose;
		$this->_request_headers = $headers;
		$this->_request_body = $body;

		$this->url = $url;
	}

	public function _done() {
		if ( $this->has_error() ) {
			$this->code = __( 'unknown', WPAUTOTERMS_SLUG );
			$this->error = join( '; ', $this->_response->get_error_codes() );
			$this->error_info = join( '; ', $this->_response->get_error_messages() );
		} else {
			$this->code = $this->_response['response']['code'];
			$this->error = $this->_response['response']['code'];
			$this->error_info = $this->_response['response']['message'];
			if ( $this->code == static::HTTP_LIMIT ) {
				$retry = $this->_response['http_response']->get_response_object()->headers->getValues( static::HEADER_RETRY_AFTER );
				if ( ! empty( $retry ) ) {
					$this->delay = intval( $retry );
				}
			}
		}
	}

	public function has_error() {
		return is_wp_error( $this->_response );
	}

	/**
	 * @return array
	 */
	public function json() {
		if ( $this->has_error() ) {
			return array();
		}
		if ( $this->_json === null ) {
			$this->_json = json_decode( $this->_response['body'], true );
		}

		return $this->_json;
	}

	public function format_error( $debug ) {
		if ( $this->has_error() ) {
			$error = __( 'Could not connect to server', WPAUTOTERMS_SLUG );
		} else if ( $this->code != Response::HTTP_OK ) {
			$json = $this->json();
			if ( $json !== null && isset( $json[ static::MESSAGE_KEY ] ) ) {
				$error = $json[ static::MESSAGE_KEY ];
			} else {
				if ( $this->code == static::HTTP_LIMIT ) {
					$error = __( 'Too many requests. Please, wait.', WPAUTOTERMS_SLUG );
				} else {
					$error = sprintf( __( 'Server response code: %s', WPAUTOTERMS_SLUG ), $this->code );
				}
			}
		} else {
			$error = '';
		}
		if ( ! empty( $error ) && $debug ) {
			$info = sprintf( __( 'URL: %s, error: %s, info: %s, headers: %s', WPAUTOTERMS_SLUG ),
				$this->url,
				$this->error,
				$this->error_info,
				print_r( $this->_request_headers, true ) );
			$error = sprintf( _x( '%s, %s', 'class Response verbose error info', WPAUTOTERMS_SLUG ), $error, $info );
		}

		return $error;
	}

	public function request_headers() {
		return $this->_request_headers;
	}

	public function request_body() {
		return $this->_request_body;
	}
}
