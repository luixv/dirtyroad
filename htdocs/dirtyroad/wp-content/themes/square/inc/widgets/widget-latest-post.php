<?php
/**
 * @package Square
 */
add_action('widgets_init', 'square_register_latest_posts');

function square_register_latest_posts() {
    register_widget('square_latest_posts');
}

class Square_Latest_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array('description' => esc_html__('A widget to display latest post with thumbnail.', 'square'));
        parent::__construct('square_latest_posts', 'Square - Latest Posts', $widget_ops);
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
                'square_widgets_field_type' => 'text'
            ),
            'post_count' => array(
                'square_widgets_name' => 'post_count',
                'square_widgets_title' => esc_html__('No of Posts to Display', 'square'),
                'square_widgets_field_type' => 'number',
                'square_widgets_default' => '5'
            ),
            'display_thumb' => array(
                'square_widgets_name' => 'display_thumb',
                'square_widgets_title' => esc_html__('Display Thumbnail', 'square'),
                'square_widgets_field_type' => 'checkbox'
            ),
            'display_excerpt' => array(
                'square_widgets_name' => 'display_excerpt',
                'square_widgets_title' => esc_html__('Display Excerpt', 'square'),
                'square_widgets_field_type' => 'checkbox',
            ),
            'excerpt_letter_count' => array(
                'square_widgets_name' => 'excerpt_letter_count',
                'square_widgets_title' => esc_html__('No of Letter to Display in Excerpt', 'square'),
                'square_widgets_field_type' => 'number',
                'square_widgets_default' => '100'
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
        $post_count = isset($instance['post_count']) ? $instance['post_count'] : 5;
        $display_thumb = (isset($instance['display_thumb']) && $instance['display_thumb']) ? true : false;
        $display_excerpt = (isset($instance['display_excerpt']) && $instance['display_excerpt']) ? true : false;
        $excerpt_letter_count = isset($instance['excerpt_letter_count']) ? $instance['excerpt_letter_count'] : 100;
        $title_html_tag = isset($instance['title_html_tag']) ? $instance['title_html_tag'] : 'div';

        echo $before_widget;
        if (!empty($title)):
            echo $before_title . apply_filters('widget_title', $title) . $after_title;
        endif;
        ?>
        <ul class="sq-latest-posts">
            <?php
            $args = array(
                'post_type' => 'post',
                'posts_per_page' => $post_count
            );

            $query = new WP_Query($args);

            while ($query->have_posts()) : $query->the_post();
                ?>
                <li class="sq-clearfix">
                    <?php
                    if ($display_thumb && has_post_thumbnail()) {
                        $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumbnail');
                        $image_alt = get_post_meta(get_the_ID(), '_wp_attachment_image_alt', true);
                        ?>
                        <div class="sq-lp-image">
                            <a href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                            </a>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="sq-lp-content">
                        <h6 class="sq-lp-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h6>

                        <?php if ($display_excerpt) { ?>
                            <div class="sq-lp-excerpt">
                                <?php echo square_excerpt(get_the_content(), $excerpt_letter_count); ?>
                            </div>
                        <?php } ?>
                    </div>
                </li>   
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </ul>
        <?php
        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param   array   $new_instance   Values just sent to be saved.
     * @param   array   $old_instance   Previously saved values from database.
     *
     * @uses    square_widgets_updated_field_value()     defined in widget-fields.php
     *
     * @return  array Updated safe values to be saved.
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
     * @param   array $instance Previously saved values from database.
     *
     * @uses    square_widgets_show_widget_field()       defined in widget-fields.php
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
