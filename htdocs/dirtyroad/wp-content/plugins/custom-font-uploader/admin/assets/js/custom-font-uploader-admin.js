jQuery(document).ready(function () {

    jQuery(document).on('click', '#open_add_font', function(){
        jQuery('#font-upload').toggle('fast');
    });

    jQuery('.delete-font').on('click', function () {
        var del_key = jQuery(this).data('delete_font_key');
        var id = jQuery(this).data('fid');

        if( confirm( 'Are you sure you want to delete '+del_key+' font?' ) == true ) {
            jQuery(this).html('Deleting...');
            jQuery('#'+id).css('background-color', '#FA838A');

            var data = {
                'action': 'delete_customfont',
                'del_key': del_key,
            };
            jQuery.post(
                cfu_ajax_object.ajax_url,
                data,
                function (response) {
                    if( response == 'custom-font-deleted' ) {
                        location.reload();
                        jQuery('#'+id).remove();
                    }
                }
            );
        }
    });

    jQuery(document).on('keyup', '#cfup-apikey', function(){
        var apikey = jQuery(this).val();
        if( apikey != '' ) {
            jQuery('#cfup-verify-apikey').show();
        } else {
            jQuery('#cfup-verify-apikey').hide();
        }
    });

    jQuery("#cfup-apikey").bind("paste", function(e){
        var apikey = e.originalEvent.clipboardData.getData('text');
        if( apikey != '' ) {
            jQuery('#cfup-verify-apikey').show();
        } else {
            jQuery('#cfup-verify-apikey').hide();
        }
    } );

    jQuery(document).on('click', '.delete-googlefont', function () {
        var del_gkey = jQuery(this).data('delete_font_gkey');
        var gid = jQuery(this).data('fid');
        if( confirm( 'Are you sure you want to delete '+del_gkey+' font?' ) == true ) {
            jQuery(this).html('Deleting...');
            jQuery('#'+gid).css('background-color', '#FA838A');
            var data = {
                'action': 'delete_googlefont',
                'del_gkey': del_gkey,
            };
            jQuery.post(
                cfu_ajax_object.ajax_url,
                data,
                function (response) {
                    if( response == 'google-font-deleted' ) {
                        jQuery('#'+gid).remove();
                    }
                }
            );
        }
    });

    /*SUPPORT*/
    var acc = document.getElementsByClassName("cfup-accordion");
    var i;
    for (i = 0; i < acc.length; i++) {
        acc[i].onclick = function() {
            this.classList.toggle("active");
            var panel = this.nextElementSibling;
            if (panel.style.maxHeight){
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        }
    }

    jQuery(document).on('click', '.cfup-accordion', function(){
        return false;
    });
    /*SUPPORT*/
});

jQuery(document).ready(function () {
    jQuery("#googlefont-select").select2();
    jQuery("#googlefont-select").change(function () {
    var str = "";
    jQuery("#googlefont-select option:selected").each(function () {
        str += jQuery(this).text() + " ";
    });

    var href = 'https://fonts.googleapis.com/css?family=' + str;
    var cssLink = jQuery("<link rel= 'stylesheet' type='text/css' href='"+href+"'>");
    jQuery("head").append(cssLink);
    jQuery('.add_text').css('font-family', str);

    });
});
