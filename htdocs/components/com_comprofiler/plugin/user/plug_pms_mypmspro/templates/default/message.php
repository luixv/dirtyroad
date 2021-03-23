<?php
/**
* Community Builder (TM)
* @version $Id: $
* @package CommunityBuilder
* @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

use CBLib\Application\Application;
use CBLib\Registry\GetterInterface;
use CBLib\Language\CBTxt;
use CB\Database\Table\UserTable;
use CB\Plugin\PMS\Table\MessageTable;
use CB\Plugin\PMS\PMSHelper;

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

/**
 * @var CBplug_pmsmypmspro $this
 * @var MessageTable       $row
 * @var array              $input
 * @var UserTable          $user
 *
 * @var string             $returnUrl
 * @var int                $toLimit
 * @var int                $messageEditor
 * @var int                $messageLimit
 */

global $_CB_framework, $_PLUGINS;

$pageTitle				=	CBTxt::T( 'Message' );

if ( $pageTitle ) {
	$_CB_framework->setPageTitle( $pageTitle );
}

$menu					=	array();

if ( $row->get( 'from_user', 0, GetterInterface::INT ) == $user->get( 'user_id', 0, GetterInterface::INT ) ) {
	$read				=	$row->getRead();

	if ( ! $read ) {
		$menu[]			=	'<li class="pmMessageMenuItem" role="presentation"><a href="' . $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => 'edit', 'id' => $row->get( 'id', 0, GetterInterface::INT ), 'return' => PMSHelper::getReturn() ) ) . '" class="dropdown-item" role="menuitem"><span class="fa fa-edit"></span> ' . CBTxt::T( 'Edit' ) . '</a></li>';
	}

	$avatar				=	$row->getTo( 'avatar' );
	$name				=	$row->getTo( 'profile' );
	$status				=	$row->getTo( 'status' );
} else {
	$read				=	$row->getRead( $user->get( 'user_id', 0, GetterInterface::INT ) );
	$avatar				=	$row->getFrom( 'avatar' );
	$name				=	$row->getFrom( 'profile' );
	$status				=	$row->getFrom( 'status' );
}

if ( $status ) {
	$name				.=	' <span class="text-small">' . $status . '</span>';
}

$readTooltip			=	cbTooltip( null, ( $read ? CBTxt::T( 'Read' ) : CBTxT::T( 'Unread' ) ), null, 'auto', null, null, null, 'data-hascbtooltip="true" data-cbtooltip-position-my="bottom center" data-cbtooltip-position-at="top center" data-cbtooltip-classes="qtip-simple" aria-label="' . htmlspecialchars( ( $read ? CBTxt::T( 'Read' ) : CBTxT::T( 'Unread' ) ) ) . '"' );

$integrations			=	$_PLUGINS->trigger( 'pm_onBeforeDisplayMessage', array( &$row, &$avatar, &$name, &$menu, $user ) );

if ( ( $row->get( 'from_user', 0, GetterInterface::INT ) == $user->get( 'user_id', 0, GetterInterface::INT ) ) || ( $row->get( 'to_user', 0, GetterInterface::INT ) == $user->get( 'user_id', 0, GetterInterface::INT ) ) || Application::MyUser()->isGlobalModerator() ) {
	$menu[]				=	'<li class="pmMessageMenuItem" role="presentation"><a href="javascript: void(0);" onclick="cbjQuery.cbconfirm( \'' . addslashes( CBTxt::T( 'Are you sure you want to delete this message?' ) ) . '\' ).done( function() { window.location.href = \'' . addslashes( $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => 'delete', 'id' => $row->get( 'id', 0, GetterInterface::INT ), cbSpoofField() => cbSpoofString( null, 'plugin' ) ) ) ) . '\'; })" class="dropdown-item" role="menuitem"><span class="fa fa-trash-o"></span> ' . CBTxt::T( 'Delete' ) . '</a></li>';
}

if ( $menu ) {
	$menuItems			=	'<ul class="list-unstyled dropdown-menu d-block position-relative m-0 pmMessageMenuItems" role="menu">'
						.		implode( '', $menu )
						.	'</ul>';

	$menuAttr			=	cbTooltip( null, $menuItems, null, 'auto', null, null, null, 'class="text-body cbDropdownMenu pmMessageMenu" data-cbtooltip-menu="true" data-cbtooltip-classes="qtip-nostyle" data-cbtooltip-open-classes="active" aria-label="' . htmlspecialchars( CBTxt::T( 'Message Options' ) ) . '"' );
}
?>
<div class="pmMessage pmMessageDefault">
	<div class="mb-2 row no-gutters border media pmMessageHeader">
		<div class="p-2 media-left pmMessageHeaderImg">
			<?php echo $avatar; ?>
		</div>
		<div class="p-2 media-body row no-gutters pmMessageHeaderDetails">
			<div class="col">
				<div class="text-wrap pmMessageHeaderUser">
					<span class="fa fa-envelope<?php echo ( $read ? '-open text-muted' : ' text-primary' ); ?>"<?php echo $readTooltip; ?>></span>
					<?php echo $name; ?>
				</div>
				<div class="pmMessageHeaderDate">
					<?php echo cbFormatDate( $row->get( 'date', null, GetterInterface::STRING ), true, true ); ?>
				</div>
			</div>
			<?php if ( $menu ) { ?>
			<div class="col-auto pmMessageHeaderMenu">
				<a href="javascript: void(0);" <?php echo trim( $menuAttr ); ?>><span class="ml-2 fa fa-ellipsis-v"></span></a>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php echo implode( '', $integrations ); ?>
	<div class="text-wrap pmMessageContent" tabindex="0">
		<?php
		echo $row->getMessage();

		if ( $this->params->get( 'messages_replies', true, GetterInterface::BOOLEAN ) && $row->getReplyTo() ) {
			$reply	=	$row->getReplyTo();
			$depth	=	1;

			require PMSHelper::getTemplate( null, 'replies' );
		}
		?>
	</div>
	<?php echo implode( '', $_PLUGINS->trigger( 'pm_onAfterDisplayMessage', array( $row, $avatar, $name, $menu, $user ) ) ); ?>
	<?php
	if ( PMSHelper::canReply( $user->get( 'id', 0, GetterInterface::INT ), $row->get( 'from_user', 0, GetterInterface::INT ) )
		 && ( ! $row->get( 'from_system', false, GetterInterface::BOOLEAN ) )
		 && ( $user->get( 'id', 0, GetterInterface::INT ) == $row->get( 'to_user', 0, GetterInterface::INT ) )
		 && ( $row->get( 'from_user', 0, GetterInterface::INT ) || ( ( ! $row->get( 'from_user', 0, GetterInterface::INT ) ) && $row->get( 'from_email', null, GetterInterface::STRING ) ) )
	) {
		require PMSHelper::getTemplate( null, 'reply' );
	} else {
	?>
	<div class="mt-3 text-right">
		<input type="button" value="<?php echo htmlspecialchars( CBTxt::T( 'Back' ) ); ?>" class="btn btn-sm btn-sm-block btn-secondary pmButton pmButtonBack" onclick="window.location.href = '<?php echo addslashes( htmlspecialchars( $returnUrl ) ); ?>';" />
	</div>
	<?php } ?>
</div>
<?php $_CB_framework->setMenuMeta(); ?>