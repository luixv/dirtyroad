<?php
/**
 * Fires at the top of the groups directory template file.
 *
 * @since 1.0.0
 */
/**
 * Fires at the top of the groups directory template file.
 *
 * @since 1.5.0
 */

global $groups_atts;
do_action( 'bp_before_directory_groups_page' ); ?>

<div id="buddypress" class="<?php echo esc_attr( $groups_atts['container_class'] ); ?>">

	<?php

	/**
	 * Fires before the display of the groups.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_groups' );
	?>

	<?php

	/**
	 * Fires before the display of the groups content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_groups_content' );
	?>

	
	<form action="" method="post" id="groups-directory-form" class="dir-form">

		<div id="template-notices" role="alert" aria-atomic="true">
			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
			do_action( 'template_notices' );
			?>

		</div>

		<h2 class="bp-screen-reader-text">
		<?php
			/* translators: accessibility text */
			esc_html_e( 'Groups directory', 'buddypress' );
		?>
		</h2>

		<div id="groups-dir-list" class="groups dir-list">
			<?php
			add_filter(
				'bp_ajax_querystring',
				function( $qs ) {
					global $groups_atts;
					return $qs .= $groups_atts['bpsh_query'];
				}
			);

			bp_get_template_part( 'groups/groups-loop' );
			?>
		</div><!-- #groups-dir-list -->

		<?php

		/**
		 * Fires and displays the group content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_directory_groups_content' );
		?>

		<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

		<?php

		/**
		 * Fires after the display of the groups content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_directory_groups_content' );
		?>

	</form><!-- #groups-directory-form -->

	<?php

	/**
	 * Fires after the display of the groups.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_groups' );
	?>

</div><!-- #buddypress -->

<?php

/**
 * Fires at the bottom of the groups directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_directory_groups_page' );
