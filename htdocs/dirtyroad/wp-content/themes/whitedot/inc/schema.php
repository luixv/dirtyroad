<?php

//============== Schema Org Markup====================

function whitedot_body_tag_schema()
{
    $schema = 'http://schema.org/';

    // Is single post
    if(is_single())
    {
        $type = "Blog";
    }
    // Contact form page ID
    else if( is_page(1) )
    {
        $type = 'ContactPage';
    }
    // Is author page
    elseif( is_author() )
    {
        $type = 'ProfilePage';
    }
    // Is search results page
    elseif( is_search() )
    {
        $type = 'SearchResultsPage';
    }
    // Is of movie post type
    elseif(is_singular('movies'))
    {
        $type = 'Movie';
    }
    // Is of book post type
    elseif(is_singular('books'))
    {
        $type = 'Book';
    }
    else
    {
        $type = 'WebPage';
    }

    echo 'itemscope="itemscope" itemtype="' . esc_attr( $schema ) . esc_attr( $type ). '"';
}

	
add_filter( 'nav_menu_link_attributes', 'whitedot_add_attribute', 10, 3 );
function whitedot_add_attribute( $atts, $item, $args )
{
  $atts['itemprop'] = 'url';
  return $atts;
}

?>