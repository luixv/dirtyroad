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

if (!$map_value = $field->value)
{
	return;
}

// Setup Variables
$mapID  = 'acf_osm_map_' . $item->id . '_' . $field->id;
$coords = $map_value;

if ($map_value = json_decode($map_value, true))
{
	$coords = $map_value['coordinates'];
	
}
$coords = explode(',', $coords);

if (!isset($coords[1]))
{
	return;
}

\JHtml::_('behavior.core');

$width = $fieldParams->get('width', '400px');
$height = $fieldParams->get('height', '350px');
$zoom = $fieldParams->get('zoom', 4);
$extra_atts[] = 'data-marker-image="media/plg_fields_acfosm/img/marker.png"';



// Add Media Files
JHtml::stylesheet('https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.0.1/css/ol.css');
JHtml::script('https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.0.1/build/ol.js');
JHtml::script('plg_fields_acfosm/acf_osm_map.js', ['relative' => true, 'version' => 'auto']);
JHtml::script('plg_fields_acfosm/script.js', ['relative' => true, 'version' => 'auto']);

$buffer = '<div clas="osm_map_item_wrapper"><div class="osm_map_item" id="' . $mapID . '" data-zoom="' . $zoom . '" data-lat="' . trim($coords[0]) . '" data-long="' . trim($coords[1]) . '" ' . implode(' ', $extra_atts) . ' style="width:' . $width . ';height:' . $height . ';max-width:100%;"></div>';



$buffer .= '</div>';

echo $buffer;