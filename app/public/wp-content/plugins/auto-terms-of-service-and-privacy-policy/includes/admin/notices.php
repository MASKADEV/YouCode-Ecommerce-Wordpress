<?php

namespace wpautoterms\admin;

use wpautoterms\admin\action\Dismiss_Notice;
use wpautoterms\cpt\CPT;

class Notices {
	const CLASS_ERROR = 'error';
	const CLASS_UPDATED = 'updated';
	const CLASS_INFO = 'notice-info';

	const TRANSIENT_EXPIRE = 90;

	const PERSISTENT_SUFFIX = '_persistent';
	const ACTION_SUFFIX = '_action';

	protected $_transient_name;
	protected $_transient_expire;
	protected $_persistent_name;
	protected $_action_name;
	/**
	 * @var Dismiss_Notice
	 */
	protected $_action;

	/**
	 * @var Notices
	 */
	public static $instance;

	/**
	 * @param string $transient_name
	 * @param string|false $persistent_name
	 * @param string|false $action_name
	 * @param int|false $transient_expire
	 */
	public function __construct( $transient_name, $persistent_name = false, $action_name = false, $transient_expire = false ) {
		$this->_transient_name = $transient_name;
		if ( $persistent_name === false ) {
			$this->_persistent_name = $this->_transient_name . static::PERSISTENT_SUFFIX;
		} else {
			$this->_persistent_name = $persistent_name;
		}
		if ( $action_name === false ) {
			$this->_action_name = $this->_transient_name . static::ACTION_SUFFIX;
		} else {
			$this->_action_name = $action_name;
		}
		if ( $transient_expire === false ) {
			$this->_transient_expire = static::TRANSIENT_EXPIRE;
		}
		add_action( 'admin_notices', array( $this, 'show' ) );
		$this->_action = new Dismiss_Notice( CPT::edit_cap(), $this->_action_name );
		$this->_action->set_notices( $this );
	}

	/**
	 * @param string $message
	 * @param string|false $class
	 * @param bool $persistent
	 * @param string|false $id
	 */
	public function add( $message, $class = false, $persistent = false, $id = false ) {
		if ( $class === false ) {
			$class = static::CLASS_UPDATED;
		}
		$name = $persistent ? $this->_persistent_name : $this->_transient_name;
		$notices = maybe_unserialize( get_transient( $name ) );
		if ( $notices === false ) {
			$notices = array();
		}
		if ( ! isset( $notices[ $class ] ) ) {
			$notices[ $class ] = array();
		}
		if ( $id === false ) {
			$notices[ $class ][] = $message;
		} else {
			$notices[ $class ][ $id ] = $message;
		}
		set_transient( $name, $notices, $persistent ? 0 : $this->_transient_expire );
	}

	public function delete_persistent( $class, $id ) {
		$notices = maybe_unserialize( get_transient( $this->_persistent_name ) );
		if ( $notices === false ) {
			return false;
		}
		if ( ! isset( $notices[ $class ] ) || ! isset( $notices[ $class ][ $id ] ) ) {
			return false;
		}
		unset( $notices[ $class ][ $id ] );
		set_transient( $this->_persistent_name, $notices, 0 );

		return true;
	}

	protected function _show_section( $notices, $template ) {
		if ( ! is_array( $notices ) ) {
			return;
		}
		$action = $this->_action;
		foreach ( $notices as $class => $messages ) {
			foreach ( $messages as $id => $message ) {
				\wpautoterms\print_template( $template, compact( 'id', 'message', 'action', 'class' ) );
			}
		}
	}

	public function show() {
		$notices = maybe_unserialize( get_transient( $this->_transient_name ) );
		$this->_show_section( $notices, 'admin-notice/regular' );
		delete_transient( $this->_transient_name );

		$notices = maybe_unserialize( get_transient( $this->_persistent_name ) );
		$this->_show_section( $notices, 'admin-notice/dismissible' );
	}
}
