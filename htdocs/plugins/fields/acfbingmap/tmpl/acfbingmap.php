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

if (!$coords = $field->value)
{
	return;
}

// Get Plugin Params
$plugin = JPluginHelper::getPlugin('fields', 'acfbingmap');
$params = new JRegistry($plugin->params);

// Setup Variables
$mapID  = 'acf_bingmap_' . $item->id . '_' . $field->id;
$coords = explode(",", $coords);

if (!isset($coords[1]))
{
	return;
}

$width  = $fieldParams->get('width', '400px');
$height = $fieldParams->get('height', '350px');
$zoom   = $fieldParams->get('zoom', '16');

// Add Media Files
JHtml::script('plg_fields_acfbingmap/script.js', ['relative' => true, 'version' => 'auto']);
JFactory::getDocument()->addScript('https://www.bing.com/api/maps/mapcontrol?callback=acf_callback_bingmaps&key=' . $params->get('key'));

// Output
$buffer = '
	<div id="' . $mapID . '" class="acf_bingmap" style="width:' . $width . '; height:' . $height . ';" data-latitude="' . $coords[0] . '" data-longitude="' . $coords[1] . '" data-zoom="' . $zoom . '"></div>';

echo $buffer;
