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

// Prepare source
$audios = ACFHelper::getFileSources($field->value, array('mp3', 'wav','ogg'));

if (!is_array($audios) || count($audios) == 0)
{
	return;
}

// Get first audio file only for now. Multiple audios will be supported in the future.
$audio = $audios[0];

// Setup Variables
$id       = 'acf_html5audio_' . $item->id . '_' . $field->id;
$preload  = $fieldParams->get('preload', 'auto');

// Prepare HTML attributes
$attributes = array_filter(array(
	$fieldParams->get('controls', true) ? 'controls' : '',
	$fieldParams->get('loop', false) ? 'loop' : '',
	$fieldParams->get('muted', false) ? 'muted' : '',
	$fieldParams->get('autoplay', false) ? 'autoplay' : ''
));

// Output
$buffer = '
	<audio id="' . $id . '" preload="' . $preload . '" controlsList="nodownload"
		' . implode(' ', $attributes) . '
		style="max-width:100%;">
		<source src="' . $audio['file'] . '" type="audio/' . $audio['ext'] . '">'
		. JText::sprintf('ACF_UNSUPPORTED_TAG', 'audio') . 
	'</audio>';

echo $buffer;
