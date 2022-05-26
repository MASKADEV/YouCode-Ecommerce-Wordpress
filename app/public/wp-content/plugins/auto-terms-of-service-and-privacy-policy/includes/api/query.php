<?php

namespace wpautoterms\api;

class Query {
	protected $_base_url;
	protected $_verbose;

	public function __construct( $base_url, $verbose = false ) {
		$this->_base_url = $base_url;
		$this->_verbose = $verbose;
	}

	/**
	 * @param string $ep remote endpoint
	 * @param array $params
	 * @param false|array $headers
	 *
	 * @return Response
	 */
	public function get( $ep, $params = array(), $headers = false ) {
		$url = $this->_base_url . $ep;
		$all_headers = array(
			'Referer' => get_site_url(),
			'X-WP-Locale' => get_locale()
		);
		if ( $headers ) {
			$all_headers = array_merge( $headers, $all_headers );
		}

		return $this->_exec( 'wp_remote_get', $all_headers, $params, $url );
	}

	/**
	 * @param string $ep remote endpoint
	 * @param mixed $params
	 * @param false|array $headers
	 *
	 * @return Response
	 */
	public function post_json( $ep, $params, $headers = false ) {
		$data = json_encode( $params );
		$url = $this->_base_url . $ep;
		$all_headers = array(
			'Content-Type' => 'application/json',
			'Content-Length' => strlen( $data ),
			'Referer' => get_site_url(),
			'X-WP-Locale' => get_locale()
		);
		if ( is_array( $headers ) ) {
			$all_headers = array_merge( $headers, $all_headers );
		}

		return $this->_exec( 'wp_remote_post', $all_headers, $data, $url );
	}

	protected function _exec( $fn, $headers, $body, $url ) {
		$resp = $fn( $url, array( 'headers' => $headers, 'body' => $body ) );
		$ret = new Response( $resp, $url, $headers, $body, $this->_verbose );
		$ret->_done();

		return $ret;

	}
}
