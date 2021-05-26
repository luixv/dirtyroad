/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview reload changes asynchronously.
 * Things like site title and description changes.
 */


(function ($) {
    $(document).ready(function () {
        $('body').on('click', '.accordion-section-title', function () {
            if ($('#customize-preview').hasClass('iframe-ready')) {
                var parentid = $(this).parent().attr('id');
                var iframe = $('#customize-preview iframe');
                var iframeContents = iframe.contents();
                if (!iframeContents.find('body').hasClass('page-template-template-front-page')) {
                    return;
                }
                if (parentid === 'accordion-section-theme_bigtitle_section') {
                    mp_theme_preview_scroll(iframeContents, ".big-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_welcome_section') {
                    mp_theme_preview_scroll(iframeContents, ".welcome-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_third_section') {
                    mp_theme_preview_scroll(iframeContents, ".third-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_install_section') {
                    mp_theme_preview_scroll(iframeContents, ".install-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_features_section') {
                    mp_theme_preview_scroll(iframeContents, ".features-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_portfolio_section') {
                    mp_theme_preview_scroll(iframeContents, ".portfolio-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_plan_section') {
                    mp_theme_preview_scroll(iframeContents, ".plan-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_accent_section') {
                    mp_theme_preview_scroll(iframeContents, ".accent-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_team_section') {
                    mp_theme_preview_scroll(iframeContents, ".team-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_subscribe_section') {
                    mp_theme_preview_scroll(iframeContents, ".subscribe-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_lastnews_section') {
                    mp_theme_preview_scroll(iframeContents, ".lastnews-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_testimonials_section') {
                    mp_theme_preview_scroll(iframeContents, ".testimonials-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_googlemap_section') {
                    mp_theme_preview_scroll(iframeContents, ".googlemap-section");
                    return;
                }
                if (parentid === 'accordion-section-theme_contactus_section') {
                    mp_theme_preview_scroll(iframeContents, ".contact-section");
                    return;
                }
                
            }
        });
		function mp_theme_preview_scroll(holder, animateto) {
			if ( holder && holder.find(animateto).length ) {
				holder.find('html, body').animate({
					scrollTop: holder.find(animateto).offset().top
				}, 1000);
			}
		}
    });
})(jQuery);
