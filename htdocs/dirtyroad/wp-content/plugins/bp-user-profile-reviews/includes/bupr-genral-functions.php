<?php
/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/buddypress-member-review/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/buddypress-member-review/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param   string $template_name          Template to load.
 * @param   string $string $template_path  Path to templates.
 * @param   string $default_path           Default path to template files.
 * @return  string                          Path to the template file.
 */
function bupr_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	if ( ! $template_path ) :
		$template_path = 'buddypress-member-review';
	endif;
	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = BUPR_PLUGIN_PATH . 'includes/templates/';
	endif;
	// Search template file in theme folder.
	$template = locate_template(
		array(
			$template_path . $template_name,
			$template_name,
		)
	);
	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;
	return apply_filters( 'bupr_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @see learnmate_locate_template()
 *
 * @param string $template_name          Template to load.
 * @param array  $args                   Args passed for the template file.
 * @param string $string $template_path  Path to templates.
 * @param string $default_path           Default path to template files.
 */
function bupr_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {
	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;
	$template_file = bupr_locate_template( $template_name, $tempate_path, $default_path );
	if ( ! file_exists( $template_file ) ) :
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	endif;
	include $template_file;
}

/**
 * Get Review tab name on memeber profile.
 *
 * @return string Tab name
 */
function bupr_profile_review_singular_tab_name() {
	global $bupr;

	if ( isset( $bupr['review_label'] ) ) {
		$tab_name = $bupr['review_label'];
	}
	return apply_filters( 'bupr_profile_review_singular_tab_name', esc_html( $tab_name ) );
}

/**
 * Get Review tab name on memeber profile.
 *
 * @return string Tab name
 */
function bupr_profile_review_tab_name() {
	global $bupr;

	if ( isset( $bupr['review_label_plural'] ) ) {
		$tab_name = $bupr['review_label_plural'];
	}
	return apply_filters( 'bupr_review_tab_name', esc_html( $tab_name ) );
}

/**
 * Get Review tab slug on memeber profile.
 *
 * @return string Tab name
 */
function bupr_profile_review_tab_plural_slug() {
	global $bupr;

	if ( isset( $bupr['review_label_plural'] ) ) {
		$tab_slug = sanitize_title( $bupr['review_label_plural'] );
	}
	return apply_filters( 'bupr_review_tab_plural_slug', esc_html( $tab_slug ) );
}


/**
 * Get Review tab slug on memeber profile.
 *
 * @return string Tab name
 */
function bupr_profile_review_tab_singular_slug() {
	global $bupr;

	if ( isset( $bupr['review_label'] ) ) {
		$tab_slug = sanitize_title( $bupr['review_label'] );
	}

	return apply_filters( 'bupr_review_tab_singular_slug', esc_html( $tab_slug ) );
}
