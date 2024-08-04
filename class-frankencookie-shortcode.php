<?php
/**
 * class-frankencookie-shortcode.php
 *
 * Copyright (c) "kento" Karim Rahimpur www.itthinx.com
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
 * @since 2.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FrankenCookie shortcode.
 */
class FrankenCookie_Shortcode {

	/**
	 * Hooks on widgets_init to add our widget.
	 */
	public static function init() {
		add_shortcode( 'frankencookie', array( __CLASS__, 'frankencookie' ) );
	}

	/**
	 * Render the [frankencookie] shortcode.
	 *
	 * Allowed shortcode parameters, all optional:
	 *
	 * - text : text to show
	 * - hide : text for the link
	 * - class : additional classes for the container
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	public static function frankencookie( $atts = array(), $content = '' ) {
		return FrankenCookie_Renderer::render( $atts );
	}
}

FrankenCookie_Shortcode::init();
