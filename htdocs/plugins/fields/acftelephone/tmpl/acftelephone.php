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

if (!$telephone = $field->value)
{
	return;
}

$click_to_call = (bool) $fieldParams->get('click_to_call', true);

$buffer = '';

// Output
if ($click_to_call)
{
	$buffer = '<a href="tel:' . $telephone . '">' . $telephone . '</a>';
}
else
{
	$buffer = $telephone;
}

echo $buffer;
