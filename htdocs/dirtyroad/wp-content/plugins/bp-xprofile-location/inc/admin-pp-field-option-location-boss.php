<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// add a field for a Google Maps API key to wp-admin > BuddyBoss > PhiloPress

if ( ! function_exists( 'buddyboss_pp_add_admin_tab' ) ) {

    function buddyboss_pp_add_admin_tab( $tabs ) {

        $tabs['99'] = array(
	        'href'  => bp_get_admin_url( add_query_arg( array( 'page' => 'philopress' ), 'admin.php' ) ),
	        'name'  => 'PhiloPress',
	        'class' => 'philopress',
        );

        return $tabs;
    }

	add_filter( 'bp_core_get_admin_tabs', 'buddyboss_pp_add_admin_tab' );
}

if ( ! function_exists( 'buddyboss_pp_admin_menus' ) ) {

    function buddyboss_pp_admin_menus() {
	    add_submenu_page(
		    'buddyboss-platform',
		    'PhiloPress',
			'PhiloPress',
		    'manage_options',
		    'philopress',
		    'buddyboss_philopress_screen'
	    );
    }

    add_action( 'bp_init', function() {
	    add_action( bp_core_admin_hook(), 'buddyboss_pp_admin_menus' );
    } );
}

function buddyboss_philopress_screen() {

	pp_buddyboss_settings_submitted();

	$pp_gapikey = bp_get_option( 'pp_gapikey' );

	?>

    <div class="wrap">

        <h2 class="nav-tab-wrapper"><?php bp_core_admin_tabs( 'PhiloPress' ); ?></h2>

		<div class="bp-admin-card section-bp_main">

			<h2>PhiloPress Settings</h2>

			<h3>Google Maps API Key</h3>

			<form action="<?php echo admin_url( '/admin.php?page=philopress' ); ?>" method="post">

				<?php wp_nonce_field( 'pp_gapikey_action', 'pp_gapikey_field' ); ?>

				<input type="hidden" id="pp_gapikey_form" name="pp_gapikey_form" value="6bfb4589">

				<input type="text" size="50" id="pp_gapikey" name="pp_gapikey" placeholder="Paste Your Google Maps API Key Here" value="<?php echo $pp_gapikey; ?>" />

				<p class="description"><?php _e("A Key is required. If you do not have one, follow these instructions:", "bp-profile-location");?>
				<br><a href="https://www.philopress.com/google-maps-api-key/" target="_blank">Get a Google Maps API Key</a></p>

				<p class="submit"><input type="submit" name="submit" class="button-primary" value="<?php _e("Save PhiloPress Settings", "bp-profile-location");?>" /></p>

			</form>
		</div>
    </div>

	<?php
}

function pp_buddyboss_settings_submitted() {

	if ( isset( $_POST['pp_gapikey_form'] ) && $_POST['pp_gapikey_form'] == '6bfb4589' ) {

		check_admin_referer( 'pp_gapikey_action', 'pp_gapikey_field' );

		if ( isset( $_POST['pp_gapikey'] ) && ! empty( $_POST['pp_gapikey'] ) ) {

			$value = sanitize_text_field( $_POST['pp_gapikey'] );
			bp_update_option( 'pp_gapikey', $value );

			?>
			<div class="notice notice-success is-dismissible">
				<p><strong><?php _e("PhiloPress Settings saved.", "bp-profile-location");?></strong></p>
			</div>
			<?php

		} else {
			?>
			<div class="notice notice-error is-dismissible">
				<p><strong><?php _e("There was an error. The PhiloPress Settings were not saved.", "bp-profile-location");?></strong></p>
			</div>
			<?php

			return false;

		}
	}

	return;
}

