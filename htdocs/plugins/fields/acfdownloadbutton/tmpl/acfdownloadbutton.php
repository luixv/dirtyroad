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

$file = $field->value;

if (!$file || $file == '-1')
{
	return;
}

// Setup Variables
$class     = $fieldParams->get('class');
$label     = $fieldParams->get('label', 'ACF_DOWNLOADBUTTON_DOWNLOAD');
$directory = ltrim($fieldParams->get('directory', 'images'), '/');
$directory = rtrim($directory, '/');
$filepath  = JURI::root() . $directory . '/' . $file;

// Output
$buffer = '<a href="' . $filepath . '" class="' . $class . '" download>' . JText::_($label) . '</a>';

echo $buffer;