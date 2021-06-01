<?php
defined( 'ABSPATH' ) || exit;

class BP_Better_Messages_Chats
{

    public static function instance()
    {

        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if ( null === $instance ) {
            $instance = new BP_Better_Messages_Chats;
            #$instance->setup_actions();
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    public function setup_actions(){
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_shortcode( 'bp_better_messages_chat_room', array( $this, 'layout' ) );
    }

    public function register_post_type(){
        $args = array(
            'public'             => false,
            'label'              => __( 'Chat Rooms', 'bp-better-messages' ),
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'author' ),

        );
        register_post_type( 'bpbm-chat', $args );
    }

    public function layout( $args ){
        $chat_id = $args['id'];

        error_reporting(0);

        global $bpbm_errors;
        $bpbm_errors = [];
        do_action('bp_better_messages_before_generation');

        $path = apply_filters('bp_better_messages_views_path', BP_Better_Messages()->path . '/views/');

        $thread_id = 1748;

        $is_mini = isset($_GET['mini']);

        $template = 'layout-chat-room.php';

        ob_start();

        $template = apply_filters( 'bp_better_messages_current_template', $path . $template, $template );

        if( ! BP_Better_Messages()->functions->is_ajax() && count( $bpbm_errors ) > 0 ) {
            echo '<p class="bpbm-notice">' . implode('</p><p class="bpbm-notice">', $bpbm_errors) . '</p>';
        }

        if( $template !== false ) {
            include($template);
        }

        if( isset($thread_id) && is_int($thread_id)  && ! isset($_GET['mini']) ){
            messages_mark_thread_read( $thread_id );
            update_user_meta(get_current_user_id(), 'bpbm-last-seen-thread-' . $thread_id, time());
        }

        $content = ob_get_clean();
        $content = str_replace('loading="lazy"', '', $content);

        $content = BP_Better_Messages()->functions->minify_html( $content );
        return $content;
    }
}

function BP_Better_Messages_Chats()
{
    return BP_Better_Messages_Chats::instance();
}