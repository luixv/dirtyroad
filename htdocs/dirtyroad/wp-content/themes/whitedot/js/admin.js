jQuery(document).on( 'click', '.whitedot-notice .notice-dismiss', function() {

	jQuery.ajax({
		url: ajaxurl,
		data: {
			action: 'whitedot_dismiss_notice'
		}
	})
});

jQuery(document).on( 'click', '.open-upgrade', function() {

	$('.whitedot-addon-upgrade').addClass("active");
});

jQuery(document).on( 'click', '.upgrade-close', function() {

	$('.whitedot-addon-upgrade').removeClass("active");
});

jQuery(document).ready(function($) {
	$(".open-upgrade").click(function(){
		$('.whitedot-addon-upgrade').addClass("active");
	});
	$(".upgrade-close").click(function(){
		$('.whitedot-addon-upgrade').removeClass("active");
	});
});