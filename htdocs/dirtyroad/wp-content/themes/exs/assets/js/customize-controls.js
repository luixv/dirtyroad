'use strict';
(function ($, api) {

	api.bind('ready', function () {

		//redirect to appropriate pages
		api.section( 'static_front_page', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.homeUrl );
				}
			} );
		} );
		api.section( 'section_blog', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.blogUrl );
				}
			} );
		} );
		api.section( 'section_blog_post', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.postUrl );
				}
			} );
		} );
		api.section( 'section_search', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.searchUrl );
				}
			} );
		} );
		api.section( 'section_exs_woocommerce_layout', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.shopUrl );
				}
			} );
		} );
		api.section( 'section_exs_woocommerce_products', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.shopUrl );
				}
			} );
		} );
		api.section( 'woocommerce_product_catalog', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.shopUrl );
				}
			} );
		} );
		api.section( 'woocommerce_checkout', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.checkoutUrl );
				}
			} );
		} );
		api.section( 'section_exs_woocommerce_product_layout', function( section ) {
			section.expanded.bind( function( isExpanded ) {
				if ( isExpanded ) {
					api.previewer.previewUrl.set( exsCustomizerObject.productUrl );
				}
			} );
		} );

		var previewWrap = document.getElementById('customize-preview');

		//////////
		//colors//
		//////////
		//set CSS variables dynamic
		var colors = [
			'colorLight',
			'colorFont',
			'colorFontMuted',
			'colorBackground',
			'colorBorder',
			'colorDark',
			'colorDarkMuted',
			'colorMain',
			'colorMain2'
		];
		colors.forEach(function (color) {
			api(color, function (control) {
				control.bind(function () {
					if(!control) {
						return;
					}
					//set style on iframe root element
					previewWrap.firstChild.contentWindow.document.documentElement.style.setProperty('--'+color, control.get());
				});
			});
		});

		////////////////
		//buttons,menu//
		////////////////
			//checkboxes
			// 'buttons_uppercase'
			// 'buttons_bold'
			// 'buttons_colormain'
			// 'buttons_outline'
		var buttonCheckboxes = [
			{ 'buttons_uppercase': 'btns-uppercase' },
			{ 'buttons_bold': 'btns-bold' },
			{ 'buttons_big': 'btns-big' },
			{ 'buttons_colormain': 'btns-colormain' },
			{ 'buttons_outline': 'btns-outline' },
			{ 'header_menu_uppercase': 'menu-uppercase' },
			{ 'header_menu_bold': 'menu-bold' },
			{ 'post_thumbnails_fullwidth': 'thumbnail-fullwidth' },
		];
		buttonCheckboxes.forEach(function (obj, i) {
			for (var prop in obj) {
				api(prop, function (control) {
					control.bind(function () {
						if (!control) {
							return;
						}
						if (control.get()) {
							previewWrap.firstChild.contentWindow.document.body.classList.add(buttonCheckboxes[i][prop]);
						} else {
							previewWrap.firstChild.contentWindow.document.body.classList.remove(buttonCheckboxes[i][prop]);
						}
					});
				});
			}
		});
		// 'buttons_radius'
		api('buttons_radius', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var val = control.get();
				previewWrap.firstChild.contentWindow.document.body.classList.remove('btns-rounded');
				previewWrap.firstChild.contentWindow.document.body.classList.remove('btns-round');
				if (control.get()) {
					previewWrap.firstChild.contentWindow.document.body.classList.add(val);
				}
			});
		});
		// 'buttons_fs'
		api('buttons_fs', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var val = control.get();
				var btnFsClasses = [
					'b-fs-9',
					'b-fs-10',
					'b-fs-11',
					'b-fs-12',
					'b-fs-13',
					'b-fs-14',
					'b-fs-15',
					'b-fs-16',
					'b-fs-17',
					'b-fs-18',
					'b-fs-19',
					'b-fs-20',
					'b-fs-21',
					'b-fs-22'
				];
				btnFsClasses.forEach(function (val) {
					previewWrap.firstChild.contentWindow.document.body.classList.remove(val);
				});
				if (control.get()) {
					previewWrap.firstChild.contentWindow.document.body.classList.add('b-'+val);
				}
			});
		});

		// 'meta icons color'
		api('color_meta_icons', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var val = control.get();
				var btnFsClasses = [
					'meta-icons-main',
					'meta-icons-main2',
					'meta-icons-border',
					'meta-icons-dark',
					'meta-icons-dark-muted'
				];
				btnFsClasses.forEach(function (val) {
					previewWrap.firstChild.contentWindow.document.body.classList.remove(val);
				});
				if (control.get()) {
					previewWrap.firstChild.contentWindow.document.body.classList.add(val);
				}
			});
		});
		// 'meta text color'
		api('color_meta_text', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var val = control.get();
				var btnFsClasses = [
					'meta-text-main',
					'meta-text-main2',
					'meta-text-border',
					'meta-text-dark',
					'meta-text-dark-muted'
				];
				btnFsClasses.forEach(function (val) {
					previewWrap.firstChild.contentWindow.document.body.classList.remove(val);
				});
				if (control.get()) {
					previewWrap.firstChild.contentWindow.document.body.classList.add(val);
				}
			});
		});

		///////////
		//presets//
		///////////
		//these settings are set in options.php file
		var presets = [
			{
				"header": "1",
				"header_fluid": "",
				"logo": "1",
				"skin": "1"
			},
			{
				"header": "2",
				"header_fluid": "1",
				"logo": "2",
				"skin": "2"
			}
		];

		api('preset', function (preset) {
			//bind function on change preset value 
			preset.bind(function () {
				if (!preset) {
					return;
				}

				var presetNum = parseInt(preset.get(), 10) - 1;
				for (var key in presets[presetNum]) {
					api(key, function (control) {
						control.set(presets[presetNum][key]);
					});
				}

			});
		});

			//skins
		api('skin', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var style = previewWrap.firstChild.contentWindow.document.getElementById('exs-skin-css');
				if( ! style) {
					return;
				}
				var val = control.get();
				if (control.get()) {
					style.setAttribute('href',exsCustomizerObject.themeUrl+'/extra/assets/css/min/skin'+val+'.css');
				} else {
					style.setAttribute('href',exsCustomizerObject.themeUrl+'/extra/assets/css/min/skin0.css');
				}
			});
		});
			//fade in
		api('box_fade_in', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				previewWrap.firstChild.contentWindow.document.body.classList.remove('window-loaded');
				previewWrap.firstChild.contentWindow.document.getElementById('box').classList.remove('box-fade-in');
				if (control.get()) {
					previewWrap.firstChild.contentWindow.document.getElementById('box').classList.add('box-fade-in');
					setTimeout(function (){
						previewWrap.firstChild.contentWindow.document.body.classList.add('window-loaded');
					},500);

				}
			});
		});

		//containers
		var conainerOptions = [
			// 'main_container_width',
			'blog_single_container_width',
			'blog_container_width',
			'search_container_width',
			'bbpress_container_width',
			'buddypress_container_width',
			'wpjm_container_width',
			'event_container_width',
			'events_container_width',
			'product_container_width',
			'shop_container_width'
		];

		function setMainContainerClass(controlId) {
			// previewWrap.firstChild.contentWindow.document.body.classList.add('container-'+api(controlId).get());
			var title = previewWrap.firstChild.contentWindow.document.getElementById('title');
			var main = previewWrap.firstChild.contentWindow.document.getElementById('main');
			var currentVal = api(controlId).get();
			if(!currentVal){
				currentVal = api().get('main_container_width');
			}
			if(title){
				title.classList.remove('container-1400', 'container-1140', 'container-960', 'container-720');
				title.classList.add('container-'+api(controlId).get());
			}
			if(main){
				main.classList.remove('container-1400', 'container-1140', 'container-960', 'container-720');
				main.classList.add('container-'+api(controlId).get());
			}
		}
		function setGlobalContainerClass(controlId) {
			var top = previewWrap.firstChild.contentWindow.document.getElementById('top-wrap');
			var bottom = previewWrap.firstChild.contentWindow.document.getElementById('bottom-wrap');
			if(top){
				top.classList.remove('container-1400', 'container-1140', 'container-960', 'container-720');
				top.classList.add('container-'+api(controlId).get());
			}
			if(bottom){
				bottom.classList.remove('container-1400', 'container-1140', 'container-960', 'container-720');
				bottom.classList.add('container-'+api(controlId).get());
			}
		}
		function setContextualContainerClass(controlId) {
			var val=api(controlId).get();
			if(val){
				setMainContainerClass(controlId);
			}else{
				setGlobalContainerClass('main_container_width');
			}
		}
		//main container
		api('main_container_width', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				setGlobalContainerClass('main_container_width');
				conainerOptions.forEach(function (cControl) {
					api(cControl, function (control) {
						if (!control) {
							return;
						}
						//process
						if (control.get()) {
							if(!previewWrap.firstChild.contentWindow.exsPreviewObject.view){
								//set main container width default
								setMainContainerClass('main_container_width');
							} else {
								switch (previewWrap.firstChild.contentWindow.exsPreviewObject.view){
									case 'product':
										setContextualContainerClass('product_container_width');
										break;
									case 'shop':
										setContextualContainerClass('shop_container_width');
										break;
									case 'event':
										setContextualContainerClass('event_container_width');
										break;
									case 'events':
										setContextualContainerClass('events_container_width');
										break;
									case 'wpjm':
										setContextualContainerClass('wpjm_container_width');
										break;
									case 'buddypress':
										setContextualContainerClass('buddypress_container_width');
										break;
									case 'bbpress':
										setContextualContainerClass('bbpress_container_width');
										break;
									case 'post':
										setContextualContainerClass('blog_single_container_width');
										break;
									case 'search':
										setContextualContainerClass('search_container_width');
										break;
									case 'archive':
										setContextualContainerClass('blog_container_width');
										break;
									default:
										setMainContainerClass('main_container_width');
										break;
								}
							}
						} else {
							//set default
							setMainContainerClass('main_container_width');
						}
					});
				});
			});
		});
		conainerOptions.forEach(function (cControl) {
			api(cControl, function (control) {
				control.bind(function () {
					if (!control) {
						return;
					}
					//process
					if (control.get()) {
						if(!previewWrap.firstChild.contentWindow.exsPreviewObject.view){
							//set main container width default
							setMainContainerClass('main_container_width');
						} else {
							switch (previewWrap.firstChild.contentWindow.exsPreviewObject.view){
								case 'product':
									setContextualContainerClass('product_container_width');
									break;
								case 'shop':
									setContextualContainerClass('shop_container_width');
									break;
								case 'event':
									setContextualContainerClass('event_container_width');
									break;
								case 'events':
									setContextualContainerClass('events_container_width');
									break;
								case 'wpjm':
									setContextualContainerClass('wpjm_container_width');
									break;
								case 'buddypress':
									setContextualContainerClass('buddypress_container_width');
									break;
								case 'bbpress':
									setContextualContainerClass('bbpress_container_width');
									break;
								case 'post':
									setContextualContainerClass('blog_single_container_width');
									break;
								case 'search':
									setContextualContainerClass('search_container_width');
									break;
								case 'archive':
									setContextualContainerClass('blog_container_width');
									break;
								default:
									setMainContainerClass('main_container_width');
									break;
							}
						}
					} else {
						//set default
						setMainContainerClass('main_container_width');
					}
				})
			});
		});

		////////
		//meta//
		////////
		var metaOptions=[
			'meta_email',
			'meta_email_label',
			'meta_phone',
			'meta_phone_label',
			'meta_address',
			'meta_address_label',
			'meta_opening_hours',
			'meta_opening_hours_label',
			'meta_facebook',
			'meta_twitter',
			'meta_youtube',
			'meta_instagram',
			'meta_pinterest',
			'meta_linkedin',
			'meta_github'
		];
		//load other parts that contains meta
		metaOptions.forEach(function (cControl) {
			api(cControl, function (control) {
				control.bind(function () {
					if (!control) {
						return;
					}
					api('side_nav_position', function (side){
						var val = side.get();
						side.set('use');
						side.set(val);
					});
					api('copyright', function (side){
						var val = side.get();
						side.set('use');
						side.set(val);
					});
				});
			});
		});

		////////
		//main//
		////////
		// 'main_sidebar_width',
		// 'main_gap_width',
		// 'main_extra_padding_top',
		// 'main_extra_padding_bottom',
		// 'main_font_size',
		api('main_sidebar_width', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var main=previewWrap.firstChild.contentWindow.document.getElementById('main');
				if(main){
					main.classList.remove('sidebar-33','sidebar-25');
					main.classList.add('sidebar-'+control.get());
				}
			});
		});
		api('main_gap_width', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var main=previewWrap.firstChild.contentWindow.document.getElementById('main');
				if(main){
					main.classList.remove('sidebar-gap-1','sidebar-gap-2','sidebar-gap-3','sidebar-gap-4');
					main.classList.add('sidebar-gap-'+control.get());
				}
			});
		});
		api('main_font_size', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var col=previewWrap.firstChild.contentWindow.document.getElementById('col');
				if(col){
					col.classList.remove('fs-9', 'fs-10', 'fs-11', 'fs-12', 'fs-13', 'fs-14', 'fs-15', 'fs-16', 'fs-17', 'fs-18', 'fs-19', 'fs-20', 'fs-21', 'fs-22');
					if(control.get()){
						col.classList.add(control.get());
					}
				}
			});
		});
		api('main_extra_padding_top', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var container=previewWrap.firstChild.contentWindow.document.querySelector('#main>.container');
				if(container){
					container.classList.remove('pt-0', 'pt-1', 'pt-2', 'pt-3', 'pt-4', 'pt-5', 'pt-6', 'pt-7', 'pt-8', 'pt-9', 'pt-10');
					if(control.get()){
						container.classList.add(control.get());
					}
				}

			});
		});
		api('main_extra_padding_bottom', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var container=previewWrap.firstChild.contentWindow.document.querySelector('#main>.container');
				if(container){
					container.classList.remove('pb-0', 'pb-1', 'pb-2', 'pb-3', 'pb-4', 'pb-5', 'pb-6', 'pb-7', 'pb-8', 'pb-9', 'pb-10');
					if(control.get()) {
						container.classList.add(control.get());
					}
				}
			});
		});

		//aside
		// 'main_sidebar_sticky',
		// 'sidebar_font_size'
		api('main_sidebar_sticky', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var sidebar=previewWrap.firstChild.contentWindow.document.getElementById('widgets-wrap');
				if(sidebar){
					sidebar.classList.remove('sticky');
					if(control.get()){
						sidebar.classList.add('sticky');
					}
				}
			});
		});
		api('sidebar_font_size', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var sidebar=previewWrap.firstChild.contentWindow.document.getElementById('aside');
				if(sidebar){
					sidebar.classList.remove('fs-9', 'fs-10', 'fs-11', 'fs-12', 'fs-13', 'fs-14', 'fs-15', 'fs-16', 'fs-17', 'fs-18', 'fs-19', 'fs-20', 'fs-21', 'fs-22');
					if(control.get()){
						sidebar.classList.add(control.get());
					}
				}
			});
		});


		//////////////
		//typography//
		//////////////
			//body
		var typoControls = [
			//body styles
			{
				'typo_body_size':
				{
					'selector':'body',
					'rule':'font-size',
					'last':'px'
				}
			},
			{
				'typo_body_weight':
				{
					'selector':'body',
					'rule':'font-weight',
					'last':''
				}
			},
			{
				'typo_body_line_height':
					{
						'selector':'body',
						'rule':'line-height',
						'last':''
					}
			},
			{
				'typo_body_letter_spacing':
				{
					'selector':'body',
					'rule':'letter-spacing',
					'last':'em'
				}
			},
			//p styles
			{
				'typo_p_margin_bottom':
				{
					'selector':'p',
					'rule':'margin-bottom',
					'last':'em'
				}
			},
		];
		for(var h=1; h<7;h++ ){
			var hSelector = 'h'+h;
			var typo_size_h = 'typo_size_h'+h;
			var typo_line_height_h = 'typo_line_height_h'+h;
			var typo_letter_spacing_h = 'typo_letter_spacing_h'+h;
			var typo_weight_h = 'typo_weight_h'+h;
			var typo_mt_h = 'typo_mt_h'+h;
			var typo_mb_h = 'typo_mb_h'+h;

			var o1={},
				o2={},
				o3={},
				o4={},
				o5={},
				o6={};
			o1[typo_size_h]={
				'selector': hSelector,
				'rule': 'font-size',
				'last': 'em',
			};
			o2[typo_line_height_h]={
				'selector':hSelector,
				'rule':'line-height',
				'last':'em',
			};
			o3[typo_letter_spacing_h]={
				'selector':hSelector,
				'rule':'letter-spacing',
				'last':'em',
			};
			o4[typo_weight_h]={
				'selector':hSelector,
				'rule':'font-weight',
				'last':'',
			};
			o5[typo_mt_h]={
				'selector':hSelector,
				'rule':'margin-top',
				'last':'em',
			};
			o6[typo_mb_h]={
				'selector':hSelector,
				'rule':'margin-bottom',
				'last':'em',
			};

			typoControls.push(
				o1,o2,o3,o4,o5,o6
			);
		}
		//TODO test #exs-style-inline-css existing on clean installation
		typoControls.forEach(function (obj, i) {
			for (var prop in obj) {
				api(prop, function (control) {
					control.bind(function () {
						var style = previewWrap.firstChild.contentWindow.document.getElementById('exs-style-inline-css');
						if( ! style) {
							return;
						}
						var rules = style.sheet.cssRules;
						if (!control) {
							return;
						}
						if (control.get()) {

							var selectorExists = false;
							var ruleExists = false;

							//i - index in typoControls
							//prop - object key - ex. 'typo_body_size'

							//search for selector in style
							for (var j=0; j<rules.length;j++){
								if(rules[j].selectorText===typoControls[i][prop].selector){
									//selector was found. searching for rule
									selectorExists = j;
									//set style
									rules[j].style.setProperty(typoControls[i][prop].rule,control.get()+typoControls[i][prop].last);
									break;
								}
							}

							//if there is no selector - we need to add it
							if(! selectorExists){
								style.sheet.insertRule(typoControls[i][prop].selector+'{'+typoControls[i][prop].rule+':'+control.get()+typoControls[i][prop].last+';}',rules.length);
							}
						} else {
							//removing style rule
							//search for selector in style
							for (var j=0; j<rules.length;j++){
								if(rules[j].selectorText===typoControls[i][prop].selector){
									//selector was found. searching for rule
									selectorExists = j;
									//set style
									rules[j].style.removeProperty(typoControls[i][prop].rule);
									break;
								}
							}
						}
					})
				});
			}
		});

		//fonts
		api( 'font_body', function( fontName ) {
			fontName.bind( function(){
				if(!fontName) {
					return;
				}
				previewWrap.firstChild.contentWindow.document.getElementById('body').style.opacity='0';
			});
		});
		api( 'font_headings', function( fontName ) {
			fontName.bind( function(){
				if(!fontName) {
					return;
				}
				previewWrap.firstChild.contentWindow.document.getElementById('body').style.opacity='0';
			});
		});

		//animate
		function animateElements(selector,cssClass){
			var elements = previewWrap.firstChild.contentWindow.document.querySelectorAll(selector);
			elements.forEach(function (el,i) {
				el.classList.remove('animated','bounce','flash','pulse','rubberBand','shake','headShake','swing','tada','wobble','jello','heartBeat','bounceIn','fadeIn','fadeInDown','fadeInLeft','fadeInRight','fadeInUp','flip','flipInX','flipInY','lightSpeedIn','jackInTheBox', 'zoomIn');
				setTimeout(function (){
					el.classList.add('animated',cssClass);
				},i*150);
			});
		}
		function checkAnimationEnabled(){
			return api('animation_enabled') ? api('animation_enabled').get() : false;
		}
		function processAnimation(controlId,selector){
			api(controlId, function (control){
				control.bind(function () {
					if (!control || !checkAnimationEnabled()) {
						return;
					}
					var val = control.get();
					if(val){
						animateElements(selector, val);
					}
				});
			});
		}
		api('animation_enabled', function (control) {
			control.bind(function () {
				if (!control) {
					return;
				}
				var style = previewWrap.firstChild.contentWindow.document.getElementById('exs-animate-css');
				if( ! style) {
					var head = previewWrap.firstChild.contentWindow.document.head;
					var link = previewWrap.firstChild.contentWindow.document.createElement("link");

					link.rel = 'stylesheet';
					link.href = exsCustomizerObject.themeUrl+'/extra/assets/css/min/animate.css';

					head.appendChild(link);
				}
				// var val = control.get();
				if (control.get()) {
					//get current animation values and init them
					api('animation_sidebar_widgets', function (anControl){
						var val = anControl.get();
						if(val){
							animateElements('.column-aside .widget', val);
						}
					});
					api('animation_footer_widgets', function (anControl){
						var val = anControl.get();
						if(val){
							animateElements('.footer-widgets .widget', val);
						}
					});
					api('animation_feed_posts', function (anControl){
						var val = anControl.get();
						if(val){
							animateElements('.hfeed article.post', val);
						}
					});
					api('animation_feed_posts', function (anControl){
						var val = anControl.get();
						if(val){
							animateElements('.hfeed .post .post-thumbnail img', val);
						}
					});
				}
			});
		});

		processAnimation('animation_sidebar_widgets','.column-aside .widget');
		processAnimation('animation_footer_widgets','.footer-widgets .widget');
		processAnimation('animation_feed_posts','.hfeed article.post');
		processAnimation('animation_feed_posts_thumbnail','.hfeed .post .post-thumbnail img');

		//dependencies
		function toggleDependentControls(controlId,dependentControlsArray){
			var c = api(controlId);
			if(typeof c === 'undefined') {
				return;
			}
			if(!c.get()){
				api.previewer.bind('ready', function (){
					dependentControlsArray.forEach(function (id){
						api.control(id).deactivate();
					});
				});
			}
			api(controlId, function (control) {
				control.bind(function () {
					if (!control) {
						return;
					}
					if(control.get()){
						dependentControlsArray.forEach(function (id){
							api.control(id).activate();
						});
					} else {
						dependentControlsArray.forEach(function (id){
							api.control(id).deactivate();
						});
					}
				});
			});
		}
		function toggleDependentControlsInverse(controlId,dependentControlsArray){
			var c = api(controlId);
			if(typeof c === 'undefined') {
				return;
			}
			if(c.get()){
				api.previewer.bind('ready', function (){
					dependentControlsArray.forEach(function (id){
						api.control(id).deactivate();
					});
				});
			}
			api(controlId, function (control) {
				control.bind(function () {
					if (!control) {
						return;
					}
					if(!control.get()){
						dependentControlsArray.forEach(function (id){
							api.control(id).activate();
						});
					} else {
						dependentControlsArray.forEach(function (id){
							api.control(id).deactivate();
						});
					}
				});
			});
		}
		//intro
		toggleDependentControls('intro_position',[
			'intro_layout',
			'intro_fullscreen',
			'intro_background',
			'intro_background_image',
			'intro_image_animation',
			'intro_background_image_cover',
			'intro_background_image_fixed',
			'intro_background_image_overlay',
			'intro_heading',
			'intro_heading_mt',
			'intro_heading_mb',
			'intro_heading_animation',
			'intro_description',
			'intro_description_mt',
			'intro_description_mb',
			'intro_description_animation',
			'intro_button_text_first',
			'intro_button_url_first',
			'intro_button_first_animation',
			'intro_button_text_second',
			'intro_button_url_second',
			'intro_button_second_animation',
			'intro_buttons_mt',
			'intro_buttons_mb',
			'intro_shortcode',
			'intro_shortcode_mt',
			'intro_shortcode_mb',
			'intro_shortcode_animation',
			'intro_alignment',
			'intro_extra_padding_top',
			'intro_extra_padding_bottom',
			'intro_show_search',
			'intro_font_size'
		]);
		//intro teasers
		toggleDependentControls('intro_teaser_section_layout',[
			'intro_teaser_section_background',
			'intro_teaser_section_padding_top',
			'intro_teaser_section_padding_bottom',
			'intro_teaser_font_size',
			'intro_teaser_layout',
			'intro_teaser_heading',
			'intro_teaser_description',
			'intro_teaser_image_1',
			'intro_teaser_title_1',
			'intro_teaser_text_1',
			'intro_teaser_link_1',
			'intro_teaser_button_text_1',
			'intro_teaser_image_2',
			'intro_teaser_title_2',
			'intro_teaser_text_2',
			'intro_teaser_link_2',
			'intro_teaser_button_text_2',
			'intro_teaser_image_3',
			'intro_teaser_title_3',
			'intro_teaser_text_3',
			'intro_teaser_link_3',
			'intro_teaser_button_text_3',
			'intro_teaser_image_4',
			'intro_teaser_title_4',
			'intro_teaser_text_4',
			'intro_teaser_link_4',
			'intro_teaser_button_text_4'
		]);
		//meta
		toggleDependentControls('meta_email',['meta_email_label']);
		toggleDependentControls('meta_phone',['meta_phone_label']);
		toggleDependentControls('meta_address',['meta_address_label']);
		toggleDependentControls('meta_opening_hours',['meta_opening_hours_label']);
		//header
		toggleDependentControls('header',[
			'header_logo_hidden',
			'header_fluid',
			'header_background',
			'header_toplogo_options_heading',
			'header_toplogo_background',
			'header_toplogo_social_hidden',
			'header_toplogo_meta_hidden',
			'header_align_main_menu',
			'header_toggler_menu_main',
			'header_absolute',
			'header_transparent',
			'header_menu_uppercase',
			'header_menu_bold',
			'header_border_top',
			'header_border_bottom',
			'header_font_size',
			'header_sticky',
			'header_login_links',
			'header_login_links_hidden',
			'header_search',
			'header_search_hidden',
			'header_button_text',
			'header_button_url',
			'header_button_hidden'
		]);
		toggleDependentControls('header_login_links',[
			'header_login_links_hidden'
		]);
		toggleDependentControls('header_search',[
			'header_search_hidden'
		]);
		toggleDependentControls('header_button_text',[,
			'header_button_url',
			'header_button_hidden'
		]);
		//topline
		toggleDependentControls('topline',[
			'topline_fluid',
			'topline_background',
			'meta_topline_text',
			'topline_font_size',
			'topline_login_links'
		]);
		//title
		toggleDependentControls('title_background_image',[
			'title_background_image_cover',
			'title_background_image_fixed',
			'title_background_image_overlay'
		]);
		//footer-top
		toggleDependentControls('footer_top',[
			'footer_top_content_heading_text',
			'footer_top_image',
			'footer_top_pre_heading',
			'footer_top_pre_heading_mt',
			'footer_top_pre_heading_mb',
			'footer_top_pre_heading_animation',
			'footer_top_heading',
			'footer_top_heading_mt',
			'footer_top_heading_mb',
			'footer_top_heading_animation',
			'footer_top_description',
			'footer_top_description_mt',
			'footer_top_description_mb',
			'footer_top_description_animation',
			'footer_top_shortcode',
			'footer_top_shortcode_mt',
			'footer_top_shortcode_mb',
			'footer_top_shortcode_animation',
			'footer_top_options_heading_text',
			'footer_top_fluid',
			'footer_top_background',
			'footer_top_border_top',
			'footer_top_border_bottom',
			'footer_top_extra_padding_top',
			'footer_top_extra_padding_bottom',
			'footer_top_background_image',
			'footer_top_background_image_cover',
			'footer_top_background_image_fixed',
			'footer_top_background_image_overlay',
			'footer_top_font_size'
		]);
		//footer
		toggleDependentControls('footer',[
			'footer_layout_gap',
			'footer_fluid',
			'footer_background',
			'footer_border_top',
			'footer_border_bottom',
			'footer_extra_padding_top',
			'footer_extra_padding_bottom',
			'footer_font_size',
			'footer_background_image',
			'footer_background_image_cover',
			'footer_background_image_fixed',
			'footer_background_image_overlay'
		]);
		//copyright
		toggleDependentControls('copyright',[
			'copyright_text',
			'copyright_fluid',
			'copyright_extra_padding_top',
			'copyright_extra_padding_bottom',
			'copyright_font_size',
			'copyright_background',
			'copyright_background_image',
			'copyright_background_image_cover',
			'copyright_background_image_fixed',
			'copyright_background_image_overlay'
		]);
		//blog
		toggleDependentControls('blog_show_author',[
			'blog_show_author_avatar',
			'blog_before_author_word'
		]);
		toggleDependentControls('blog_show_date',[
			'blog_before_date_word',
			'blog_show_human_date'
		]);
		toggleDependentControls('blog_show_categories',[
			'blog_before_categories_word'
		]);
		toggleDependentControls('blog_show_tags',[
			'blog_before_tags_word'
		]);
		//post
		toggleDependentControls('blog_single_show_author_bio',[
			'blog_single_author_bio_about_word'
		]);
		toggleDependentControls('blog_single_post_nav',[
			'blog_single_post_nav_word_prev',
			'blog_single_post_nav_word_next'
		]);
		toggleDependentControls('blog_single_related_posts',[
			'blog_single_related_posts_title',
			'blog_single_related_posts_number'
		]);
		toggleDependentControls('blog_single_show_author',[
			'blog_single_show_author_avatar',
			'blog_single_before_author_word'
		]);
		toggleDependentControls('blog_single_show_date',[
			'blog_single_before_date_word',
			'blog_single_show_human_date'
		]);
		toggleDependentControls('blog_single_show_categories',[
			'blog_single_before_categories_word'
		]);
		toggleDependentControls('blog_single_show_tags',[
			'blog_single_before_tags_word'
		]);
		//title post meta
		toggleDependentControls('title_blog_single_show_author_bio',[
			'title_blog_single_author_bio_about_word'
		]);
		toggleDependentControls('title_blog_single_post_nav',[
			'title_blog_single_post_nav_word_prev',
			'title_blog_single_post_nav_word_next'
		]);
		toggleDependentControls('title_blog_single_related_posts',[
			'title_blog_single_related_posts_title',
			'title_blog_single_related_posts_number'
		]);
		toggleDependentControls('title_blog_single_show_author',[
			'title_blog_single_show_author_avatar',
			'title_blog_single_before_author_word'
		]);
		toggleDependentControls('title_blog_single_show_date',[
			'title_blog_single_before_date_word',
			'title_blog_single_show_human_date'
		]);
		toggleDependentControls('title_blog_single_show_categories',[
			'title_blog_single_before_categories_word'
		]);
		toggleDependentControls('title_blog_single_show_tags',[
			'title_blog_single_before_tags_word'
		]);
		//search
		toggleDependentControls('search_show_author',[
			'search_show_author_avatar',
			'search_before_author_word'
		]);
		toggleDependentControls('search_show_date',[
			'search_before_date_word',
			'search_show_human_date'
		]);
		toggleDependentControls('search_show_categories',[
			'search_before_categories_word'
		]);
		toggleDependentControls('search_show_tags',[
			'search_before_tags_word'
		]);
		toggleDependentControls('blog_single_toc_enabled',[
			'blog_single_toc_title',
			'blog_single_toc_background',
			'blog_single_toc_bordered',
			'blog_single_toc_shadow',
			'blog_single_toc_rounded',
			'blog_single_toc_mt',
			'blog_single_toc_mb'
		]);
		//animation
		toggleDependentControls('animation_enabled',[
			'animation_sidebar_widgets',
			'animation_footer_widgets',
			'animation_feed_posts',
			'animation_feed_posts_thumbnail'
		]);
		//messages
		toggleDependentControls('message_top_id',[
			'message_top_text',
			'message_top_close_button_text',
			'message_top_background',
			'message_top_font_size'
		]);
		toggleDependentControls('message_bottom_id',[
			'message_bottom_text',
			'message_bottom_close_button_text',
			'message_bottom_background',
			'message_bottom_layout',
			'message_bottom_bordered',
			'message_bottom_shadow',
			'message_bottom_rounded',
			'message_bottom_font_size'
		]);
		//cats
		toggleDependentControls('category_portfolio',[
			'category_portfolio_layout',
			'category_portfolio_layout_gap',
			'category_portfolio_sidebar_position'
		]);
		toggleDependentControls('category_services',[
			'category_services_layout',
			'category_services_layout_gap',
			'category_services_sidebar_position'
		]);
		toggleDependentControls('category_team',[
			'category_team_layout',
			'category_team_layout_gap',
			'category_team_sidebar_position'
		]);
		//side
		toggleDependentControls('side_nav_sticked',[
			'side_nav_sticked_shadow',
			'side_nav_sticked_border',
			'side_nav_header_overlap'
		]);
		//shop
		toggleDependentControlsInverse('product_simple_add_to_cart_hide_button',[
			'product_simple_add_to_cart_hide_icon',
			'product_simple_add_to_cart_block_button'
		]);
		// toggleDependentControls('product_related_separate',[
		// 	'product_related_separate_background',
		// 	'product_related_separate_container_width'
		// ]);


	}); //api ready

})(jQuery, wp.customize);
