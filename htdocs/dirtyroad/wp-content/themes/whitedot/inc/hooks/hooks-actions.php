<?php

//Header Actions
add_action('whitedot_header_content','whitedot_header_content');
add_action('whitedot_header_content_before','whitedot_header_hamburger', 10);
add_action('whitedot_header_branding','whitedot_header_logo', 10);
add_action('whitedot_header_branding','whitedot_header_identity', 20);
add_action('whitedot_header_nav','whitedot_header_navigation', 10);

add_action('whitedot_header_end','whitedot_header_column_full_open', 10);




//Footer Actions
add_action('whitedot_footer_branding_content','whitedot_footer_site_title', 10);
add_action('whitedot_footer_branding_content','whitedot_footer_site_description', 20);
add_action('whitedot_footer_content','whitedot_footer_widgets', 20);
add_action('whitedot_footer_content','whitedot_footer_info', 20);

add_action('whitedot_footer_start','whitedot_header_column_full_close', 10);



//Whitedot Blog Home
add_action('whitedot_blog_home_content_before','whitedot_blog_list_thumbnail', 10);
add_action('whitedot_blog_home_content','whitedot_blog_list_meta', 10);
add_action('whitedot_blog_home_content','whitedot_blog_list_header', 20);
add_action('whitedot_blog_home_content','whitedot_blog_list_excerpt', 30);
add_action('whitedot_blog_excerpt_content','whitedot_blog_excerpt_main', 10);
add_action('whitedot_blog_excerpt_content','whitedot_blog_excerpt_readmore', 20);
add_action( 'whitedot_pagination', 'whitedot_blog_home_number_pagination', 10 );
add_action( 'whitedot_archive_header', 'whitedot_archive_head', 10 );

//Whitedot Single Posts actions
add_action('whitedot_single_post_before','whitedot_thumbnail', 10);
add_action('whitedot_single_post','whitedot_post_header', 10);
add_action('whitedot_single_post','whitedot_post_meta', 20);
add_action('whitedot_single_post','whitedot_post_content', 30);
add_action('whitedot_single_post_content_after','whitedot_post_pagination', 10);
add_action('whitedot_single_post','whitedot_single_post_tags', 40);
add_action('whitedot_main_single_content_after','whitedot_post_comment', 20);

//Whitedot Page actions
add_action('whitedot_page_content_before','whitedot_thumbnail', 10);
add_action('whitedot_page_content_before','whitedot_post_header', 20);
add_action('whitedot_page_custom_after','whitedot_post_pagination', 10);
add_action('whitedot_page_content_after','whitedot_post_comment', 10);

//SideBar Actions
add_action('whitedot_the_sidebar','whitedot_main_sidebar', 10);

//Whitedot Search Page actions
add_action('whitedot_search_form','whitedot_search_page_form', 10);


