<?php

/**
 * @package Square
 */
function square_dymanic_styles() {
    $color = get_theme_mod('square_template_color', '#5bc2ce');
    $color_rgba = square_hex2rgba($color, 0.9);
    $darker_color = squareColourBrightness($color, -0.9);
    $custom_css = "
button,
input[type='button'],
input[type='reset'],
input[type='submit'],
.sq-main-navigation ul ul li:hover > a,
#sq-home-slider-section .owl-carousel .owl-nav button.owl-prev, 
#sq-home-slider-section .owl-carousel .owl-nav button.owl-next,
.sq-featured-post h4:after,
.sq-section-title:after,
.sq-tab li.sq-active:after,
#sq-colophon h5.widget-title:after,
.widget-area .widget-title:before,
.widget-area .widget-title:after,
.square-share-buttons a:hover,
h3#reply-title:after, 
h3.comments-title:after,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range, 
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
.woocommerce div.product .woocommerce-tabs ul.tabs li.active:after,
.woocommerce #respond input#submit, 
.woocommerce table.shop_table thead,
.woocommerce ul.products li.product .button.add_to_cart_button, 
.woocommerce a.added_to_cart,
.woocommerce a.button, 
.woocommerce button.button, 
.woocommerce input.button,
.woocommerce ul.products li.product:hover .button,
.woocommerce #respond input#submit.alt, 
.woocommerce a.button.alt, 
.woocommerce button.button.alt, 
.woocommerce input.button.alt,
.woocommerce span.onsale,
.woocommerce #respond input#submit.disabled, 
.woocommerce #respond input#submit:disabled, 
.woocommerce #respond input#submit:disabled[disabled], 
.woocommerce a.button.disabled, .woocommerce a.button:disabled, 
.woocommerce a.button:disabled[disabled], 
.woocommerce button.button.disabled, 
.woocommerce button.button:disabled, 
.woocommerce button.button:disabled[disabled], 
.woocommerce input.button.disabled, 
.woocommerce input.button:disabled, 
.woocommerce input.button:disabled[disabled],
.woocommerce #respond input#submit.alt.disabled, 
.woocommerce #respond input#submit.alt.disabled:hover, 
.woocommerce #respond input#submit.alt:disabled, 
.woocommerce #respond input#submit.alt:disabled:hover, 
.woocommerce #respond input#submit.alt:disabled[disabled], 
.woocommerce #respond input#submit.alt:disabled[disabled]:hover, 
.woocommerce a.button.alt.disabled, 
.woocommerce a.button.alt.disabled:hover, 
.woocommerce a.button.alt:disabled, 
.woocommerce a.button.alt:disabled:hover, 
.woocommerce a.button.alt:disabled[disabled], 
.woocommerce a.button.alt:disabled[disabled]:hover, 
.woocommerce button.button.alt.disabled, 
.woocommerce button.button.alt.disabled:hover, 
.woocommerce button.button.alt:disabled, 
.woocommerce button.button.alt:disabled:hover, 
.woocommerce button.button.alt:disabled[disabled], 
.woocommerce button.button.alt:disabled[disabled]:hover, 
.woocommerce input.button.alt.disabled, 
.woocommerce input.button.alt.disabled:hover, 
.woocommerce input.button.alt:disabled, 
.woocommerce input.button.alt:disabled:hover, 
.woocommerce input.button.alt:disabled[disabled], 
.woocommerce input.button.alt:disabled[disabled]:hover,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce-MyAccount-navigation-link a{
	background:{$color};
}

a,
.sq-featured-post .sq-featured-readmore:hover,
.sq-tab li.sq-active .fa,
.widget-area a:hover,
.woocommerce nav.woocommerce-pagination ul li a:focus, 
.woocommerce nav.woocommerce-pagination ul li a:hover, 
.woocommerce nav.woocommerce-pagination ul li span.current,
.pagination a:hover, 
.pagination span,
.woocommerce ul.products li.product .price,
.woocommerce div.product p.price, 
.woocommerce div.product span.price,
.woocommerce .product_meta a:hover,
.woocommerce-error:before, 
.woocommerce-info:before, 
.woocommerce-message:before,
.entry-meta a:hover,
.entry-footer a:hover{
	color:{$color};
}

.comment-list a:hover{
	color:{$color} !important;
}

.sq-slide-caption,
.square-share-buttons a:hover,
.woocommerce ul.products li.product:hover, 
.woocommerce-page ul.products li.product:hover,
.woocommerce #respond input#submit, 
.sq-woo-title-price,
.woocommerce nav.woocommerce-pagination ul li a:focus, 
.woocommerce nav.woocommerce-pagination ul li a:hover, 
.woocommerce nav.woocommerce-pagination ul li span.current,
.pagination a:hover,
.pagination span,
.woocommerce a.button, 
.woocommerce button.button, 
.woocommerce input.button,
.woocommerce ul.products li.product:hover .button,
.woocommerce #respond input#submit.alt, 
.woocommerce a.button.alt, 
.woocommerce button.button.alt, 
.woocommerce input.button.alt,
.woocommerce #respond input#submit.alt.disabled, 
.woocommerce #respond input#submit.alt.disabled:hover, 
.woocommerce #respond input#submit.alt:disabled, 
.woocommerce #respond input#submit.alt:disabled:hover, 
.woocommerce #respond input#submit.alt:disabled[disabled], 
.woocommerce #respond input#submit.alt:disabled[disabled]:hover, 
.woocommerce a.button.alt.disabled, 
.woocommerce a.button.alt.disabled:hover, 
.woocommerce a.button.alt:disabled, 
.woocommerce a.button.alt:disabled:hover, 
.woocommerce a.button.alt:disabled[disabled], 
.woocommerce a.button.alt:disabled[disabled]:hover, 
.woocommerce button.button.alt.disabled, 
.woocommerce button.button.alt.disabled:hover, 
.woocommerce button.button.alt:disabled, 
.woocommerce button.button.alt:disabled:hover, 
.woocommerce button.button.alt:disabled[disabled], 
.woocommerce button.button.alt:disabled[disabled]:hover, 
.woocommerce input.button.alt.disabled, 
.woocommerce input.button.alt.disabled:hover, 
.woocommerce input.button.alt:disabled, 
.woocommerce input.button.alt:disabled:hover, 
.woocommerce input.button.alt:disabled[disabled], 
.woocommerce input.button.alt:disabled[disabled]:hover
{
	border-color: {$color};
}


.woocommerce-error, 
.woocommerce-info, 
.woocommerce-message{
	border-top-color: {$color};
}

#sq-home-slider-section .owl-carousel .owl-nav button.owl-prev:hover, 
#sq-home-slider-section .owl-carousel .owl-nav button.owl-next:hover,
.woocommerce #respond input#submit:hover, 
.woocommerce a.button:hover, 
.woocommerce button.button:hover, 
.woocommerce input.button:hover,
.woocommerce #respond input#submit.alt:hover, 
.woocommerce a.button.alt:hover, 
.woocommerce button.button.alt:hover, 
.woocommerce input.button.alt:hover,
.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content
{
	background: {$darker_color} ;
}



.woocommerce ul.products li.product .onsale:after{
	border-color: transparent transparent {$darker_color} {$darker_color};
}

.woocommerce span.onsale:after{
	border-color: transparent {$darker_color} {$darker_color} transparent
}
}
";

    return square_css_strip_whitespace($custom_css);
}
