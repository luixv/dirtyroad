/**
 * Square Custom JS
 *
 * @package square
 *
 * Distributed under the MIT license - http://opensource.org/licenses/MIT
 */

jQuery(function ($) {

    $('#sq-bx-slider').owlCarousel({
        autoplay: true,
        items: 1,
        loop: true,
        nav: true,
        dots: false,
        autoplayTimeout: 7000,
        animateOut: 'fadeOut',
        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>']
    });

    $(".sq_client_logo_slider").owlCarousel({
        autoplay: true,
        items: 5,
        loop: true,
        nav: false,
        dots: false,
        autoplayTimeout: 7000,
        responsive: {
            0: {
                items: 2,
            },
            768: {
                items: 3,
            },
            979: {
                items: 4,
            },
            1200: {
                items: 5,
            }
        }
    });

    $(".sq-tab-pane:first").show();
    $(".sq-tab li:first").addClass('sq-active');

    $(".sq-tab li a").click(function () {
        var tab = $(this).attr('href');
        $(".sq-tab li").removeClass('sq-active');
        $(this).parent('li').addClass('sq-active');
        $(".sq-tab-pane").hide();
        $(tab).show();
        return false;
    });

    $(window).scroll(function () {
        var scrollTop = $(window).scrollTop();
        if (scrollTop > 0) {
            $('#sq-masthead').addClass('sq-scrolled');
        } else {
            $('#sq-masthead').removeClass('sq-scrolled');
        }
    });

    $('.sq-menu > ul').superfish({
        delay: 500,
        animation: {opacity: 'show', height: 'show'},
        speed: 'fast'
    });

    $('.sq-toggle-nav').click(function () {
        $('#sq-site-navigation').slideToggle();
        squareKeyboardLoop($('.sq-main-navigation'));
        return false;
    });

    var squareKeyboardLoop = function (elem) {

        var tabbable = elem.find('select, input, textarea, button, a').filter(':visible');

        var firstTabbable = tabbable.first();
        var lastTabbable = tabbable.last();
        /*set focus on first input*/
        firstTabbable.focus();

        /*redirect last tab to first input*/
        lastTabbable.on('keydown', function (e) {
            if ((e.which === 9 && !e.shiftKey)) {
                e.preventDefault();
                firstTabbable.focus();
            }
        });

        /*redirect first shift+tab to last input*/
        firstTabbable.on('keydown', function (e) {
            if ((e.which === 9 && e.shiftKey)) {
                e.preventDefault();
                lastTabbable.focus();
            }
        });

        /* allow escape key to close insiders div */
        elem.on('keyup', function (e) {
            if (e.keyCode === 27) {
                elem.hide();
            }
            ;
        });
    };

});

if (jQuery('#sq-elasticstack').length > 0) {
    new ElastiStack(document.getElementById('sq-elasticstack'), {
        // distDragBack: if the user stops dragging the image in a area that does not exceed [distDragBack]px 
        // for either x or y then the image goes back to the stack 
        distDragBack: 200,
        // distDragMax: if the user drags the image in a area that exceeds [distDragMax]px 
        // for either x or y then the image moves away from the stack 
        distDragMax: 450,
        // callback
        onUpdateStack: function (current) {
            return false;
        }
    });
}