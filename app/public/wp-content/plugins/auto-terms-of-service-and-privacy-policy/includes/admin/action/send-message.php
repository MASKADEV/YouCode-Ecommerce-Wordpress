<?php

namespace wpautoterms\admin\action;


use wpautoterms\Action_Base;

class Send_Message extends Action_Base {
	const SITE_INFO_NONE = 0;
	const SITE_INFO_SHORT = 1;
	const SITE_INFO_EXTENDED = 2;

	const DEFAULT_SITE_INFO = 2;

	const MAX_TEXT_LENGTH = 2500;

	const TRANSIENT_EXPIRATION = 90;
	protected $_data = null;

	public function site_info_options() {
		return array(
			static::SITE_INFO_NONE => __( 'none', WPAUTOTERMS_SLUG ),
			static::SITE_INFO_SHORT => __( 'short', WPAUTOTERMS_SLUG ),
			static::SITE_INFO_EXTENDED => __( 'extended', WPAUTOTERMS_SLUG ),
		);
	}

	public function site_info() {
		global $wp_version;
		global $wp_db_version;
		$ext = array_map( function ( $x ) {
			return $x . ': ' . phpversion( $x );
		}, get_loaded_extensions() );
		$plugins = get_plugins();
		$plugins = array_map( function ( $k, $x ) {
			if ( ! is_plugin_active( $k ) ) {
				return false;
			}

			return $x['Name'] . ': ' . $x['Version'] . ' (' . $x['PluginURI'] . ') ';
		}, array_keys( $plugins ), array_values( $plugins ) );

		$prefix = 'Plugin version: ' . WPAUTOTERMS_VERSION . "\nPHP version: " . phpversion();

		return array(
			static::SITE_INFO_NONE => '',
			static::SITE_INFO_SHORT => $prefix . "\nWP version: " . $wp_version . "\nWPDB version: " . $wp_db_version,
			static::SITE_INFO_EXTENDED => $prefix . "\nPHP extensions:\n" . join( "\n", $ext ) .
			                              "\nWP version: " . $wp_version . "\nWPDB version: " . $wp_db_version .
			                              "\nWP plugins:\n" . join( "\n", array_filter( $plugins ) ),
		);
	}

	public function max_text_length() {
		return static::MAX_TEXT_LENGTH;
	}

	protected function _handle( $admin_post ) {
		if ( $admin_post ) {
			wp_die( 'Not supported.' );
		}
		$message = '';
		$site_name = static::_request_var( 'site_name' );
		$site_url = static::_request_var( 'site_url' );
		$email = static::_request_var( 'email' );
		$text = static::_request_var( 'text' );
		$site_info = static::_request_var( 'site_info' );
		if ( empty( $site_name ) || empty( $site_url ) || empty( $email ) || empty( $text ) ) {
			$message = __( 'Please fill in the required information in order to send the message.', WPAUTOTERMS_SLUG );
		} else if ( count( explode( '@', $email ) ) != 2 ) {
			$message = __( 'Wrong email address', WPAUTOTERMS_SLUG );
		} else if ( count( explode( '.', $site_url ) ) < 2 || count( explode( '..', $site_url ) ) > 1 ) {
			$message = __( 'Wrong URL', WPAUTOTERMS_SLUG );
		}

		$result = empty( $message );
		$ret = array(
			'message' => $message,
			'valid' => $result,
		);
		$data = array(
			'site_name' => $site_name,
			'site_url' => $site_url,
			'email' => $email,
			'text' => $text,
			'site_info' => $site_info
		);
		set_transient( $this->_transient_name(), $data, static::TRANSIENT_EXPIRATION );
		wp_send_json( $ret );
	}

	protected function _transient_name() {
		return 'wpautoterms_send_message_' . $this->name();
	}

	public function get_data() {
		if ( empty( $this->_data ) ) {
			$this->_data = get_transient( $this->_transient_name() );
			if ( ! empty( $this->_data ) ) {
				delete_transient( $this->_transient_name() );
			}
		}

		return $this->_data;
	}
}
