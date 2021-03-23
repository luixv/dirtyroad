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

// Prepare video Source
$videos = ACFHelper::getFileSources($field->value, array('mp4', 'webm','ogg'));

if (!is_array($videos) || count($videos) == 0)
{
	return;
}

// Get first video only for now. Multiple videos will be supported in the future.
$video = $videos[0];

// Setup Variables
$id      = 'acf_html5video_' . $item->id . '_' . $field->id;
$width   = $fieldParams->get('width', '400');
$height  = $fieldParams->get('height', 'auto');
$preload = $fieldParams->get('preload', 'auto');

// Prepare video
$attributes = array_filter(array(
	$fieldParams->get('controls', true) ? 'controls' : '',
	$fieldParams->get('loop', false) ? 'loop' : '',
	$fieldParams->get('muted', false) ? 'muted' : '',
	$fieldParams->get('autoplay', false) ? 'autoplay playsinline' : ''
));

// Output
$buffer = '
	<video id="' . $id . '" width="' . $width . '" height="' . $height . '" preload="' . $preload . '" controlsList="nodownload" 
		' . implode(' ', $attributes) . '
		style="max-width:100%;">
		<source src="' . $video['file'] . '" type="video/' . $video['ext'] . '">'
		. JText::sprintf('ACF_UNSUPPORTED_TAG', 'video') . 
	'</video>';

echo $buffer;
