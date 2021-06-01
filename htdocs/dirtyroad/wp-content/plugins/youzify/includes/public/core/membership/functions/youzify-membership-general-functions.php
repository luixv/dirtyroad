<?php

/**
 * Get Plugin Pages
 */
function youzify_membership_pages( $request_type = null, $id = null ) {

    // Get pages.
    $pages = youzify_option( 'youzify_membership_pages' );

    // Switch Key <=> Values
    if ( 'ids' == $request_type && ! empty( $pages ) ) {
        $pages_ids = array_flip( $pages );
        return $pages_ids;
    }

    return $pages;
}

/**
 * Check if it's a Membership Page
 */
function youzify_is_youzify_membership_page() {

    // Get Pages By ID's
    $pages = youzify_membership_pages( 'ids' );

    // check if its our plugin page.
    if ( is_page() && isset( $pages[ get_the_ID() ] ) ) {
        return true;
    }

    return false;
}

/**
 * Check for current page.
 */
function youzify_is_membership_page( $page ) {

    if ( 'register' == $page || 'activate' == $page ) {
        // Get Buddypress Pages.
        $bp_pages = get_option( 'bp-pages' );
        // Get Page ID.
        if ( ! isset( $bp_pages[ $page ] ) ) {
            return false;
        }
    } else{

        $page_id = youzify_membership_page_id( $page );

        if ( ! empty( $page_id ) && is_page( $page_id ) ) {
            return true;
        }

    }

    return false;
}

/**
 * Get Page Template.
 */
function youzify_membership_template( $page_template ) {

    // Check if it's Youzify membership page.
    if ( youzify_is_youzify_membership_page() ) {
		return YOUZIFY_TEMPLATE . 'membership/membership-template.php';
    }

    return $page_template;

}

add_filter( 'page_template', 'youzify_membership_template', 10 );

/**
 * Get Page Shortcode.
 */
function youzify_get_membership_page_shortcode( $page_id = null ) {

    // Get Plugin Pages.
    $pages = array_flip( youzify_option( 'youzify_membership_pages' ) );

    return '[youzify_' . str_replace( '-', '_', $pages[ $page_id ] ) . '_page]';
}

/**
 * Check If Registration is Incomplete
 */
function youzify_is_registration_incomplete() {

    // Get User Session Data.
    $user_session_data = youzify_membership_user_session_data( 'get' );

    if ( ! empty( $user_session_data ) ) {
        return true;
    }

    return false;
}

/**
 * Check If Limit login is enabled.
 */
function youzify_is_limit_login_enabled() {
    return youzify_option( 'youzify_enable_limit_login', 'on' ) == 'on' ? true : false;
}

/**
 * Login Form Short Code "[youzify_login]";
 */
function youzify_login_shortcode( $attributes = null ) {

    global $Youzify_Membership;

    ob_start();

    // Print Form
    echo '<div class="youzify-membership-login-shortcode">';
    $Youzify_Membership->form->get_form( 'login', $attributes );
    echo '</div>';

    ob_flush();

    $content = ob_get_contents();

    ob_end_clean();

    return $content;
}

add_shortcode( 'youzify_login', 'youzify_login_shortcode' );

/**
 * Register Form ShortCode "[youzify_register]";
 */
function youzify_register_shortcode( $attributes = null ) {

    // Get BuddyPress Data
    $bp = buddypress();

    // Get Results Data.
    $signup_results = get_transient( 'youzify_shortcode_register_' . $_SERVER['REMOTE_ADDR'] );

    // check to see if the Post ID/IP ($key) address is currently stored as a transient
    if ( false !== $signup_results ) {

            // Restore the old signup object.
            $bp->signup = $signup_results['signup'];

            if ( isset( $bp->signup->errors ) && !empty( $bp->signup->errors ) ) {

                $_POST = $signup_results['post'];

                foreach ( (array) $bp->signup->errors as $fieldname => $error_message ) {
                    /**
                     * Filters the error message in the loop.
                     *
                     * @since 1.5.0
                     *
                     * @param string $value Error message wrapped in html.
                     */
                    add_action( 'bp_' . $fieldname . '_errors', function() use ( $error_message ) {
                        echo apply_filters( 'bp_members_signup_error_message', "<div class=\"error\">" . $error_message . "</div>" );
                    } );
                }

            }

            // Reset Registration.
            delete_transient( 'youzify_shortcode_register_' . $_SERVER['REMOTE_ADDR'] );

    } else {
        // Init Step.
        $bp->signup->step = 'request-details';
    }

    ob_start();

    // Print Form
    echo '<div class="youzify-membership-register-shortcode">';
    require_once YOUZIFY_TEMPLATE . 'membership/members/register.php';
    echo '</div>';

    $content = ob_get_contents();

    ob_end_clean();

    return $content;

}

add_shortcode( 'youzify_register', 'youzify_register_shortcode' );

/**
 * Lost Password Form Short Code "[youzify_lost_password]";
 */
function youzify_lost_password_shortcode( $attributes = null ) {

    if ( is_user_logged_in() ) {
        return false;
    }

    global $Youzify_Membership;

    ob_start();
    // Print Form
    echo '<div class="youzify-membership-lost-password-shortcode">';
    $Youzify_Membership->form->get_form( 'lost_password', $attributes );
    echo '</div>';

    return ob_get_clean();

}

add_shortcode( 'youzify_lost_password', 'youzify_lost_password_shortcode' );

/**
 * Get Wordpress Error Messages.
 */
function youzify_membership_get_error_messages( $messages ) {

    // Init Array.
    $errors = array();

    // Get Errors
    foreach ( $messages as $message ) {
        $errors[] = youzify_membership_get_message( $message );
    }

    return $errors;

}

/**
 * Add Form Error Message.
 */
function youzify_membership_get_message( $content, $type = null ) {

    // Get Message Type.
    $type = ! empty( $type ) ? $type : 'error';

    // Get Message data.
    $error = array(
        'type'    => $type,
        'content' => $content
    );

    return $error;
}

/**
 * Cookie Name.
 */
function youzify_message_cookie_name() {
    return apply_filters( 'youzify_message_cookie_name', 'youzify-message' );
}

/**
 * Cookie Name Type.
 */
function youzify_message_type_cookie_name() {
    return apply_filters( 'youzify_message_type_cookie_name', 'youzify-message-type' );
}

/** Messages ******************************************************************/

/**
 * Add a feedback (error/success) message to the WP cookie so it can be displayed after the page reloads.
 *
 * @since 1.0.0
 *
 * @param string $message Feedback message to be displayed.
 * @param string $type    Message type. 'updated', 'success', 'error', 'warning'.
 *                        Default: 'success'.
 */
function youzify_membership_add_message( $message, $type = null ) {

    // Get Message Error.
    $type = ! empty( $type ) ? $type: 'error';

    // Get Message Content and serialize it.
    $message = serialize( $message );

    // Send the values to the cookie for page reload display.
    @setcookie( youzify_message_cookie_name(), $message, time() + 60 * 60 * 24, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
    @setcookie( youzify_message_type_cookie_name(), $type, time() + 60 * 60 * 24, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );

    // Get BuddyPress.

    global $Youzify_Membership;
    /**
     * Send the values to the $bp global so we can still output messages
     * without a page reload
     */
    $Youzify_Membership->template_message = $message;
    $Youzify_Membership->template_message_type = $type;
}

/**
 * Set up the display of the 'template_notices' feedback message.
 *
 * Checks whether there is a feedback message in the WP cookie and, if so, adds
 * a "template_notices" action so that the message can be parsed into the
 * template and displayed to the user.
 *
 * After the message is displayed, it removes the message vars from the cookie
 * so that the message is not shown to the user multiple times.
 *
 * @since 1.1.0
 *
 */
function youzify_core_setup_message() {

    // Get BuddyPress.
    global $Youzify_Membership;

    $cookie_msg_name = youzify_message_cookie_name();
    $cookie_msg_type = youzify_message_type_cookie_name();

    if ( empty( $Youzify_Membership->template_message ) && isset( $_COOKIE[ $cookie_msg_name ] ) ) {
        $Youzify_Membership->template_message = stripslashes( $_COOKIE[ $cookie_msg_name ] );
    }

    if ( empty( $Youzify_Membership->template_message_type ) && isset( $_COOKIE[ $cookie_msg_type ] ) ) {
        $Youzify_Membership->template_message_type = stripslashes( $_COOKIE[ $cookie_msg_type ] );
    }

    add_action( 'youzify_form_notices', 'youzify_core_render_message' );

    if ( isset( $_COOKIE[ $cookie_msg_name ] ) ) {
        @setcookie( $cookie_msg_name, false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
    }

    if ( isset( $_COOKIE[ $cookie_msg_type ] ) ) {
        @setcookie( $cookie_msg_type, false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
    }
}

add_action( 'bp_actions', 'youzify_core_setup_message', 0 );

/**
 * Render the 'template_notices' feedback message.
 */
function youzify_core_render_message() {

    global $Youzify_Membership;

    if ( ! isset( $Youzify_Membership->template_message_type ) ) {
        return false;
    }

    $messages = unserialize( $Youzify_Membership->template_message );

    if ( ! empty( $messages ) ) :

        $type = ! empty( $Youzify_Membership->template_message_type ) ? $Youzify_Membership->template_message_type : 'error';

        /**
         * Filters the 'template_notices' feedback message content.
         *
         * @since 1.5.5
         *
         * @param string $template_message Feedback message content.
         * @param string $type             The type of message being displayed.
         *                                 Either 'updated' or 'error'.
         */

        // Get Messages
        $messages = apply_filters( 'youzify_core_render_messages', $messages, $type ); ?>

        <div class="youzify-membership-form-message youzify-membership-<?php echo esc_attr( $type ); ?>-msg">
            <?php foreach( $messages as $message ) : ?>
                <p><?php echo $message['content']; ?></p>
            <?php endforeach; ?>
            <?php do_action( 'youzify_membership_form_messages_list' );?>
        </div>

    <?php

        /**
         * Fires after the display of any template_notices feedback messages.
         *
         * @since 1.1.0
         */
        do_action( 'youzify_membership_core_render_message' );

    endif;
}

/**
 * Call Membership Scripts .
 */
function youzify_membership_scripts() {

    // Main Css.
    wp_enqueue_style( 'youzify-membership', YOUZIFY_ASSETS . 'css/youzify-membership.min.css', array( 'youzify-opensans' ), YOUZIFY_VERSION );
    wp_enqueue_style( 'youzify-icons' );

}

add_action( 'wp_enqueue_scripts', 'youzify_membership_scripts' );