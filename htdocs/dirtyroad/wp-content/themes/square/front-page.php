<?php

/**
 * Front Page
 *
 * @package Square
 */
get_header();

$square_enable_frontpage = get_theme_mod('square_enable_frontpage', square_enable_frontpage_default());

if ($square_enable_frontpage) {

    get_template_part('template-parts/frontpage', 'sections');
    
} else {
    if ('posts' == get_option('show_on_front')) {
        include( get_home_template() );
    } else {
        include( get_page_template() );
    }
}

get_footer();
