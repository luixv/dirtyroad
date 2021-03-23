<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once(dirname(__FILE__) . '/helper.php');

$data = ModPhotoStationHelper::getData($params);
require JModuleHelper::getLayoutPath('mod_photostation');
