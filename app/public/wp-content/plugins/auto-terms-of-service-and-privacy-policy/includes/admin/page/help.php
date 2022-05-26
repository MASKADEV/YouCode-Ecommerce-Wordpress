<?php

namespace wpautoterms\admin\page;


use wpautoterms\admin\action\Send_Message;

class Help extends Base {
	const EP_MESSAGE = 'contact/v2/message_prepare';
	/**
	 * @var Send_Message
	 */
	public $action;

	public function api_endpoint() {
		return WPAUTOTERMS_API_URL . static::EP_MESSAGE;
	}

	public function enqueue_scripts() {
		parent::enqueue_scripts();
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_style( 'wpautoterms-page-help', WPAUTOTERMS_PLUGIN_URL . 'css/page-help.css', array(), WPAUTOTERMS_VERSION );
		wp_enqueue_style( 'jquery-ui-structure', WPAUTOTERMS_PLUGIN_URL . 'css/jquery-ui.structure.css', array(), WPAUTOTERMS_VERSION );
		wp_enqueue_style( 'jquery-ui-theme', WPAUTOTERMS_PLUGIN_URL . 'css/jquery-ui-themes/base/theme.css', array(), WPAUTOTERMS_VERSION );
		wp_enqueue_style( 'jquery-ui-accordion', WPAUTOTERMS_PLUGIN_URL . 'css/jquery-ui-accordion.css', array( 'wp-jquery-ui-dialog' ), WPAUTOTERMS_VERSION );
		wp_enqueue_script( WPAUTOTERMS_SLUG . '_contact_form', WPAUTOTERMS_PLUGIN_URL . 'js/contact-form.js',
			array( 'underscore', 'wp-util', WPAUTOTERMS_JS_BASE ), WPAUTOTERMS_VERSION, true );
		wp_localize_script( WPAUTOTERMS_SLUG . '_contact_form', 'wpautotermsContact', array(
			'nonce' => $this->action->nonce(),
			'id' => $this->action->name(),
			'siteInfo' => $this->action->site_info()
		) );
	}
}
