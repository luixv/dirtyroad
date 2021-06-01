<?php

/**
 * Wall Settings.
 */

function youzify_wall_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_url_preview',
            'title' => __( 'URL Live Preview', 'youzify' ),
            'desc'  => __( 'Display URL preview in the wall form', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_activity_loader',
            'title' => __( 'Infinite Loader', 'youzify' ),
            'desc'  => __( 'Enable activity infinite loader', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_activity_effects',
            'title' => __( 'Activity Loading Effect', 'youzify' ),
            'desc'  => __( 'Enable activity loading effect', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Sticky Posts Settings', 'youzify' ),
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_activity_sticky_posts',
            'title' => __( 'Enable Activity Sticky Posts', 'youzify' ),
            'desc'  => __( 'Allow admins to pin or unpin posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_groups_sticky_posts',
            'title' => __( 'Enable Groups Sticky Posts', 'youzify' ),
            'desc'  => __( 'Allow admins to pin or unpin posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posting Form Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_activity_privacy',
            'title' => __( 'Privacy', 'youzify' ),
            'desc'  => __( 'Enable activity posts privacy', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_activity_mood',
            'title' => __( 'Feeling / Activity', 'youzify' ),
            'desc'  => __( 'Enable posts feeling and activity', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_activity_tag_friends',
            'title' => __( 'Tag Friends', 'youzify' ),
            'desc'  => __( 'Enable tagging friends in posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Filters Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_filter_bar',
            'title' => __( 'Display Profile Activity Filter', 'youzify' ),
            'desc'  => __( 'Show profile activity filter bar', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_activity_directory_filter_bar',
            'title' => __( 'Display Activity Stream Filter', 'youzify' ),
            'desc'  => __( 'Show global activity stream page filter bar', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Embeds Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_embeds',
            'title' => __( 'Enable Posts Embeds', 'youzify' ),
            'desc'  => __( 'Activate Embeds inside posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_comments_embeds',
            'title' => __( 'Enable Comments Embeds', 'youzify' ),
            'desc'  => __( 'Activate Embeds inside comments', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Buttons Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_likes',
            'title' => __( 'Enable Likes', 'youzify' ),
            'desc'  => __( 'Allow users to like posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_comments',
            'title' => __( 'Enable Comments', 'youzify' ),
            'desc'  => __( 'Allow posts comments', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'is_premium' => true,
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_shares',
            'title' => __( 'Enable Shares', 'youzify' ),
            'desc'  => __( 'Allow users to share posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_deletion',
            'title' => __( 'Enable Deletion', 'youzify' ),
            'desc'  => __( 'Enable posts delete button', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_reply',
            'title' => __( 'Enable Comments Replies', 'youzify' ),
            'desc'  => __( 'Allow posts comments replies', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'is_premium' => true,
            'type'  => 'checkbox',
            'id'    => 'youzify_wall_comments_gif',
            'title' => __( 'Enable Comments GIFs', 'youzify' ),
            'desc'  => __( 'Allow comments GIFs', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity Attachments Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_attachments_max_nbr',
            'title' => __( 'Max Attachments Number', 'youzify' ),
            'desc'  => __( 'Slideshow and photos max number per post', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_attachments_max_size',
            'title' => __( 'Max File Size', 'youzify' ),
            'desc'  => __( 'Attachment max size by megabytes', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_atts_allowed_images_exts',
            'title' => __( 'Image Extensions', 'youzify' ),
            'desc'  => __( 'Allowed image extensions', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_atts_allowed_videos_exts',
            'title' => __( 'Video Extensions', 'youzify' ),
            'desc'  => __( 'Allowed video extensions', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_atts_allowed_audios_exts',
            'title' => __( 'Audio Extensions', 'youzify' ),
            'desc'  => __( 'Allowed audio extensions', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_atts_allowed_files_exts',
            'title' => __( 'Files Extensions', 'youzify' ),
            'desc'  => __( 'Allowed files extensions', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Comments Attachments Settings', 'youzify' ),
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_wall_comments_attachments',
            'title' => __( 'Comments Attachments', 'youzify' ),
            'desc'  => __( 'Enable comments attachments', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_wall_comments_attachments_extensions',
            'title' => __( 'Allowed Extensions', 'youzify' ),
            'desc'  => __( 'Allowed extensions list', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_wall_comments_attachments_max_size',
            'title' => __( 'Max File Size', 'youzify' ),
            'desc'  => __( 'Attachment max size by megabytes', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity Moderation Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_moderation_keys',
            'title' => __( 'Forbidden Community Words', 'youzify' ),
            'desc'  => __( 'Add a list of forbidden words that cannot be used on the activity posts and comments.', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Per Page Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_profile_wall_posts_per_page',
            'title' => __( 'Profile - Posts Per Page', 'youzify' ),
            'desc'  => __( 'Profile wall posts per page', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_groups_wall_posts_per_page',
            'title' => __( 'Groups - Posts Per Page', 'youzify' ),
            'desc'  => __( 'Groups wall posts per page', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_activity_wall_posts_per_page',
            'title' => __( 'Activity - Posts Per Page', 'youzify' ),
            'desc'  => __( 'Global activity wall posts per page', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Control Wall Posts Visibility', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $post_types = youzify_activity_post_types();

    // Get Unallowed Types.
    $unallowed_types = array_flip( get_option( 'youzify_unallowed_activities', array() ) );

    if ( isset( $unallowed_types['friendship_accepted,friendship_created'] ) ) {
        $unallowed_types['friendship_accepted'] = 'on';
        $unallowed_types['friendship_created'] = 'on';
    }

    foreach ( $post_types as $post_type => $name ) {

        $Youzify_Settings->get_field(
            array(
                'type'  => 'checkbox',
                'std'   => isset( $unallowed_types[ $post_type ] ) ? 'off' : 'on',
                'id'    => $post_type,
                'title' => $name,
                'desc'  => sprintf( __( 'Enable activity %s posts', 'youzify' ), $name ),
            ), false, 'youzify_unallowed_activities'
        );

    }

    do_action( 'youzify_wall_posts_visibility_settings' );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}