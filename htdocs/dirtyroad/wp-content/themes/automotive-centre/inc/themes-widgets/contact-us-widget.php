<?php
/**
 * Custom Contact us Widget
 */

class Automotive_Centre_Contact_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'Automotive_Centre_Contact_Widget', 
			__('VW Contact us', 'automotive-centre'),
			array( 'description' => __( 'Widget for contact us section in sidebar', 'automotive-centre' ), ) 
		);
	}
	
	public function widget( $args, $instance ) {
		?>
		<aside class="widget">
			<?php
			$title = isset( $instance['title'] ) ? $instance['title'] : '';
			$phone = isset( $instance['phone'] ) ? $instance['phone'] : '';
			$email = isset( $instance['email'] ) ? $instance['email'] : '';
			$address = isset( $instance['address'] ) ? $instance['address'] : '';
			$timing = isset( $instance['timing'] ) ? $instance['timing'] : '';
			$longitude = isset( $instance['longitude'] ) ? $instance['longitude'] : '';
			$latitude = isset( $instance['latitude'] ) ? $instance['latitude'] : '';
			$contact_form = isset( $instance['contact_form'] ) ? $instance['contact_form'] : '';

	        echo '<div class="custom-contact-us">';
	        if(!empty($title) ){ ?><h3 class="custom_title"><?php echo esc_html($title); ?></h3><?php } ?>
		        <?php if(!empty($phone) ){ ?><p><span class="custom_details"><?php esc_html_e('Phone Number: ','automotive-centre'); ?></span><span class="custom_desc"><?php echo esc_html($phone); ?></span></p><?php } ?>
		        <?php if(!empty($email) ){ ?><p><span class="custom_details"><?php esc_html_e('Email ID: ','automotive-centre'); ?></span><span class="custom_desc"><?php echo esc_html($email); ?></span></p><?php } ?>
		        <?php if(!empty($address) ){ ?><p><span class="custom_details"><?php esc_html_e('Address: ','automotive-centre'); ?></span><span class="custom_desc"><?php echo esc_html($address); ?></span></p><?php } ?> 
		        <?php if(!empty($timing) ){ ?><p><span class="custom_details"><?php esc_html_e('Opening Time: ','automotive-centre'); ?></span><span class="custom_desc"><?php echo esc_html($timing); ?></span></p><?php } ?>
		        <?php if(!empty($longitude) ){ ?><embed width="100%" height="200px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo esc_html($longitude); ?>,<?php echo esc_html($latitude); ?>&hl=es;z=14&amp;output=embed"></embed><?php } ?>
		        <?php if(!empty($contact_form) ){ ?><?php echo do_shortcode($contact_form); ?><?php } ?>
	        <?php echo '</div>';
			?>
		</aside>
		<?php
	}
	
	// Widget Backend 
	public function form( $instance ) {

		$title= ''; $phone= ''; $email = ''; $address = ''; $timing = ''; $longitude = ''; $latitude = ''; $contact_form = ''; 
		
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$phone = isset( $instance['phone'] ) ? $instance['phone'] : '';
		$email = isset( $instance['email'] ) ? $instance['email'] : '';
		$address = isset( $instance['address'] ) ? $instance['address'] : '';
		$timing = isset( $instance['timing'] ) ? $instance['timing'] : '';
		$longitude = isset( $instance['longitude'] ) ? $instance['longitude'] : '';
		$latitude = isset( $instance['latitude'] ) ? $instance['latitude'] : '';
		$contact_form = isset( $instance['contact_form'] ) ? $instance['contact_form'] : '';
		
		?>

		<p>
        	<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:','automotive-centre'); ?></label>
        	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
    	</p>
    	<p>
        	<label for="<?php echo esc_attr($this->get_field_id('phone')); ?>"><?php esc_html_e('Phone Number:','automotive-centre'); ?></label>
        	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('phone')); ?>" name="<?php echo esc_attr($this->get_field_name('phone')); ?>" type="text" value="<?php echo esc_attr($phone); ?>">
    	</p>
    	<p>
        	<label for="<?php echo esc_attr($this->get_field_id('email')); ?>"><?php esc_html_e('Email id:','automotive-centre'); ?></label>
        	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('email')); ?>" name="<?php echo esc_attr($this->get_field_name('email')); ?>" type="text" value="<?php echo esc_attr($email); ?>">
    	</p>
    	<p>
        	<label for="<?php echo esc_attr($this->get_field_id('address')); ?>"><?php esc_html_e('Address:','automotive-centre'); ?></label>
        	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('address')); ?>" name="<?php echo esc_attr($this->get_field_name('address')); ?>" type="text" value="<?php echo esc_attr($address); ?>">
    	</p>
    	<p>
        	<label for="<?php echo esc_attr($this->get_field_id('timing')); ?>"><?php esc_html_e('Opening Time:','automotive-centre'); ?></label>
        	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('timing')); ?>" name="<?php echo esc_attr($this->get_field_name('timing')); ?>" type="text" value="<?php echo esc_attr($timing); ?>">
    	</p>
    	<p>
        	<label for="<?php echo esc_attr($this->get_field_id('longitude')); ?>"><?php esc_html_e('Longitude:','automotive-centre'); ?></label>
        	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('longitude')); ?>" name="<?php echo esc_attr($this->get_field_name('longitude')); ?>" type="text" value="<?php echo esc_attr($longitude); ?>">
    	</p>
    	<p>
        	<label for="<?php echo esc_attr($this->get_field_id('latitude')); ?>"><?php esc_html_e('Latitude:','automotive-centre'); ?></label>
        	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('latitude')); ?>" name="<?php echo esc_attr($this->get_field_name('latitude')); ?>" type="text" value="<?php echo esc_attr($latitude); ?>">
    	</p>
    	<p>
        	<label for="<?php echo esc_attr($this->get_field_id('contact_form')); ?>"><?php esc_html_e('Contact Form Shortcode:','automotive-centre'); ?></label>
        	<input class="widefat" id="<?php echo esc_attr($this->get_field_id('contact_form')); ?>" name="<?php echo esc_attr($this->get_field_name('contact_form')); ?>" type="text" value="<?php echo esc_attr($contact_form); ?>">
    	</p>
		
		<?php 
	}
	
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();	
		$instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
		$instance['phone'] = (!empty($new_instance['phone']) ) ? automotive_centre_sanitize_phone_number($new_instance['phone']) : '';
		$instance['email'] = (!empty($new_instance['email']) ) ? sanitize_email($new_instance['email']) : '';
		$instance['address'] = (!empty($new_instance['address']) ) ? strip_tags($new_instance['address']) : '';
		$instance['timing'] = (!empty($new_instance['timing']) ) ? strip_tags($new_instance['timing']) : '';
		$instance['longitude'] = (!empty($new_instance['longitude']) ) ? strip_tags($new_instance['longitude']) : '';
		$instance['latitude'] = (!empty($new_instance['latitude']) ) ? strip_tags($new_instance['latitude']) : '';
		$instance['contact_form'] = (!empty($new_instance['contact_form']) ) ? strip_tags($new_instance['contact_form']) : '';
        
		return $instance;
	}
}
// Register and load the widget
function automotive_centre_contact_custom_load_widget() {
	register_widget( 'Automotive_Centre_Contact_Widget' );
}
add_action( 'widgets_init', 'automotive_centre_contact_custom_load_widget' );