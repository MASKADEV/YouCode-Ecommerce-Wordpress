<?php

namespace wpautoterms\api;

class License_Transfer {
	const _EP_STATUS = 'license/v2/transfer/';

	const RESULT_QUERY_ERROR = 'query_error';
	const RESULT_FAILED = 'failed';
	const RESULT_SUCCESS = 'success';

	/**
	 * @var Query
	 */
	protected $_query;

	public function __construct( Query $query ) {
		$this->_query = $query;
	}

	public function execute( $site, $email, $key ) {
		$site = trim( $site );
		if ( empty( $site ) ) {
			$site = get_site_url();
		}
		$resp = $this->_query->post_json( static::_EP_STATUS, array(
			'site' => $site,
			'email' => trim( $email ),
			'key' => trim( $key ),
		) );
		if ( $resp->has_error() ) {
			return static::RESULT_QUERY_ERROR;
		}
		$json = $resp->json();

		return ( isset( $json['success'] ) && $json['success'] ) ? static::RESULT_SUCCESS : static::RESULT_FAILED;
	}
}
