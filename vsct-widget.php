<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class vsct_widget extends WP_Widget {
	// Constructor 
	public function __construct() {
		$widget_ops = array( 'classname' => 'vsct-sidebar', 'description' => __('Text widget with extra input fields for image and more info link.', 'very-simple-custom-textwidget') );
		parent::__construct( 'vsct-widget', __('Very Simple Custom Textwidget', 'very-simple-custom-textwidget'), $widget_ops );
	}

	// Set widget in dashboard
	function form( $instance ) {
		$instance = wp_parse_args( $instance, array(
			'title' => '',
			'image' => '',
			'image_location' => '',
			'text' => '',
			'url' => '',
			'label' => '',
			'target' => ''
		));
		$title = !empty( $instance['title'] ) ? $instance['title'] : esc_attr__('Very Simple Custom Textwidget', 'very-simple-custom-textwidget'); 
		$image = $instance['image'];
		$image_location = $instance['image_location'];
		$text = $instance['text'];
		$url = $instance['url'];
		$label = $instance['label'];
		$target = $instance['target'];
		?> 
		<p> 
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'very-simple-custom-textwidget'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" maxlength='50' value="<?php echo esc_attr( $title ); ?>">
 		</p> 
		<p>
		<label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image', 'very-simple-custom-textwidget'); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" maxlength='150' placeholder="<?php _e( 'Example: www.mydomain.com/wp-content/uploads...', 'very-simple-custom-textwidget' ); ?>" value="<?php echo esc_url( $image ); ?>" />
		</p>
		<p> 
		<label for="<?php echo $this->get_field_id( 'image_location' ); ?>"><?php _e( 'Display image', 'very-simple-custom-textwidget' ); ?>:</label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'image_location' ); ?>" name="<?php echo $this->get_field_name( 'image_location' ); ?>";"> 
			<option value='top'<?php echo ($image_location == 'top')?'selected':''; ?>><?php _e( 'Above content', 'very-simple-custom-textwidget' ); ?></option> 
			<option value='middle'<?php echo ($image_location == 'middle')?'selected':''; ?>><?php _e( 'Underneath content', 'very-simple-custom-textwidget' ); ?></option> 
			<option value='bottom'<?php echo ($image_location == 'bottom')?'selected':''; ?>><?php _e( 'Underneath link to more info', 'very-simple-custom-textwidget' ); ?></option> 
		</select> 
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Content', 'very-simple-custom-textwidget'); ?>:</label>
		<textarea class="widefat monospace" rows="10" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo wp_kses_post( $text ); ?></textarea>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Link to more info', 'very-simple-custom-textwidget'); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" maxlength='150' placeholder="<?php _e( 'Example: www.mydomain.com/news', 'very-simple-custom-textwidget' ); ?>" value="<?php echo esc_url( $url ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('label'); ?>"><?php _e('Link label', 'very-simple-custom-textwidget'); ?>:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>" type="text" maxlength='50' placeholder="<?php _e( 'Example: More info', 'very-simple-custom-textwidget' ); ?>" value="<?php echo esc_attr( $label ); ?>" />
		</p>
		<p> 
		<input id="<?php echo $this->get_field_id('target'); ?>" name="<?php echo $this->get_field_name('target'); ?>" type="checkbox" value="yes" <?php checked( 'yes', $target ); ?> /> 
		<label for="<?php echo $this->get_field_id('target'); ?>"><?php _e('Open link in new window', 'very-simple-custom-textwidget'); ?></label> 
		</p> 
	<?php }

	// Update widget 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Strip tags to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['image'] = esc_url_raw( $new_instance['image'] );
		$instance['image_location'] = strip_tags( $new_instance['image_location'] );
		$instance['text'] = wp_kses_post( $new_instance['text'] );
		$instance['url'] = esc_url_raw( $new_instance['url'] );
		$instance['label'] = strip_tags( $new_instance['label'] );
		$instance['target'] = strip_tags( $new_instance['target'] );

		return $instance;
	}

	// Display widget in frontend 
	function widget( $args, $instance ) {
		echo $args['before_widget']; 

		if ( !empty( $instance['title'] ) ) { 
			echo $args['before_title'] . apply_filters( 'widget_title', esc_attr($instance['title']) ). $args['after_title']; 
		} 
		if ( !empty($instance['image_location']) && ($instance['image_location'] == 'top') ) { 
			if ( !empty( $instance['image'] ) ) { 
				echo '<img class="vsct-image-top" src="' . esc_url($instance['image']) . '">';
			}
		}
		// Support old plugin versions
		if ( !empty($instance['image']) && empty($instance['image_location']) ) { 
			echo '<img class="vsct-image" src="' . esc_url($instance['image']) . '">';
		}
		if ( !empty( $instance['text'] ) ) { 
			echo '<div class="vsct-text">' . wpautop(wp_kses_post($instance['text'])) . '</div>';
		}
		if ( !empty($instance['image_location']) && ($instance['image_location'] == 'middle') ) { 
			if ( !empty( $instance['image'] ) ) { 
				if ( !empty( $instance['url'] )  ) { 
					echo '<img class="vsct-image-middle" src="' . esc_url($instance['image']) . '">';
				} else {
					echo '<img class="vsct-image-default" src="' . esc_url($instance['image']) . '">';
				}
			}
		}
		if ( $instance['target'] == 'yes' ) {
			$link_target = ' target="_blank"';
		} else {
			$link_target = ' target="_self"';
		}
		if ( empty($instance['label']) ) {
			$instance['label'] = esc_attr__( 'More info', 'very-simple-custom-textwidget' );
		}
		if ( !empty( $instance['url'] )  ) { 
			$output = sprintf( '<div class="vsct-link"><a href="%1$s"' . $link_target . '>%2$s</a></div>', esc_url($instance['url']), esc_attr($instance['label']) ); 
			echo $output;
		}
		if ( !empty($instance['image_location']) && ($instance['image_location'] == 'bottom') ) { 
			if ( !empty( $instance['image'] ) ) { 
				if ( !empty( $instance['url'] )  ) { 
					echo '<img class="vsct-image-bottom" src="' . esc_url($instance['image']) . '">';
				} else {
					echo '<img class="vsct-image-default" src="' . esc_url($instance['image']) . '">';
				}
			}
		}
		echo $args['after_widget']; 
	}
}
