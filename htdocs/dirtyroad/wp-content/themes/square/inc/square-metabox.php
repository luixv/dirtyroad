<?php

/**
 *
 * @package Square
 */

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function sq_sidebar_layout_meta_box() {

    $screens = array('post', 'page');

    add_meta_box(
            'sq_sidebar_layout', esc_html__('Sidebar Layout', 'square'), 'sq_sidebar_layout_meta_box_callback', $screens, 'side', 'high'
    );
}

add_action('add_meta_boxes', 'sq_sidebar_layout_meta_box');

/**
 * Prints the box content.
 * 
 * @param WP_Post $post The object for the current post/page.
 */
function sq_sidebar_layout_meta_box_callback($post) {

    // Add a nonce field so we can check for it later.
    wp_nonce_field('sq_sidebar_layout_save_meta_box', 'sq_sidebar_layout_meta_box_nonce');

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $sq_sidebar_layout = get_post_meta($post->ID, 'sq_sidebar_layout', true);

    if (!$sq_sidebar_layout) {
        $sq_sidebar_layout = 'right_sidebar';
    }

    echo '<label>';
    echo '<input type="radio" name="sq_sidebar_layout" value="right_sidebar" ' . checked($sq_sidebar_layout, 'right_sidebar', false) . ' />';
    echo '<img src="' . get_template_directory_uri() . '/inc/css/right-sidebar.jpg"/>';
    echo '</label>';

    echo '<label>';
    echo '<input type="radio" name="sq_sidebar_layout" value="left_sidebar" ' . checked($sq_sidebar_layout, 'left_sidebar', false) . ' />';
    echo '<img src="' . get_template_directory_uri() . '/inc/css/left-sidebar.jpg"/>';
    echo '</label>';

    echo '<label>';
    echo '<input type="radio" name="sq_sidebar_layout" value="no_sidebar" ' . checked($sq_sidebar_layout, 'no_sidebar', false) . ' />';
    echo '<img src="' . get_template_directory_uri() . '/inc/css/no-sidebar.jpg"/>';
    echo '</label>';

    echo '<label>';
    echo '<input type="radio" name="sq_sidebar_layout" value="no_sidebar_condensed" ' . checked($sq_sidebar_layout, 'no_sidebar_condensed', false) . ' />';
    echo '<img src="' . get_template_directory_uri() . '/inc/css/no-sidebar-condensed.jpg"/>';
    echo '</label>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function sq_sidebar_layout_save_meta_box($post_id) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['sq_sidebar_layout_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['sq_sidebar_layout_meta_box_nonce'], 'sq_sidebar_layout_save_meta_box')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if (isset($_POST['sq_sidebar_layout'])) {
        // Sanitize user input.
        $sq_data = sanitize_text_field($_POST['sq_sidebar_layout']);
        // Update the meta field in the database.
        update_post_meta($post_id, 'sq_sidebar_layout', $sq_data);
    }
}

add_action('save_post', 'sq_sidebar_layout_save_meta_box');
