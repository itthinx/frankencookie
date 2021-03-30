<?php
/**
 * frankencookie.php
 *
 * Copyright (c) 2013 - 2015 "kento" Karim Rahimpur www.itthinx.com
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
 * Plugin URI: http://www.itthinx.com/plugins/frankencookie
 * Description: FrankenCookie provides a widget that offers visitors an explanation about cookies being placed on their computer.
 * Version: 1.0.4
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 * Donate-Link: http://www.itthinx.com
 * License: GPLv3
 */
define( 'FCOOK_CORE_VERSION',  '1.0.4' );
define( 'FCOOK_FILE',          __FILE__ );
define( 'FCOOK_DIR',           untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'FCOOK_CORE_URL',      plugins_url( '/frankencookie' ) );
define( 'FCOOK_PLUGIN_DOMAIN', 'frankencookie' );

/**
 * FrankenCookie plugin main class - fires up things if we must.
 */
class FrankenCookie {

	/**
	 * Load the widget stuff.
	 */
	public static function init() {
		require_once( FCOOK_DIR . '/class-frankencookie-widget.php' );
	}
}

FrankenCookie::init();
