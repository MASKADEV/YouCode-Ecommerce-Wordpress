<?php
/*
Plugin Name: WP AutoTerms
Plugin URI: https://wpautoterms.com
Description: Create Privacy Policy with GDPR and CCPA, Terms & Conditions, Disclaimers. Cookie Consent Notice Banner. More Compliance Kits to help you get compliant with the law.
Author: WP AutoTerms
Author URI: https://wpautoterms.com
Version: 2.4.9
License: GPLv2 or later
Text Domain: wpautoterms
Domain Path: /languages
*/

/*

DISCLAIMER: WP AutoTerms is provided with the purpose of helping you with compliance. While we do our best to provide you useful information to use as a starting point, nothing can substitute professional legal advice in drafting your legal agreements and/or assisting you with compliance. We cannot guarantee any conformity with the law, which only a lawyer can do. We are not attorneys. We are not liable for any content, code, or other errors or omissions or inaccuracies. This plugin provides no warranties or guarantees. Nothing in this plugin, therefore, shall be considered legal advice and no attorney-client relationship is established. Please note that in some cases, depending on your legislation, further actions may be required to make your WordPress website compliant with the law.

*/

/*

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

namespace wpautoterms;

use wpautoterms\admin\Admin;
use wpautoterms\api\Query;
use wpautoterms\api\License;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	return;
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'defines.php';


function get_version( $file_name ) {

	$fh = fopen( $file_name, "r" );
	if ( $fh ) {
		$cmp = 'Version:';
		$len = strlen( $cmp );
		while ( true ) {
			$line = fgets( $fh );
			if ( $line === false ) {
				break;
			}
			$line = ltrim( $line );
			if ( strncasecmp( $line, $cmp, $len ) === 0 ) {
				return trim( substr( $line, $len ) );
			}
		}
		fclose( $fh );
	} else {
		die( 'Unexpected error, fopen failed for ' . $file_name );
	}
	die( 'Could not find version in ' . $file_name );
}

define( 'WPAUTOTERMS_VERSION', get_version( __FILE__ ) );

require_once WPAUTOTERMS_PLUGIN_DIR . 'api.php';
require_once WPAUTOTERMS_PLUGIN_DIR . 'deactivate.php';
register_deactivation_hook( __FILE__, '\wpautoterms\deactivate' );

require_once WPAUTOTERMS_PLUGIN_DIR . 'includes' . DIRECTORY_SEPARATOR . 'autoload.php';

function print_template( $__template, $args = array(), $__to_buffer = false ) {

	if ( ! $__template ) {
		return false;
	}
	if ( false !== strstr( '..', $__template ) ) {
		return false;
	}

	extract( $args );
	$__path = WPAUTOTERMS_PLUGIN_DIR . 'templates/' . $__template . '.php';

	if ( $__to_buffer ) {
		ob_start();
	}
	include $__path;
	if ( $__to_buffer ) {
		$ret = ob_get_contents();
		ob_end_clean();

		return $ret;
	}

	return true;
}

$_query   = new Query( WPAUTOTERMS_API_URL, WP_DEBUG );
$_license = new License( $_query );

Wpautoterms::init( $_license, $_query );
if ( is_admin() ) {
	Admin::init( $_license, $_query );
} else {
	Frontend::init( $_license );
}
