<?php

/**
 * Template for a single Member map
 * You can copy this file to your-theme/buddypress/members/single
 * and then edit the layout.
 */

?>

<?php

$settings_single = get_site_option( 'bp-member-map-single-settings' );
extract($settings_single);
$key = 'geocode_' . $map_location_field;

$latlng = get_user_meta( bp_displayed_user_id(), $key, true );
$address = xprofile_get_field_data( $map_location_field, bp_displayed_user_id(), 'comma' );


if ( ! empty( $latlng ) ) {

	if ( wp_script_is( 'google-places-api', 'registered' ) ) {

		wp_enqueue_script( 'google-places-api' );
		wp_print_scripts( 'google-places-api' );

	}
}

?>

<div class="member-map-profile">

	<?php
	if ( ! empty( $address ) ) {
		echo stripslashes( $address ) . '<br>';
	}
	?>

	<?php if ( ! empty( $latlng ) ) : ?>

		<?php $map_id = uniqid( 'pp_member_map_' ); ?>


		<div id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo $map_height; ?>px; width: 100%;"></div>

	    <script type="text/javascript">
			var map_<?php echo $map_id; ?>;
			function pp_run_map_<?php echo $map_id ; ?>(){
				var location = new google.maps.LatLng(<?php echo $latlng; ?>);
				var icon = "<?php echo pp_mm_load_dot(); ?>";
				var map_options = {
					zoom: <?php echo $map_zoom_level; ?>,
					center: location,
					mapTypeId: google.maps.MapTypeId.<?php echo strtoupper( $map_type ); ?>
				}
				map_<?php echo $map_id ; ?> = new google.maps.Map(document.getElementById("<?php echo $map_id; ?>"), map_options);
				var marker = new google.maps.Marker({
				position: location,
				map: map_<?php echo $map_id ; ?>,
				icon:  new google.maps.MarkerImage(icon)
				});

			}

			google.maps.event.addDomListener(window, "resize", function() {
				var map = map_<?php echo $map_id; ?>;
				var center = map.getCenter();
				google.maps.event.trigger(map, "resize");
				map.setCenter(center);
			});

			pp_run_map_<?php echo $map_id ; ?>();
		</script>

	<?php else : ?>

		<?php _e( 'A map cannot be created for this Member. The geocode does not exist.', 'bp-member-maps' ); ?>

	<?php endif; ?>

</div>
