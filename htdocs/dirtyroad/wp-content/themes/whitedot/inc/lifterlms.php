<?php
/**
 * LifterLMS Compatibility File
 *
 * @link https://lifterlms.com/
 *
 * @package WhiteDot
 */

/**
 * Declare explicit theme support for LifterLMS course and lesson sidebars
 * @return   void
 */
function whitedot_llms_theme_support(){
	add_theme_support( 'lifterlms-sidebars' );
}
add_action( 'after_setup_theme', 'whitedot_llms_theme_support' );



// Integrating Lifterlms Sidebars 
function whitedot_llms_sidebar_function( $id ) {
$my_sidebar_id = 'sidebar-1'; 
return $my_sidebar_id;
}


/**
 * LifterLMS Catalog Compatibility
 */

//Removing Default wrapper
remove_action( 'lifterlms_before_main_content', 'lifterlms_output_content_wrapper', 10 );
remove_action( 'lifterlms_after_main_content', 'lifterlms_output_content_wrapper_end', 10 );


//Adding Theme Wrapper
add_action( 'lifterlms_before_main_content', 'whitedot_content_wrapper_open', 10 );
add_action( 'lifterlms_after_main_content', 'whitedot_content_wrapper_close', 10 );
function whitedot_content_wrapper_open() {
	?>
	<div id="primary" class="content-area wd-lifterlms-wrap">
		<main id="main" class="site-main" role="main">
	<?php
}
function whitedot_content_wrapper_close() {
	?>
		</main>
	</div>
	<?php
}

/**
 * Customize the number of columns displayed on LifterLMS Course and Membership Catalogs
 *
 */
function whitedot_llms_loop_cols( $cols ) {

    $wd_course_clmns = absint( get_theme_mod( 'whitedot_course_catalog_column', 3 ) );
    $wd_membership_clmns = absint( get_theme_mod( 'whitedot_membership_catalog_column', 3 ) );

    if ( is_post_type_archive( 'course' ) ) {
        return $wd_course_clmns;
    } elseif ( is_post_type_archive( 'llms_membership' ) ) {
        return $wd_membership_clmns;
    }
  return $cols;
}
add_filter( 'lifterlms_loop_columns', 'whitedot_llms_loop_cols' );





