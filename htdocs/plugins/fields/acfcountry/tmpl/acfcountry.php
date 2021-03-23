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

if (!$countries = $field->value)
{
	return;
}

if ($fieldParams->get('countrydisplay', 'name') == 'name')
{
	if (!is_array($countries))
	{
		$countries = array($countries);
	}
	
	$countries_temp = array();
	
	foreach ($countries as $c)
	{
		$countries_temp[] = \NRFramework\Countries::$map[$c];
	}

	$countries = $countries_temp;
}

echo (is_array($countries)) ? implode (', ', $countries) : $countries;