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

if (!$videoID = $field->value)
{
	return;
}

if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $field->value, $match))
{
	$videoID = $match[1];
}

// Setup Variables
$id                = 'acf_yt_' . $item->id . '_' . $field->id;
$size              = $fieldParams->get('size', 'fixed');
$width             = $fieldParams->get('width', '480');
$height            = $fieldParams->get('height', '270');
$width_height_atts = ($size == 'fixed') ? 'width="' . $width . '" height="' . $height . '"' : '';
$autoplay_att 	   = '';
$query             = $videoID;



// Output
$buffer = '
	<iframe
		id="' . $id . '"
		class="acf_yt"
		' . $width_height_atts . '
		src="https://www.youtube.com/embed/' . $query . '"
		frameborder="0"
		' . $autoplay_att . '
		allowfullscreen>
	</iframe>
';

if ($size == 'responsive')
{
    JHtml::stylesheet('plg_system_acf/responsive_embed.css', ['relative' => true, 'version' => 'auto']);
	$buffer = '<div class="acf-responsive-embed">' . $buffer . '</div>';
}

echo $buffer;
