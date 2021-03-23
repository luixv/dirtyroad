<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

namespace CB\Plugin\PMS\Table;

use CB\Plugin\PMS\PMSHelper;
use CBLib\Application\Application;
use CBLib\Database\Table\Table;
use CBLib\Input\Get;
use CBLib\Registry\GetterInterface;
use CBLib\Language\CBTxt;

defined('CBLIB') or die();

class MessageTable extends Table
{
	/** @var int  */
	public $id					=	null;
	/** @var int  */
	public $from_user			=	null;
	/** @var string  */
	public $from_name			=	null;
	/** @var string  */
	public $from_email			=	null;
	/** @var int  */
	public $from_system			=	null;
	/** @var int  */
	public $to_user				=	null;
	/** @var int  */
	public $reply_to			=	null;
	/** @var string  */
	public $message				=	null;
	/** @var int  */
	public $from_user_delete	=	null;
	/** @var int  */
	public $to_user_delete		=	null;
	/** @var string  */
	public $date				=	null;

	/**
	 * Table name in database
	 *
	 * @var string
	 */
	protected $_tbl			=	'#__comprofiler_plugin_messages';

	/**
	 * Primary key(s) of table
	 *
	 * @var string
	 */
	protected $_tbl_key		=	'id';

	/**
	 * @return bool
	 */
	public function check()
	{
		if ( ( ! $this->get( 'from_user', 0, GetterInterface::INT ) ) && ( ! $this->get( 'from_system', false, GetterInterface::BOOLEAN ) ) ) {
			if ( $this->get( 'from_name', null, GetterInterface::STRING ) == '' ) {
				$this->setError( CBTxt::T( 'Name not specified!' ) );

				return false;
			}

			if ( $this->get( 'from_email', null, GetterInterface::STRING ) == '' ) {
				$this->setError( CBTxt::T( 'Email Address not specified!' ) );

				return false;
			} elseif ( ! cbIsValidEmail( $this->get( 'from_email', null, GetterInterface::STRING ) ) ) {
				$this->setError( CBTxt::T( 'Email Address is not valid!' ) );

				return false;
			}
		}

		if ( $this->get( 'message', null, GetterInterface::STRING ) == '' ) {
			$this->setError( CBTxt::T( 'Message not specified!' ) );

			return false;
		}

		return true;
	}

	/**
	 * @param bool $updateNulls
	 * @return bool
	 */
	public function store( $updateNulls = false )
	{
		global $_CB_framework, $_PLUGINS;

		$new								=	( $this->get( 'id', 0, GetterInterface::INT ) ? false : true );
		$old								=	new self();

		$this->set( 'date', $this->get( 'date', Application::Database()->getUtcDateTime(), GetterInterface::STRING ) );

		if ( ! $new ) {
			$old->load( $this->get( 'id', 0, GetterInterface::INT ) );

			$integrations					=	$_PLUGINS->trigger( 'pm_onBeforeUpdateMessage', array( &$this, $old ) );
		} else {
			$integrations					=	$_PLUGINS->trigger( 'pm_onBeforeCreateMessage', array( &$this ) );
		}

		if ( in_array( false, $integrations, true ) ) {
			return false;
		}

		if ( ! parent::store( $updateNulls ) ) {
			return false;
		}

		if ( ! $new ) {
			$_PLUGINS->trigger( 'pm_onAfterUpdateMessage', array( $this, $old ) );
		} else {
			$_PLUGINS->trigger( 'pm_onAfterCreateMessage', array( $this ) );

			// Send Notification:
			$notify							=	PMSHelper::getGlobalParams()->get( 'messages_notify', 0, GetterInterface::INT );

			if ( $notify && $this->get( 'to_user', 0, GetterInterface::INT ) ) {
				if ( PMSHelper::getGlobalParams()->get( 'messages_notify_offline', false, GetterInterface::BOOLEAN ) && ( $_CB_framework->userOnlineLastTime( $this->get( 'to_user', 0, GetterInterface::INT ) ) != null ) ) {
					// Notifications are set to only be sent to offline users, but the user is online so disable notifications:
					$notify					=	0;
				} elseif ( $notify == 4 ) {
					$notifyField			=	PMSHelper::getGlobalParams()->get( 'messages_notify_field', 0, GetterInterface::INT );

					// Default notifications off encase no field was selected or the field couldn't be found:
					$notify					=	0;

					if ( $notifyField ) {
						$cbUser				=	\CBuser::getInstance( $this->get( 'to_user', 0, GetterInterface::INT ), false );
						$notifyFieldValue	=	$cbUser->getField( $notifyField, null, 'php', 'none', 'profile', 0, true );

						if ( is_array( $notifyFieldValue ) ) {
							$notify			=	array_shift( $notifyFieldValue );

							if ( is_array( $notify ) ) {
								$notify		=	implode( '|*|', $notify );
							}

							$notify			=	(int) $notify;
						}
					}
				}

				if ( ( $notify == 1 ) || ( ( $notify == 2 ) && ( ! $this->get( 'reply_to', 0, GetterInterface::INT ) ) ) || ( ( $notify == 3 ) && $this->get( 'reply_to', 0, GetterInterface::INT ) ) ) {
					$cbNotification			=	new \cbNotification();

					$savedLanguage			=	CBTxt::setLanguage( \CBuser::getUserDataInstance( $this->get( 'to_user', 0, GetterInterface::INT ) )->getUserLanguage() );

					if ( $this->get( 'reply_to', 0, GetterInterface::INT ) ) {
						$subject			=	CBTxt::T( 'You have a new private message reply' );

						if ( PMSHelper::getGlobalParams()->get( 'messages_notify_message', false, GetterInterface::BOOLEAN ) ) {
							$message		=	CBTxt::T( 'FROM_HAS_REPLIED_MESSAGE_WITH', '[from] has replied to your private message.<br /><br />[message]', array( '[from]' => $this->getFrom( 'profile' ), '[message]' => $this->getMessage() ) );
						} else {
							$message		=	CBTxt::T( 'FROM_HAS_REPLIED_MESSAGE', '[from] has replied to your private message.', array( '[from]' => $this->getFrom( 'profile' ) ) );
						}
					} else {
						$subject			=	CBTxt::T( 'You have a new private message' );

						if ( PMSHelper::getGlobalParams()->get( 'messages_notify_message', false, GetterInterface::BOOLEAN ) ) {
							$message		=	CBTxt::T( 'FROM_HAS_SENT_MESSAGE_WITH', '[from] has sent you a new private message.<br /><br />[message]', array( '[from]' => $this->getFrom( 'profile' ), '[message]' => $this->getMessage() ) );
						} else {
							$message		=	CBTxt::T( 'FROM_HAS_SENT_MESSAGE', '[from] has sent you a new private message.', array( '[from]' => $this->getFrom( 'profile' ) ) );
						}
					}

					$cbNotification->sendFromSystem( $this->get( 'to_user', 0, GetterInterface::INT ), $subject, $message, false, 1, null, null, null, array(), true, CBTxt::T( PMSHelper::getGlobalParams()->get( 'messages_notify_from_name', null, GetterInterface::STRING ) ), PMSHelper::getGlobalParams()->get( 'messages_notify_from_email', null, GetterInterface::STRING ) );

					CBTxt::setLanguage( $savedLanguage );
				}
			}
		}

		return true;
	}

	/**
	 * @param null|int $id
	 * @return bool
	 */
	public function delete( $id = null )
	{
		global $_PLUGINS;

		$integrations	=	$_PLUGINS->trigger( 'pm_onBeforeDeleteMessage', array( &$this ) );

		if ( in_array( false, $integrations, true ) ) {
			return false;
		}

		if ( ! parent::delete( $id ) ) {
			return false;
		}

		// Cleans up read states for this message:
		$query			=	"SELECT *"
						.	"\n FROM " . $this->getDbo()->NameQuote( '#__comprofiler_plugin_messages_read' )
						.	"\n WHERE " . $this->getDbo()->NameQuote( 'message' ) . " = " . $this->get( 'id', 0, GetterInterface::INT );
		$this->getDbo()->setQuery( $query );
		$dates			=	$this->getDbo()->loadObjectList( null, '\CB\Plugin\PMS\Table\ReadTable', array( $this->getDbo() ) );

		/** @var ReadTable[] $dates */
		foreach ( $dates as $date ) {
			$date->delete();
		}

		$_PLUGINS->trigger( 'pm_onAfterDeleteMessage', array( $this ) );

		return true;
	}

	/**
	 * @param string $field
	 * @return string|array
	 */
	public function getFrom( $field = 'name' )
	{
		global $_CB_framework;

		static $cache				=	array();

		$userId						=	Application::MyUser()->getUserId();
		$id							=	$this->get( 'id', null, GetterInterface::INT );

		if ( ! isset( $cache[$userId][$id] ) ) {
			$status					=	null;

			if ( $this->get( 'from_system', false, GetterInterface::BOOLEAN ) ) {
				$cbUser				=	\CBuser::getMyInstance();
				$avatar				=	CBTxt::T( PMSHelper::getGlobalParams()->get( 'messages_system_avatar', null, GetterInterface::STRING ) );

				if ( $avatar ) {
					if ( $avatar[0] == '/' ) {
						$avatar		=	$_CB_framework->getCfg( 'live_site' ) . $avatar;
					}

					switch ( PMSHelper::getGlobalParams()->get( 'messages_system_avatar_style', 'roundedbordered', GetterInterface::STRING ) ) {
						case 'rounded':
							$style	=	' rounded';
							break;
						case 'roundedbordered':
							$style	=	' img-thumbnail';
							break;
						case 'circle':
							$style	=	' rounded-circle';
							break;
						case 'circlebordered':
							$style	=	' img-thumbnail rounded-circle';
							break;
						default:
							$style	=	null;
							break;
					}

					$avatar			=	'<img src="' . htmlspecialchars( $avatar ) . '" class="cbImgPict cbThumbPict' . htmlspecialchars( $style ) . '" />';
				} else {
					$avatar			=	\CBuser::getInstance( 0, false )->getField( 'avatar', null, 'html', 'none', 'list', 0, true, array( '_allowProfileLink' => false ) );
				}

				$name				=	CBTxt::T( PMSHelper::getGlobalParams()->get( 'messages_system_name', 'System', GetterInterface::HTML ) );

				if ( ! $name ) {
					$name			=	CBTxt::T( 'System' );
				}

				$name				=	$cbUser->replaceUserVars( $name, true, false, null, false );
				$link				=	$cbUser->replaceUserVars( PMSHelper::getGlobalParams()->get( 'messages_system_url', null, GetterInterface::STRING ), false, false, null, false );
				$profile			=	$name;

				if ( $link ) {
					$avatar			=	'<a href="' . htmlspecialchars( $link ) . '">' . $avatar . '</a>';
					$profile		=	'<a href="' . htmlspecialchars( $link ) . '">' . $profile . '</a>';
				}
			} elseif ( ! $this->get( 'from_user', 0, GetterInterface::INT ) ) {
				$email				=	$this->get( 'from_email', null, GetterInterface::STRING );

				if ( ! cbIsValidEmail( $email ) ) {
					$email			=	null;
				}

				$avatar				=	\CBuser::getInstance( 0, false )->getField( 'avatar', null, 'html', 'none', 'list', 0, true, array( '_allowProfileLink' => false ) );
				$name				=	htmlspecialchars( $this->get( 'from_name', null, GetterInterface::STRING ) );

				if ( ! $name ) {
					$name			=	CBTxt::T( 'Guest' );
				}

				$profile			=	$name;

				if ( $email ) {
					$avatar			=	'<a href="mailto:' . htmlspecialchars( $email ) . '">' . $avatar . '</a>';
					$profile		=	'<a href="mailto:' . htmlspecialchars( $email ) . '">' . $profile . '</a>';
				}
			} else {
				$cbUser				=	\CBuser::getInstance( $this->get( 'from_user', 0, GetterInterface::INT ), false );
				$avatar				=	$cbUser->getField( 'avatar', null, 'html', 'none', 'list', 0, true );

				if ( ! $cbUser->getUserData()->get( 'id', 0, GetterInterface::INT ) ) {
					$name			=	CBTxt::T( 'Deleted' );
					$profile		=	$name;
				} else {
					$name			=	$cbUser->getField( 'formatname', null, 'html', 'none', 'profile', 0, true );
					$profile		=	$cbUser->getField( 'formatname', null, 'html', 'none', 'list', 0, true, array( 'params' => array( 'fieldHoverCanvas' => false ) ) );
					$status			=	$cbUser->getField( 'onlinestatus', null, 'html', 'none', 'profile', 0, true, array( 'params' => array( 'displayMode' => 1 ) ) );
				}
			}

			$cache[$userId][$id]	=	array( 'avatar' => $avatar, 'name' => $name, 'profile' => $profile, 'status' => $status );
		}

		if ( in_array( $field, array( 'avatar', 'name', 'profile', 'status' ) ) ) {
			return $cache[$userId][$id][$field];
		}

		return $cache[$userId][$id];
	}

	/**
	 * @param string $field
	 * @return string|array
	 */
	public function getTo( $field = 'name' )
	{
		static $cache				=	array();

		$userId						=	Application::MyUser()->getUserId();
		$id							=	$this->get( 'id', null, GetterInterface::INT );

		if ( ! isset( $cache[$userId][$id] ) ) {
			$status					=	null;

			if ( ! $this->get( 'to_user', 0, GetterInterface::INT ) ) {
				$avatar				=	\CBuser::getInstance( 0, false )->getField( 'avatar', null, 'html', 'none', 'list', 0, true, array( '_allowProfileLink' => false ) );
				$name				=	CBTxt::T( 'All Users' );
				$profile			=	$name;
			} else {
				$cbUser				=	\CBuser::getInstance( $this->get( 'to_user', 0, GetterInterface::INT ), false );
				$avatar				=	$cbUser->getField( 'avatar', null, 'html', 'none', 'list', 0, true );

				if ( ! $cbUser->getUserData()->get( 'id', 0, GetterInterface::INT ) ) {
					$name			=	CBTxt::T( 'Deleted' );
					$profile		=	$name;
				} else {
					$name			=	$cbUser->getField( 'formatname', null, 'html', 'none', 'profile', 0, true );
					$profile		=	$cbUser->getField( 'formatname', null, 'html', 'none', 'list', 0, true, array( 'params' => array( 'fieldHoverCanvas' => false ) ) );
					$status			=	$cbUser->getField( 'onlinestatus', null, 'html', 'none', 'profile', 0, true, array( 'params' => array( 'displayMode' => 1 ) ) );
				}
			}

			$cache[$userId][$id]	=	array( 'avatar' => $avatar, 'name' => $name, 'profile' => $profile, 'status' => $status );
		}

		if ( in_array( $field, array( 'avatar', 'name', 'profile', 'status' ) ) ) {
			return $cache[$userId][$id][$field];
		}

		return $cache[$userId][$id];
	}

	/**
	 * @param int $length
	 * @return string
	 */
	public function getMessage( $length = 0 )
	{
		$editor				=	PMSHelper::getGlobalParams()->get( 'messages_editor', 2, GetterInterface::INT );

		if ( ( $editor == 3 ) && ( ! Application::User( $this->get( 'from_user', 0, GetterInterface::INT ) )->isGlobalModerator() ) ) {
			$editor			=	1;
		}

		if ( ( $editor >= 2 ) || $this->get( 'from_system', false, GetterInterface::BOOLEAN ) ) {
			$message		=	$this->get( 'message', null, GetterInterface::HTML );
		} else {
			$message		=	htmlspecialchars( $this->get( 'message', null, GetterInterface::STRING ) );
		}

		// BBCode:
		$bbCode				=	PMSHelper::getGlobalParams()->get( 'messages_bbcode', 1, GetterInterface::INT );

		if ( ( $bbCode == 1 ) || ( ( $bbCode == 2 ) && $this->get( 'from_system', false, GetterInterface::BOOLEAN ) ) ) {
			$message		=	PMSHelper::bbcodeToHTML( $message );
		}

		// Remove duplicate spaces, tabs, and linebreaks:
		$message			=	PMSHelper::removeDuplicateSpacing( $message );

		if ( $length ) {
			// We just want a snippet so remove any html:
			$message		=	Get::clean( $message, GetterInterface::STRING );

			if ( $editor >= 2 ) {
				// And escape what's left encase we allowed HTML in the message:
				$message	=	htmlspecialchars( $message );
			}

			if ( cbutf8_strlen( $message ) > $length ) {
				$message	=	cbutf8_substr( $message, 0, $length ) . '...';
			}
		} else {
			// Linebreaks:
			$message		=	str_replace( array( "\r\n", "\r", "\n" ), '<br />', $message );
		}

		return $message;
	}

	/**
	 * Returns if message was read by a specific user or anyone
	 *
	 * @param int $userId
	 * @return bool
	 */
	public function getRead( $userId = 0 )
	{
		global $_CB_database;

		if ( $userId && ( $userId == $this->get( 'from_user', 0, GetterInterface::INT ) ) ) {
			return true;
		}

		$readCount					=	$this->get( '_read', null, GetterInterface::INT );

		if ( $readCount !== null ) {
			// We know for sure from existing query that the message was read or not so use that instead of querying any further:
			return ( $readCount ? true : false );
		}

		static $cache				=	array();

		$id							=	$this->get( 'id', 0, GetterInterface::INT );

		if ( ! isset( $cache[$id][$userId] ) ) {
			$query					=	"SELECT COUNT(*)"
									.	"\n FROM " . $_CB_database->NameQuote( '#__comprofiler_plugin_messages_read' );
			if ( $userId ) {
				$query				.=	"\n WHERE " . $_CB_database->NameQuote( 'to_user' ) . " = " . (int) $userId
									.	"\n AND " . $_CB_database->NameQuote( 'message' ) . " = " . $id;
			} else {
				$query				.=	"\n WHERE " . $_CB_database->NameQuote( 'message' ) . " = " . $id;
			}
			$_CB_database->setQuery( $query );
			$cache[$id][$userId]	=	( $_CB_database->loadResult() ? true : false );
		}

		return $cache[$id][$userId];
	}

	/**
	 * Sets the read state for this message for the supplied user id
	 *
	 * @param int $userId
	 * @param int $state
	 * @return bool
	 */
	public function setRead( $userId = 0, $state = 1 )
	{
		if ( ! $userId ) {
			return false;
		}

		$read	=	new ReadTable();

		$read->load( array( 'to_user' => (int) $userId, 'message' => $this->get( 'id', 0, GetterInterface::INT ) ) );

		if ( $state ) {
			if ( ! $read->get( 'id', 0, GetterInterface::INT ) ) {
				$read->set( 'to_user', (int) $userId );
				$read->set( 'message', $this->get( 'id', 0, GetterInterface::INT ) );

				$read->store();
			}

			$this->set( '_read', 1 );
		} else {
			if ( $read->get( 'id', 0, GetterInterface::INT ) ) {
				$read->delete();
			}

			$this->set( '_read', 0 );
		}

		return true;
	}

	/**
	 * Returns the message this message is replying to
	 *
	 * @return bool|MessageTable
	 */
	public function getReplyTo()
	{
		static $cache				=	array();

		$reply						=	$this->get( 'reply_to', 0, GetterInterface::INT );

		if ( ! $reply ) {
			return false;
		}

		if ( ! isset( $cache[$reply] ) ) {
			$row					=	new MessageTable();

			$row->load( $reply );

			if ( ! $row->get( 'id', 0, GetterInterface::INT ) ) {
				$cache[$reply]		=	false;
			} else {
				$cache[$reply]		=	$row;
			}
		}

		return $cache[$reply];
	}
}