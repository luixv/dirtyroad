<?php

/**
 * Custom hooks and function for woocommerce compatibility
 *
 * @package Square
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close');
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open');

add_action('woocommerce_before_main_content', 'square_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'square_theme_wrapper_end', 10);
add_action('sq_woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
add_action('sq_woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
add_action('woocommerce_before_subcategory_title', 'square_before_subcategory_html', 10);
add_action('woocommerce_after_subcategory_title', 'square_after_subcategory_html', 10);

function square_theme_wrapper_start() {
    echo '<header class="sq-main-header">';
    echo '<div class="sq-container">';
    echo '<h1 class="sq-main-title">';
    woocommerce_page_title();
    echo '</h1>';
    do_action('sq_woocommerce_archive_description');
    echo '</div>';
    echo '</header>';

    echo '<div class="sq-container">';
    echo '<div id="primary">';
}

function square_theme_wrapper_end() {
    echo '</div>';
    get_sidebar('shop');
    echo '</div>';
}

function square_before_subcategory_html() {
    echo '<div class="sq-woo-title-price sq-clearfix">';
}

function square_after_subcategory_html() {
    echo '</div>';
}

//Change number of related products on product page
add_filter('woocommerce_output_related_products_args', 'sq_related_products_args');

function sq_related_products_args($args) {
    $args['posts_per_page'] = 3; // 3 related products
    $args['columns'] = 3; // arranged in 3 columns
    return $args;
}

add_filter('woocommerce_product_description_heading', '__return_false');

add_filter('woocommerce_product_additional_information_heading', '__return_false');

add_filter('woocommerce_pagination_args', 'sq_change_prev_text');

function sq_change_prev_text($args) {
    $args['prev_text'] = '&lang;';
    $args['next_text'] = '&rang;';
    return $args;
}

add_action('woocommerce_before_shop_loop_item', 'square_template_loop_product_link_open');

function square_template_loop_product_link_open() {
    echo '<div class="sq-woo-thumb-wrap">';
    echo '<a href="' . esc_url(get_the_permalink()) . '" class="woocommerce-LoopProduct-link sq-thumb-link">';
}

add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 20);
add_action('woocommerce_before_shop_loop_item_title', 'square_template_loop_product_link_close', 20);

function square_template_loop_product_link_close() {
    echo '</a>';
    echo '</div>';
    echo '<div class="sq-woo-title-price sq-clearfix">';
}
