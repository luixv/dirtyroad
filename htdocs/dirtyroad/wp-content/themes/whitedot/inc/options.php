<?php
/**
 * WhiteDot Options Page
 *
 * @package WhiteDot
 */


//Creates a sub-menu under Appearence
function whitedot_add_theme_page() { 	
	add_theme_page("WhiteDot", "WhiteDot", "manage_options", "whitedot-settings", "whitedot_settings_page"); 

	add_action('admin_init', 'whitedot_init_settings' );
} 

add_action("admin_menu", "whitedot_add_theme_page");


//Registers and adds settings, sections and feilds
function whitedot_init_settings(){
}


function whitedot_settings_page() { 

	if (!current_user_can('manage_options' )) {
		wp_die('You do nat have permission to access this page' );
	}
	
	?>

	 <div class="wrap whitedot-options-page" >

		 	<h2></h2>

	 	<div class="whitedot-settings-wrap">

		 	<div class="whitedot-setting-header">
	 
	        <div class="whitedot_icon"><img src="<?php echo(get_template_directory_uri() . "/img/whitedot.png") ?>"></div>
	        <h1>WhiteDot</h1>
	 		</div>

			<?php
			if (isset($_GET['tab'])) {
	    		$active_tab = $_GET[ 'tab' ];
			}
			else {
				$active_tab = 'general_options';
			} // end if
			?>

	        <div class="nav-tab-wrapper whitedot-tab-wrapper">
	            <a href="?page=whitedot-settings&tab=general_options" class="nav-tab whitedot_setting_nav <?php if ($active_tab == 'general_options') echo 'nav-tab-active'; ?>">General</a>
	            <a href="?page=whitedot-settings&tab=whitedot_addons" class="nav-tab whitedot_setting_nav <?php if ($active_tab == 'whitedot_addons') echo 'nav-tab-active'; ?>">WhiteDot Addons</a>
	        </div>

	        <div class="whitedot_setting_content">

	        	<div class="whitedot-settings_error">
		        <?php settings_errors(); ?>
				</div>

				<?php if ($active_tab == 'general_options') { ?>

					<form method="post" action="options.php">

						<?php 
						$users_id = get_current_user_id();
    					if ( !get_user_meta( $users_id, 'whitedot_whats_new_notice_dismissed_1_0_2' ) ) {
    					?>

							<!-- <div class="whitedot-whats-new">
								<a href="?whitedot-whatsnew-notice-dismissed-1-0-2">
									<button type="button" class="notice-dismiss">
										<span class="screen-reader-text">Dismiss this notice.</span>
									</button>
								</a>
								<h2 class="getting-started-head">What's New in 1.0.2</h2>
								<div class="new-feature">
									<h3>Premium Add-on will be released on 29 September</h3>
									<p>I have been working on a premium add-on - <b>WhiteDot Designer</b> for the theme. It just needs a finishing touch, and the plugin/add-on will be ready and released on 29 september.</p>
									<a target="_blank"  class="button-primary" href="https://zeetheme.com/plugins/whitedot-designer">Watch Video</a>
								</div>
							</div> -->
						<?php } ?>

						<div class="whitedot-getting-started-wrap">

							<h2 class="getting-started-head">Getting Started with Whitedot</h2>

							<p>WhiteDot has a lot of customization options. Lets Customize your site.</p>
							<div class="whitedot-customize-btns">
								<a class="whitedot-customize-btn customize" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php">Customize Your Site</a>
								<a target="_blank" class="whitedot-customize-btn guide" href="https://whitedot-docs.zeetheme.com/knowledgebase_category/customizing-whitedot/">Read Customization Guide</a>
							</div>

							<div class="whitedot-branding-setting">
								<h3>Branding</h3>

								<div class="whitedot-site-title-settings whitedot-branding-options">
									<h4>Site Title</h4>
									<div class="whitedot-branding-options-wrap">
										<span class="whitedot-site-name"><?php bloginfo('name'); ?></span>
									</div>
									<a class="whitedot-site-name-edit branding-edit" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?autofocus[control]=blogname">Edit</a>
								</div>

								<div class="whitedot-site-tag-settings whitedot-branding-options">
									<h4>Site Tag</h4>
									<div class="whitedot-branding-options-wrap">
										<span class="whitedot-site-tag"><?php echo get_bloginfo( 'description', 'display' ); ?></span>
									</div>
									<a class="whitedot-site-tag-edit branding-edit" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?autofocus[control]=blogdescription">Edit</a>
								</div>

								<div class="whitedot-logo-upload whitedot-branding-options">
									<h4>Logo</h4>
									<?php if ( has_custom_logo() ) { ?>
										<div class="whitedot-branding-options-wrap">
											<?php the_custom_logo(); ?>
										</div>
										<a class="whitedot-logo-upload branding-edit" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?autofocus[control]=custom_logo">Edit</a>
									<?php }else{ ?>
										<div class="whitedot-branding-options-wrap">
											<span>No Logo</span>
										</div>
										<a class="whitedot-logo-upload branding-edit" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?autofocus[control]=custom_logo">Upload</a>
									<?php } ?>
								</div>

							</div>

							<div class="whitedot-more-customization-setting">
								<h3>More Customizer Settings</h3>

								<div class="whitedot-customizer-options">
									<h4>Header Setting</h4>
									<a class="whitedot-site-name-edit branding-edit" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?autofocus[section]=whitedot_header_settings_section">Edit</a>
								</div>

								<div class="whitedot-customizer-options">
									<h4>Footer Setting</h4>
									<a class="whitedot-site-name-edit branding-edit" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?autofocus[section]=whitedot_footer_settings_section">Edit</a>
								</div>

								<div class="whitedot-customizer-options">
									<h4>Color Options</h4>
									<a class="whitedot-site-name-edit branding-edit" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?autofocus[section]=whitedot_color_settings_section">Edit</a>
								</div>

								<div class="whitedot-customizer-options">
									<h4>Typography options</h4>
									<a class="whitedot-site-name-edit branding-edit" href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?autofocus[section]=whitedot_typography_settings_section">Edit</a>
								</div>

							</div>

							<div class="whitedot-premium" style="position: relative;">
								<!-- <a href="?whitedot-whatsnew-notice-dismissed-1-0-2">
									<button type="button" class="notice-dismiss">
										<span class="screen-reader-text">Dismiss this notice.</span>
									</button>
								</a> -->
								<h2> Try WhiteDot Premium Free for 7-days (No Credit Card Required)</h2>
								<p>Start your 7-day free trial for WhiteDot Premium Add-on - WHITEDOT DESIGNER. No credit card is required!! Just Download and install the plugin and activate your free trial. The trial gives you access to all the premium features that you would get as a paying customer.</p>
								<a class="btns" target="_blank" href="https://www.zeetheme.com/whitedot-designer-trial">Start Free Trial</a>
								<a class="btns" target="_blank" href="https://www.zeetheme.com/plugins/whitedot-designer">Know more about WhiteDot Designer</a>
							</div>


						</div>

					</form>

				<?php } ?>

				<?php if ($active_tab == 'whitedot_addons') { ?>

					<form method="post" action="options.php">

						<div class="whitedot-addon-wrap">

							<h2 class="whitedot_addons_heading">WhiteDot Add-ons</h2>

							<div class="whitedot-addon-box-wrap">

								<div class="whitedot-addon-box">
									<img src="https://res.cloudinary.com/zeetheme/image/upload/v1543600016/WhiteDot%20Addons/WhiteDot_Designer.jpg">
									<div class="whitedot-addon-content">
										<h3>WhiteDot Designer</h3>
										<?php if ( class_exists( 'Whitedot_Designer' ) ) { ?>
											<span class="addon-coming-soon">Installed</span>
										<?php } else { ?>
											<a target="_blank" class="get-addon-btn" href="https://www.zeetheme.com/plugins/whitedot-designer">Start Free Trial</a>
										<?php }  ?>
									</div>
								</div>

								<div class="whitedot-addon-box">
									<img src="https://res.cloudinary.com/zeetheme/image/upload/v1543600016/WhiteDot%20Addons/WhiteDot_Remove_Credits.jpg">
									<div class="whitedot-addon-content">
										<h3>WhiteDot Remove Credits</h3>
										<?php if ( class_exists( 'WhitedotRemoveCredits' ) ) { ?>
											<span class="addon-coming-soon">Installed</span>
										<?php } else { ?>
											<a target="_blank" class="get-addon-btn" href="https://www.zeetheme.com/plugins/whitedot-remove-credit">Get Add-on</a>
										<?php }  ?>
									</div>
								</div>

								<div class="whitedot-addon-box">
									<img src="https://res.cloudinary.com/zeetheme/image/upload/v1543600016/WhiteDot%20Addons/WhiteDot_LifterLMS_Plus.jpg">
									<div class="whitedot-addon-content">
										<h3>WhiteDot LifterLMS Plus</h3>
										<span class="addon-coming-soon">Coming Soon</span>
									</div>
								</div>

								<div class="whitedot-addon-box">
									<img src="https://res.cloudinary.com/zeetheme/image/upload/v1543600016/WhiteDot%20Addons/WhiteDot_WooCommerce_Plus.jpg">
									<div class="whitedot-addon-content">
										<h3>WhiteDot WooCommerce Plus</h3>
										<span class="addon-coming-soon">Coming Soon</span>
									</div>
								</div>

							</div>

						</div>

					</form>

				<?php } ?>
			</div>

			<div class="rate-us">
				<p>I love and care about you. I am putting maximum efforts to provide you the best functionalities. It would be highly appreciable if you could spend a couple of seconds to give a Nice Review to the theme to appreciate my efforts. So I can work hard to provide new features regularly &#128578;</p>
				<a class="whitedot-notice-btn" target="_blank" href="https://wordpress.org/support/theme/whitedot/reviews/#new-post">Give a 5 star Rating</a>
			</div> 
			<style type="text/css">
				.rate-us {
					padding: 20px 40px;
					background: #f9f9f9;
					padding-bottom: 40px;
				}
				.rate-us p {
					font-size: 14px;
				}
				.rate-us a {
					box-shadow: 0 17px 25px -14px #00b9eb;
					text-transform: uppercase;
				}
			</style>

		</div>
    </div><!-- /.wrap -->

	<?php   
	
}



