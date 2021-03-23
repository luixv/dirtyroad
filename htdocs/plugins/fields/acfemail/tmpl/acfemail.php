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

$email = $field->value;

if (empty($email))
{
	return;
}

// Get field params
$cloak_support = $fieldParams->get('cloak_support', true);
$display_as    = $fieldParams->get('display_as', 'link');

// We have selected cloak support
if ($cloak_support) {
	if ($display_as == 'text') {
		// Cloak it but display it as text
		$buffer = JHtml::_('email.cloak', $email, 0);
	} else {
		// Cloak it but display it as a link
		$buffer = JHtml::_('email.cloak', $email);
	}
} else {
	if ($display_as == 'text') {
		$buffer = $email;
	} else {
		$buffer = '<a href="mailto:' . $email . '">' . $email . '</a>';
	}
}

echo $buffer;