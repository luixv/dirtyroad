<?php
/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2019 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');

class JFormFieldACFSoundCloud extends JFormField
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
            // Support old values
            if (is_numeric($this->value))
            {
                $this->value = [
                    'id' => $this->value
                ];
            } else 
            {
                // Guess here is the JSON string from 'default' attribute
                $this->value = json_decode($this->value, true);
            }
        }
        
		return true;
	}
    
    protected function getInput()
    {
        $form_source = new SimpleXMLElement('
            <form>
                <fieldset name="acfsoundcloud">
                    <field name="id" type="text"
                        label="ACF_SOUNDCLOUD_ID"
                        description="ACF_SOUNDCLOUD_ID_DESC"
                        hint="341546259"
                        required="' . $this->required . '"
                    />
                    <field name="playlist" type="nrtoggle" 
                        label="ACF_SOUNDCLOUD_PLAYLIST"
                        description="ACF_SOUNDCLOUD_PLAYLIST_DESC"
                    />
                </fieldset>
            </form>
        ');

        $control  = $this->name;
        $formname = 'acfsoundcloud.' . str_replace(['jform[', '[', ']'], ['', '.', ''], $control);

        $form = JForm::getInstance($formname, $form_source->asXML(), ['control' => $control]);
        $form->bind($this->value);

        return $form->renderFieldset('acfsoundcloud');
    }
}