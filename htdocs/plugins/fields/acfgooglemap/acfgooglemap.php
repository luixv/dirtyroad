<?php

/**
 * @package         Advanced Custom Fields
 * @version         1.2.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2020 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

JLoader::register('ACF_Field', JPATH_PLUGINS . '/system/acf/helper/plugin.php');

if (!class_exists('ACF_Field'))
{
	JFactory::getApplication()->enqueueMessage('Advanced Custom Fields System Plugin is missing', 'error');
	return;
}

class PlgFieldsACFGoogleMap extends ACF_Field
{
	/**
	 *  The validation rule will be used to validate the field on saving
	 *
	 *  @var  string
	 */
	protected $validate = 'NRCoordinates';

	/**
	 *  Field's Class
	 *
	 *  @var  string
	 */
	protected $class = 'input-xlarge';
	
	/**
	 *  Field's Hint Description
	 *
	 *  @var  string
	 */
	protected $hint = '36.4891506,27.287723';

	/**
	 * The form event. Load additional parameters when available into the field form.
	 * Only when the type of the form is of interest.
	 *
	 * @param   JForm     $form  The form
	 * @param   stdClass  $data  The data
	 *
	 * @return  void
	 *
	 * @since   3.7.0
	 */
	public function onContentPrepareForm(JForm $form, $data)
	{
		// When editing a new and unsaved field the $data variable is passed as an array for a reason.
		$data = is_array($data) ? (object) $data : $data;

		// Make sure we are manipulating the right field.
		if (!isset($data->type) || $data->type != $this->_name)
		{
			return;
		}

		$key = $this->params->get('key');
		// Display a warning message to set the API key if empty
		if (empty($key))
		{
			$extensionID = NRFramework\Functions::getExtensionID('acfgooglemap', 'fields');
			$backEndURL  = 'index.php?option=com_plugins&task=plugin.edit&extension_id=' . $extensionID;
			$url = JURI::base() . $backEndURL;

			JFactory::getApplication()->enqueueMessage(JText::sprintf('ACF_GOOGLEMAP_API_KEY_WARNING', $url), 'warning');
		}

		return parent::onContentPrepareForm($form, $data);
	}
}
