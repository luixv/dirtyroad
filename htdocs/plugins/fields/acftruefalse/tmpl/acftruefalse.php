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

$fieldValue = $field->value;

if ($fieldValue == '')
{
	return;
}

echo ($fieldValue) ? $fieldParams->get('true', JText::_('JTRUE')) : $fieldParams->get('false', JText::_('JFALSE'));