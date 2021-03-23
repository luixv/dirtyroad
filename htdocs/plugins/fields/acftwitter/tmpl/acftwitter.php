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

// Grab the widget type
$widget_type = $fieldParams->get('widget_type', true);

$file = __DIR__ . '/plugins/' . $widget_type . '.php';
if(file_exists($file))
{
	// Display selected widget
	require $file;
}