( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Get Page Number
		function find_page_number( el ) {
			el.find( '.youzify-page-symbole' ).remove();
			return parseInt( el.text() );
		}

		// Get Posts Page
		$( document ).on( 'click', '.posts-nav-links a', function( e ) {

			e.preventDefault();

            $( 'html, body' ).animate( {
                scrollTop: $( '.youzify-posts' ).offset().top - 150
            }, 1000 );

			// Get Page Number
			var page = find_page_number( $( this ).clone() ),
				base = $( this ).closest( '.youzify-pagination' ).attr( 'data-base' );

			$.ajax( {
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'youzify_pages_pagination',
					query_vars: ajaxpagination.query_vars,
					youzify_base: base,
					youzify_page: page
				},
				beforeSend: function() {
					$( '#youzify-main-posts' ).find( '.youzify-posts-page' ).remove();
					$( document ).scrollTop();
					$( '#youzify-main-posts .youzify-loading' ).show();
				},
				success: function( html ) {
					$( '#youzify-main-posts .youzify-loading' ).hide();
					$( '#youzify-main-posts' ).append( html );
				}
			})

		});

		// Get Comments Page
		$( document ).on( 'click', '.comments-nav-links a', function( e ) {

			e.preventDefault();

            $( 'html, body' ).animate( {
                scrollTop: $( '.youzify-comments' ).offset().top - 150
            }, 1000 );

			// Get Page Number
			var cpage = find_page_number( $( this ).clone() ),
				cbase = $( this ).closest( '.youzify-pagination' ).attr( 'data-base' );

			$.ajax( {
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'youzify_comments_pagination',
					query_vars: ajaxpagination.query_vars,
					youzify_base: cbase,
					youzify_page: cpage
				},
				beforeSend: function() {
					$( '#youzify-main-comments' ).find( '.youzify-comments-page' ).remove();
					$( document ).scrollTop();
					$( '#youzify-main-comments .youzify-loading' ).show();
				},
				success: function( html ) {
					$( '#youzify-main-comments .youzify-loading' ).hide();
					$( '#youzify-main-comments' ).append( html );
				}
			})

		});

	});

})( jQuery );