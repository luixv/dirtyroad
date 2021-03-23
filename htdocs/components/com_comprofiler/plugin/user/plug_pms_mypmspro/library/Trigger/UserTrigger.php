<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

namespace CB\Plugin\PMS\Trigger;

use CB\Database\Table\UserTable;
use CBLib\Registry\GetterInterface;
use CB\Plugin\PMS\PMSHelper;
use CB\Plugin\PMS\UddeIM;
use CB\Plugin\PMS\Table\MessageTable;
use CB\Plugin\PMS\Table\ReadTable;

defined('CBLIB') or die();

class UserTrigger extends \cbPluginHandler
{

	/**
	 * Called when a user is deleted to clean up their private messages
	 *
	 * @param UserTable $user
	 * @param bool      $success
	 */
	public function deleteMessages( $user, $success )
	{
		global $_CB_database;

		if ( UddeIM::isUddeIM() ) {
			UddeIM::deleteMessages( $user, $success );
			return;
		}

		if ( ! PMSHelper::getGlobalParams()->get( 'pmsDelete', false, GetterInterface::BOOLEAN ) ) {
			return;
		}

		$sent				=	PMSHelper::getGlobalParams()->get( 'pmsDeleteSent', false, GetterInterface::BOOLEAN );
		$received			=	PMSHelper::getGlobalParams()->get( 'pmsDeleteRecieved', true, GetterInterface::BOOLEAN );

		if ( $sent || $received ) {
			$query			=	"SELECT *"
							.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_plugin_messages' );
			if ( $sent && $received ) {
				$query		.=	"\n WHERE ( " . $_CB_database->NameQuote( 'from_user' ) . " = " . $user->get( 'id', 0, GetterInterface::INT )
							.	" OR " . $_CB_database->NameQuote( 'to_user' ) . " = " . $user->get( 'id', 0, GetterInterface::INT ) . " )";
			} elseif ( $sent ) {
				$query		.=	"\n WHERE " . $_CB_database->NameQuote( 'from_user' ) . " = " . $user->get( 'id', 0, GetterInterface::INT );
			} elseif ( $received ) {
				$query		.=	"\n WHERE " . $_CB_database->NameQuote( 'to_user' ) . " = " . $user->get( 'id', 0, GetterInterface::INT );
			}
			$_CB_database->setQuery( $query );
			$messages		=	$_CB_database->loadObjectList( null, '\CB\Plugin\PMS\Table\MessageTable', array( $_CB_database ) );

			/** @var MessageTable[] $messages */
			foreach ( $messages as $message ) {
				$message->delete();
			}
		}

		$query				=	"SELECT *"
							.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_plugin_messages_read' )
							.	"\n WHERE " . $_CB_database->NameQuote( 'to_user' ) . " = " . $user->get( 'id', 0, GetterInterface::INT );
		$_CB_database->setQuery( $query );
		$dates				=	$_CB_database->loadObjectList( null, '\CB\Plugin\PMS\Table\ReadTable', array( $_CB_database ) );

		/** @var ReadTable[] $dates */
		foreach ( $dates as $date ) {
			$date->delete();
		}
	}
}