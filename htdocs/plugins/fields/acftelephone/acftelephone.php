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

class PlgFieldsACFTelephone extends ACF_Field
{
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
	protected $hint = '';

	/**
	 * Transforms the field into a DOM XML element and appends it as a child on the given parent.
	 *
	 * @param   stdClass    $field   The field.
	 * @param   DOMElement  $parent  The field node parent.
	 * @param   JForm       $form    The form.
	 *
	 * @return  DOMElement
	 *
	 * @since   3.7.0
	 */
	public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form)
	{
		if (!$fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form))
		{
			return $fieldNode;
		}

		// Set custom class and type
		$fieldNode->setAttribute('class', $this->class);
		$fieldNode->setAttribute('type', 'tel');
		
		// Get the Input Mask entered on Field settings
		$tel_mask = $field->fieldparams->get('tel_mask');
		
		if (isset($tel_mask)) {

			// Add `acf-input-mask` class on this field as it has an input mask
			$fieldNode->setAttribute('class', $this->class . ' acf-input-mask');
			
			JHtml::script('plg_fields_acftelephone/inputmask.js', ['relative' => true, 'version' => 'auto']);
			JHtml::script('plg_fields_acftelephone/script.js', ['relative' => true, 'version' => 'auto']);
			
			$doc = JFactory::getDocument();
			$inputMasks = $doc->getScriptOptions('acf_telephone_masks');
			$inputMasks[] = $tel_mask;
			$doc->addScriptOptions('acf_telephone_masks', $inputMasks);
		}

		return $fieldNode;
	}
}
