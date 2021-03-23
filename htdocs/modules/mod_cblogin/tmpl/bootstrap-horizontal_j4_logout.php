<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

use CBLib\Language\CBTxt;
use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::_('behavior.keepalive');
?>
<?php echo modCBLoginHelper::getPlugins( $params, $type, 'beforeForm', 'span', 1 ); ?>
<form action="<?php echo $_CB_framework->viewUrl( 'logout', true, null, 'html', $secureForm ); ?>" method="post" id="login-form-<?php echo $module->id; ?>" class="mod-login-logout form-horizontal cbLogoutForm">
	<input type="hidden" name="option" value="com_comprofiler" />
	<input type="hidden" name="view" value="logout" />
	<input type="hidden" name="op2" value="logout" />
	<input type="hidden" name="return" value="B:<?php echo $logoutReturnUrl; ?>" />
	<input type="hidden" name="message" value="<?php echo (int) $params->get( 'logout_message', 0 ); ?>" />
	<?php echo cbGetSpoofInputTag( 'logout' ); ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'start', 'span', 1 ); ?>
	<?php if ( $preLogoutText ) { ?>
		<div class="d-inline-block mr-2 mod-login-logout__pretext pretext <?php echo htmlspecialchars( $templateClass ); ?>"><?php echo $preLogoutText; ?></div>
	<?php } ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'almostStart', 'span', 1 ); ?>
	<?php if ( (int) $params->get( 'greeting', 1 ) ) { ?>
		<div class="d-inline-block mr-2 mod-login-logout__login-greeting login-greeting <?php echo htmlspecialchars( $templateClass ); ?>"><?php echo $greetingText; ?></div>
	<?php } ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'beforeButton', 'span', 1, null, '&nbsp;' ); ?>
	<div class="d-inline-block mod-login-logout__button logout-button">
		<button type="submit" name="Submit" class="<?php echo ( $styleLogout ? htmlspecialchars( $styleLogout ) : 'btn btn-primary' ); ?>"<?php echo $buttonStyle; ?>>
			<?php if ( in_array( $showButton, array( 1, 2, 3 ) ) ) { ?>
				<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
					<span class="cbModuleLogoutIcon fa fa-sign-out" title="<?php echo htmlspecialchars( CBTxt::T( 'Log out' ) ); ?>"></span>
				</span>
			<?php } ?>
			<?php if ( in_array( $showButton, array( 0, 1, 4 ) ) ) { ?>
				<?php echo htmlspecialchars( CBTxt::T( 'Log out' ) ); ?>
			<?php } ?>
		</button>
	</div>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'afterButton', 'span', 1, null, '&nbsp;' ); ?>
	<?php if ( $profileViewText || $profileEditText || $showPrivateMessages || $showConnectionRequests ) { ?>
		<div class="d-inline-block mod-login-logout__options">
			<?php if ( $showPrivateMessages ) { ?>
				<div class="d-inline-block ml-2 logout-options-private-messages">
					<a href="<?php echo $privateMessageURL; ?>"<?php echo ( $stylePrivateMsgs ? ' class="' . htmlspecialchars( $stylePrivateMsgs ) . '"' : null ); ?>>
						<?php if ( $params->get( 'show_pms_icon', 0 ) ) { ?>
							<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
								<span class="cbModulePMIcon fa fa-envelope" title="<?php echo htmlspecialchars( CBTxt::T( 'Private Messages' ) ); ?>"></span>
							</span>
						<?php } ?>
						<?php if ( $newMessageCount ) { ?>
							<?php echo ( $newMessageCount == 1 ? CBTxt::T( 'YOU_HAVE_COUNT_NEW_PRIVATE_MESSAGE', 'You have [count] new private message.', array( '[count]' => $newMessageCount ) ) : CBTxt::T( 'YOU_HAVE_COUNT_NEW_PRIVATE_MESSAGES', 'You have [count] new private messages.', array( '[count]' => $newMessageCount ) ) ); ?>
						<?php } else { ?>
							<?php echo CBTxt::T( 'You have no new private messages.' ); ?>
						<?php } ?>
					</a>
				</div>
			<?php } ?>
			<?php if ( $showConnectionRequests ) { ?>
				<div class="d-inline-block ml-2 logout-options-connection-requests">
					<a href="<?php echo $_CB_framework->viewUrl( 'manageconnections' ); ?>"<?php echo ( $styleConnRequests ? ' class="' . htmlspecialchars( $styleConnRequests ) . '"' : null ); ?>>
						<?php if ( $params->get( 'show_connection_notifications_icon', 0 ) ) { ?>
							<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
								<span class="cbModuleConnectionsIcon fa fa-users" title="<?php echo htmlspecialchars( CBTxt::T( 'Connections' ) ); ?>"></span>
							</span>
						<?php } ?>
						<?php if ( $newConnectionRequests ) { ?>
							<?php echo ( $newConnectionRequests == 1 ? CBTxt::T( 'YOU_HAVE_COUNT_NEW_CONNECTION_REQUEST', 'You have [count] new connection request.', array( '[count]' => $newConnectionRequests ) ) : CBTxt::T( 'YOU_HAVE_COUNT_NEW_CONNECTION_REQUESTS', 'You have [count] new connection requests.', array( '[count]' => $newConnectionRequests ) ) ); ?>
						<?php } else { ?>
							<?php echo CBTxt::T( 'You have no new connection requests.' ); ?>
						<?php } ?>
					</a>
				</div>
			<?php } ?>
			<?php if ( $profileViewText ) { ?>
				<div class="d-inline-block ml-2 logout-options-profile">
					<a href="<?php echo $_CB_framework->userProfileUrl(); ?>"<?php echo ( $styleProfile ? ' class="' . htmlspecialchars( $styleProfile ) . '"' : null ); ?>>
						<?php if ( $params->get( 'icon_show_profile', 0 ) ) { ?>
							<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
								<span class="cbModuleProfileViewIcon fa fa-user" title="<?php echo htmlspecialchars( $profileViewText ); ?>"></span>
							</span>
						<?php } ?>
						<?php echo $profileViewText; ?>
					</a>
				</div>
			<?php } ?>
			<?php if ( $profileEditText ) { ?>
				<div class="d-inline-block ml-2 logout-options-profile-edit">
					<a href="<?php echo $_CB_framework->userProfileEditUrl(); ?>"<?php echo ( $styleProfileEdit ? ' class="' . htmlspecialchars( $styleProfileEdit ) . '"' : null ); ?>>
						<?php if ( $params->get( 'icon_edit_profile', 0 ) ) { ?>
							<span class="<?php echo htmlspecialchars( $templateClass ); ?>">
								<span class="cbModuleProfileEditIcon fa fa-pencil" title="<?php echo htmlspecialchars( $profileEditText ); ?>"></span>
							</span>
						<?php } ?>
						<?php echo $profileEditText; ?>
					</a>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'almostEnd', 'span', 1 ); ?>
	<?php if ( $postLogoutText ) { ?>
		<div class="d-inline-block ml-2 mod-login-logout__posttext posttext <?php echo htmlspecialchars( $templateClass ); ?>"><?php echo $postLogoutText; ?></div>
	<?php } ?>
	<?php echo modCBLoginHelper::getPlugins( $params, $type, 'end', 'span', 1 ); ?>
</form>
<?php echo modCBLoginHelper::getPlugins( $params, $type, 'afterForm', 'span', 1 ); ?>