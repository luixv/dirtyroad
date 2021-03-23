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

$qrcode_text = $field->value;

if ($qrcode_text == '')
{
	return;
}

// QR Code Label to be used as `alt`
$label = $field->label;

// size, color and bg color
$size	  = $fieldParams->get('size', '100');
$size	  = str_replace('px', '', $size);
$color	  = ltrim($fieldParams->get('color', '#000000'), '#');
$bgcolor  = ltrim($fieldParams->get('bgcolor', '#ffffff'), '#');

// create size, ex. 50x50
$size_att = $size . 'x' . $size;

$buffer = '<img src="http://api.qrserver.com/v1/create-qr-code/?data=' . $qrcode_text . '&size=' . $size_att . '&color=' . $color . '&bgcolor=' . $bgcolor . '&format=png" alt="' . $label . '" />';

echo $buffer;