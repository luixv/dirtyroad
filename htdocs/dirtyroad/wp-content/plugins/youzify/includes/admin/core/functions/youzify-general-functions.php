<?php

/**
 * Disable Gravatars
 */
add_filter( 'bp_core_fetch_avatar_no_grav', '__return_true' );

/**
 * Check Is Youzify Panel Page.
 */
function is_youzify_panel_page( $page_name ) {

    // Is Panel.
    $is_panel = isset( $_GET['page'] ) && $_GET['page'] == $page_name ? true : false;

    return apply_filters( 'is_youzify_panel_page', $is_panel, $page_name );
}

/**
 * Check Is Youzify Panel Page.
 */
function is_youzify_panel_tab( $tab_name ) {

    // Is Panel.
    $is_tab = isset( $_GET['tab'] ) && $_GET['tab'] == $tab_name ? true : false;

    return apply_filters( 'is_youzify_panel_tab', $is_tab, $tab_name );
}

/**
 * Admin Youzify Icon Css
 */
function youzify_admin_bar_icon_css() { ?>
    <style>
        #adminmenu .toplevel_page_youzify-panel img {
            padding-top: 3px !important;
        }
    </style>
    <?php
}

add_action( 'admin_head', 'youzify_admin_bar_icon_css' );

/**
 * Check if page is an admin page  tab
 */
function youzify_is_panel_tab( $page_name, $tab_name ) {

    if ( is_admin() && isset( $_GET['page'] ) && isset( $_GET['tab'] ) && $_GET['page'] == $page_name && $_GET['tab'] == $tab_name ) {
        return true;
    }

    return false;
}


/**
 * Get Panel Profile Fields.
 */
function youzify_get_panel_profile_fields() {

    // Init Panel Fields.
    $panel_fields = array();

    // Get All Fields.
    $all_fields = youzify_get_all_profile_fields();

    foreach ( $all_fields as $field ) {

        // Get ID.
        $field_id = $field['id'];

        // Add Data.
        $panel_fields[ $field_id ] = $field['name'];

    }

    // Add User Login Option Data.
    $panel_fields['user_login'] = __( 'Username', 'youzify' );

    return $panel_fields;
}

/**
 * Get Panel Profile Fields.
 */
function youzify_get_user_tags_xprofile_fields() {

    // Init Panel Fields.
    $xprofile_fields = array();

    // Get xprofile Fields.
    $fields = youzify_get_bp_profile_fields();

    foreach ( $fields as $field ) {

        // Get ID.
        $field_id = $field['id'];

        // Add Data.
        $xprofile_fields[ $field_id ] = $field['name'];

    }

    return $xprofile_fields;
}

/**
 * Get Activity Posts Types
 */
function youzify_activity_post_types() {

    // Get Post Types Visibility
    $post_types = array(
        'activity_status'       => __( 'Status', 'youzify' ),
        'activity_photo'        => __( 'Photo', 'youzify' ),
        'activity_slideshow'    => __( 'Slideshow', 'youzify' ),
        'activity_link'         => __( 'Link', 'youzify' ),
        'activity_quote'        => __( 'Quote', 'youzify' ),
        'activity_giphy'        => __( 'GIF', 'youzify' ),
        'activity_video'        => __( 'Video', 'youzify' ),
        'activity_audio'        => __( 'Audio', 'youzify' ),
        'activity_file'         => __( 'File', 'youzify' ),
        'activity_share'        => __( 'Share', 'youzify' ),
        'new_cover'             => __( 'New Cover', 'youzify' ),
        'new_avatar'            => __( 'New Avatar', 'youzify' ),
        'new_member'            => __( 'New Member', 'youzify' ),
        'friendship_created'    => __( 'Friendship Created', 'youzify' ),
        'friendship_accepted'   => __( 'Friendship Accepted', 'youzify' ),
        'created_group'         => __( 'Group Created', 'youzify' ),
        'joined_group'          => __( 'Group Joined', 'youzify' ),
        'new_blog_post'         => __( 'New Blog Post', 'youzify' ),
        'new_blog_comment'      => __( 'New Blog Comment', 'youzify' ),
        // 'activity_comment'      => __( 'Comment Post', 'youzify' ),
        'updated_profile'       => __( 'Updates Profile', 'youzify' )
    );

    if ( class_exists( 'WooCommerce' ) ) {
        $post_types['new_wc_product'] = __( 'New Product', 'youzify' );
        $post_types['new_wc_purchase'] = __( 'New Purchase', 'youzify' );
    }

    if ( class_exists( 'bbPress' ) ) {
        $post_types['bbp_topic_create'] = __( 'Forum Topic', 'youzify' );
        $post_types['bbp_reply_create'] = __( 'Forum Reply', 'youzify' );
    }

    return apply_filters( 'youzify_activity_post_types', $post_types );
}

/**
 * Admin Modal Form
 */
function youzify_panel_modal_form( $args, $modal_function ) {

    $button_title = isset( $args['button_title'] ) ? $args['button_title'] : __( 'Save', 'youzify' );

    ?>

    <div class="youzify-md-modal youzify-md-effect-1" id="<?php echo $args['id'] ;?>">
        <h3 class="youzify-md-title" data-title="<?php echo $args['title']; ?>"><?php echo $args['title']; ?><i class="fas fa-times youzify-md-close-icon"></i></h3>
        <div class="youzify-md-content"><?php $modal_function(); ?></div>
        <div class="youzify-md-actions">
            <button id="<?php echo $args['button_id']; ?>" data-add="<?php echo $args['button_id']; ?>" class="youzify-md-button youzify-md-save"><?php echo $button_title ?></button>
            <button class="youzify-md-button youzify-md-close"><?php _e( 'Close', 'youzify' ); ?></button>
        </div>
    </div>

    <?php
}

/**
 * Exclude Youzify Media from Wordpress Media Library.
 */
add_filter( 'parse_query', 'youzify_exclude_youzify_media_from_media_library' );

function youzify_exclude_youzify_media_from_media_library( $wp_query ) {

    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
        $term = get_term_by( 'slug', 'youzify_media', 'category' );
        $wp_query->set( 'category__not_in', array( $term->term_id ) );
    }

}

/**
 * Check if feature is available
 */
function youzify_is_feature_available() {
    return apply_filters( 'youzify_is_feature_available', false );
}

/**
 * Get Features Tag.
 */
function youzify_get_premium_tag() {
    return '<div class="youzify-premium-tag"><i class="fas fa-gem"></i>' . __( 'Premium', 'youzify' ) . '</div>';
}

/**
 * Get User Statistics Options.
 */
function youzify_get_user_statistics_options() {

    $statistics = array(
        'posts'     => __( 'Posts', 'youzify' ),
        'comments'  => __( 'Comments', 'youzify' ),
        'views'     => __( 'Views', 'youzify' ),
        'ratings'   => __( 'Ratings', 'youzify' ),
        'followers' => __( 'Followers', 'youzify' ),
        'following' => __( 'Following', 'youzify' ),
        'points'    => __( 'Points', 'youzify' )
    );

    return apply_filters( 'youzify_get_user_statistics_options', $statistics );

}