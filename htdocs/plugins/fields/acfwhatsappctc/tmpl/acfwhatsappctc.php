<?php

/**
 * @package         Advanced Custom Fields
 * @version         1.2.0 Free
 *
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2019 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

// Setup Variables
$id             	  = 'acf_whatsappctc_' . $item->id . '_' . $field->id;
$tel_number 		  = $field->value;
$prefilled_msg  	  = $fieldParams->get('prefilled_msg');
$label          	  = $fieldParams->get('label');
$require_phone_number = $fieldParams->get('require_phone_number', true);
$buffer = '';

// If the phone number we entered
// on the article editing page is empty, we do show nothing.
if (empty($tel_number))
{
	return;
}

// base url addons
$add_prefilled_msg = (!empty($prefilled_msg)) ? '?text='.urlencode($prefilled_msg) : '';

// base url
$base_url = 'https://wa.me/' . $tel_number . $add_prefilled_msg;

// link text
$link_text = (!empty($label)) ? $label : $base_url;

// final element
$buffer = '<a href="' . $base_url . '">' . $link_text . '</a>';

echo $buffer;
