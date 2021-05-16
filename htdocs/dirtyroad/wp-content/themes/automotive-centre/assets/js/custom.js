function automotive_centre_menu_open_nav() {
	window.automotive_centre_responsiveMenu=true;
	jQuery(".sidenav").addClass('show');
}
function automotive_centre_menu_close_nav() {
	window.automotive_centre_responsiveMenu=false;
 	jQuery(".sidenav").removeClass('show');
}

jQuery(function($){
 	"use strict";
   	jQuery('.main-menu > ul').superfish({
		delay:       500,
		animation:   {opacity:'show',height:'show'},
		speed:       'fast'
   	});
});

jQuery(document).ready(function () {
	window.automotive_centre_currentfocus=null;
  	automotive_centre_checkfocusdElement();
	var automotive_centre_body = document.querySelector('body');
	automotive_centre_body.addEventListener('keyup', automotive_centre_check_tab_press);
	var automotive_centre_gotoHome = false;
	var automotive_centre_gotoClose = false;
	window.automotive_centre_responsiveMenu=false;
 	function automotive_centre_checkfocusdElement(){
	 	if(window.automotive_centre_currentfocus=document.activeElement.className){
		 	window.automotive_centre_currentfocus=document.activeElement.className;
	 	}
 	}
 	function automotive_centre_check_tab_press(e) {
		"use strict";
		e = e || event;
		var activeElement;

		if(window.innerWidth < 999){
		if (e.keyCode == 9) {
			if(window.automotive_centre_responsiveMenu){
			if (!e.shiftKey) {
				if(automotive_centre_gotoHome) {
					jQuery( ".main-menu ul:first li:first a:first-child" ).focus();
				}
			}
			if (jQuery("a.closebtn.mobile-menu").is(":focus")) {
				automotive_centre_gotoHome = true;
			} else {
				automotive_centre_gotoHome = false;
			}

		}else{

			if(window.automotive_centre_currentfocus=="responsivetoggle"){
				jQuery( "" ).focus();
			}}}
		}
		if (e.shiftKey && e.keyCode == 9) {
		if(window.innerWidth < 999){
			if(window.automotive_centre_currentfocus=="header-search"){
				jQuery(".responsivetoggle").focus();
			}else{
				if(window.automotive_centre_responsiveMenu){
				if(automotive_centre_gotoClose){
					jQuery("a.closebtn.mobile-menu").focus();
				}
				if (jQuery( ".main-menu ul:first li:first a:first-child" ).is(":focus")) {
					automotive_centre_gotoClose = true;
				} else {
					automotive_centre_gotoClose = false;
				}
			
			}else{

			if(window.automotive_centre_responsiveMenu){
			}}}}
		}
	 	automotive_centre_checkfocusdElement();
	}
});

(function( $ ) {
	jQuery(window).load(function() {
	    jQuery("#status").fadeOut();
	    jQuery("#preloader").delay(1000).fadeOut("slow");
	})
	$(window).scroll(function(){
		var sticky = $('.header-sticky'),
			scroll = $(window).scrollTop();

		if (scroll >= 100) sticky.addClass('header-fixed');
		else sticky.removeClass('header-fixed');
	});
	$(document).ready(function () {
		$(window).scroll(function () {
		    if ($(this).scrollTop() > 100) {
		        $('.scrollup i').fadeIn();
		    } else {
		        $('.scrollup i').fadeOut();
		    }
		});
		$('.scrollup i').click(function () {
		    $("html, body").animate({
		        scrollTop: 0
		    }, 600);
		    return false;
		});
	});
})( jQuery );

jQuery(document).ready(function () {
	  function automotive_centre_search_loop_focus(element) {
	  var automotive_centre_focus = element.find('select, input, textarea, button, a[href]');
	  var automotive_centre_firstFocus = automotive_centre_focus[0];  
	  var automotive_centre_lastFocus = automotive_centre_focus[automotive_centre_focus.length - 1];
	  var KEYCODE_TAB = 9;

	  element.on('keydown', function automotive_centre_search_loop_focus(e) {
	    var isTabPressed = (e.key === 'Tab' || e.keyCode === KEYCODE_TAB);

	    if (!isTabPressed) { 
	      return; 
	    }

	    if ( e.shiftKey ) /* shift + tab */ {
	      if (document.activeElement === automotive_centre_firstFocus) {
	        automotive_centre_lastFocus.focus();
	          e.preventDefault();
	        }
	      } else /* tab */ {
	      if (document.activeElement === automotive_centre_lastFocus) {
	        automotive_centre_firstFocus.focus();
	          e.preventDefault();
	        }
	      }
	  });
	}
	jQuery('.search-box span a').click(function(){
        jQuery(".serach_outer").slideDown(1000);
    	automotive_centre_search_loop_focus(jQuery('.serach_outer'));
    });

    jQuery('.closepop a').click(function(){
        jQuery(".serach_outer").slideUp(1000);
    });
});