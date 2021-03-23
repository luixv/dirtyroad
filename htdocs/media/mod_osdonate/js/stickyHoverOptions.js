/**
 * @package   OSDonate
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2010 VeroPlus.com
 * @copyright 2011-2020 Joomlashack.com. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of OSDonate.
 *
 * OSDonate is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * OSDonate is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OSDonate.  If not, see <http://www.gnu.org/licenses/>.
 */

function setStickyHoverStyle(osdonate) {
    'use strict';
    //declaring selectors
    var parentOfOSDonate = jQuery(osdonate).parent('div'),
        OSDonate = jQuery(osdonate);

    //moving header text inside of #osdonatesticky
    OSDonate.prev().prependTo(OSDonate);

    parentOfOSDonate.css({
        'visibility': 'hidden',
        'margin': 0,
        'padding': 0,
        'min-height': 0,
        'border': 0
    });
    OSDonate.attr('style', function () {
        return jQuery(osdonate).data(osdonate);
    });
}
function disableStickyHoverStyle(osdonate) {
    'use strict';
    //declaring selectors
    var parentOfOSDonate = jQuery(osdonate).parent('div'),
        OSDonate = jQuery(osdonate);

    //moving header text back out of #osdonatesticky
    var headerText = jQuery(osdonate + ' h3').detach();
    parentOfOSDonate.prepend(headerText);

    parentOfOSDonate.attr('style', '');
    OSDonate.attr('style', '');
}

jQuery(document).ready(function () {
    'use strict';
    var i;
    //if there is more than one sticky option
    if (jQuery('.osdonate-sticky-hover').length > 1) {
        i = 0;
        jQuery('.osdonate-sticky-hover').each(function () {
            if (i > 0) { jQuery(this).attr('id', 'osdonatesticky' + i); }
            var osdonate = (i > 0 ? '#osdonatesticky' + i : '#osdonatesticky');

            jQuery(osdonate).data(osdonate, jQuery(osdonate).attr('style'));

            i += 1;
        });

    } else {
        //if only one osdonate (saving if browser resized)
        jQuery('#osdonatesticky').data('#osdonatesticky', jQuery('#osdonatesticky').attr('style'));
    }

    //in case there is multiple .osdonate-sticky-hover options
    i = 0;
    jQuery('.osdonate-sticky-hover').each(function () {
        var osdonate = (i > 0 ? '#osdonatesticky' + i : '#osdonatesticky');

        //checking first without resize
        if (jQuery(window).width() <= 768) {
            disableStickyHoverStyle(osdonate);
        } else {
            setStickyHoverStyle(osdonate);
        }
        i += 1;
    });

    //when window resizes
    jQuery(window).resize(function () {
        i = 0;
        jQuery('.osdonate-sticky-hover').each(function () {
            var osdonate = (i > 0 ? '#osdonatesticky' + i : '#osdonatesticky');
            if (jQuery(window).width() <= 768) {
                disableStickyHoverStyle(osdonate);
            } else {
                setStickyHoverStyle(osdonate);
            }
            i += 1;
        });
    });
});
