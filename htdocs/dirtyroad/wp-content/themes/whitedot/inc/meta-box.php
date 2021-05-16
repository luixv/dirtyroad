<?php
/**
 * Builds the main Layout meta box.
 *
 * @package WhiteDot
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function whitedot_settings_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function whitedot_settings_add_meta_box() {
	add_meta_box(
		'whitedot_settings-whitedot-settings',
		__( 'WhiteDot Settings', 'whitedot' ),
		'whitedot_settings_html',
		null,
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'whitedot_settings_add_meta_box' );

function whitedot_settings_html( $post) {
	wp_nonce_field( '_whitedot_settings_nonce', 'whitedot_settings_nonce' ); ?>

	<p>
		<label for="whitedot_settings_sidebar"><strong><?php _e( 'Sidebar', 'whitedot' ); ?></strong></label><br>
		<select name="whitedot_settings_sidebar" id="whitedot_settings_sidebar">
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'Default (Customizer Settings)' ) ? 'selected' : '' ?>>Default (Customizer Settings)</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'Left Sidebar' ) ? 'selected' : '' ?>>Left Sidebar</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'Right Sidebar' ) ? 'selected' : '' ?>>Right Sidebar</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'No Sidebar' ) ? 'selected' : '' ?>>No Sidebar</option>
		</select>
	</p>	<p>
		<label for="whitedot_settings_content_layout"><strong><?php _e( 'Content Layout', 'whitedot' ); ?></strong></label><br>
		<select name="whitedot_settings_content_layout" id="whitedot_settings_content_layout">
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Default (Customizer Settings)' ) ? 'selected' : '' ?>>Default (Customizer Settings)</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Boxed' ) ? 'selected' : '' ?>>Boxed</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Contained' ) ? 'selected' : '' ?>>Contained</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Full Width (Page Builder)' ) ? 'selected' : '' ?>>Full Width (Page Builder)</option>
		</select>
	</p>

	<?php if ( class_exists( 'Whitedot_Designer' ) ) { if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) { ?>
	<p>
		<label for="whitedot_settings_transparent_header"><strong><?php _e( 'Transparent Header', 'whitedot' ); ?></strong></label><br>
		<select name="whitedot_settings_transparent_header" id="whitedot_settings_transparent_header">
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_transparent_header' ) === 'Default (Customizer Settings)' ) ? 'selected' : '' ?>>Default (Customizer Settings)</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_transparent_header' ) === 'Enabled' ) ? 'selected' : '' ?>>Enabled</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_transparent_header' ) === 'Disabled' ) ? 'selected' : '' ?>>Disabled</option>
		</select>
	</p>	<p>
		<label for="whitedot_settings_sticky_header"><strong><?php _e( 'Sticky Header', 'whitedot' ); ?></strong></label><br>
		<select name="whitedot_settings_sticky_header" id="whitedot_settings_sticky_header">
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_sticky_header' ) === 'Default (Customizer Settings)' ) ? 'selected' : '' ?>>Default (Customizer Settings)</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_sticky_header' ) === 'Enabled' ) ? 'selected' : '' ?>>Enabled</option>
			<option <?php echo (whitedot_settings_get_meta( 'whitedot_settings_sticky_header' ) === 'Disabled' ) ? 'selected' : '' ?>>Disabled</option>
		</select>
	</p>
	<?php }} ?>

	<strong><?php _e( 'Disable Elements', 'whitedot' ); ?></strong>
	<p>

		<input type="checkbox" name="whitedot_settings_disable_primary_header" id="whitedot_settings_disable_primary_header" value="disable-primary-header" <?php echo ( whitedot_settings_get_meta( 'whitedot_settings_disable_primary_header' ) === 'disable-primary-header' ) ? 'checked' : ''; ?>>
		<label for="whitedot_settings_disable_primary_header"><?php _e( 'Disable Primary Header', 'whitedot' ); ?></label>	</p>	

		<?php if ( class_exists( 'Whitedot_Designer' ) ) { if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) { ?>
		<p>
		<input type="checkbox" name="whitedot_settings_disable_above_header_bar" id="whitedot_settings_disable_above_header_bar" value="disable-above-header-bar" <?php echo ( whitedot_settings_get_meta( 'whitedot_settings_disable_above_header_bar' ) === 'disable-above-header-bar' ) ? 'checked' : ''; ?>>
		<label for="whitedot_settings_disable_above_header_bar"><?php _e( 'Disable Above Header Bar', 'whitedot' ); ?></label>
		</p>	
		<?php }} ?>

		<p>
		<input type="checkbox" name="whitedot_settings_disable_title" id="whitedot_settings_disable_title" value="disable-title" <?php echo ( whitedot_settings_get_meta( 'whitedot_settings_disable_title' ) === 'disable-title' ) ? 'checked' : ''; ?>>
		<label for="whitedot_settings_disable_title"><?php _e( 'Disable Title', 'whitedot' ); ?></label>	
		</p>	

		<p>
		<input type="checkbox" name="whitedot_settings_disable_featured_image" id="whitedot_settings_disable_featured_image" value="disable-featured-image" <?php echo ( whitedot_settings_get_meta( 'whitedot_settings_disable_featured_image' ) === 'disable-featured-image' ) ? 'checked' : ''; ?>>
		<label for="whitedot_settings_disable_featured_image"><?php _e( 'Disable Featured Image', 'whitedot' ); ?></label>	
		</p>	

		<p>
		<input type="checkbox" name="whitedot_settings_disable_footer_branding" id="whitedot_settings_disable_footer_branding" value="disable-footer-branding" <?php echo ( whitedot_settings_get_meta( 'whitedot_settings_disable_footer_branding' ) === 'disable-footer-branding' ) ? 'checked' : ''; ?>>
		<label for="whitedot_settings_disable_footer_branding"><?php _e( 'Disable Footer Branding', 'whitedot' ); ?></label>	
		</p> 

		<p>
		<input type="checkbox" name="whitedot_settings_disable_footer_widgets" id="whitedot_settings_disable_footer_widgets" value="disable-footer-widgets" <?php echo ( whitedot_settings_get_meta( 'whitedot_settings_disable_footer_widgets' ) === 'disable-footer-widgets' ) ? 'checked' : ''; ?>>
		<label for="whitedot_settings_disable_footer_widgets"><?php _e( 'Disable Footer Widgets', 'whitedot' ); ?></label>	
		</p> 

		<p>
		<input type="checkbox" name="whitedot_settings_disable_footer_copyright" id="whitedot_settings_disable_footer_copyright" value="disable-footer-copyright" <?php echo ( whitedot_settings_get_meta( 'whitedot_settings_disable_footer_copyright' ) === 'disable-footer-copyright' ) ? 'checked' : ''; ?>>
		<label for="whitedot_settings_disable_footer_copyright"><?php _e( 'Disable Footer Copyright', 'whitedot' ); ?></label>
		</p>

		<?php
}

function whitedot_settings_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['whitedot_settings_nonce'] ) || ! wp_verify_nonce( $_POST['whitedot_settings_nonce'], '_whitedot_settings_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;


	if ( isset( $_POST['whitedot_settings_sidebar'] ) )
		update_post_meta( $post_id, 'whitedot_settings_sidebar', esc_attr( $_POST['whitedot_settings_sidebar'] ) );


	if ( isset( $_POST['whitedot_settings_content_layout'] ) )
		update_post_meta( $post_id, 'whitedot_settings_content_layout', esc_attr( $_POST['whitedot_settings_content_layout'] ) );


	if ( class_exists( 'Whitedot_Designer' ) ) { if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) { 

		if ( isset( $_POST['whitedot_settings_transparent_header'] ) )
			update_post_meta( $post_id, 'whitedot_settings_transparent_header', esc_attr( $_POST['whitedot_settings_transparent_header'] ) );
		if ( isset( $_POST['whitedot_settings_sticky_header'] ) )
			update_post_meta( $post_id, 'whitedot_settings_sticky_header', esc_attr( $_POST['whitedot_settings_sticky_header'] ) );
	}}


	if ( isset( $_POST['whitedot_settings_disable_primary_header'] ) )
		update_post_meta( $post_id, 'whitedot_settings_disable_primary_header', esc_attr( $_POST['whitedot_settings_disable_primary_header'] ) );
	else
		update_post_meta( $post_id, 'whitedot_settings_disable_primary_header', null );



	if ( class_exists( 'Whitedot_Designer' ) ) { if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) {

		if ( isset( $_POST['whitedot_settings_disable_above_header_bar'] ) )
			update_post_meta( $post_id, 'whitedot_settings_disable_above_header_bar', esc_attr( $_POST['whitedot_settings_disable_above_header_bar'] ) );
		else
			update_post_meta( $post_id, 'whitedot_settings_disable_above_header_bar', null );
	}}


	if ( isset( $_POST['whitedot_settings_disable_title'] ) )
		update_post_meta( $post_id, 'whitedot_settings_disable_title', esc_attr( $_POST['whitedot_settings_disable_title'] ) );
	else
		update_post_meta( $post_id, 'whitedot_settings_disable_title', null );


	if ( isset( $_POST['whitedot_settings_disable_featured_image'] ) )
		update_post_meta( $post_id, 'whitedot_settings_disable_featured_image', esc_attr( $_POST['whitedot_settings_disable_featured_image'] ) );
	else
		update_post_meta( $post_id, 'whitedot_settings_disable_featured_image', null );


	if ( isset( $_POST['whitedot_settings_disable_footer_branding'] ) )
		update_post_meta( $post_id, 'whitedot_settings_disable_footer_branding', esc_attr( $_POST['whitedot_settings_disable_footer_branding'] ) );
	else
		update_post_meta( $post_id, 'whitedot_settings_disable_footer_branding', null );

	if ( isset( $_POST['whitedot_settings_disable_footer_widgets'] ) )
		update_post_meta( $post_id, 'whitedot_settings_disable_footer_widgets', esc_attr( $_POST['whitedot_settings_disable_footer_widgets'] ) );
	else
		update_post_meta( $post_id, 'whitedot_settings_disable_footer_widgets', null );

	if ( isset( $_POST['whitedot_settings_disable_footer_copyright'] ) )
		update_post_meta( $post_id, 'whitedot_settings_disable_footer_copyright', esc_attr( $_POST['whitedot_settings_disable_footer_copyright'] ) );
	else
		update_post_meta( $post_id, 'whitedot_settings_disable_footer_copyright', null );
}
add_action( 'save_post', 'whitedot_settings_save' );


function whitedot_single_post_sidebar_settings() {

	if ( whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'Left Sidebar' ){
		remove_action('whitedot_the_sidebar','whitedot_main_sidebar', 10);
		add_action('whitedot_the_sidebar','whitedot_main_sidebar_left', 10);
	}elseif ( whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'Right Sidebar' ) {
		remove_action('whitedot_the_sidebar','whitedot_main_sidebar', 10);
		add_action('whitedot_the_sidebar','whitedot_main_sidebar_right', 10);
	}elseif ( whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'No Sidebar' ) {
		remove_action('whitedot_the_sidebar','whitedot_main_sidebar', 10);
	}else{
		
	}
}
add_action( 'template_redirect', 'whitedot_single_post_sidebar_settings' );


function whitedot_full_width_container_layout_body_class( $classes ) {
	$classes[] = 'whitedot-full-width whitedot-page-builder';

	return $classes;
}

function whitedot_single_post_container_layout_page_builder() {

	if ( whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Full Width (Page Builder)' ){
		remove_action('whitedot_header_end','whitedot_header_column_full_open', 10);
		remove_action('whitedot_footer_start','whitedot_header_column_full_close', 10);
		add_filter( 'body_class', 'whitedot_full_width_container_layout_body_class' );
	}
}
add_action( 'template_redirect', 'whitedot_single_post_container_layout_page_builder' );

function whitedot_supress_title( $title, $post_id = 0 ) {
	if ( ! $post_id ) {
		return $title;
	}

	if (whitedot_settings_get_meta( 'whitedot_settings_disable_title' ) === 'disable-title') {
		$hide_title = true;
	}else{
		$hide_title = false;
	}

	
	if ( ! is_admin() && is_singular() && intval( $hide_title ) && in_the_loop() ) {
		return '';
	}

	return $title;
}


function whitedot_single_post_disable_elements() {

	// Title
	add_filter( 'the_title', 'whitedot_supress_title', 10, 2 );

	// Featured Image
	if ( whitedot_settings_get_meta( 'whitedot_settings_disable_featured_image' ) === 'disable-featured-image' ){
		if ( is_page() ) {
			remove_action('whitedot_page_content_before','whitedot_thumbnail', 10);
		}else{
			remove_action('whitedot_single_post_before','whitedot_thumbnail', 10);
		}
	}

	// Primary Header
	if ( whitedot_settings_get_meta( 'whitedot_settings_disable_primary_header' ) === 'disable-primary-header' ){
		add_filter( 'body_class', 'whitedot_single_post_disable_primary_header_class' );
	}

	// Above Header
	if ( whitedot_settings_get_meta( 'whitedot_settings_disable_above_header_bar' ) === 'disable-above-header-bar' ){
		add_filter( 'body_class', 'whitedot_single_post_disable_above_header_class' );
	}

	// Footer Branding
	if ( whitedot_settings_get_meta( 'whitedot_settings_disable_footer_branding' ) === 'disable-footer-branding' ){
		remove_action('whitedot_footer_content','whitedot_footer_branding', 10);
		// add_filter( 'body_class', 'whitedot_single_post_disable_footer_branding_class' );
	}

	// Footer Branding
	if ( whitedot_settings_get_meta( 'whitedot_settings_disable_footer_widgets' ) === 'disable-footer-widgets' ){
		remove_action('whitedot_footer_content','whitedot_footer_widgets', 20);
	}

	// Footer Branding
	if ( whitedot_settings_get_meta( 'whitedot_settings_disable_footer_copyright' ) === 'disable-footer-copyright' ){
		remove_action('whitedot_footer_content','whitedot_footer_info', 20);
	}
}
add_action( 'template_redirect', 'whitedot_single_post_disable_elements' );



function whitedot_single_post_disable_primary_header_class( $classes ) {
	$classes[] = 'whitedot-primary-header-disabled';

	return $classes;
}

function whitedot_single_post_disable_above_header_class( $classes ) {
	$classes[] = 'whitedot-above-header-disabled';

	return $classes;
}

function whitedot_primary_id() {

	if ( whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'Left Sidebar' ){
		$primary_id = 'primary-right';
	}elseif ( whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'Right Sidebar' ) {
		$primary_id = 'primary-left';
	}elseif ( whitedot_settings_get_meta( 'whitedot_settings_sidebar' ) === 'No Sidebar' ) {
		$primary_id = 'primary-full-width';
	}else{
		$primary_id = 'primary';
	}
	return $primary_id;
}

function whitedot_container_class() {

	if ( whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Contained' || 'contained' === get_theme_mod( 'whitedot_page_container_layout', 'boxed' ) ){
		$container_class = 'contained-layout';
	}elseif ( whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Full Width (Page Builder)' ) {
		$container_class = 'page-builder-layout';
	}else{
		$container_class = 'boxed-layout';
	}
	return $container_class;
}

function whitedot_single_post_elements_disabled() {

	if ( is_single() ) {
		if ( whitedot_settings_get_meta( 'whitedot_settings_disable_featured_image' ) === 'disable-featured-image' ){
			add_filter('post_class','whitedot_single_post_image_disabled_post_class');
		}
		if ( !has_post_thumbnail() ){
			add_filter('post_class','whitedot_single_post_no_image_post_class');
		}
	}
	
}
add_action( 'template_redirect', 'whitedot_single_post_elements_disabled' );

function whitedot_single_post_image_disabled_post_class( $classes ) {
	$classes[] = 'thumbnail-disabled';

	return $classes;
}

function whitedot_single_post_no_image_post_class( $classes ) {
	$classes[] = 'no-thumbnail';

	return $classes;
}

