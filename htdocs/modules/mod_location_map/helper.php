<?php
/**
 * @version 3.0
 * @package LocationMap
 * @copyright 2009 OrdaSoft
 * @author 2009 Sergey Brovko-OrdaSoft(brovinho@mail.ru)
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @description Location map for Joomla 3.0
*/
defined('_JEXEC') or die;

class modOSLocationHelper
{
  public static function checkJavaScriptIncluded($name) {
      
      $doc = JFactory::getDocument();

      foreach($doc->_scripts as $script_path=>$value){
        if(strpos( $script_path, $name ) !== false ) return true ;
      }
      return false;
  }
    
  public static function getLink(&$params)
  {
    $document = JFactory::getDocument();

    foreach ($document->_links as $link => $value)
    {
      $value = JArrayHelper::toString($value);
      if (strpos($value, 'application/'.$params->get('format').'+xml'))
      {
        return $link;
      }
    }

  }
}
