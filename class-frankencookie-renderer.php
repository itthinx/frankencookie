<?php
/**
 * class-frankencookie-renderer.php
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
 * FrankenCookie renderer.
 */
class FrankenCookie_Renderer {

	/**
	 * Render the FrankenCookie message and link.
	 *
	 * Allowed parameters, all optional:
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
	public static function render( $atts = array() ) {

		$text = __( 'We use cookies to optimize your experience on our site and assume you\'re OK with that if you stay.', 'frankencookie' );
		$hide = __( 'OK, hide this message.', 'frankencookie' );
		$class = 'frankencookie';

		if ( isset( $atts['text'] ) ) {
			$text = $atts['text'];
		}
		$text = apply_filters( 'frankencookie_text', $text );

		if ( isset( $atts['hide'] ) ) {
			$hide = $atts['hide'];
		}
		$hide = apply_filters( 'frankencookie_hide', $hide );

		if ( isset( $atts['class'] ) ) {
			$class = $atts['class'];
		}
		$class = apply_filters( 'frankencookie_class', $class );

		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		$output = '';
		$show = !FrankenCookie::has_cookie();

		if ( $show ) {

			global $frankencookie_did_script;

			if ( !isset( $frankencookie_did_script ) ) {

				$frankencookie_did_script = true;

				$output .= sprintf( '<div id="frankencookie-container" class="frankencookie-container %s">', esc_attr( $class ) );
				$output = '<div class="frankencookie-message">';
				$output .= wp_kses_post( $text );
				$output .=  '</div>';
				$output .= '<div class="frankencookie-hide">';
				$output .= sprintf(
					'<a href="%s" rel="nofollow">%s</a>',
					esc_url( wp_nonce_url( add_query_arg( 'frankencookie', 'set', remove_query_arg( 'frankencookie', $current_url ) ), 'frankencookie' ) ),
					wp_kses_post( $hide )
				);
				$output .= '<noscript>';
				$output .= esc_html__( 'You have disabled Javascript, to hide this notice, Javascript must be enabled.', 'frankencookie' );
				$output .= '</noscript>';
				$output .= '</div>';
				$output .= '</div>'; // .frankencookie-container
	
				// Using Javascript to hide the message to avoid caching issues:
				// With caching, the widget would be rendered for a visitor and after
				// the visitor clicks the 'hide' link, the message would still appear.
				$output .= '<script type="text/javascript">';
				$output .= 'if (document.cookie.indexOf("frankencookie") >= 0 ) {';
				$output .= 'let frankencookie_container = document.getElementById( "frankencookie-container" );';
				$output .= 'if ( typeof frankencookie_container !== "undefined" ) {';
				$output .= 'frankencookie_container.style.display = "none";';
				$output .= '}';
				$output .= '}';
				$output .= '</script>';
			}
		}

		return $output;
	}
}

FrankenCookie_Shortcode::init();
