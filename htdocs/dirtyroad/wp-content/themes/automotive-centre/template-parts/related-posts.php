<?php
/**
 * Related posts based on categories and tags.
 * 
 */

$automotive_centre_related_posts_taxonomy = get_theme_mod( 'automotive_centre_related_posts_taxonomy', 'category' );

$automotive_centre_post_args = array(
    'posts_per_page'    => absint( get_theme_mod( 'automotive_centre_related_posts_count', '3' ) ),
    'orderby'           => 'rand',
    'post__not_in'      => array( get_the_ID() ),
);

$automotive_centre_tax_terms = wp_get_post_terms( get_the_ID(), 'category' );
$automotive_centre_terms_ids = array();
foreach( $automotive_centre_tax_terms as $tax_term ) {
	$automotive_centre_terms_ids[] = $tax_term->term_id;
}

$automotive_centre_post_args['category__in'] = $automotive_centre_terms_ids; 

if(get_theme_mod('automotive_centre_related_post',true)==1){

$automotive_centre_related_posts = new WP_Query( $automotive_centre_post_args );

if ( $automotive_centre_related_posts->have_posts() ) : ?>
    <div class="related-post">
        <h3><?php echo esc_html(get_theme_mod('automotive_centre_related_post_title','Related Post'));?></h3>
        <div class="row">
            <?php while ( $automotive_centre_related_posts->have_posts() ) : $automotive_centre_related_posts->the_post(); ?>
                <?php get_template_part('template-parts/grid-layout'); ?>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif;
wp_reset_postdata();

}