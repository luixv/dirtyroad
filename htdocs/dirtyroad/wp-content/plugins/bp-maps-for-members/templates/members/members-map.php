<?php
/**
 * Members Directory Map Template
 *
 * You can copy this file to your-theme/buddypress/members/
 * and then edit the layout.
 */

do_action( 'bp_before_members_page_map' );

$settings = get_site_option( 'bp-member-map-all-settings' );
extract($settings);	//var_dump( $settings );

?>

	<?php $members_directory_url = bp_get_members_directory_permalink(); ?>

	<div id="buddypress" class="<?php echo pp_mm_main_div_class(); ?>">

		<!-- only show NAV on members directory . membersmap -->

		<?php if ( bp_is_members_directory () ) : ?>

			<?php if ( bp_get_theme_compat_id() == 'nouveau' ) : ?>

				<nav class="members-type-navs main-navs bp-navs dir-navs " role="navigation" aria-label="Directory menu">
					<ul class="component-navigation members-nav">
						<li id="members-all">
							<a href="<?php echo $members_directory_url; ?>">
								<?php _e( 'All Members', 'bp-member-maps' ); ?><span class="count"><?php bp_total_member_count(); ?></span>
							</a>
						</li>
					</ul>
				</nav>

				<?php bp_nouveau_template_notices(); ?>

				<?php bp_nouveau_before_loop(); ?>

				<div class="screen-content">

			<?php else : ?>

				<div class="item-list-tabs" aria-label="Members directory main navigation" role="navigation">
					<ul>
						<li class="no-ajax" id="members-no-all">
							<a  class="no-ajax" href="<?php echo $members_directory_url; ?>">
								<?php _e( 'All Members', 'bp-member-maps' ); ?> <span><?php bp_total_member_count(); ?></span>
							</a>
						 </li>
					</ul>
				</div>
				<br>

				<?php do_action( 'template_notices' ); 	?>

			<?php endif; ?>

		<?php endif; ?>
		<!-- end nav -->


		<?php do_action( 'bp_before_members_map' ); ?>

		<?php
		// maybe comment out this hook if the shortcode is on the Members page?
		do_action( 'bp_members_page_map_scripts' );
		?>


		<!-- maybe show filters -->

		<?php echo pp_mm_map_filters();	?>

		<!-- end maybe show filters -->


		<?php $members = pp_mm_gather_map_data(); ?>


		<div id="members-dir-map" class="members dir-list">

			<?php if ( ! empty( $members['geo_names'] ) ) : ?>

				<?php $map_id = uniqid( 'members_' ); ?>

				<div id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo $map_height_all; ?>px; width: 100%;"></div>

				<script type="text/javascript">

					var map_<?php echo $map_id; ?>;

					var markerBounds = new google.maps.LatLngBounds();

					var latLongMap = new Object();

					function readLatLongMap( key ) {
						return latLongMap[key];
					}

					function jiggleMarkers( locations ) {

						var currentLat;
						var currentLong;

						for ( var i = 0; i < locations.length; i++) {

							currentLat = +(locations[i][0]);
							currentLong = +(locations[i][1]);
							if( Math.abs(readLatLongMap( currentLat ) - currentLong) < .0005 ) {
								var longChange = +(2*( Math.random() - 0.5) * .01);
								var latChange = +(2*( Math.random() - 0.5) * .01);
								latLongMap[ (currentLat + latChange) ] = currentLong + longChange;
								locations[i][0] = currentLat + latChange;
								locations[i][1] = currentLong + longChange;

							} else {
								latLongMap[ currentLat ] = currentLong;
							}
						}
					}

					function pp_run_map_<?php echo $map_id ; ?>(){

						var locations = <?php echo json_encode( $members['geo_locations'] ); ?>;
						
						var titles = <?php echo json_encode( $members['geo_names'] ); ?>;
						var markers_content = <?php echo json_encode( $members['geo_content'] ); ?>;
						var infoWindow = new google.maps.InfoWindow( { maxWidth: 200 });

						jiggleMarkers( locations );

						var map_options = {
							maxZoom: <?php echo $map_zoom_level_all; ?>,
							mapTypeId: google.maps.MapTypeId.<?php echo strtoupper( $map_type_all ); ?>
						}
						map_<?php echo $map_id ; ?> = new google.maps.Map(document.getElementById("<?php echo $map_id ; ?>"), map_options);

						var markers = [];
						for(var i=0;i<locations.length;i++){
							var data = markers_content[i];
							var lat = locations[i][0];
							var lng = locations[i][1];
							var location = new google.maps.LatLng(lat,lng);
							var icon = "<?php echo pp_mm_load_green_dot(); ?>";
							var icon = "<?php 								
								if ( ! empty( $available ) ) {
									echo pp_mm_load_green_dot(); }
								else {
									echo pp_mm_load_dot(); 
								}
								?>"; 
							
							var marker = new google.maps.Marker({
								position: location,
								title: decode_title( titles[i] ),
								map: map_<?php echo $map_id ; ?>,
								icon:  new google.maps.MarkerImage(icon)
							});

							(function (marker, data) {
								google.maps.event.addListener(marker, "click", function (e) {
									infoWindow.setContent(data);
									infoWindow.open(map_<?php echo $map_id ; ?>, marker);
								});
							})(marker, data);

							markers.push(marker);

							markerBounds.extend(location);
						}

						var markerCluster = new MarkerClusterer(map_<?php echo $map_id; ?>, markers, {
							imagePath: '<?php echo pp_mm_load_cluster_icons(); ?>'
						});

						map_<?php echo $map_id; ?>.fitBounds(markerBounds);

					}

					function decode_title(txt){
						var sp = document.createElement('span');
						sp.innerHTML = txt;
						return sp.innerHTML;
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
				<?php _e( 'No Members with valid locations were found.', 'bp-member-maps' ); ?>

			<?php endif; ?>
		</div>


		<?php if ( bp_get_theme_compat_id() == 'nouveau' ) : ?>

			<?php bp_nouveau_after_loop(); ?>

			</div><!-- // .screen-content -->

		<?php endif; ?>

	</div><!-- #buddypress -->


<?php
do_action( 'bp_after_members_page_map' );
