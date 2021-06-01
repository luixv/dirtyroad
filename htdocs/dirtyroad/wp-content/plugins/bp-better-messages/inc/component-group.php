<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Component Class.
 *
 * @since 1.0.0
 */
class BP_Better_Messages_Group extends BP_Group_Extension
{

    public static function instance()
    {

        // Store the instance locally to avoid private static replication
        static $instance = null;

        // Only run these methods if they haven't been run previously
        if ( null === $instance ) {
            $instance = new BP_Better_Messages_Group;
            $instance->setup_hooks();
        }

        // Always return the instance
        return $instance;

        // The last metroid is in captivity. The galaxy is at peace.
    }

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        $args = array(
            'slug'              => 'bp-messages',
            'name'              => __( 'Messages', 'bp-better-messages' ),
            'nav_item_position' => 105,
            'enable_nav_item'   => apply_filters( 'bp_better_messages_enable_groups_tab', true ),
            'screens'           => array(),
            'visibility'        => 'private',
            'access'            => 'member'
        );

        if( BP_Better_Messages()->settings['enableGroups'] === '1' ){
            parent::init( $args );
        }
    }

    /**
     * Set some hooks to maximize BuddyPress integration.
     *
     * @since 1.0.0
     */
    public function setup_hooks()
    {
        add_action( 'groups_join_group',    array( $this, 'on_groups_member_status_change'), 10, 2 );
        add_action( 'groups_leave_group',   array( $this, 'on_groups_member_status_change'), 10, 2 );
        add_action( 'groups_ban_member',    array( $this, 'on_groups_member_status_change'), 10, 2 );
        add_action( 'groups_remove_member', array( $this, 'on_groups_member_status_change'), 10, 2 );
        add_action( 'groups_unban_member',  array( $this, 'on_groups_member_status_change'), 10, 2 );

        add_action( 'bp_rest_group_members_create_item', array( $this, 'on_groups_member_rest_update'), 10, 5 );
        add_action( 'bp_rest_group_members_update_item', array( $this, 'on_groups_member_rest_update'), 10, 5 );
        add_action( 'bp_rest_group_members_delete_item', array( $this, 'on_groups_member_rest_update'), 10, 5 );
    }

    public function on_groups_member_rest_update( $user, $group_member, $group, $response, $request ){
        $this->on_groups_member_status_change( $group->id, $user->id );
    }

    public function on_groups_member_status_change( $group_id, $user_id ){
        $thread_id = $this->get_group_thread_id( $group_id );
        $this->sync_thread_members( $thread_id );
    }

    public function get_group_thread_id( $group_id ){
        global $wpdb;

        $thread_id = (int) $wpdb->get_var( $wpdb->prepare( "
        SELECT bpbm_threads_id 
        FROM `" . bpbm_get_table('threadsmeta') . "` 
        WHERE `meta_key` = 'group_id' 
        AND   `meta_value` = %s
        ", $group_id ) );

        $recipients_count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(*)  FROM `" . bpbm_get_table('recipients') . "` WHERE `thread_id` = %d", $thread_id));

        if( $recipients_count === 0 ){
            $thread_id = false;
        }

        if( ! $thread_id ) {
            $last_thread = intval($wpdb->get_var("SELECT MAX(thread_id) FROM `" . bpbm_get_table('messages') . "`;"));
            $thread_id = $last_thread + 1;
            $group = new BP_Groups_Group( $group_id );

            $wpdb->insert(
                bpbm_get_table('messages'),
                array(
                    'sender_id' => 0,
                    'thread_id' => $thread_id,
                    'subject' => $group->name,
                    'message' => '<!-- BBPM START THREAD -->'
                )
            );

            BP_Better_Messages()->functions->update_thread_meta( $thread_id, 'group_thread', true );
            BP_Better_Messages()->functions->update_thread_meta( $thread_id, 'group_id', $group_id );

            $this->sync_thread_members( $thread_id );
        }

        return $thread_id;
    }

    public function sync_thread_members( $thread_id ){

        $group_id = BP_Better_Messages()->functions->get_thread_meta( $thread_id, 'group_id' );
        $group    = new BP_Groups_Group( $group_id );

        if( ! $group ) {
            return false;
        }

        global $wpdb;
        $members   = BP_Groups_Member::get_group_member_ids( $group_id );
        $array     = [];

        /**
         * All users ids in thread
         */
        $recipients = BP_Messages_Thread::get_recipients_for_thread( $thread_id );

        foreach( $members as $index => $member ){
            if( isset( $recipients[$member] ) ){
                unset( $recipients[$member] );
                continue;
            }

            $array[] = [
                'user_id'      => $member,
                'thread_id'    => $thread_id,
                'unread_count' => 0,
                'sender_only'  => 0,
                'is_deleted'   => 0,
            ];
        }

        if( count($array) > 0 ) {
            foreach ($array as $item) {
                $wpdb->insert( bpbm_get_table('recipients'), $item, ['%d','%d','%d','%d','%d'] );
            }
        }

        if( count($recipients) > 0 ) {
            foreach ($recipients as $user_id => $recipient) {
                global $wpdb;
                $wpdb->delete( bpbm_get_table('recipients'), [
                    'thread_id' => $thread_id,
                    'user_id'   => $user_id
                ], ['%d','%d'] );
            }
        }

        return true;
    }

    function display( $group_id = NULL ) {
        echo BP_Better_Messages()->functions->get_group_page( $group_id );
    }
}

bp_register_group_extension( 'BP_Better_Messages_Group' );

function BP_Better_Messages_Group()
{
    return BP_Better_Messages_Group::instance();
}