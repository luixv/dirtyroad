'use strict';
(function (api) {
	api.bind('preview-ready', function () {
		var d = document;
		var w = window;
		var gid = document.getElementById.bind(document);

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

		api.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
			switch (placement.partial.params.selector) {
				//totop
				case '#to-top-wrap':
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
						w.dispatchEvent(new Event('scroll'));
					}
				break;

				//header
				case '#top-wrap':
					var headerWrap = gid('header-affix-wrap');
					if (headerWrap) {
						var header = gid('header');
						affix(header);
					}
					w.dispatchEvent(new Event('scroll'));
				break;

				//preloader
				case '#preloader-wrap':
					var preloader = gid('preloader');
					if (preloader) {
						setTimeout(function () {
							preloader.classList.add('loaded');
						},1500);
					}
				break;

				//head (fonts)
				case 'head':
					jQuery('body').animate({opacity:1},1000);
				break;
			}
		}); //partial-content-rendered

		//sidebar positions
		function setSidebarProcessor(controlId,view) {
			api( controlId, function( control ) {
				control.bind( function( value ) {
					if(view===w.exsPreviewObject.viewGlobal) {
						d.body.classList.remove('with-sidebar','sidebar-left');
						//left
						//right
						//no
						switch (value){
							case 'left':
								d.body.classList.add('with-sidebar', 'sidebar-left');
								break;
							case 'right':
								d.body.classList.add('with-sidebar');
								break;
						}
						//set body classes
					}
				});
			});
		}
		var sidebars = [
			{controlId:'blog_single_sidebar_position',view:'post'},
			{controlId:'blog_sidebar_position',view:'archive'},
			{controlId:'search_sidebar_position',view:'search'},
			{controlId:'shop_sidebar_position',view:'shop'},
			{controlId:'product_sidebar_position',view:'product'},
			{controlId:'bbpress_sidebar_position',view:'bbpress'},
			{controlId:'buddypress_sidebar_position',view:'buddypress'},
			{controlId:'events_sidebar_position',view:'events'},
			{controlId:'event_sidebar_position',view:'event'},
			{controlId:'wpjm_sidebar_position',view:'wpjm'}
		];
		sidebars.forEach(function (obj,i){
			setSidebarProcessor(obj.controlId, obj.view);
		});

	});
})(wp.customize);
