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
$plugin = JPluginHelper::getPlugin('fields', 'acfgooglemap');
$params = new JRegistry($plugin->params);

// Setup Variables
$mapID  = 'acf_map_' . $item->id . '_' . $field->id;
$coords = explode(",", $coords);

if (!isset($coords[1]))
{
	return;
}

$width  = $fieldParams->get('width', '400px');
$height = $fieldParams->get('height', '350px');
$zoom   = $fieldParams->get('zoom', '16');

// Add Media Files
JFactory::getDocument()->addScript('//maps.googleapis.com/maps/api/js?key=' . $params->get("key"));
JHtml::script('plg_fields_acfgooglemap/gmaps.js', ['relative' => true, 'version' => 'auto']);

// Output
$buffer = '
	<style>
		#' . $mapID . ' {
			width: ' . $width . ';
			height: ' . $height . ';
		}
	</style>
	
	<div id="' . $mapID . '"></div>

	<script>
		' . $mapID . ' = new GMaps({
			div: "#' . $mapID . '",
			lat: ' . $coords[0] . ',
			lng: ' . $coords[1] . ',
			zoom: ' . $zoom . '
		});	

		' . $mapID . '.addMarker({
			lat: ' . $coords[0] . ',
			lng: ' . $coords[1] . '
		});
	</script>
';

echo $buffer;
