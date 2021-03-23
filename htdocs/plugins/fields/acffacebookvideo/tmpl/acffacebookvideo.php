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

if (!$videoURL = $field->value)
{
	return;
}

// Setup Variables
$id              = 'acf_fv_' . $item->id . '_' . $field->id;
$width           = (int) $fieldParams->get('width', null);
$width  		 = $width > 0 ? $width : 'auto';
$allowfullscreen = $fieldParams->get('allowfullscreen', 'true');
$autoplay        = $fieldParams->get('autoplay', 'false');
$showtext        = $fieldParams->get('includepost', 'false');
$showcaptions    = $fieldParams->get('showcaptions', 'true');
$languageTag     = str_replace('-', '_', JFactory::getLanguage()->getTag());

// Output
$buffer = '
	<!-- Facebook SDK for JavaScript -->
	<div id="fb-root"></div>
	<script>
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/' . $languageTag . '/sdk.js#xfbml=1&version=v2.6";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, "script", "facebook-jssdk"));
	</script>

	<!-- Embedded video player code -->
	<div id="' . $id . '" class="fb-video" 
		data-href="' . $videoURL . '" 
		data-width="' . $width . '" 
		data-show-text="' . $showtext . '" 
		data-show-captions="' . $showcaptions . '" 
		data-allowfullscreen="' . $allowfullscreen . '" 
		data-autoplay="' . $autoplay . '">
	</div>
';

echo $buffer;
