<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access to this file
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldACFOSM extends JFormFieldText
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	public function getInput()
	{
		// Setup properties
		$this->width    	  		= $this->element['width'] ? $this->element['width'] : '500px';
		$this->height   	  		= $this->element['height'] ? $this->element['height'] : '400px';
		$this->zoom     	  		= $this->element['zoom'] ? $this->element['zoom'] : 4;
		$this->scale				= $this->element['scale'];
		$this->marker_image			= !empty($this->element['marker_image']) ? $this->element['marker_image'] : 'media/plg_fields_acfosm/img/marker.png';
		$this->show_address_input   = $this->element['show_address_input'] == '1';
		$decoded_value 				= json_decode($this->value, true);
		$coords_value				= (is_array($decoded_value) && isset($decoded_value['coordinates']) && !empty($decoded_value['coordinates'])) ? $decoded_value['coordinates'] : ((!empty($this->value) && strpos($this->value, '{') === false) ? $this->value : $this->element['default_coords']);
		$resetButtonClass			= empty($coords_value) ? ' is-hidden' : '';
		$this->component_classes	= '';

		$this->name .= '[coordinates]';
		$this->value = $coords_value;
		
		$lang = JFactory::getLanguage();
		$lang_tag = $lang->getTag();
		
		$doc = JFactory::getDocument();
		$doc->addScriptOptions('com_acf_osm_admin', [
			'lang_tag' => $lang_tag
		]);
		
		JText::script('ACF_OSM_ADDRESS_DESC');

		// Add scripts to DOM
		JHtml::stylesheet('plg_fields_acfosm/acf_osm_map_admin.css', ['relative' => true, 'version' => 'auto']);
		JHtml::stylesheet('https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.0.1/css/ol.css');
		JHtml::script('https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.0.1/build/ol.js');
		
		// load geocoder only if we need to display the address text input
		if ($this->show_address_input)
		{
			JHtml::stylesheet('https://cdn.jsdelivr.net/npm/ol-geocoder@latest/dist/ol-geocoder.min.css');
			JHtml::script('https://cdn.jsdelivr.net/npm/ol-geocoder');

			$this->component_classes = ' padding-top';
		}
		JHtml::script('plg_fields_acfosm/acf_osm_map.js', ['relative' => true, 'version' => 'auto']);
		JHtml::script('plg_fields_acfosm/acf_osm_map_admin.js', ['relative' => true, 'version' => 'auto']);

		$coordsInput = $this->getRenderer($this->layout)->render($this->getLayoutData());

		$coords = $coords_value ? explode(',', $coords_value) : [0, 0];

		$html = 
			'<div class="nr-address-component' . $this->component_classes . '"
				id="nr_' . $this->id . '_map_wrapper"
				data-geocoder="' . $this->show_address_input . '">
				<div id="' . $this->id . '_map"
					class="osm_map_item nr-address-map"
					data-lat="' . trim($coords[0]) . '"
					data-long="' . trim($coords[1]) . '"
					data-scale="' . $this->scale . '"
					data-marker-image="' . $this->marker_image . '"
					data-zoom="' . $this->zoom . '">
				</div>';
		$html .= '<div class="osm-field-settings">';
			$html .= '<div class="control-group acf-map-coordinates-setting' . $resetButtonClass . '">';
				$html .= '<label class="control-label" for="' . $this->id . '_coords_input">' . JText::_('ACF_OSM_COORDINATES_LABEL') . '</label>';
				$html .= '<div class="controls acf-coords-wrapper">';
				$html .= $coordsInput;
				$html .= '<a href="#" class="acf_osm_map_reset_btn' . $resetButtonClass . '" title="' . JText::_('ACF_OSM_CLEAR_BUTTON_TITLE') . '">' . JText::_('ACF_OSM_CLEAR_BUTTON_TEXT') . '</a>';
				$html .= '</div>';
			$html .= '</div>';
			
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Method to get the data to be passed to the layout for rendering.
	 *
	 * @return  array
	 */
	protected function getLayoutData()
	{
		$data = parent::getLayoutData();

		$extraData = [
			'id' => $this->id . '_coords_input',
			'class' => 'nr_address_coords',
			'readonly' => 'readonly'
		];

		return array_merge($data, $extraData);
	}
}