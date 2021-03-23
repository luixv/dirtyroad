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

if (!$page_url = $field->value)
{
	return;
}

// Setup Variables
$id	    = 'acf_facebook_' . $item->id . '_' . $field->id;
$width  = str_replace('px', '', $fieldParams->get('page.width', 400));
$height = str_replace('px', '', $fieldParams->get('page.height', 214));
$tabs   = $fieldParams->get('page.tabs', '');

// if tabs is an array, it means we have selected more than 1 tab, separate them via comma
if (is_array($tabs))
{
	$tabs = implode(', ', $tabs);
}

$hide_cover_photo  = $fieldParams->get('page.hide_cover_photo', false);
$small_header	   = $fieldParams->get('page.small_header', false);
$show_friend_faces = $fieldParams->get('page.show_friend_faces', true);
$hide_cta 		   = $fieldParams->get('page.hide_cta', false);

// Output
echo '
	<iframe src="https://www.facebook.com/plugins/page.php?href=' . $page_url . '&tabs=' . $tabs . '&width=' . $width . '&height=' . $height . '&small_header=' . $small_header . '&adapt_container_width=true&hide_cover=' . $hide_cover_photo . '&show_facepile=' . $show_friend_faces . '&hide_cta=' . $hide_cta . '&appId"
		width="' . $width . '"
		height="' . $height . '"
		style="border:none;overflow:hidden;"
		scrolling="no"
		frameborder="0"
		allowTransparency="true"
		allow="encrypted-media">
	</iframe>';