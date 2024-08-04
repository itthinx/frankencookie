<?php
/**
 * frankencookie.php
 *
 * Copyright (c) 2013 - 2024 "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package frankencookie
 * @since frankencookie 1.0.0
 *
 * Plugin Name: FrankenCookie
 * Plugin URI: https://www.itthinx.com/plugins/frankencookie
 * Description: FrankenCookie reminds visitors of the use of cookies.
 * Version: 2.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: itthinx
 * Author URI: https://www.itthinx.com
 * Donate-Link: https://www.itthinx.com/shop/
 * Text Domain: frankencookie
 * License: GPLv3
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FCOOK_CORE_VERSION',  '2.0.0' );
define( 'FCOOK_FILE',          __FILE__ );
define( 'FCOOK_DIR',           untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'FCOOK_CORE_URL',      plugins_url( '/frankencookie' ) );
define( 'FCOOK_PLUGIN_DOMAIN', 'frankencookie' );

/**
 * FrankenCookie plugin main class - fires up things if we must.
 */
class FrankenCookie {

	const TEN_YEARS = 315360000;

	/**
	 * Load plugin files and hook on the init action.
	 */
	public static function init() {
		require_once FCOOK_DIR . '/class-frankencookie-renderer.php';
		require_once FCOOK_DIR . '/class-frankencookie-widget.php';
		require_once FCOOK_DIR . '/class-frankencookie-shortcode.php';
		add_action( 'init', array( __CLASS__, 'wp_init' ) );
	}

	/**
	 * Sets the cookie when the message should be hidden and
	 * redirects to clean up the URL.
	 */
	public static function wp_init() {
		if ( isset( $_GET['_wpnonce'] ) ) {
			if ( wp_verify_nonce( $_GET['_wpnonce'], 'frankencookie' ) ) {
				setcookie( 'frankencookie', 1, time() + self::TEN_YEARS );
				$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				$redirect_url = remove_query_arg( '_wpnonce', remove_query_arg( 'frankencookie', $current_url ) );
				wp_redirect( $redirect_url );
				exit;
			}
		}
	}

	/**
	 * Whether the 'frankencookie' cookie is there.
	 *
	 * @return boolean
	 */
	public static function has_cookie() {
		return isset( $_COOKIE['frankencookie'] );
	}
}

FrankenCookie::init();
