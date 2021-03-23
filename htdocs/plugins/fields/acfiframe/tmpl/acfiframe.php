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

if (!$iframe = $field->value)
{
	return;
}

$buffer = '';

// Setup Variables
$id        = 'acf_iframe_' . $item->id . '_' . $field->id;
$height    = $fieldParams->get('iframeheight', '500px');
$scrolling = $fieldParams->get('iframescrolling', 'auto');
$params    = $fieldParams->get('iframeparams', 'auto');
$async     = (bool) $fieldParams->get('iframeasync', false);

// Output
$content = '
	<iframe
		src="' . $iframe . '"
		width="100%"
		height="' . $height . '"
		scrolling="' . $scrolling . '"
		' . $params . '
		frameborder="0"
		allowtransparency="true"
		allowfullscreen>
	</iframe>
';

$buffer .= '<div class="acf_iframe_wrapper" id="' . $id . '">';

//if not async
if (!$async) {
	$buffer .= $content;
}
 
$buffer .= '</div>';

echo $buffer;

// if async
// We can't use addScriptDeclaration() here due to a bug which is fires twices the same event.
// https://github.com/joomla/joomla-cms/issues/21004
if ($async) {
	echo '<script>
		jQuery(function($) {
			var container  = $("#' . $id . '.acf_iframe_wrapper");
			var content    = ' . json_encode($content) .';

			$(window).on("load", function() {
				container.html(content);
			})
		});</script>';
}