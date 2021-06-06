<?php

/**
 * General functionality for Block, Suspend, Report for BuddyPress
 *
 */
/**
 * Gets number of reports made by user.
 *
 * @since 3.0.0
 *
 */
function bptk_reports_made_by_user( $user_id )
{
    $args = array(
        'post_type'   => 'report',
        'numberposts' => -1,
        'meta_key'    => '_bptk_reported_by',
        'meta_value'  => $user_id,
    );
    $query = new WP_Query( $args );
    $count = $query->found_posts;
    return $count;
}

/**
 * Gets number of reports made about a user's activities.
 *
 * @since 3.0.0
 *
 */
function bptk_reports_about_user( $user_id )
{
    $args = array(
        'post_type'   => 'report',
        'numberposts' => -1,
        'meta_query'  => array( array(
        'key'   => '_bptk_member_reported',
        'value' => $user_id,
    ) ),
    );
    $query = new WP_Query( $args );
    $count = $query->found_posts;
    return $count;
}

/**
 * Gets number of substantiated reports made about a user's activities.
 *
 * @since 3.0.0
 *
 */
function bptk_substantiated_reports_about_user( $user_id )
{
    $args = array(
        'post_type'   => 'report',
        'numberposts' => -1,
        'meta_query'  => array( array(
        'key'   => '_bptk_member_reported',
        'value' => $user_id,
    ), array(
        'key'   => 'is_upheld',
        'value' => 1,
    ) ),
    );
    $query = new WP_Query( $args );
    $count = $query->found_posts;
    return $count;
}

/**
 * Gets number of reports made about an item.
 *
 * @since 3.0.0
 *
 */
function bptk_reports_per_item( $item_id )
{
    if ( !isset( $item_id ) || $item_id == '' ) {
        return;
    }
    $args = array(
        'post_type'   => 'report',
        'numberposts' => -1,
        'meta_query'  => array( array(
        'key'   => '_bptk_item_id',
        'value' => $item_id,
    ) ),
    );
    $query = new WP_Query( $args );
    $count = $query->found_posts;
    return $count;
}

/**
 * Gets number of substantiated reports made about an item.
 *
 * @since 3.0.0
 *
 */
function bptk_substantiated_reports_per_item( $item_id )
{
    if ( !isset( $item_id ) || $item_id == '' ) {
        return;
    }
    $args = array(
        'post_type'   => 'report',
        'numberposts' => -1,
        'meta_query'  => array( array(
        'key'   => '_bptk_item_id',
        'value' => $item_id,
    ), array(
        'key'   => 'is_upheld',
        'value' => 1,
    ) ),
    );
    $query = new WP_Query( $args );
    $count = $query->found_posts;
    return $count;
}

/**
 * Do ordinals.
 *
 * @since 3.0.0
 *
 */
function bptk_ordinal( $number )
{
    $ends = array(
        'th',
        'st',
        'nd',
        'rd',
        'th',
        'th',
        'th',
        'th',
        'th',
        'th'
    );
    
    if ( $number % 100 >= 11 && $number % 100 <= 13 ) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }

}

/**
 * Suspend member.
 *
 * @param $member_id The User ID
 *
 * @since 3.0.0
 *
 */
function bptk_suspend_member( $member_id )
{
    // Bail if no member id.
    if ( empty($member_id) ) {
        return;
    }
    // Check if already suspended
    $status = get_user_meta( $member_id, 'bptk_suspend', true );
    if ( $status == 1 ) {
        return;
    }
    // Update meta
    update_user_meta( $member_id, 'bptk_suspend', 1 );
    // Update moderated list
    bptk_add_to_moderated_list( $member_id, 'member' );
    // If on the front-end, display a message
    // if ( !is_admin() ) {
    bp_core_add_message( __( 'User successfully suspended', 'bp-toolkit' ) );
    // }
    $options = get_option( 'report_emails_section' );
    // If user suspension notifications are enabled, send mail
    if ( isset( $options['bptk_report_emails_automod_user'] ) && $options['bptk_report_emails_automod_user'] == "on" ) {
        bptk_send_email(
            'bptk-user-suspended',
            $member_id,
            'member',
            'suspensions'
        );
    }
    // Get the user's sessions object and destroy all sessions.
    WP_Session_Tokens::get_instance( $member_id )->destroy_all();
}

/**
 * Unsuspend member.
 *
 * @param $member_id The User ID
 *
 * @since 3.0.0
 *
 */
function bptk_unsuspend_member( $member_id )
{
    // Bail if no member id.
    if ( empty($member_id) ) {
        return;
    }
    // Check if already suspended
    $status = get_user_meta( $member_id, 'bptk_suspend', true );
    if ( $status == 0 || empty($status) ) {
        return;
    }
    // Update meta
    update_user_meta( $member_id, 'bptk_suspend', 0 );
    // Update moderated list
    bptk_remove_from_moderated_list( $member_id, 'member' );
    // If on the front-end, display a message
    // if ( !is_admin() ) {
    bp_core_add_message( __( 'User successfully unsuspended', 'bp-toolkit' ) );
    // }
    $options = get_option( 'report_emails_section' );
    // If user unsuspension notifications are enabled, send mail
    if ( isset( $options['bptk_report_emails_restored_user'] ) && $options['bptk_report_emails_restored_user'] == "on" ) {
        bptk_send_email(
            'bptk-user-item-restored',
            $member_id,
            'member',
            'suspensions'
        );
    }
}

/**
 * Get any integrations.
 *
 * @since 3.0.0
 *
 */
function get_integrations()
{
    return false;
}

/**
 * Returns all unread reports.
 *
 * @since 3.0.0
 */
function bptk_get_unread_reports()
{
    $args = array(
        'post_type'   => 'report',
        'numberposts' => -1,
        'meta_query'  => array( array(
        'key'     => 'is_read',
        'value'   => '0',
        'compare' => '=',
    ) ),
    );
    $unread_reports = get_posts( $args );
    return $unread_reports;
}

/**
 * Display a bptk help tip.
 *
 * @param string $tip Help tip text.
 * @param bool $allow_html Allow sanitized HTML if true or escape.
 *
 * @return string
 *
 * @since  3.1.0
 *
 */
function bptk_help_tip( $tip, $allow_html = false )
{
    
    if ( $allow_html ) {
        $tip = bptk_sanitize_tooltip( $tip );
    } else {
        $tip = esc_attr( $tip );
    }
    
    return '<span class="bptk-help-tip" data-tip="' . $tip . '"></span>';
}

/**
 * Sanitize a string destined to be a tooltip.
 *
 * Tooltips are encoded with htmlspecialchars to prevent XSS. Should not be used in conjunction with esc_attr()
 *
 * @param string $var Data to sanitize.
 *
 * @return string
 *
 * @since  3.1.0
 *
 */
function bptk_sanitize_tooltip( $var )
{
    return htmlspecialchars( wp_kses( html_entity_decode( $var ), array(
        'br'     => array(),
        'em'     => array(),
        'strong' => array(),
        'small'  => array(),
        'span'   => array(),
        'ul'     => array(),
        'li'     => array(),
        'ol'     => array(),
        'p'      => array(),
    ) ) );
}

/**
 * Checks to see if user is suspended.
 *
 * @param int $user_id User ID.
 *
 * @return boolean
 *
 * @since  3.1.0
 *
 */
function is_suspended( $user_id )
{
    $status = get_user_meta( $user_id, 'bptk_suspend', true );
    
    if ( $status == 0 || empty($status) ) {
        return false;
    } else {
        return true;
    }

}

/**
 * Checks to see if user has been blacklisted.
 *
 * @param int $user_id User ID.
 *
 * @return boolean
 *
 * @since  3.1.0
 *
 */
function is_blacklisted( $user_id )
{
    $options = get_option( 'report_section' );
    
    if ( isset( $options['bptk_report_blacklist'] ) ) {
        // Convert string to array
        $blacklist = explode( ',', $options['bptk_report_blacklist'] );
        // Search the array for current user and return result
        
        if ( array_search( $user_id, $blacklist ) !== false ) {
            return true;
        } else {
            return false;
        }
    
    }

}

/**
 * Add member to blacklist.
 *
 * @param $member_id The User ID
 *
 * @since 3.1.0
 *
 */
function bptk_blacklist_member( $user_id )
{
    // Bail if no user id.
    if ( empty($user_id) ) {
        return;
    }
    // Check if already blacklisted
    
    if ( is_blacklisted( $user_id ) ) {
        return;
    } else {
        // Clear
        wp_cache_delete( 'alloptions', 'options' );
        $options = get_option( 'report_section' );
        
        if ( isset( $options['bptk_report_blacklist'] ) ) {
            // Convert string to array
            $blacklist = explode( ',', $options['bptk_report_blacklist'] );
            // Add user to array
            $blacklist[] = $user_id;
            // Convert back to string
            $comma_separated = implode( ",", $blacklist );
            $options['bptk_report_blacklist'] = $comma_separated;
            // Save
            update_option( 'report_section', $options );
        }
    
    }

}

/**
 * Remove member from blacklist.
 *
 * @param $member_id The User ID
 *
 * @since 3.1.0
 *
 */
function bptk_unblacklist_member( $user_id )
{
    // Bail if no user id.
    if ( empty($user_id) ) {
        return;
    }
    // Clear
    wp_cache_delete( 'alloptions', 'options' );
    $options = get_option( 'report_section' );
    
    if ( isset( $options['bptk_report_blacklist'] ) ) {
        // Convert string to array
        $blacklist = explode( ',', $options['bptk_report_blacklist'] );
        // Search the array for current user and return result
        $key = array_search( $user_id, $blacklist );
        // If present, remove from array
        
        if ( $key !== false ) {
            unset( $blacklist[$key] );
        } else {
            return;
        }
        
        // Convert back to string
        $comma_separated = implode( ",", $blacklist );
        $options['bptk_report_blacklist'] = $comma_separated;
        // Save
        update_option( 'report_section', $options );
    }

}

/**
 * Checks to see if report has been upheld.
 *
 * @param int $post_id Post ID.
 *
 * @return boolean
 *
 * @since  3.1.0
 *
 */
function is_upheld( $post_id )
{
    $status = get_post_meta( $post_id, 'is_upheld', true );
    
    if ( $status == 0 || empty($status) ) {
        return false;
    } else {
        return true;
    }

}

/**
 * Checks to see if report item has been moderated.
 *
 * @param int $post_id Post ID.
 *
 * @return boolean
 *
 * @since  3.1.0
 *
 */
function is_moderated( $post_id )
{
    global  $post ;
    $option = 'bptk_moderated_' . $post->_bptk_activity_type . '_list';
    $exists = get_option( $option );
    
    if ( $exists && in_array( $post->_bptk_item_id, $exists ) ) {
        return true;
    } else {
        return false;
    }

}

/**
 * Checks to see if BuddyBoss is the theme.
 *
 * @return boolean
 *
 * @since  3.1.0
 *
 */
function bptk_is_buddyboss()
{
    $theme = get_stylesheet();
    
    if ( $theme == 'buddyboss-theme' || $theme == 'buddyboss-theme-child' ) {
        return true;
    } elseif ( in_array( 'buddyboss-platform/bp-loader.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        return true;
    } else {
        return false;
    }

}

/**
 * Marks a report as upheld.
 *
 * @param $post_id The ID of the Post.
 *
 * @since  3.1.0
 *
 */
function bptk_set_upheld( $post_id )
{
    if ( !$post_id ) {
        return;
    }
    update_post_meta( $post_id, 'is_upheld', 1 );
}

/**
 * Marks a report as not upheld.
 *
 * @param $post_id The ID of the Post.
 *
 * @since  3.1.0
 *
 */
function bptk_remove_upheld( $post_id )
{
    if ( !$post_id ) {
        return;
    }
    update_post_meta( $post_id, 'is_upheld', 0 );
}
