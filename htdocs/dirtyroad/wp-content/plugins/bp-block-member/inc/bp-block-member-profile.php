<?php
if ( !defined( 'BUDDYBLOCK_VERSION' ) ) exit;

function bp_profile_block_actions () {
	bp_profile_block_handle_actions();
}
add_action( 'bp_ready', 'bp_profile_block_actions' );


function bp_block_setup_navigation() {

	if ( ! is_super_admin() )
		if ( !is_user_logged_in() || get_current_user_id() != bp_displayed_user_id() ) 
			return;

	if ( bp_displayed_user_domain() ) {
		$user_domain = bp_displayed_user_domain();
	} elseif ( bp_loggedin_user_domain() ) {
		$user_domain = bp_loggedin_user_domain();
	} else {
		return;
	}		
		
	bp_core_new_subnav_item( array(
		'name'                    => __( 'Blocked Members', 'bp-block-member' ),
		'slug'                    => 'blocked',
		'parent_url'              => $user_domain . 'settings/',
		'parent_slug'             => 'settings',
		'screen_function'         => 'bp_my_blocked_members',
		'show_for_displayed_user' => false
	) );
}
add_action( 'bp_setup_nav',   'bp_block_setup_navigation' );

function bp_block_setup_tool_bar() {

	if ( !bp_use_wp_admin_bar() || defined( 'DOING_AJAX' ) )
		return;

	if ( is_user_logged_in() ) {
		global $wp_admin_bar;

		$wp_admin_bar->add_menu( array(
			'parent' => 'my-account-settings',
			'id'     => 'my-block-list',
			'title'  => __( 'Blocked Members', 'bp-block-member' ),
			'href'   => bp_loggedin_user_domain() . 'settings/blocked/'
		) );
	}
}
add_action( 'admin_bar_menu', 'bp_block_setup_tool_bar', 110 );


function bp_my_blocked_members() {
	add_action( 'bp_template_content', 'bp_my_blocked_members_screen' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}



function bp_my_blocked_members_screen() {
	global $wpdb;

	bp_blocked_member_types_update();
	
	$user_id = bp_displayed_user_id();
	
	
	$member_types = bp_get_member_types();
	
	if ( ! empty( $member_types ) ) {
	
		$blocked_member_types = get_user_meta( $user_id, 'blocked_member_types' ); 	// var_dump( $blocked_member_types );

		if ( empty( $blocked_member_types ) )
			$blocked_member_types = array();
		else
			$blocked_member_types = $blocked_member_types[0];
	
		//var_dump( $blocked_member_types );
	}
	
	
	$blocked_ids = $wpdb->get_col( "SELECT DISTINCT target_id FROM {$wpdb->base_prefix}bp_block_member WHERE user_id = '$user_id' ");
	
	if ( bp_displayed_user_domain() ) {
		$user_domain = bp_displayed_user_domain();
	} elseif ( bp_loggedin_user_domain() ) {
		$user_domain = bp_loggedin_user_domain();
	} else {
		return;
	}	
	
	$visibility = get_site_option( 'bp_block_visibility' );
	
?>

	<?php if (  $visibility && ! empty( $member_types ) ) : ?>
	
		<form action="<?php echo $user_domain . 'settings/blocked/'; ?>" method="post" class="standard-form" id="settings-form">
	
			<table class="users-blocked">
			
			<?php
		
				echo '<tr><td colspan="2"><strong>' . __( 'Hide by Member Type', 'bp-block-member' ) . '</strong><br/>';
				echo  __( 'Selected Member Types will not be visible to you in the Members Directory. &nbsp;And they will not be able to view your profile.', 'bp-block-member' ) . '</td></tr>';
				
				$mtypes = bp_get_member_types( array(), 'objects' );
				
				foreach( $mtypes as $mtype ) {
				?>
					
					<tr><td><?php echo $mtype->labels['name']; ?></td><td><input type="checkbox" name="member-type[]" value="<?php echo $mtype->name; ?>" <?php if ( in_array( $mtype->name, $blocked_member_types ) ) echo 'checked="checked"'; ?>></td></tr>
		
				<?php
				}
			?>
		
			</table>
			<?php wp_nonce_field( 'blocked_member_types-action', 'blocked_member_types-field' ); ?>
			<input type="hidden" name="profile-blocked-member-types" value="1">
			<input type="submit" name="submit" value="<?php _e( 'Save Hidden Member Types', 'bp-block-member' ); ?>" id="submit" class="auto">
			
		</form>
	
<?php endif; ?>	
	
	<table class="users-blocked">

	<?php
		echo '<tr><td colspan="2"><strong>' . __( 'Individual Members', 'bp-block-member' ) . '</strong></td></tr>';

		if ( empty( $blocked_ids ) )
			echo '<tr><td colspan="2">' . __( 'You are not blocking any individual members.', 'bp-block-member' ) . '</td></tr>';

		else {
			echo '<tr><td colspan="2">' . __( 'You are blocking these individual members:', 'bp-block-member' ) . '</td></tr>';

			foreach ( $blocked_ids as $blocked_id ) {
	?>
				<tr>
					<td class="user"><a href="<?php echo bp_core_get_user_domain( $blocked_id ); ?>"><?php echo bp_core_get_username( $blocked_id ); ?></a></td>
					<td class="actions"><a href="<?php echo bp_profile_unblock_link( $user_id, $blocked_id ); ?>"><?php _e( 'UnBlock', 'bp-block-member' ); ?></a></td>
				</tr>
	<?php
			}
		}
?>
	</table>
<?php
}

function bp_blocked_member_types_update() {
	
	if ( isset( $_POST['profile-blocked-member-types'] ) ) {

		if ( !wp_verify_nonce( $_POST['blocked_member_types-field'], 'blocked_member_types-action' ) )
			die('Security Check - Fail');
		
		if ( isset( $_POST['member-type'] ) ) {
			//echo 'vals: '; 	var_dump( $_POST['member-type']);
			update_user_meta( bp_displayed_user_id(), 'blocked_member_types', $_POST['member-type'] );
		}
		else
			delete_user_meta( bp_displayed_user_id(), 'blocked_member_types' );
		
		echo '<table class="users-blocked"><tr><td>' . 	__( 'Your Hidden Member Types have been updated.', 'bp-block-member' ) . '</td></tr></table>';
		
	}
	
}

function bp_profile_block_unblock( $blocker, $blockee ) {
	global $wpdb;
	
	$wpdb->query( $wpdb->prepare(
		"DELETE FROM {$wpdb->base_prefix}bp_block_member WHERE user_id = %d AND target_id = %d",
		$blocker, $blockee
		)
	);

}

function bp_profile_unblock_link( $user_id = 0, $blocked_id = 0 ) {
	return apply_filters( 'bp_profile_unblock_link', esc_url( add_query_arg( array(
		'action' => 'unblock',
		'list'   => $user_id,
		'num'    => $blocked_id,
		'token'  => wp_create_nonce( 'unblock-' . $blocked_id )
	) ) ), $user_id, $blocked_id );
}

function bp_profile_block_handle_actions() {

	if ( !isset( $_REQUEST['action'] ) || !isset( $_REQUEST['list'] ) || !isset( $_REQUEST['token'] ) || !isset( $_REQUEST['num'] ) ) return;
	
	switch ( $_REQUEST['action'] ) {
		case 'unblock' :
			if ( wp_verify_nonce( $_REQUEST['token'], 'unblock-' . $_REQUEST['num'] ) ) {
					
				bp_profile_block_unblock( $_REQUEST['list'], $_REQUEST['num'] );
				
				bp_core_add_message( __( 'Member was UnBlocked.', 'bp-block-member' ) );
			}
		break;

		default :
			do_action( 'bp_block_action' );
		break;
	}
	
	wp_safe_redirect(  esc_url_raw( remove_query_arg( array( 'action', 'list', 'num', 'token' ) ) ) );
	exit();
}
