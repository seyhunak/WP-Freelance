<?php
/*
Plugin Name: WP-Freelance
Plugin URI: http://www.seyhunakyurek.com/
Description: Creates freelance status message to your sidebar
Author: S.AKYUREK
Version: 1.0
Author URI: http://www.seyhunakyurek.com/
*/

/** register scripts */
function add_wpfreelance_script() {
 $url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
 
 if (!is_admin()) {
	  wp_enqueue_script('jquery');
	  wp_enqueue_script('timeago', $url.'/jquery.timeago.js', array('jquery', 'jquery-form'));	 
	  wp_enqueue_script('application', $url.'/application.js', array('jquery', 'jquery-form'));	 
	}
}

/** register styles */
function add_wpfreelance_stylesheet() {
        $url = WP_PLUGIN_URL . '/WP-freelance/styles.css';
        $file = WP_PLUGIN_DIR . '/WP-freelance/styles.css';
        if (file_exists($file)) {
            wp_register_style('stylesheet', $url);
            wp_enqueue_style('stylesheet');
        }
}

/**
 * WP-Freelance Class
 */

class MyFreelanceWidget extends WP_Widget {

/** constructor */
function MyFreelanceWidget() {
	$widget = array('classname' => 'MyFreelanceWidget', 'description' => __( "Creates freelance status message to your sidebar") );
	$control = array('width' => 400, 'height' => 300);   
	parent::WP_Widget('MyFreelanceWidget', __('My Freelance'), $widget, $control);	
}

/** @see WP_Widget::widget */
function widget($args, $instance) {		
		extract($args); 	
		$description = isset( $instance['description'] ) ? $instance['description'] : false;	
        $until = isset( $instance['until'] ) ? $instance['until'] : false;		  
		$status = isset( $instance['status'] ) ? $instance['status'] : false;	
        $contact = isset( $instance['contact'] ) ? $instance['contact'] : false;	
		?>
	    <?php echo $before_widget; ?>
          <?php echo $before_title
              . $after_title; ?>   
           <?php
              echo '<div id="wp-freelance">';
			  if ($status == "Available") {
			  echo '<p><a target="_blank" href="http://www.delicious.com/'. $available .'">
              <img id="status" src="'.get_bloginfo('url').'/wp-content/plugins/wp-freelance/images/ready.png"></a></p>
			  <h2>Im available for work</h2>';   
			  } else {
			  echo '<p><a target="_blank" href="http://www.delicious.com/'. $busy .'">
              <img src="'.get_bloginfo('url').'/wp-content/plugins/wp-freelance/images/busy.png"></a></p>
			  <h2>Im busy right now</h2>';	 
  		      if ($description) echo '<p> '. $description . ' </p>'; 
			  if ($until) echo 'Work will be done at <abbr class="timeago" title="'. $until.'"> '.date("Y-m-d") . ' </abbr>
			  . I am busy until '. $until.''; 
			  }
			  echo '<h2>Contact</h2>';
			  if ($contact) echo '<img src="'.get_bloginfo('url').'/wp-content/plugins/wp-freelance/images/contact.png">
			  <a href="'.get_bloginfo('url').'/'.$contact .'">Contact me</a>';
			  echo '</div>';
            ?>
      <?php echo $after_widget; ?>
	<?php
}

/** @see WP_Widget::update */
function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['description'] = strip_tags( $new_instance['description'] );  
	$instance['until'] = strip_tags( $new_instance['until'] );    
	$instance['status'] = strip_tags( $new_instance['status'] );
	$instance['contact'] = strip_tags( $new_instance['contact'] );   
    return $instance;
}

/** @see WP_Widget::form */
function form($instance) {	
 ?>	
     
     <p>
        <label for="<?php echo $this->get_field_id('description'); ?>">Your Description: (describe your work)</label>
         <textarea name="<?php echo $this->get_field_name('description'); ?>" id="<?php echo $this->get_field_id('description'); ?>" 
         cols="45" rows="5"><?php echo $instance['description']; ?></textarea>       
     </p>   
     <p>
        <label for="<?php echo $this->get_field_id('until'); ?>">Until: describe when you are available for work (YY-mm-dd)</label>
        <input id="<?php echo $this->get_field_id('until'); ?>" name="<?php echo $this->get_field_name('until' ); ?>" 
        value="<?php echo $instance['until']; ?>" style="width:50%;" />
    </p>  
    
     <p>
        <label for="<?php echo $this->get_field_id('status'); ?>">Your status: </label> 
        <label for="<?php echo $this->get_field_id('status'); ?>">Selected: <?php echo $instance['status']; ?></label> 
        <select name="<?php echo $this->get_field_name('status'); ?>" id="<?php echo $this->get_field_id('status'); ?>" value="<?php echo $instance['size']; ?>">
        <option value="Select">Select your status</option>
        <option value="Available">Available</option>
        <option value="Busy">Busy</option>
        </select>
     </p>     
     
    
    <p>
    <label for="<?php echo $this->get_field_id('contact'); ?>">Your Contact Page: (point your contact page)</label>
    <input id="<?php echo $this->get_field_id('contact'); ?>" name="<?php echo $this->get_field_name('contact' ); ?>" 
    value="<?php echo $instance['contact']; ?>" style="width:50%;" />
    </p>   
    
	<?php }
} 

// register widget
add_action('wp_print_scripts', 'add_wpfreelance_script');
add_action('wp_print_styles', 'add_wpfreelance_stylesheet');
add_action('widgets_init', create_function('', 'return register_widget("MyFreelanceWidget");'));
?>
