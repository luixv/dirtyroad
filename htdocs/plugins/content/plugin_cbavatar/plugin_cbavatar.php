<?php
/**********************************************************************************
 * plugCBAvatar
 * @version 3.0
 * @copyright (c) 2013 Surrey Community Music Society
 * @license GNU GPL (http://www.gnu.org/licenses/gpl.txt)
 * @author Surrey Community Music Society www.surreymusic.org - email cliff@surreymusic.org.uk
 * Additional coding by http://www.joomtut.com
 * Version History:
 *
 * 3.0: Release version 
 **********************************************************************************/
defined('_JEXEC') or die;

/**
* Uses the Community Builder User Profile picture in a Joomla content item
*
* <b>Usage:</b>
* {cbavatar}~Insert CB username here~{/cbavatar}
*
*/
class plgContentPlugin_CBavatar extends JPlugin
{
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{

		// expression to search for
		$regex = "#{cbavatar(=(.*?))?}(.*?){/cbavatar}#s";

		// perform the replacement
		$article->text = preg_replace_callback( $regex, 'self::plugCBavatar_replacer', $article->text );
		return true;
	}

	function plugCBavatar_replacer( &$matches ) 
	{

//	echo "<pre>"; print_r ( $matches ); echo "</pre>";

		$username = $matches[2];
		$text = $matches[3];

		if (empty($username)) {
			$username = $text;
		}

		// Find the user id
		$database = JFactory::getDBO();
		$database->setQuery("SELECT id FROM #__users WHERE username='$username'");
		$userid = $database->loadResult();

		if ($userid) {
			$database = JFactory::getDBO();
			$database-> setQuery("SELECT avatar FROM #__comprofiler WHERE user_id = $userid AND avatarapproved = '1'");
			$avatar = $database->loadResult();
        
			if (!empty($avatar)) {
			}

			// Replace username with profile pic in content with link to user profile. Alt=username, Title=username, float=left. Margins are left: 0px, top: 5px, bottom: 5px, right: 5px and width of 75px.
			
			$text = str_replace($text, "<a href=index.php?option=com_comprofiler&task=userProfile&user=$userid><img src=/images/comprofiler/$avatar alt='$username' title='$username' style='float: left; margin-left:0px; margin-top:5px; margin-bottom:5px; margin-right:5px;  width:75px'></a>", $text);
		}
		return $text;
	}
}