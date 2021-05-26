/*
 * center menu 
 */
(function ($) {
    "use strict";

    function menu_align() {
        var headerWrap = $('.site-header');
        var navWrap = $('.navbar');
        var logoWrap = $('.site-logo');
        var containerWrap = $('.container');
        var classToAdd = 'header-align-center';
        if (headerWrap.hasClass(classToAdd)) {
            headerWrap.removeClass(classToAdd);
        }
        var logoWidth = logoWrap.outerWidth();
        var menuWidth = navWrap.outerWidth();
        var containerWidth = containerWrap.width();
        if (menuWidth + logoWidth > containerWidth) {
            headerWrap.addClass(classToAdd);
        } else {
            if (headerWrap.hasClass(classToAdd)) {
                headerWrap.removeClass(classToAdd);
            }
        }

    }

    function ifraimeResize() {
        $('.entry-media iframe:visible , .entry-content iframe:visible').each(function () {
            var parentWidth = $(this).parent().width();
            var thisWidth = $(this).attr('width');
            var thisHeight = $(this).attr('height');
            $(this).css('width', parentWidth);
            var newHeight = thisHeight * parentWidth / thisWidth;
            $(this).css('height', newHeight);
        });
    }

    function flexsliderInit() {
        if ($('.gallery.flexslider').length) {
            $('.gallery.flexslider').each(function () {
                $(this).flexslider({
                    animation: "slide",
                    controlNav: false,
                    prevText: "",
                    nextText: "",
                    slideshow: false,
                    animationLoop: false,
                    minItems: 1,
                    maxItems: 1,
                    itemMargin: 0,
                    smoothHeight: false,
                    start: function () {
                        if ($('.masonry-blog').length) {
                            var container = $('.masonry-blog');
                            container.masonry('layout');
                        }
                    }
                });
            });
        }

    }

    function animateAppear(el) {
        el.addClass('anVisible').addClass(el.attr("data-animation"));
        setTimeout(function () {
            el.removeClass('animated').removeClass(el.attr("data-animation")).removeClass('anHidden').removeClass('anVisible');
        }, 2000);
    }

    
    $(document).ready(function () {
        
        if ($('#wpadminbar').length) {
            $('.site-header').addClass('wpadminbar-show');
        }

        /*
         * Superfish menu
         */
        var superfishOption = {
            onBeforeShow: function () {
                $(this).removeClass('toleft');
                if ($(this).parent().offset()) {
                    if (($(this).parent().offset().left + $(this).parent().width() - $(window).width() + 170) > 0) {
                        $(this).addClass('toleft');
                    }
                }
            }
        };
        var example = $('#main-menu');
        example.superfish(superfishOption);
        if ($(document).width() < 992) {
            example.superfish('destroy');
        }
        $(window).resize(function () {
            if ($(document).width() < 992) {
                example.superfish('destroy');
            } else {
                example.superfish(superfishOption);
            }
        });
        /*
         * Back to top
         */
        $('body').on('click', '.toTop', function (e) {
            e.preventDefault();
            var mode = (window.opera) ? ((document.compatMode == "CSS1Compat") ? $('html') : $('body')) : $('html,body');
            mode.animate({
                scrollTop: 0
            }, 800, function () {
                $('.site-header').removeClass('fixed');
            });
            return false;
        });
        /*
         * style select 
         */
        $("select").each(function () {
            if ($(this).parent('.select-wrapper').length === 0) {
                $(this).wrap("<div class='select-wrapper'></div>");
            }
        });


        $('#main-menu .current').removeClass('current');
        $('#main-menu a[href$="' + window.location.hash + '"]').parent('li').addClass('current');

        $('body').on('click', 'a[href*="#"]:not([href="#"])', function () {
            var addTo = 0;
            if ($('.site-header').attr('data-sticky-menu') === 'on' && $(document).width() > 991) {
                if ($('.site-header').hasClass('fixed')) {
                    addTo = $('.site-header').outerHeight();
                } else {
                    addTo = $('.site-header').outerHeight() + $('.site-header').outerHeight();
                }
            }
            var headerHeight = 0;
            var hash = this.hash;
            var idName = hash.substring(1);
            var alink = this;
            var wpadminbar = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
            if($(document).width()<=600){
                wpadminbar=0;
            }
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top - headerHeight - addTo - wpadminbar
                    }, 1200, function () {
                        $('#main-menu .current').removeClass('current');
                        $('#main-menu a[href$="' + idName + '"]').parent('li').addClass('current');
                    });
                    $(alink).blur();
                    return false;
                }
            }
        });
        ifraimeResize();

        var container = $('.masonry-blog');
        var top = 0;
        if ($('.site-header').length) {
            top = $('.site-header').offset().top;
        }

        $(window).scroll(function () {
            var theme_scrollTop = $(window).scrollTop();

            /*
             * Stycky menu
             */
            if ($('.site-header').attr('data-sticky-menu') === 'on' && $(document).width() > 991) {
                var y = $(this).scrollTop();
                if (y > top) {
                    $('.site-header').addClass('fixed');
                } else {
                    $('.site-header').removeClass('fixed');
                }
            }
            var addTo = 0;
            if ($('.site-header').attr('data-sticky-menu') === 'on' && $(document).width() > 991) {
                if ($('.site-header').hasClass('fixed')) {
                    addTo = $('.site-header').outerHeight();
                } else {
                    addTo = $('.site-header').outerHeight() + $('.site-header').outerHeight();
                }
            }
            var headerHeight = $('.site-header').outerHeight()
            var isInOneSection = 'no';
            $("section").each(function () {
                var thisID = '#' + jQuery(this).attr('id');
                var theme_offset = jQuery(this).offset().top;
                var thisHeight = jQuery(this).outerHeight();
                var wpadminbar = $('#wpadminbar').length > 0 ? $('#wpadminbar').height() : 0;
                var thisBegin = theme_offset - headerHeight - wpadminbar;
                var thisEnd = theme_offset + thisHeight - headerHeight - addTo;
                if (theme_scrollTop >= thisBegin && theme_scrollTop <= thisEnd) {
                    isInOneSection = 'yes';
                    $('#main-menu .current').removeClass('current');

                    $('#main-menu a[href$="' + thisID + '"]').parent('li').addClass('current');
                    return false;
                }
                if (isInOneSection == 'no') {
                    $('#main-menu .current').removeClass('current');
                }
            });
        });
        $(window).resize(function () {
            menu_align();
            ifraimeResize();
        });
    });

    /**
     * In newer jquery version ready handlers are called async
     * https://github.com/jquery/jquery/issues/3194#issuecomment-228556922
     * so shoul call window.load outside document.ready
     */
    $(window).load(function () {
        menu_align();
        ifraimeResize();
        flexsliderInit();
        if ($.isFunction($.fn.masonry) && $.isFunction($.fn.infinitescroll)) {
            container.masonry({
                itemSelector: '.post',
                columnWidth: function (containerWidth) {
                    return containerWidth / 3;
                },
                animationOptions: {
                    duration: 400
                },
                isRTL: $('body').is('.rtl')
            });
            container.infinitescroll({
                navSelector: ".navigation",
                nextSelector: ".navigation a:last-child",
                itemSelector: ".masonry-blog .post",
                loading: {
                    finishedMsg: '',
                    img: (template_directory_uri.url + '/images/loader.svg'),
                    msgText: ''
                }
            }, function (newElements) {
                var newElems = $(newElements).addClass('masonry-hidden');
                $(newElems).imagesLoaded(function () {
                    container.masonry('appended', $(newElems), true);
                    ifraimeResize();
                    flexsliderInit();
                    setTimeout(function () {
                        $(newElems).removeClass('masonry-hidden');
                    }, 500);

                });
            });
        }
        $('.animated').appear();

        $(document.body).on('appear', '.animated', function (e, $affected) {
            if (!$(this).hasClass('animation-active')) {
                animateAppear($(this));
            }
        });
        $('.animated:appeared').each(function () {
            $(this).addClass('animation-active');
            animateAppear($(this));
        });
        

    });

    /**
     * Contains handlers for navigation.
     */
    var masthead, menuToggle, siteNavigation, siteHeaderMenu;

    function initMainNavigation(container) {
        // Add dropdown toggle that displays child menu items.
        var dropdownToggle = $('<button />', {
            'class': 'dropdown-toggle',
            'aria-expanded': false
        }).append($('<i class="fa fa-angle-down" aria-hidden="true"></i>')).append($('<span />', {
            'class': 'screen-reader-text',
            text: screenReaderText.expand
        }));

        container.find('.menu-item-has-children > a').after(dropdownToggle);

        // Toggle buttons and submenu items with active children menu items.
        container.find('.current-menu-ancestor > button').addClass('toggled-on');
        container.find('.current-menu-ancestor > .sub-menu').addClass('toggled-on');

        // Add menu items with submenus to aria-haspopup="true".
        container.find('.menu-item-has-children').attr('aria-haspopup', 'true');

        container.find('.dropdown-toggle').click(function (e) {
            var _this = $(this),
                screenReaderSpan = _this.find('.screen-reader-text');

            e.preventDefault();
            _this.toggleClass('toggled-on');
            _this.next('.children, .sub-menu').toggleClass('toggled-on');

            // jscs:disable
            _this.attr('aria-expanded', _this.attr('aria-expanded') === 'false' ? 'true' : 'false');
            // jscs:enable
            screenReaderSpan.text(screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
        });
    }

    initMainNavigation($('.main-navigation'));

    masthead = $('#header');
    menuToggle = masthead.find('.menu-toggle');
    siteHeaderMenu = $('.main-navigation');
    // Enable menuToggle.
    (function () {
        // Return early if menuToggle is missing.
        if (!menuToggle.length) {
            return;
        }

        // Add an initial values for the attribute.
        menuToggle.add(siteNavigation).attr('aria-expanded', 'false');

        menuToggle.on('click', function () {
            $(this).add(siteHeaderMenu).toggleClass('toggled-on');

            // jscs:disable
            $(this).add(siteNavigation).attr('aria-expanded', $(this).add(siteNavigation).attr('aria-expanded') === 'false' ? 'true' : 'false');
            // jscs:enable
        });
    })();
})
(jQuery);