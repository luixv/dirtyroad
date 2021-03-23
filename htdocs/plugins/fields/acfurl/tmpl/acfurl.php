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

use Joomla\Registry\Registry;

if (!$url = $field->value)
{
	return;
}

$url = new Registry($url);

if (empty($url['url']))
{
	return;
}

$id  = 'acf_url_' . $item->id . '_' . $field->id;

// Output
$CSSClass = trim('acf_url ' . $fieldParams->get('url_class'));
$buffer = '<a id="' . $id . '" href="' . $url->get('url') . '" class="' . $CSSClass . '"';

// Set target attribute
if ($url->get('target') == 'new_tab')
{
	$buffer .= ' target="_blank"';
}

if ($url->get('target') == 'popup')
{
	$onclick = $fieldParams->get('onclick');
	$new_window_code = 'window.open(\'' . $url->get('url') . '\', \'_blank\', \'width=800,height=600\'); return false;';
	$fieldParams->set('onclick', $onclick . $new_window_code);
}

// Set the onClick handler - Do not remove this block from Free version as it's required by the target property.
$onclick = $fieldParams->get('onclick');
if (!empty($onclick)) {
	$buffer .= ' onclick="' . $onclick . '"';
}



$buffer .= '>' . $url->get('text', $fieldParams->get('default_text')) . '</a>';

echo $buffer;