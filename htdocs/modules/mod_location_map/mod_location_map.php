<?php
/**
 * @version 3.0
 * @package LocationMap
 * @copyright 2009 OrdaSoft
 * @author 2009 Sergey Brovko-OrdaSoft
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @description Location map for Joomla 3.0
*/
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$link = modOSLocationHelper::getLink($params);

require JModuleHelper::getLayoutPath('mod_location_map', $params->get('layout'));
