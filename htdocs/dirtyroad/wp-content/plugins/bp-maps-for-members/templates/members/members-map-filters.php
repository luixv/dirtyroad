<?php
/**
 * Members Directory Map Filters Template
 *
 * You can copy this file to your-theme/buddypress/members/
 * and then edit the layout.
 */

if ( !defined( 'ABSPATH' ) ) exit;

$settings = get_site_option( 'bp-member-map-all-settings' );

extract($settings);

if ( isset( $_POST["member-type-filter"] ) && $_POST["member-type-filter"]  != '-1' ) {

	$member_type_selected = sanitize_text_field( $_POST["member-type-filter"] );
}


do_action( 'bp_before_members_page_map_filters' );

?>

	<div class="members-dir-map-search-div">

		<form action="" id="members-dir-map-search-form"  method="post">

			<?php

			if ( isset( $map_member_filter_types ) ) {

				$member_types =  bp_get_member_types( array(), 'objects');

				if ( ! isset( $map_member_types ) ) {
					$map_member_types = array();
				}

				//echo 'mts: '; var_dump(  $member_types );

				if ( ! empty( $member_types ) ) {

						if ( isset( $_POST["member-type-filter"] ) && $_POST["member-type-filter"]  != '-1' ) {

							$member_type = sanitize_text_field( $_POST["member-type-filter"] );
						}

					?>

					<div id="members-dir-map-filter-type" class="members dir-list-filter-type" style="display: flex;">

						<div style="width:150px;" >
							<?php _e("Member Types", "bp-member-maps"); ?>
						</div>

						<div style="flex-grow: 1;">

							<select name="member-type-filter" id="member-type-filter">

								<?php if ( empty( $map_member_types ) ) : ?>
									<option value="-1"><?php _e("All", "bp-member-maps"); ?></option>
								<?php endif; ?>

								<?php
								foreach ( $member_types as $mt ) {

									if ( isset( $map_member_types ) ) {
										if ( ! empty( $map_member_types ) && ! in_array( $mt->name, $map_member_types ) )
											continue;
									}

									$selected = '';

									if ( isset( $member_type_selected ) ) {
										//write_log('$member_type_selected in members-map-filters');
										//write_log($member_type_selected);
										//foreach( $member_type as $mtype ) {
											if ( strcasecmp( $mt->name, $member_type_selected ) == 0 ) {
												$selected = ' selected ';
											}
										//}

									}
									echo '<option value="' . $mt->name . '"' . $selected . '>' .  $mt->labels['name'] . '</option>';
								}
								?>

							</select>
						</div>

					</div>

					<?php

				}
			}

			?>

			<?php if ( isset( $map_member_filter_distance ) ) : ?>

				<div id="members-dir-map-filter-distance" class="members-dir-map-filter-distance" style="display: flex;">

					<div style="width:150px;" >
						<?php _e("Distance From: ", "bp-member-maps"); ?>
					</div>

					<div style="flex-grow: 1;">
						<?php
						if ( isset( $search_center ) ) {
							$center_value = $search_center;
						} else {
							$center_value = '';
						}
						?>


						<input id="pp_member_search_center" name="pp_member_search_center" type="text" value="<?php echo $center_value; ?>" placeholder="<?php _e("Type - then Select...", "bp-member-maps"); ?>" class="form-control" autocomplete="off">

						<?php

						if ( $map_member_distance_measurement == 'miles' ) {

							_e("Miles: ", "bp-member-maps");

						} else {

							_e("Kilometers: ", "bp-member-maps");
						}

						?>

						<?php
						if ( isset( $search_radius ) ) {
							$radius = $search_radius;
						} else {
							$radius = 10;
						}
						?>

						<input type="number" min="1" max="200" name="pp_member_search_radius" value="<?php  echo $radius; ?>">


						<?php
						if ( isset( $search_coords ) ) {
							$center_coords = $search_coords;
						} else {
							$center_coords = '';
						}
						?>

						<input type="hidden" id="pp_member_search_center_coords" name="pp_member_search_center_coords" value="<?php echo $center_coords; ?>" />

						<script type="text/javascript">
							function pp_member_search_radial() {

								var input = document.getElementById('pp_member_search_center');
								var options = {types: ['geocode']};
								var autocomplete = new google.maps.places.Autocomplete(input, options);
								autocomplete.setFields(['geometry']);

								google.maps.event.addListener(autocomplete, 'place_changed', function() {
									var place = autocomplete.getPlace();
									if ( place.geometry ) {
										console.log(place);
										var lat = place.geometry.location.lat();
										var lng = place.geometry.location.lng();
										var latlng = lat + ',' + lng;
										document.getElementById('pp_member_search_center_coords').value = latlng;
									}
								});
							}
							jQuery(document).ready (pp_member_search_radial);
						</script>

					</div>

				</div>

			<?php endif; ?>


			<?php if ( isset( $map_member_filter_keywords ) ) : ?>

				<div id="groups-dir-map-filter-keywords" class="groups-dir-map-filter-keywords" style="display: flex;">

					<div style="width:150px;" >
						<?php _e("Keywords: ", "bp-member-maps"); ?>
					</div>

					<div style="flex-grow: 1;">

						<input type="text" name="pp_member_search_keywords" id="pp_member_search_keywords" placeholder="Type..." value="<?php if ( isset( $keywords ) ) echo $keywords; ?>">

					</div>

				</div>

			<?php endif; ?>

			<div id="members-dir-map-filter-keywords" class="members-dir-map-filter-keywords" style="display: flex;">

				<div style="width:150px;" >
					&nbsp;
				</div>

				<div style="flex-grow: 1;">
					<input class="button-primary" type="submit" value="<?php _e("Submit", "bp-member-maps"); ?>">
				</div>

			</div>

		</form>


		<br><br>
	<div>


<?php do_action( 'bp_after_members_page_map_filters' ); ?>
