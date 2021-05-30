<?php
/**
 * BuddyPress Member Review shortcode tab.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wbcom-tab-content">
	<div class="bupr-adming-setting">
		<div class="bupr-tab-header">
			<h3>
				<?php esc_html_e( 'Reviews Shortcode ', 'bp-member-reviews' ); ?>
			</h3>
			<input type="hidden" class="bupr-tab-active" value="shortcode"/>
		</div>

		<div class="bupr-admin-settings-block">
			<div id="bupr-settings-tbl" class="bupr-table">
				<div class="bupr-admin-row border">
					<div class="bupr-admin-col-6 bupr-label">
						<strong>
							<?php echo esc_attr( '[add_profile_review_form]' ); ?>
						</strong>
					</div>
					<div class="bupr-admin-col-6">
						<p class="description">
							<?php esc_html_e( 'This shortcode will display the BuddyPress member review form.', 'bp-member-reviews' ); ?>
						</p>
					</div>
					<div class="bupr-admin-row border">
						<div class="bupr-admin-col-6 bupr-label">
							<strong>
								<?php echo esc_attr( '[bupr_display_top_members]' ); ?>
							</strong>
						</div>
						<div class="bupr-admin-col-6">
							<p class="description">
								<?php esc_html_e( 'This shortcode will display Top rated/reviewed members.', 'bp-member-reviews' ); ?>							
							</p>
							<h4><?php esc_html_e( 'Parameters:', 'bp-member-reviews' ); ?></h4>
							<ul>
								<li><p><b><?php echo esc_html( 'title' ) . '</b>:  ' . esc_html__( 'Using this parameter, you can add a title before top-rated/reviewed members listing. For example ', 'bp-member-reviews' ) . "[bupr_display_top_members title='Top Rated Member Listing']"; ?></p></li>
								<li><p><b><?php echo esc_html( 'total_member' ) . '</b>:  ' . esc_html__( 'This parameter lets you limit the number of reviews on the Top Rated/Reviewed Members listing page. For example: ', 'bp-member-reviews' ) . '[bupr_display_top_members total_member=4]'; ?></b></li>
								<li><p><b><?php echo esc_html( 'type' ) . '</b>:  ' . esc_html__( "Using this parameter you can display members according to 'top reviewed' or 'top rated'. For example: ", 'bp-member-reviews' ) . "[bupr_display_top_members type='top reviewed']"; ?></p></li>
								<li><p><b><?php echo esc_html( 'avatar' ) . '</b>:  ' . esc_html__( "This parameter gives you flexibility to 'show' or 'hide' member avatar in the listing. For example: ", 'bp-member-reviews' ) . "[bupr_display_top_members avatar='hide']"; ?></p></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
