<?php
/**
 * @package Square
 */
add_action('widgets_init', 'square_register_personal_info');

function square_register_personal_info() {
    register_widget('square_personal_info');
}

class Square_Personal_Info extends WP_Widget {

    public function __construct() {
        parent::__construct(
                'square_personal_info', 'Square - Personal Info', array(
            'description' => esc_html__('A widget to display Personal Information', 'square')
                )
        );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(
            'title' => array(
                'square_widgets_name' => 'title',
                'square_widgets_title' => esc_html__('Title', 'square'),
                'square_widgets_field_type' => 'text',
            ),
            'image' => array(
                'square_widgets_name' => 'image',
                'square_widgets_title' => esc_html__('Image', 'square'),
                'square_widgets_field_type' => 'upload',
            ),
            'intro' => array(
                'square_widgets_name' => 'intro',
                'square_widgets_title' => esc_html__('Short Intro', 'square'),
                'square_widgets_field_type' => 'textarea',
                'square_widgets_row' => '4'
            ),
            'signature' => array(
                'square_widgets_name' => 'name',
                'square_widgets_title' => esc_html__('Name', 'square'),
                'square_widgets_field_type' => 'text',
            )
        );

        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        extract($args);

        $title = isset($instance['title']) ? $instance['title'] : '';
        $image = isset($instance['image']) ? $instance['image'] : '';
        $intro = isset($instance['intro']) ? $instance['intro'] : '';
        $name = isset($instance['name']) ? $instance['name'] : '';

        echo $before_widget;  // WPCS: XSS OK.
        ?>
        <div class="sq-personal-info">
            <?php
            if (!empty($title)):
                echo $before_title . esc_html($title) . $after_title;  // WPCS: XSS OK.
            endif;

            if (!empty($image)):
                $image_id = attachment_url_to_postid($image);
                if ($image_id) {
                    $image_array = wp_get_attachment_image_src($image_id, 'thumbnail');
                    $image_url = $image_array[0];
                } else {
                    $image_url = $image;
                }
                echo '<div class="sq-pi-image"><img src="' . esc_url($image_url) . '"/></div>';
            endif;

            if (!empty($name)):
                echo '<h5 class="sq-pi-name"><span>' . esc_html($name) . '</span></h5>';
            endif;

            if (!empty($intro)):
                echo '<div class="sq-pi-intro">' . wp_kses_post($intro) . '</div>';
            endif;
            ?>
        </div>
        <?php
        echo $after_widget;  // WPCS: XSS OK.
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param	array	$new_instance	Values just sent to be saved.
     * @param	array	$old_instance	Previously saved values from database.
     *
     * @uses	square_widgets_updated_field_value()		defined in widget-fields.php
     *
     * @return	array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ($widget_fields as $widget_field) {

            extract($widget_field);

            // Use helper function to get updated field values
            $instance[$square_widgets_name] = square_widgets_updated_field_value($widget_field, $new_instance[$square_widgets_name]);
        }

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param	array $instance Previously saved values from database.
     *
     * @uses	square_widgets_show_widget_field()		defined in widget-fields.php
     */
    public function form($instance) {
        $widget_fields = $this->widget_fields();

        // Loop through fields
        foreach ($widget_fields as $widget_field) {

            // Make array elements available as variables
            extract($widget_field);
            $square_widgets_field_value = !empty($instance[$square_widgets_name]) ? esc_attr($instance[$square_widgets_name]) : '';
            square_widgets_show_widget_field($this, $widget_field, $square_widgets_field_value);
        }
    }

}
