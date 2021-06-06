<?php
/**
 * Fires at the top of the members directory template file.
 *
 * @since 1.0.0
 */
/**
 * Fires at the top of the members directory template file.
 *
 * @since 1.5.0
 */

global $members_atts;
do_action( 'bp_before_directory_members_page' ); ?>

<div id="buddypress" class="<?php echo esc_attr( $members_atts['container_class'] ); ?>">
	
	<div class="groups-members-search">
		<!--input type="hidden" data-bp-filter="members" value="<?php // echo $members_atts['bpsh_query'] ?>" /-->
	</div>

	<?php
	/**
	 * Fires before the display of the members list tabs.
	 *
	 * @since 1.8.0
	 */
	do_action( 'bp_before_directory_members_tabs' );
	?>

	<form action="" method="post" id="members-directory-form" class="dir-form">
		
		<h2 class="bp-screen-reader-text">
		<?php
			/* translators: accessibility text */
			esc_html_e( 'Members directory', 'buddypress' );
		?>
		</h2>
		
		

		<div id="members-dir-list" class="members dir-list">
			
			<?php
			add_filter(
				'bp_ajax_querystring',
				function( $qs ) {
					global $members_atts;
					return $qs .= $members_atts['bpsh_query'];
				}
			);


			bp_get_template_part( 'members/members-loop' );
			?>
		</div><!-- #members-dir-list -->

		<?php

		/**
		 * Fires and displays the members content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_directory_members_content' );
		?>

		<?php wp_nonce_field( 'directory_members', '_wpnonce-member-filter' ); ?>

		<?php

		/**
		 * Fires after the display of the members content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_directory_members_content' );
		?>

	</form><!-- #members-directory-form -->

	<?php

	/**
	 * Fires after the display of the members.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_members' );
	?>

</div><!-- #buddypress -->

<?php

/**
 * Fires at the bottom of the members directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_directory_members_page' );
