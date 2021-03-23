<?php
/**
 * @package   AllediaFreeDefaultFiles
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright Copyright (C) Open Sources Training, LLC, All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Form field to show an advertisement for the pro version
 */
class JFormFieldBase extends JFormField
{
    /**
     * @var bool
     */
    protected $fromInstaller = false;

    public function __set($property, $value = null)
    {
        switch ($property) {
            case 'fromInstaller':
                $this->fromInstaller = (bool)$value;
                break;

            default:
                parent::__set($property, $value);
        }
    }

    protected function getInput()
    {
        return '';
    }

    protected function getStyle($path)
    {
        $html = '';

        if (file_exists($path)) {
            $style = file_get_contents($path);
            $html  .= '<style>' . $style . '</style>';
        }

        return $html;
    }

    protected function getLabel()
    {
        return '';
    }

    public function getInputUsingCustomElement(SimpleXMLElement $element)
    {
        $this->element = $element;
        $this->setup($element, null);

        return $this->getInput();
    }
}
