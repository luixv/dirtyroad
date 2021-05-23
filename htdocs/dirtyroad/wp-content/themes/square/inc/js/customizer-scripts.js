jQuery(document).ready(function ($) {
    "use strict";

    //FontAwesome Icon Control JS
    $('body').on('click', '.square-icon-list li', function () {
        var icon_class = $(this).find('i').attr('class');
        $(this).addClass('icon-active').siblings().removeClass('icon-active');
        $(this).parent('.square-icon-list').prev('.square-selected-icon').children('i').attr('class', '').addClass(icon_class);
        $(this).parent('.square-icon-list').next('input').val(icon_class).trigger('change');
    });

    $('body').on('click', '.square-selected-icon', function () {
        $(this).next().slideToggle();
    });

    //MultiCheck box Control JS
    $('.customize-control-checkbox-multiple input[type="checkbox"]').on('change', function () {

        var checkbox_values = $(this).parents('.customize-control').find('input[type="checkbox"]:checked').map(
                function () {
                    return $(this).val();
                }
        ).get().join(',');

        $(this).parents('.customize-control').find('input[type="hidden"]').val(checkbox_values).trigger('change');

    });

    //Chosen JS
    $(".hs-chosen-select").chosen({
        width: "100%"
    });

    // Gallery Control
    $('.square-gallery-button').click(function (e) {
        e.preventDefault();

        var button = $(this);
        var hiddenfield = button.prev();
        if (hiddenfield.val()) {
            var hiddenfieldvalue = hiddenfield.val().split(",");
        } else {
            var hiddenfieldvalue = new Array();
        }

        var frame = wp.media({
            title: 'Insert Images',
            library: {
                type: 'image',
                post__not_in: hiddenfieldvalue
            },
            button: {text: 'Use Images'},
            multiple: 'add'
        });

        frame.on('select', function () {
            var attachments = frame.state().get('selection').map(function (a) {
                a.toJSON();
                return a;
            });
            var i;
            /* loop through all the images */
            for (i = 0; i < attachments.length; ++i) {
                /* add HTML element with an image */
                $('ul.square-gallery-container').append('<li data-id="' + attachments[i].id + '"><span style="background-image:url(' + attachments[i].attributes.url + ')"></span><a href="#" class="square-gallery-remove">×</a></li>');
                /* add an image ID to the array of all images */
                hiddenfieldvalue.push(attachments[i].id);
            }
            /* refresh sortable */
            $("ul.square-gallery-container").sortable("refresh");
            /* add the IDs to the hidden field value */
            hiddenfield.val(hiddenfieldvalue.join()).trigger('change');
        }).open();
    });

    $('ul.square-gallery-container').sortable({
        items: 'li',
        cursor: '-webkit-grabbing', /* mouse cursor */
        stop: function (event, ui) {
            ui.item.removeAttr('style');

            var sort = new Array(), /* array of image IDs */
                    gallery = $(this); /* ul.square-gallery-container */

            /* each time after dragging we resort our array */
            gallery.find('li').each(function (index) {
                sort.push($(this).attr('data-id'));
            });
            /* add the array value to the hidden input field */
            gallery.next().val(sort.join()).trigger('change');
        }
    });
    
    /*
     * Remove certain images
     */
    $('body').on('click', '.square-gallery-remove', function () {
        var id = $(this).parent().attr('data-id'),
                gallery = $(this).parent().parent(),
                hiddenfield = gallery.next(),
                hiddenfieldvalue = hiddenfield.val().split(","),
                i = hiddenfieldvalue.indexOf(id);

        $(this).parent().remove();

        /* remove certain array element */
        if (i != -1) {
            hiddenfieldvalue.splice(i, 1);
        }

        /* add the IDs to the hidden field value */
        hiddenfield.val(hiddenfieldvalue.join()).trigger('change');

        /* refresh sortable */
        gallery.sortable("refresh");

        return false;
    });

    //Scroll to section
    $('body').on('click', '#sub-accordion-panel-square_home_settings_panel .control-subsection .accordion-section-title', function (event) {
        var section_id = $(this).parent('.control-subsection').attr('id');
        scrollToSection(section_id);
    });

});

function scrollToSection(section_id) {
    var preview_section_id = "sq-home-slider-section";

    var $contents = jQuery('#customize-preview iframe').contents();

    switch (section_id) {
        case 'accordion-section-square_slider_sec':
            preview_section_id = "sq-home-slider-section";
            break;

        case 'accordion-section-square_featured_page_sec':
            preview_section_id = "sq-featured-post-section";
            break;

        case 'accordion-section-square_about_sec':
            preview_section_id = "sq-about-us-section";
            break;

        case 'accordion-section-square_tab_sec':
            preview_section_id = "sq-tab-section";
            break;

        case 'accordion-section-square_logo_sec':
            preview_section_id = "sq-logo-section";
            break;

        case 'accordion-section-squarepress_team_sec':
            preview_section_id = "sq-team-section";
            break;

        case 'accordion-section-squarepress_testimonial_sec':
            preview_section_id = "sq-testimonial-section";
            break;
    }

    if ($contents.find('#' + preview_section_id).length > 0) {
        $contents.find("html, body").animate({
            scrollTop: $contents.find("#" + preview_section_id).offset().top - 90
        }, 1000);
    }
}

// Extends our custom section.
(function (api) {

    api.sectionConstructor['square-pro-section'] = api.Section.extend({

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    });
    
    api.sectionConstructor['square-upgrade-section'] = api.Section.extend({

        // No events for this type of section.
        attachEvents: function () {},

        // Always make the section active.
        isContextuallyActive: function () {
            return true;
        }
    });

})(wp.customize);