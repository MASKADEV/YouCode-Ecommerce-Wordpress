<?php

namespace wpautoterms\frontend;

use wpautoterms\cpt\CPT;

class Widget extends \WP_Widget {
	const ORDER_POST_TITLE = 'post_title';
	const ORDER_MENU_ORDER = 'menu_order';
	const ORDER_ID = 'ID';

	protected $_default_order;

	function __construct() {
		$this->_default_order = static::ORDER_MENU_ORDER;
		parent::__construct(
			WPAUTOTERMS_SLUG . '_widget',
			esc_html__( 'Legal Pages', WPAUTOTERMS_SLUG ),
			array( 'description' => esc_html__( 'Show WP AutoTerms Legal Pages list', WPAUTOTERMS_SLUG ), )
		);
	}

	public static function init() {
		add_action( 'widgets_init', array( __CLASS__, '_register_widget' ) );
	}

	public static function _register_widget() {
		register_widget( 'wpautoterms\frontend\Widget' );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'WP AutoTerms Legal Pages' );
		$sortby = empty( $instance['sortby'] ) ? $this->_default_order : $instance['sortby'];
		$exclude = empty( $instance['exclude'] ) ? '' : $instance['exclude'];

		if ( $sortby == static::ORDER_MENU_ORDER ) {
			$sortby = 'menu_order, post_title';
		}

		$out = wp_list_pages( array(
			'title_li' => '',
			'echo' => 0,
			'sort_column' => $sortby,
			'exclude' => $exclude,
			'post_type' => CPT::type()
		) );

		if ( ! empty( $out ) ) {
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			}
			?>
            <ul>
				<?php echo $out; ?>
            </ul>
			<?php
			echo $args['after_widget'];
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array(
			'sortby' => $this->_default_order,
			'title' => '',
			'exclude' => ''
		) );
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php
				echo _x( 'Title:', 'widget', WPAUTOTERMS_SLUG ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $instance['title'] ); ?>"/>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'sortby' ) ); ?>"><?php
				echo _x( 'Sort by:', 'widget', WPAUTOTERMS_SLUG ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'sortby' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'sortby' ) ); ?>" class="widefat">
                <option value="<?php static::ORDER_POST_TITLE; ?>"<?php
				selected( $instance['sortby'], static::ORDER_POST_TITLE ); ?>><?php
					echo _x( 'Page title', 'widget', WPAUTOTERMS_SLUG ); ?></option>
                <option value="<?php static::ORDER_MENU_ORDER; ?>"<?php
				selected( $instance['sortby'], static::ORDER_MENU_ORDER ); ?>><?php
					echo _x( 'Page order', 'widget', WPAUTOTERMS_SLUG ); ?></option>
                <option value="<?php static::ORDER_ID; ?>"<?php
				selected( $instance['sortby'], static::ORDER_ID ); ?>><?php
					echo _x( 'Page ID', 'widget', WPAUTOTERMS_SLUG ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>"><?php
				echo _x( 'Exclude:', 'widget', WPAUTOTERMS_SLUG ); ?></label>
            <input type="text" value="<?php echo esc_attr( $instance['exclude'] ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'exclude' ) ); ?>"
                   id="<?php echo esc_attr( $this->get_field_id( 'exclude' ) ); ?>" class="widefat"/>
            <br/>
            <small><?php echo _x( 'Page IDs, separated by commas.', 'widget', WPAUTOTERMS_SLUG ); ?></small>
        </p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		if ( in_array( $new_instance['sortby'], array(
			static::ORDER_POST_TITLE,
			static::ORDER_MENU_ORDER,
			static::ORDER_ID
		) ) ) {
			$instance['sortby'] = $new_instance['sortby'];
		} else {
			$instance['sortby'] = $this->_default_order;
		}

		$instance['exclude'] = sanitize_text_field( $new_instance['exclude'] );

		return $instance;
	}
}
