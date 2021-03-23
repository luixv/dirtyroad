<?php
/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2019 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');

class JFormFieldUrlAdvanced extends JFormField
{
	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   3.6
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		if (!parent::setup($element, $value, $group))
		{
			return false;
        }
        
		if ($this->value && is_string($this->value))
		{
			// Guess here is the JSON string from 'default' attribute
			$this->value = json_decode($this->value, true);
        }
        
		return true;
	}
    
    protected function getInput()
    {
        $form_source = new SimpleXMLElement('
            <form>
                <fieldset name="url">
                    <field name="text" type="text"
                        label="ACF_URL_TEXT"
                        description="ACF_URL_TEXT_DESC"
                        hint="ACF_URL_TEXT_DESC"
                        required="' . $this->required . '"
                    />
                    <field name="url" type="url"
                        label="NR_URL"
                        description="ACF_URL_VALUE_DESC"
                        hint="ACF_URL_VALUE_DESC"
                        required="' . $this->required . '"
                    />
                    <field name="target" type="list" 
                        label="ACF_URL_TARGET"
                        description="ACF_URL_TARGET_DESC"
                        required="' . $this->required . '">
                        <option value="same_tab">ACF_URL_TARGET_SAME_TAB</option>
                        <option value="new_tab">ACF_URL_TARGET_NEW_TAB</option>
                        <option value="popup">ACF_URL_TARGET_POPUP</option>
                    </field>
                </fieldset>
            </form>
        ');

        $control  = $this->name;
        $formname = 'urladvanced.' . str_replace(['jform[', '[', ']'], ['', '.', ''], $control);

        $form = JForm::getInstance($formname, $form_source->asXML(), ['control' => $control]);
        $form->bind($this->value);

        return $form->renderFieldset('url');
    }
}