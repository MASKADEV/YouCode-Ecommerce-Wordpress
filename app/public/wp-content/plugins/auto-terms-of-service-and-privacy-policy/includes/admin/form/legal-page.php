<?php

namespace wpautoterms\admin\form;

use wpautoterms\Countries;
use wpautoterms\cpt\CPT;

class Legal_Page {
	protected $_id;
	protected $_title;
	protected $_page_title;
	protected $_description;

	protected $_wizard;
	protected $_hidden;
	protected $_dependencies;

	/**
	 * Legal_Page constructor.
	 *
	 * @param $id
	 * @param $title string: wizard and listing title.
	 * @param $description
	 * @param $page_title string: generated page title.
	 */
	public function __construct( $id, $title, $description, $page_title ) {
		$this->_id = $id;
		$this->_title = $title;
		$this->_page_title = $page_title;
		$this->_description = $description;
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function id() {
		return $this->_id;
	}

	public function page_title() {
		return $this->_page_title;
	}

	public function title() {
		return $this->_title;
	}

	public function description() {
		return $this->_description;
	}

	protected function _generate_wizard() {
		if ( $this->_wizard !== null ) {
			return;
		}
		Section::init();
		$this->_wizard = $this->_wizard_text() . \wpautoterms\print_template( WPAUTOTERMS_LEGAL_PAGES_DIR . 'common',
				array( 'page' => $this ), true );
		$this->_hidden = Section::get_start_hidden();
		$this->_dependencies = Section::get_dependencies();
	}

	protected function _wizard_text() {
		return \wpautoterms\print_template( WPAUTOTERMS_LEGAL_PAGES_DIR . 'admin/' . $this->id(),
			array( 'page' => $this ), true );
	}

	public function wizard() {
		$this->_generate_wizard();

		return $this->_wizard;
	}

	public function hidden() {
		$this->_generate_wizard();

		return $this->_hidden;
	}

	public function dependencies() {
		$this->_generate_wizard();

		return $this->_dependencies;
	}

	public static function sanitize( $s ) {
		if ( ! is_array( $s ) ) {
			return sanitize_text_field( $s );
		}
		$r = array();
		if ( ! empty( $s ) ) {
			foreach ( $s as $k => $v ) {
				// NOTE: forbid nested arrays
				if ( is_array( $v ) ) {
					$r[ $k ] = '';
				} else {
					$r[ $k ] = sanitize_text_field( $v );
				}
			}
		}

		return $r;
	}

	public function save_post( $post_id ) {
		$post = get_post( $post_id );
		if ( ( CPT::type() != $post->post_type ) || ! isset( $_POST['legal_page'] ) ) {
			return;
		}
		$legal_page = sanitize_text_field( $_POST['legal_page'] );
		if ( $this->id() !== $legal_page ) {
			return;
		}
		$cls = get_class( $this );
		$args = array_map( function ( $v ) use ( $cls ) {
			if ( $v == 'legal-page-radio-yes' ) {
				return true;
			} else {
				if ( $v == 'legal-page-radio-no' ) {
					return false;
				}
			}

			return $cls::sanitize( $v );
		}, $_POST );
		if ( isset( $args['country'] ) ) {
			$args['country_name'] = Countries::translate( $args['country'], Countries::DEFAULT_LOCALE );
		}
		if ( isset( $args['state'] ) ) {
			$args['state_name'] = Countries::translate( $args['state'], Countries::DEFAULT_LOCALE );
		}
		$content = $this->_get_content( $args );
		remove_action( 'save_post', array( $this, 'save_post' ) );
		if ( empty( $content ) ) {
			wp_delete_post( $post_id );
			wp_redirect( wp_get_referer() );
			die;
		}
		wp_update_post( array(
			'ID' => $post_id,
			'post_content' => $content,
			'post_title' => $this->page_title(),
			'post_name' => $this->id(),
		) );
	}

	public function availability() {
		return true;
	}

	protected function _get_content( $args ) {
		return \wpautoterms\print_template( 'legal-pages/pages/' . $this->id(), $args, true );
	}

	public function enqueue_scripts() {
		Countries::enqueue_scripts();
	}
}