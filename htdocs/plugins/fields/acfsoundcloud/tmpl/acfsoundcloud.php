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

if (!$field->value)
{
	return;
}

// Support old value
if (is_numeric($field->value))
{
	$value = (object) [
		'id' => $field->value,
		'playlist' => false
	];
} else 
{
	$value = json_decode($field->value);
}

if (empty($value->id))
{
	return;
}

// Setup Variables
$width        = $fieldParams->get('width', '100%');
$height       = $fieldParams->get('height', '166');
$mode         = (bool) $value->playlist ? 'playlists' : 'tracks';
$query		  = $value->id;



// Output
$buffer = '
	<iframe
		src="https://w.soundcloud.com/player/?url=https://api.soundcloud.com/' . $mode . '/' . $query . '"
		width="' . $width . '"
		height="' . $height . '"
		scrolling="no"
		frameborder="0">
	</iframe>
';

echo $buffer;