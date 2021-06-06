/**
 * File script.js.
 *
 * The main javascript and jQuery file of the theme.
 */


/**
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
( function() {
  var isIe = /(trident|msie)/i.test( navigator.userAgent );

  if ( isIe && document.getElementById && window.addEventListener ) {
    window.addEventListener( 'hashchange', function() {
      var id = location.hash.substring( 1 ),
        element;

      if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
        return;
      }

      element = document.getElementById( id );

      if ( element ) {
        if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
          element.tabIndex = -1;
        }

        element.focus();
      }
    }, false );
  }
} )();


/**
 *
 * Hamburger Animation Effect
 *
 */
(function() {

  "use strict";

  var toggles = document.querySelectorAll(".wd-hamburger");

  for (var i = toggles.length - 1; i >= 0; i--) {
    var toggle = toggles[i];
    toggleHandler(toggle);
  };

  function toggleHandler(toggle) {
    toggle.addEventListener( "click", function(e) {
      e.preventDefault();
      (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
    });
  }

})(jQuery);


/**
 *
 * Toggle Menu Button in Mobile
 *
 */
function wd_menu_toggle() {
    document.getElementById("wd-primary-nav").classList.toggle("show");
}

/**
 *
 * Toggle Search Button
 *
 */
function wd_search_open() {
    document.getElementById("wd-header-search").classList.toggle("show");
}

function wd_search_close() {
    document.getElementById("wd-header-search").classList.remove("show");
}

function wd_mob_search_open() {
    document.getElementById("wd-mob-header-search").classList.toggle("show");
    
}

function wd_mob_search_close() {
    document.getElementById("wd-mob-header-search").classList.remove("show");
}

jQuery(document).ready(function($){

  $(".plan-switch.yearly").click(function(){
      $(".plan-switch.yearly").addClass("active");
      $(".plan-switch.lifetime").removeClass("active");
      $('.lifetime-plan').css( {
            'display': 'none'
          } );
      $('.year-plan').css( {
            'display': 'block'
          } );
  });

  $(".plan-switch.lifetime").click(function(){
      $(".plan-switch.lifetime").addClass("active");
      $(".plan-switch.yearly").removeClass("active");
      $('.year-plan').css( {
            'display': 'none'
          } );
      $('.lifetime-plan').css( {
            'display': 'block'
          } );
  });

});


/**
 *
 * Toggle Product Filter
 *
 */
function filtertoggle() {
    document.getElementById("filter-main").classList.toggle("active");
}

function filterremovetoggle() {
    document.getElementById("filter-main").classList.remove('active');
}

/**
 *
 * Mobile Menu Toggle
 *
 */
jQuery(document).ready(function($){

  $('.primary-nav .menu-item-has-children').children('a')
          .after('<button role="button" class="mob-menu-toggle" id="mob-menu-toggle"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>');
  $('.primary-nav .page-item-has-children').children('a')
          .after('<button role="button" class="mob-menu-toggle" id="mob-menu-toggle"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>');
  $('.primary-nav .mob-menu-toggle').on ( 'click', function() {
    $(this).siblings('ul').slideToggle(100);
  });

});

/**
 *
 * Responsive OEmbed for Course Catalog videos
 *
 */
jQuery(document).ready(function($) {
 
  var $all_oembed_videos = $(".llms-loop-item-content iframe[src*='youtube'], .llms-loop-item-content iframe[src*='vimeo'], .llms-loop-item-content iframe[src*='wistia']");
  
  $all_oembed_videos.each(function() {
  
    $(this).removeAttr('height').removeAttr('width').wrap( "<div class='embed-container'></div>" );
  
  });
 
});

//WooCommerce My Account Menu
jQuery(document).ready(function($) {

    $(window).on('resize', function () {

          var headerHeight = $('#masthead').outerHeight();
          var aboveheaderHeight = $('.whitedot-above-header-bar').outerHeight();
          var mainheaderheight = headerHeight + aboveheaderHeight;

          if (window.matchMedia('(min-width: 768px)').matches) {
            var headerHeightAdmin = "calc( " + mainheaderheight + "px + 32px )"
          } else if (window.matchMedia('(max-width: 767px)').matches) {
            var headerHeightAdmin = "calc( " + mainheaderheight + "px + 46px )"
          }

          if( $('#wpadminbar').length ) {
            $( '.woocommerce-MyAccount-navigation' ).css( {
                    'padding-top': headerHeightAdmin
            } );
          }else{
            $( '.woocommerce-MyAccount-navigation' ).css( {
                    'padding-top': mainheaderheight
            } );
          }
    });

    $(window).trigger('resize');
});


jQuery(document).ready(function($){

  var offset = 300,

    offset_opacity = 1200,

    scroll_top_duration = 700,

    $back_to_top = $('.to-top');

  $(window).scroll(function(){
    ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('top-is-visible') : $back_to_top.removeClass('top-is-visible top-fade-out');
    if( $(this).scrollTop() > offset_opacity ) { 
      $back_to_top.addClass('top-fade-out');
    }
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
      $back_to_top.addClass('at-bottom');
    }else{
      $back_to_top.removeClass('at-bottom');
    }
  });

  $back_to_top.on('click', function(event){
    event.preventDefault();
    $('body,html').animate({
      scrollTop: 0 ,
      }, scroll_top_duration
    );
  });

});



