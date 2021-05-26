<?php
/**
 * Emmet Customize Header Image Control class.
 *
 * @since 1.1.0
 *
 * @see WP_Customize_Header_Image_Control
 */
if (class_exists('WP_Customize_Header_Image_Control')):

    class MP_Emmet_Theme_Customize_Header_Image_Control extends WP_Customize_Header_Image_Control {

        public function render_content() {
            $this->print_header_image_template();
            $visibility = $this->get_current_image_src() ? '' : 'display:none;';
            $width = absint(get_theme_support('custom-header', 'width'));
            $height = absint(get_theme_support('custom-header', 'height'));
            ?>


            <div class="customize-control-content">
                <p class="customizer-section-intro"><i>
                        <?php
                        esc_html_e('Note: this image is for pages with the "With Header Image" template.', 'emmet-lite');
                        ?>
                    </i><hr/></p>
            <p class="customizer-section-intro">
                <?php
                if ($width && $height) {
	                /* translators: %1$s - width, %2$s - height */
                    printf(wp_kses_data(__('While you can crop images to your liking after clicking <strong>Add new image</strong>, your theme recommends a header size of <strong>%1$s &times; %2$s</strong> pixels.', 'emmet-lite')), esc_html($width), esc_html($height));
                } elseif ($width) {
	                /* translators: %s - width */
                    printf(wp_kses_data(__('While you can crop images to your liking after clicking <strong>Add new image</strong>, your theme recommends a header width of <strong>%s</strong> pixels.', 'emmet-lite')), esc_html($width));
                } else {
	                /* translators: %s - height */
                    printf(wp_kses_data(__('While you can crop images to your liking after clicking <strong>Add new image</strong>, your theme recommends a header height of <strong>%s</strong> pixels.', 'emmet-lite')), esc_html($height));
                }
                ?>
            </p>

            <div class="current">
                <span class="customize-control-title">
                    <?php esc_html_e('Current header', 'emmet-lite'); ?>
                </span>
                <div class="container">
                </div>
            </div>
            <div class="actions">
                <?php /* translators: Hide as in hide header image via the Customizer */ ?>
                <button type="button" style="<?php echo esc_attr($visibility); ?>" class="button remove"><?php echo esc_html_x('Hide image', 'custom header','emmet-lite'); ?></button>
                <?php /* translators: New as in add new header image via the Customizer */ ?>
                <button type="button" class="button new"><?php echo esc_html_x('Add new image', 'header image','emmet-lite'); ?></button>
                <div style="clear:both"></div>
            </div>
            <div class="choices">
                <span class="customize-control-title header-previously-uploaded">
                    <?php echo esc_html_x('Previously uploaded', 'custom headers','emmet-lite'); ?>
                </span>
                <div class="uploaded">
                    <div class="list">
                    </div>
                </div>
                <span class="customize-control-title header-default">
                    <?php echo esc_html_x('Suggested', 'custom headers','emmet-lite'); ?>
                </span>
                <div class="default">
                    <div class="list">
                    </div>
                </div>
            </div>
            </div>
            <?php
        }

    }

    endif;
/**
 * Emmet Customize Textarea class.
 *
 * @since 1.1.0
 *
 * @see WP_Customize_Header_Image_Control
 */
if (class_exists('WP_Customize_Control')) {

    class MP_Emmet_Theme_Customize_Textarea_Control extends WP_Customize_Control {

        public $type = 'textarea';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
            </label>
            <?php
        }

    }
}