<?php
/**
 * class-frankencookie-widget.php
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
 * @since frankencookie 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FrankenCookie Widget implementation.
 */
class FrankenCookie_Widget extends WP_Widget {

	private $default_title = '';
	private $default_text = '';
	private $default_hide = '';

	/**
	 * Hooks on widgets_init to add our widget.
	 */
	public static function init() {
		add_action( 'widgets_init', array( __CLASS__, 'widgets_init' ) );
	}

	/**
	 * Registers the widget.
	 */
	public static function widgets_init() {
		register_widget( __CLASS__ );
	}

	/**
	 * Widget constructor.
	 */
	public function __construct() {
		$this->default_text = __( 'We use cookies to optimize your experience on our site and assume you\'re OK with that if you stay.', 'frankencookie' );
		$this->default_hide = __( 'OK, hide this message.', 'frankencookie' );

		$widget_options = array(
			'classname' => 'frankencookie',
			'description' => __( 'Text or HTML', 'frankencookie' )
		);

		parent::__construct(
			'frankencookie',
			__( 'FrankenCookie', 'frankencookie' ),
			$widget_options
		);
	}

	/**
	 * Widget content renderer.
	 *
	 * @see WP_Widget::widget()
	 */
	public function widget( $args, $instance ) {

		$before_widget = isset( $args['before_widget'] ) ? $args['before_widget'] : '';
		$after_widget = isset( $args['after_widget'] ) ? $args['after_widget'] : '';
		$before_title = isset( $args['before_title'] ) ? $args['before_title'] : '';
		$after_title = isset( $args['after_title'] ) ? $args['after_title'] : '';

		$title = apply_filters(
			'frankencookie_widget_title',
			empty( $instance['title'] ) ? $this->default_title : $instance['title'],
			$instance,
			$this->id_base
		);

		$params = array();
		if ( !empty( $instance['text'] ) ) {
			$params['text'] = $instance['text'];
		}
		if ( empty( $instance['hide'] ) ) {
			$params['hide'] = $instance['hide'];
		}

		echo $before_widget;
		// Using Javascript to hide the message to avoid caching issues:
		// With caching, the widget would be rendered for a visitor and after
		// the visitor clicks the 'hide' link, the message would still appear.

		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		echo FrankenCookie_Renderer::render( $params );

		echo $after_widget;
	}

	/**
	 * Updates widget settings.
	 *
	 * @see WP_Widget::update()
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['title'] = $new_instance['title'];
			$instance['text'] = $new_instance['text'];
			$instance['hide'] = $new_instance['hide'];
		} else {
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['text'] = strip_tags( $new_instance['text'] );
			$instance['hide'] = strip_tags( $new_instance['hide'] );
		}
		return $instance;
	}

	/**
	 * Widget settings renderer.
	 *
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
		echo esc_html__( 'Title', 'frankencookie' );
		echo sprintf(
			'<input class="widefat" id="%s" name="%s" type="text" value="%s" />',
			$this->get_field_id( 'title' ),
			$this->get_field_name( 'title' ),
			esc_attr( $title )
		);
		echo '</label>';
		echo '<br/>';
		echo '<span class="description">';
		echo esc_html__( 'The widget\'s title.', 'frankencookie' );
		echo '</span>';
		echo '</p>';

		echo '<p>';
		echo '<label>';
		echo esc_html__( 'Message about cookies', 'frankencookie' );
		echo sprintf(
			'<textarea class="widefat" rows="16" cols="20" id="%s" name="%s">%s</textarea>',
			$this->get_field_id( 'text' ),
			$this->get_field_name( 'text' ),
			esc_html( stripslashes( $text ) )
		);
		echo '</label>';
		echo '<br/>';
		echo '<span class="description">';
		echo esc_html__( 'The text that explains that cookies are placed on the visitor\'s computer.', 'frankencookie' );
		echo '</span>';
		echo '</p>';

		echo '<p>';
		echo '<label>';
		echo esc_html__( 'Text to hide the message', 'frankencookie' );
		echo sprintf(
			'<input class="widefat" id="%s" name="%s" type="text" value="%s" />',
			$this->get_field_id( 'hide' ),
			$this->get_field_name( 'hide' ),
			esc_attr( stripslashes( $hide ) )
		);
		echo '</label>';
		echo '<br/>';
		echo '<span class="description">';
		echo esc_html__( 'The text for the link that the visitor clicks to hide the widget.', 'frankencookie' );
		echo '</span>';
		echo '</p>';
	}
}
FrankenCookie_Widget::init();
