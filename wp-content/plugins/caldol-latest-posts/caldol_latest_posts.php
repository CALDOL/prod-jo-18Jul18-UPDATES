<?php
/*
 Plugin Name: CALDOL Latest Posts
Plugin URI: http://www.wpexplorer.com/
Description: Gets the latest posts made
Version: 1.0
Author: Thomas O. Morel
Author URI: http://www.wpexplorer.com/
License: GPL2
*/


class caldol_latest_posts extends WP_Widget {

	// constructor
	//function caldol_latest_posts() {
	function __construct() {
		$widget_ops = array('classname' => 'caldol_latest_posts', 'description' => __('Show latest posts', 'caldol_latest_posts'));
		$control_ops = array('width' => 800, 'height' => 300);
		parent::WP_Widget(false, $name = __('CALDOL Latest Posts', 'caldol_latest_posts'), $widget_ops, $control_ops );
	}//end constructor

	// widget form creation
	function form($instance) {


		// Check values and escape the values
		if( $instance) {
			$title = esc_attr($instance['title']);
			$text = esc_attr($instance['text']);
			$textarea = esc_textarea($instance['textarea']);
		} else {
			$title = '';
			$text = '';
			$textarea = '';
		}
		?>


<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'caldol_latest_posts'); ?>
	</label> <input class="widefat"
		id="<?php echo $this->get_field_id('title'); ?>"
		name="<?php echo $this->get_field_name('title'); ?>" type="text"
		value="<?php echo $title; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'caldol_latest_posts'); ?>
	</label> <input class="widefat"
		id="<?php echo $this->get_field_id('text'); ?>"
		name="<?php echo $this->get_field_name('text'); ?>" type="text"
		value="<?php echo $text; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Textarea:', 'caldol_latest_posts'); ?>
	</label>
	<textarea class="widefat"
		id="<?php echo $this->get_field_id('textarea'); ?>"
		name="<?php echo $this->get_field_name('textarea'); ?>">
		<?php echo $textarea; ?>
	</textarea>
</p>

<?php


	} //end form

	// widget update
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		// Fields
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['textarea'] = strip_tags($new_instance['textarea']);
		return $instance;
	} //end update

	// widget display
	function widget($args, $instance) {
		extract( $args );

		/*
		 //  Query only one random image (post of type attachment with image mimetype)
		$myposts = get_posts('post_type=page');

		echo '<h1>after myposts</h1><ul style="list-style:none;margin-left:0px;">';
		 
		//  Let's display the image(s)
		foreach ($myposts as $mypost) {

		//  Here you can ask for 'thumbnail', 'medium', 'large' or 'original', depending of what your theme permits.
		// $myimage = wp_get_attachment_image($mypost->ID, 'medium', false);
		 
		//  Extract the image path
		// $myimagepieces = explode('"', $myimage);
		// $myimagepath = $myimagepieces[5];

		//  Outputs the image
		echo '  <li><h1 style="color:white;">' . $mypost->ID . ', ' . $mypost->post_name . '</h1></li>';
		 
		}
		 
		echo '</ul>';

		*/

		//global $is_safari, $is_gecko;

		// these are the widget options
		$title = apply_filters('widget_title', $instance['title']);
		$text = $instance['text'];
		$textarea = $instance['textarea'];
		$body = ( $instance['body'] ) ? $instance['body'] : "No posts available";
		
		echo $before_widget;
		// Display the widget
		echo '<div style="background-color:white;display:block;" class="widget-text caldol_latest_posts">';
/* 		if($is_safari == 1)
			echo "You are on Safari";
		elseif ($is_gecko == 1)
			echo "You are on Firefox";
 */
		// Check if title is set
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		echo "<div>body: " . $body . " after body</div>";

		// Check if text is set
		if( $text ) {
			echo '<p class="caldol_latest_posts_text">'.$text.'</p>';
		}
		// Check if textarea is set
		if( $textarea ) {
			echo '<p class="caldol_latest_posts_textarea">'.$textarea.'</p>';
		}
		echo '</div>';
		echo $after_widget;
	} //end widget
}

function caldol_latest_posts_init(){
	register_widget("caldol_latest_posts");
}

// register widget
add_action('widgets_init','caldol_latest_posts_init');

?>