<?php
/**
 * Fires before the activity directory listing.
 *
 * @since 1.0.0
 */

/**
 * Fires before the activity directory listing.
 *
 * @since 1.5.0
 */

global $activity_atts;
do_action( 'bp_before_directory_activity' ); ?>

<div id="buddypress" class="<?php echo esc_attr( $activity_atts['container_class'] ); ?>">

	<?php

	/**
	 * Fires before the activity directory display content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_directory_activity_content' );
	?>

	<?php if ( $activity_atts['allow_posting'] == 'true' && is_user_logged_in() ) : ?>

		<?php bp_get_template_part( 'activity/post-form' ); ?>

	<?php endif; ?>

	<div id="template-notices" role="alert" aria-atomic="true">
		<?php

		/**
		 * Fires towards the top of template pages for notice display.
		 *
		 * @since 1.0.0
		 */
		do_action( 'template_notices' );
		?>

	</div>
	

	<?php

	/**
	 * Fires before the display of the activity list.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_before_directory_activity_list' );
	?>

	<div class="activity" aria-live="polite" aria-atomic="true" aria-relevant="all">

		<?php
		add_filter(
			'bp_ajax_querystring',
			function( $qs ) {
				global $activity_atts;
				return $qs .= $activity_atts['bpsh_query'];
			}
		);

		bp_get_template_part( 'activity/activity-loop' );
		?>

	</div><!-- .activity -->

	<?php

	/**
	 * Fires after the display of the activity list.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_after_directory_activity_list' );
	?>

	<?php

	/**
	 * Fires inside and displays the activity directory display content.
	 */
	do_action( 'bp_directory_activity_content' );
	?>

	<?php

	/**
	 * Fires after the activity directory display content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_directory_activity_content' );
	?>

	<?php

	/**
	 * Fires after the activity directory listing.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_after_directory_activity' );
	?>

</div>
