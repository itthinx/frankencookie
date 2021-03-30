<?php
/**
 * class-frankencookie-widget.php
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
 */

/**
 * FrankenCookie Widget implementation.
 */
class FrankenCookie_Widget extends WP_Widget {

	const TEN_YEARS = 315360000;

	/**
	 * Hooks on widgets_init to add our widget.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'wp_init' ) );
		add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ) );
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
	 * Registers the widget.
	 */
	public static function widgets_init() {
		register_widget( __CLASS__ );
	}

	private $default_title = '';
	private $default_text = '';
	private $default_hide = '';

	/**
	 * Widget constructor.
	 */
	public function __construct() {
		$this->default_text = __( 'We use cookies to optimize your experience on our site and assume you\'re OK with that if you stay.', FCOOK_PLUGIN_DOMAIN );
		$this->default_hide = __( 'OK, hide this message.', FCOOK_PLUGIN_DOMAIN );

		$widget_options = array(
			'classname' => 'frankencookie',
			'description' => __( 'Text or HTML', FCOOK_PLUGIN_DOMAIN )
		);

		parent::__construct(
			'frankencookie',
			__( 'FrankenCookie', FCOOK_PLUGIN_DOMAIN ),
			$widget_options
		);
	}

	/**
	 * Widget content renderer.
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {

		$show = !isset( $_COOKIE['frankencookie'] ) ? 'true' : 'false';

		extract( $args );
		$title = apply_filters(
			'frankencookie_widget_title',
			empty( $instance['title'] ) ? $this->default_title : $instance['title'],
			$instance,
			$this->id_base
		);
		$text = apply_filters(
			'frankencookie_widget_text',
			empty( $instance['text'] ) ? $this->default_text : $instance['text'],
			$instance
		);
		$hide = apply_filters(
			'frankencookie_widget_hide',
			empty( $instance['hide'] ) ? $this->default_hide : $instance['hide'],
			$instance
		);

		echo $before_widget;
		// Using Javascript to hide the message to avoid caching issues:
		// With caching, the widget would be rendered for a visitor and after
		// the visitor clicks the 'hide' link, the message would still appear.

		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		echo '<div class="frankencookie-message">';
		echo $text;
		echo '</div>';
		echo '<div class="frankencookie-hide">';
		$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		echo sprintf(
			__( '<a href="%s">%s</a>', FCOOK_PLUGIN_DOMAIN ),
			wp_nonce_url( add_query_arg( 'frankencookie', 'set', remove_query_arg( 'frankencookie', $current_url ) ), 'frankencookie' ),
			$hide
		);
		echo '<noscript>';
		echo __( 'You have disabled Javascript, to hide this notice, Javascript must be enabled.', FCOOK_PLUGIN_DOMAIN );
		echo '</noscript>';
		echo '</div>';

		if ( isset( $widget_id ) ) {

			echo '<script type="text/javascript">';
			echo 'if (document.cookie.indexOf("frankencookie") >= 0 ) {';
			// We must look for elements by class (*) :
			echo 'var elements = document.getElementsByTagName("*");';
			echo 'for (i in elements) {';
			echo 'if (typeof elements[i].className !== "undefined") {';
			echo 'if (elements[i].className.indexOf("frankencookie") >= 0) {';
			echo 'elements[i].style.display = "none";';
			echo '}';
			echo '}';
			echo '}';
			echo '}';
			echo '</script>';

			// (*) We can't use the ID because if the widget changes and we have caching in place, the wrong element
			// would be searched for and the message would not be hidden:
			//echo sprintf( 'var frankenCookieWidget = document.getElementById("%s");', $widget_id );
			//echo 'if ( typeof frankenCookieWidget !== "undefined" ) {';
			//echo 'frankenCookieWidget.style.display = "none";';
			//echo '}';
		}
		echo $after_widget;
	}

	/**
	 * Updates widget settings.
	 * @see WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] =  $new_instance['text'];
		} else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) );
		}
		$instance['hide'] = strip_tags( $new_instance['hide'] );
		return $instance;
	}

	/**
	 * Widget settings renderer.
	 * @see WP_Widget::form()
	 */
	public function form( $instance ) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => $this->default_title,
				'text'  => $this->default_text,
				'hide'  => $this->default_hide
			)
		);

		$title = strip_tags( $instance['title'] );
		$text  = esc_textarea( $instance['text'] );
		$hide  = strip_tags( $instance['hide'] );

		echo '<p>';
		echo '<label>';
		echo __( 'Title', FCOOK_PLUGIN_DOMAIN );
		echo sprintf(
			'<input class="widefat" id="%s" name="%s" type="text" value="%s" />',
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			esc_attr( $title )
		);
		echo '</label>';
		echo '<br/>';
		echo '<span class="description">';
		echo __( 'The widget\'s title.', FCOOK_PLUGIN_DOMAIN );
		echo '</span>';
		echo '</p>';

		echo '<p>';
		echo '<label>';
		echo __( 'Message about cookies', FCOOK_PLUGIN_DOMAIN );
		echo sprintf(
			'<textarea class="widefat" rows="16" cols="20" id="%s" name="%s">%s</textarea>',
			$this->get_field_id( 'text' ),
			$this->get_field_name( 'text' ), $text
		);
		echo '</label>';
		echo '<br/>';
		echo '<span class="description">';
		echo __( 'The text that explains that cookies are placed on the visitor\'s computer.', FCOOK_PLUGIN_DOMAIN );
		echo '</span>';
		echo '</p>';

		echo '<p>';
		echo '<label>';
		echo __( 'Text to hide the message', FCOOK_PLUGIN_DOMAIN );
		echo sprintf(
				'<input class="widefat" id="%s" name="%s" type="text" value="%s" />',
				$this->get_field_id( 'hide' ),
				$this->get_field_name( 'hide' ),
				esc_attr( $hide )
		);
		echo '</label>';
		echo '<br/>';
		echo '<span class="description">';
		echo __( 'The text for the link that the visitor clicks to hide the widget.', FCOOK_PLUGIN_DOMAIN );
		echo '</span>';
		echo '</p>';
	}
}
FrankenCookie_Widget::init();
