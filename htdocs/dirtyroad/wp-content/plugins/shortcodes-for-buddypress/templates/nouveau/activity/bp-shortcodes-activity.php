<?php
global $activity_atts;

add_filter(
	'bp_ajax_querystring',
	function( $qs ) {
		global $activity_atts;
		return $qs .= $activity_atts['bpsh_query'];
	}
);
add_filter(
	'bp_current_component',
	function() {
		return 'activity';
	}
);

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
do_action( 'bp_before_directory_activity' );

if ( $activity_atts['use_compat'] ) {
	echo '<div id="buddypress" class="buddypress-wrap bp-dir-hori-nav ' . esc_attr( $activity_atts['container_class'] ) . '">';
}
	bp_nouveau_before_activity_directory_content(); ?>

	<?php if ( $activity_atts['allow_posting'] == 'true' && is_user_logged_in() ) : ?>

		<?php bp_get_template_part( 'activity/post-form' ); ?>

	<?php endif; ?>

	<?php bp_nouveau_template_notices(); ?>

	<?php // if ( ! bp_nouveau_is_object_nav_in_sidebar() ) : ?>

		<?php // bp_get_template_part( 'common/nav/directory-nav' ); ?>

	<?php // endif; ?>
	
	<input type="hidden" id="activity_filters_objects" data-bp-filter="activity" value="<?php  echo $activity_atts['bpsh_query'] ?>" />
	<div class="screen-content">

		<?php bp_get_template_part( 'common/search-and-filters-bar' ); ?>

		<?php bp_nouveau_activity_hook( 'before_directory', 'list' ); ?>
		
		
		
		<div id="activity-stream" class="activity" data-bp-list="activity" >

				<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'directory-activity-loading' ); ?></div>

		</div><!-- .activity -->

		<?php bp_nouveau_after_activity_directory_content(); ?>

	</div><!-- // .screen-content -->

<?php

if ( $activity_atts['use_compat'] ) {
	echo '</div>';
}
