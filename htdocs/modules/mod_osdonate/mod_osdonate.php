<?php
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

use Alledia\OSDonate\Free\Helper;
use Joomla\Utilities\ArrayHelper;

// no direct access
defined('_JEXEC') or die();

require_once 'include.php';

//load css
$document = JFactory::getDocument();
JHtml::_('stylesheet', 'mod_osdonate/style.css', array('relative' => true));

/*
 * Return the selected paypal language from the module parameters
 * substr returns part of the string.
 * In this case substr starts at the first character and returns 1 more (2 total)
 * e.g. substr(en_US, 3, 2); //will return "US"
 * instead of using substr, we could have set the local values to just the lower case code.
 * e.g. "en_US" could be "US"
 */
$langSite = substr($params->get('locale'), 3, 2) ?: 'US';

$introtext = $params->get('show_text', 1) && $params->get('intro_text')
    ? sprintf('<p class="osdonate-introtext">%s</p>' . "\n", $params->get('intro_text', ''))
    : '';

$amountAttribs = array(
    'type'  => 'hidden',
    'name'  => 'amount',
    'value' => $params->get('amount')
);
if (!$params->get('show_amount')) {
    $amountLine = sprintf("<input %s/>\n", ArrayHelper::toString($amountAttribs));

} else {
    $amountAttribs = array_replace(
        $amountAttribs,
        array(
            'type'      => 'text',
            'size'      => 4,
            'maxlength' => 10,
            'class='    => 'osdonate-amount'
        )
    );

    $amountLine = sprintf(
        "%s<br><input %s/>\n",
        JText::_($params->get('amount_label')),
        ArrayHelper::toString($amountAttribs)
    );
}

$currencies = array_map('trim', array_filter(explode(',', $params->get('currencies'))));

$availableCurrencies = array(
    'EUR',
    'USD',
    'GBP',
    'BRL',
    'CHF',
    'AUD',
    'HKD',
    'CAD',
    'JPY',
    'NZD',
    'SGD',
    'SEK',
    'DKK',
    'PLN',
    'NOK',
    'HUF',
    'CZK',
    'ILS',
    'MXN'
);

// Filter out any invalid currencies
$currencies = array_values(array_intersect($currencies, $availableCurrencies));


$currencyCount = count($currencies);
if ($currencyCount === 0) {
    $amountLine = sprintf(
        '<p class="error">%s<br/>%s</p>',
        'Error - no currencies selected!',
        'Please check the backend parameters!'
    );

    $fe_c = '';

} elseif ($currencyCount === 1) {
    $fe_c = sprintf(
        '<input type="hidden" name="currency_code" value="%s"/>%s' . "\n",
        $currencies[0],
        $params->get('show_amount', 1) ? ('&nbsp;' . $currencies[0]) : ''
    );

} elseif ($currencyCount > 1) {
    if ($params->get('show_amount', 1)) {
        $currencyOptions = array_map(
            function ($row) {
                return JHtml::_('select.option', $row);
            },
            $currencies
        );

        $fe_c = JHtml::_('select.genericlist', $currencyOptions, 'currency_code') . "\n";

    } else {
        $fe_c = '<input type="hidden" name="currency_code" value="' . $currencies[0] . '" />' . "\n";
    }
}

$returnMenuListIds = array(
    $params->get('return', ''),
    $params->get('cancel_return', '')
);

foreach ($returnMenuListIds as $index => $itemId) {
    // Check if the $itemId is a number or not (legacy params)
    if (is_numeric($itemId)) {
        // A menu item
        $menu = $app->getMenu();
        $link = $menu->getItem($itemId)->link;
    } else {
        // String, probably a relative or external URL
        $link = $itemId;
    }

    if (JUri::isInternal($link)) {
        $linkOfMenuItems[$index] = Helper::stripDoubleSlashes(JUri::base()) . JRoute::_('index.php?Itemid=' . $itemId);

    } else {
        $linkOfMenuItems[$index] = $link;
    }
}

$target = $params->get('open_new_window', 1) ? 'target="paypal"' : '';

$widthOfModule             = $params->get('width_of_sticky_hover', 200);
$use_sticky_hover          = $params->get('use_sticky_hover', '0');
$horizontal_reference_side = $params->get('horizontal_reference_side');
$horizontal_distance       = $params->get('horizontal_distance');
$vertical_reference_side   = $params->get('vertical_reference_side');
$vertical_distance         = $params->get('vertical_distance');
$sticky                    = '';

if ($use_sticky_hover == 1) {
    JHtml::_('script', 'mod_osdonate/stickyHoverOptions.js', array('relative' => true));

    $stickyStyles  = array(
        $horizontal_reference_side . ':' . $horizontal_distance . 'px',
        $vertical_reference_side . ':' . $vertical_distance . 'px',
        'width:' . $widthOfModule . 'px',
        'z-index:1000',
        'visibility:visible'
    );
    $stickyAttribs = array(
        'id'    => 'osdonatesticky',
        'class' => 'osdonate-sticky-hover',
        'style' => join(';', $stickyStyles)
    );

    $sticky = sprintf('<div  %s>', ArrayHelper::toString($stickyAttribs));

} else {
    $sticky .= '<div id="osdonatestatic">';
}

require JModuleHelper::getLayoutPath('mod_osdonate', $params->get('layout', 'default'));
