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

if (!$email = $field->value)
{
	return;
}

// Check if valid email is given
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    return;
}

// get size, if we have a rounded avatar and default icon
$size = $fieldParams->get('size', '100');
$rounded_avatar = $fieldParams->get('rounded_avatar', false);
$rounded_avatar_att = ($rounded_avatar) ? ' style="border-radius:100%;"' : '';
$default = 'identicon';

// build img element
$buffer = '<img src="https://www.gravatar.com/avatar/' . md5( strtolower( trim( $email ) ) ) . '?d=' . ( $default ) . '&s=' . $size . '"' . $rounded_avatar_att . ' />';

echo $buffer;