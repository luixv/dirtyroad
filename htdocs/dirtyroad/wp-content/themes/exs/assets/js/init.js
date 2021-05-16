'use strict';
//IIFE
;(function(d,w,gid) {
	//remove no-js class - very late for preloader
	//d.documentElement.classList.remove('no-js');

	function activateEl(btn, el, cssClass, body, bodyClass) {
		el.classList.add(cssClass);
		body.classList.add(bodyClass);
		btn.setAttribute('aria-expanded', 'true');
		if (btn.id === 'search_toggle') {
			//TODO test on mobile devices
			gid('search_dropdown').children[0].children[0].focus();
		}
		if (btn.id==='message_top_toggle' || btn.id==='message_bottom_toggle') {
			setCookie(btn.getAttribute('data-id'));
			btn.parentNode.remove();
		}
	}

	function deactivateEl(btn, el, cssClass, body, bodyClass) {
		el.classList.remove(cssClass);
		body.classList.remove(bodyClass);
		btn.setAttribute('aria-expanded', 'false');
	}

	function hasClass(el, cssClass) {
		return -1 !== el.className.indexOf(cssClass);
	}

	//toggle CSS class function declaration
	function toggleElListener(elId, btnId, cssClass, body, bodyClass) {
		//if clicked not on button and element is active - closing
		body.addEventListener('click', function(e) {
			var btn = gid(btnId);
			var el = gid(elId);
			if (!btn || !el) {
				return;
			}
			if(e.target.id===btnId||e.target.closest('#' + btnId)){
				if (hasClass(el, cssClass)) {
					deactivateEl(btn, el, cssClass, body, bodyClass);
				} else {
					activateEl(btn, el, cssClass, body, bodyClass);
				}
			}

			if (hasClass(el, cssClass) && e.target.id !== btnId && e.target.closest('#' + btnId) !== btn && !e.target.closest('#' + elId)) {
				deactivateEl(btn, el, cssClass, body, bodyClass);
			}
		});
		//close modals on scroll
		if (btnId !== 'message_top_toggle' && btnId !== 'message_bottom_toggle') {
			w.addEventListener('scroll', function(e) {
				var btn = gid(btnId);
				var el = gid(elId);
				if (!btn || !el) {
					return;
				}
				if (hasClass(el, cssClass)) {
					deactivateEl(btn, el, cssClass, body, bodyClass);
				}
			});
		}
	}

	function hashLinksPrevent(links) {
		for (var i = 0; i < links.length; ++i) {
			links[i].addEventListener('click', function(e) {
				e.preventDefault();
				// https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
				// e.stopPropagation();
			});
		}
	}

	function wrap(el, wrapperClass) {
		for (var i = 0; i < el.length; ++i) {
			var wrapper = d.createElement('div');
			wrapper.setAttribute('class', wrapperClass);

			el[i].parentNode.insertBefore(wrapper, el[i]);
			wrapper.appendChild(el[i]);
		}
	}

	function affix(el) {
		var affix = el.offsetTop;
		var id = el.id;
		var abs = d.getElementById('header-absolute-wrap');
		var orig = el.getAttribute('data-bg');
		var origList = [];
		var need=(id==='header'&&abs&&orig&&el.classList.contains('transparent'));
		if(need){
			origList=orig.split(' ');
		}
		function toggleHeader(header,classes,add){
			if(add){
				if(!header.classList.contains('affix')){
					header.classList.remove('i');
					classes.forEach(function(cl){
						header.classList.add(cl);
					});
				}
			}else{
				if(header.classList.contains('affix')) {
					classes.forEach(function (cl) {
						header.classList.remove(cl);
					});
					header.classList.add('i');
				}
			}
		}
		w.onscroll = function(e) {
			if (w.pageYOffset >= affix) {
				if(!el.classList.contains('affix')){
					if(need){
						toggleHeader(el,origList,true);
					}
					el.classList.add('affix');
				}
			} else {
				if(el.classList.contains('affix')) {
					if(need){
						toggleHeader(el,origList,false);
					}
					el.classList.remove('affix');
				}
			}
			if (w.pageYOffset===0) {
				if(el.classList.contains('affix')) {
					if(need){
						toggleHeader(el,origList,false);
					}
					el.classList.remove('affix');
				}
			}
			if (this.oldScroll > this.scrollY) {
				el.classList.add('scrolling-up');
				el.classList.remove('scrolling-down');
			} else {
				el.classList.remove('scrolling-up');
				el.classList.add('scrolling-down');
			}
			this.oldScroll = this.scrollY;
		}
	}

	function setCookie(name) {
		var date = new Date();
		date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
		var expires = "expires=" + date.toUTCString();
		d.cookie = name + "=" + '1' + ";" + expires + ";path=/";
	}

	function dummyClickEvent(){
		d.body.dispatchEvent(new Event('click'));
	}

	function setMinHeight(elModel,el){
		var height = elModel.offsetHeight;
		el.style.minHeight = height+'px';
	}

	//on document ready calling function
	d.addEventListener('DOMContentLoaded', function(event) {
		var body = d.body;
		//set togglers for menus
		toggleElListener('nav_top', 'nav_toggle', 'active', body, 'top-menu-active');
		toggleElListener('nav_side', 'nav_side_toggle', 'active', body, 'side-menu-active');
		toggleElListener('search_dropdown', 'search_toggle', 'active', body, 'search-dropdown-active');
		toggleElListener('topline_dropdown', 'topline_dropdown_toggle', 'active', body, 'topline-dropdown-active');
		toggleElListener('dropdown-cart', 'dropdown-cart-toggle', 'active', body, 'cart-dropdown-active');
		toggleElListener('message_top', 'message_top_toggle', 'active', body, 'messagee-top-active');
		toggleElListener('message_bottom', 'message_bottom_toggle', 'active', body, 'messagee-bottom-active');

		//search modal TAB navigation
		var searchCloseBtn=gid('search_modal_close');
		var searchToggle=gid('search_toggle');
		var searchInput = d.querySelector('#search_dropdown .search-field');
		if(searchCloseBtn){
			searchCloseBtn.onclick=function(e){
				if(searchToggle){
					dummyClickEvent();
					searchToggle.focus();
					e.preventDefault();
					e.stopPropagation();
				}
			}
			//cycle tab navigation
			searchCloseBtn.onblur=function(e) {
				if(searchInput){
					//this not work if no elements in the DOM after search close button
					searchInput.focus();
				}
			};
		}

		//TAB navigation
		var logo = gid('logo');
		var menuToggler = gid('nav_toggle');
		var navClose = gid('nav_close');
		var firstLink = d.querySelector('.top-menu li:first-child>a');
		var skipLink = gid('skip_link');

		if(navClose && menuToggler){
			menuToggler.addEventListener('click', function(e) {
				navClose.focus();
			});
		}

		d.addEventListener('keydown',function(e) {
			//close all on ESC key click
			if(e.key==='Escape'){
				dummyClickEvent();
			}
			if(e.key==='Tab'){
				//find active element
				//d.activeElement;
			}
			//shift+tab
			if(e.key==='Tab'&&e.shiftKey){
				//close menu on shift+tab on nav close
				if(e.target===navClose){
					dummyClickEvent();
					if(menuToggler){
						menuToggler.focus();
					}
					e.preventDefault();
					e.stopPropagation();
				}
				//shift tab on first menu item
				if(e.target===firstLink&&navClose){
					navClose.focus();
					e.preventDefault();
					e.stopPropagation();
				}
				//search modal
				if(e.target===searchInput&&searchCloseBtn){
					searchCloseBtn.focus();
					e.preventDefault();
					e.stopPropagation();
				}
				//search toggler
				if(e.target===searchToggle){
					logo.focus();
					e.preventDefault();
					e.stopPropagation();
				}
				//burger button
				if(e.target===menuToggler){
					if(searchToggle){
						searchToggle.focus();
					}else{
						logo.focus();
					}
					e.preventDefault();
					e.stopPropagation();
				}
				//logo
				if(e.target===logo&&skipLink){
					skipLink.focus();
					e.preventDefault();
					e.stopPropagation();
				}
			}
		});
		if (navClose) {
			//focus on the first menu item on blur
			navClose.onblur=function(e) {
				if(firstLink){
					firstLink.focus();
				}
			};
			navClose.addEventListener('click', function(e) {
				dummyClickEvent();
				if(menuToggler){
					menuToggler.focus();
				}
			});
		}

		//stop links with '#' href value
		var links = d.querySelectorAll('a[href="#"]');
		hashLinksPrevent(links);
		//sticky header
		// https://www.w3schools.com/howto/howto_js_navbar_sticky.asp
		var headerWrap = gid('header-affix-wrap');
		if (headerWrap) {
			var header = gid('header');
			affix(header);
			setTimeout(function (){
				setMinHeight(header,headerWrap);
			},200);
			w.addEventListener('resize',function (){
				setTimeout(function (){
					setMinHeight(header,headerWrap);
				},200);
			});
		}
		// init masonry
		if (typeof (Masonry) !== 'undefined' && typeof (imagesLoaded) !== 'undefined') {
			var grids = d.querySelectorAll('.masonry');
			if (grids.length) {
				var i;
				for (i = 0; i < grids.length; i++) {
					imagesLoaded(grids[i], function(el) {
						new Masonry(el.elements[0], {
							"itemSelector": ".grid-item",
							"columnWidth": ".grid-sizer",
							"percentPosition": true
						});
					});
				}
			}
		}
		//toTop
		var toTop = gid('to-top');
		if (toTop) {
			toTop.addEventListener('click', function(e) {
				e.preventDefault();
				w.scroll({top: 0, left: 0, behavior: 'smooth'});
			});
			w.addEventListener('scroll', function(e) {
				if (w.pageYOffset > 60) {
					toTop.classList.add('visible');
				} else {
					toTop.classList.remove('visible');
				}
			});
		}
		//showing affix header and toTop button if scrolled down
		if(toTop || headerWrap) {
			w.dispatchEvent(new Event('scroll'));
		}
		//contact forms processing
		var forms=d.querySelectorAll('.exs-ajax-form');
		if(forms){
			forms.forEach(function(form,index){
				form.onsubmit = function (e){
					e.preventDefault();
					var form=e.target;
					//remove message if exists
					var oldMessageEl=form.querySelector('.exs-form-message');
					if(oldMessageEl){
						oldMessageEl.remove();
					}
					//disable button
					var submitBtn=form.querySelector('button');
					if(submitBtn){
						submitBtn.setAttribute('disabled','disabled');
						submitBtn.classList.add('loading');
					}
					//building request string
					var requestString='nonce='+body.getAttribute('data-nonce')+'&action=exs_ajax_form'
					for (var i=0; i<form.length-1;i++) {
						requestString+='&'+form[i].name+'='+form[i].placeholder+'|||'+form[i].value;
					}
					//sending AJAX request
					var r=new XMLHttpRequest();
					r.onload=function (e) {
						var response=JSON.parse(r.response);
						var messageEl=d.createElement('div');
						messageEl.classList.add('exs-form-message');
						messageEl.appendChild(d.createTextNode(response.data.message));
						form.appendChild(messageEl);
						form.reset();
						submitBtn.removeAttribute('disabled');
					}
					r.onerror = function () {
						console.error('error');
					}
					r.open('post',body.getAttribute('data-ajax'));
					r.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
					r.send(requestString);
				}
			});
		}
		body.classList.add('dom-loaded');
	});
	w.onload=function() {
		d.body.classList.add('window-loaded');
		//preloader
		var preloader = gid('preloader');
		if (preloader) {
			preloader.classList.add('loaded');
		}
	}
})(document,window,document.getElementById.bind(document));