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
 * @var int                $total
 * @var MessageTable[]     $rows
 * @var array              $input
 * @var UserTable          $user
 * @var cbPageNav          $pageNav
 * @var bool               $searching
 *
 * @var string             $returnUrl
 * @var string             $type
 * @var bool               $allowTypeFilter
 * @var int                $unread
 */

global $_CB_framework, $_PLUGINS;

$pageTitle			=	null;

if ( $type != 'modal' ) {
	$pageTitle		=	( $type == 'sent' ? CBTxt::T( 'Sent Messages' ) : CBTxt::T( 'Received Messages' ) );

	if ( $pageTitle ) {
		$_CB_framework->setPageTitle( $pageTitle );
	}
}
?>
<div class="<?php echo ( $type == 'modal' ? 'd-flex flex-column h-100 mh-100 ' : null ); ?>pmMessages pmMessagesDefault">
	<?php echo implode( '', $_PLUGINS->trigger( 'pm_onBeforeDisplayMessages', array( &$rows, &$input, $type, $user ) ) ); ?>
	<?php if ( $pageTitle ) { ?>
	<div class="mb-3 border-bottom cb-page-header pmMessagesTitle"><h3 class="m-0 p-0 mb-2 cb-page-header-title"><?php echo $pageTitle; ?></h3></div>
	<?php } ?>
	<div class="<?php echo ( $type == 'modal' ? 'm-2 flex-shrink-0' : 'mb-2' ); ?> row no-gutters pkbHeader pmMessagesHeader">
		<?php if ( PMSHelper::canMessage( $user->get( 'id', 0, GetterInterface::INT ), false ) || ( ( $type != 'sent' ) && $unread ) ) { ?>
		<div class="col-sm text-center text-sm-left">
			<?php if ( PMSHelper::canMessage( $user->get( 'id', 0, GetterInterface::INT ), false ) ) { ?>
			<button type="button" onclick="window.location.href='<?php echo $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'messages', 'func' => 'new', 'return' => $returnUrl ) ); ?>';" class="btn<?php echo ( $type == 'modal' ? ' btn-sm' : null ); ?> btn-sm-block btn-success pmButton pmButtonNew"><span class="fa fa-plus-circle"></span> <?php echo CBTxt::T( 'New Message' ); ?></button>
			<?php } ?>
			<?php if ( ( $type != 'sent' ) && $unread ) { ?>
			<a href="<?php echo $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => 'read', 'return' => $returnUrl ) ); ?>" class="align-middle pmButton pmButtonRead"><?php echo CBTxt::T( 'Mark All Read' ); ?></a>
			<?php } ?>
		</div>
		<?php } ?>
		<?php if ( $type == 'modal' ) { ?>
		<div class="col-sm-6 mt-1 mt-sm-0 text-center text-sm-right">
			<a href="javascript:void(0);" class="align-middle pmButton pmButtonClose cbTooltipClose"><span class="fa fa-times"></span> <span class="d-inline-block d-sm-none"><?php echo CBTxt::T( 'Close' ); ?></span></a>
		</div>
		<?php } elseif ( $input['search'] || $input['type'] ) { ?>
		<div class="col-sm-<?php echo ( $input['search'] && $input['type'] ? '6' : '4' ); ?> mt-1 mt-sm-0 text-sm-right" role="search">
			<form action="<?php echo $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'messages', 'func' => ( ! $allowTypeFilter ? $type : null ) ) ); ?>" method="post" name="pmMessagesForm" class="m-0 pmMessagesForm">
				<?php echo $pageNav->getLimitBox( false ); ?>
				<?php if ( $input['search'] ) { ?>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><span class="fa fa-search"></span></span>
					</div>
					<?php echo $input['search']; ?>
					<?php echo $input['type']; ?>
				</div>
				<?php } else { ?>
					<?php echo $input['type']; ?>
				<?php } ?>
			</form>
		</div>
		<?php } ?>
	</div>
	<div class="<?php echo ( $type == 'modal' ? 'p-2 flex-grow-1 ' : null ); ?>pmMessagesRows" role="grid">
		<?php
		$i							=	0;

		if ( $rows ) foreach ( $rows as $row ) {
			$i++;

			$menu					=	array();

			if ( $row->get( 'from_user', 0, GetterInterface::INT ) == $user->get( 'user_id', 0, GetterInterface::INT ) ) {
				$read				=	$row->getRead();

				if ( ! $read ) {
					$menu[]			=	'<li class="pmMessagesMenuItem" role="presentation"><a href="' . $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => 'edit', 'id' => $row->get( 'id', 0, GetterInterface::INT ), 'return' => $returnUrl ) ) . '" class="dropdown-item" role="menuitem"><span class="fa fa-edit"></span> ' . CBTxt::T( 'Edit' ) . '</a></li>';
				}

				$readTooltip		=	cbTooltip( null, ( $row->getRead() ? CBTxt::T( 'Read' ) : CBTxT::T( 'Unread' ) ), null, 'auto', null, null, null, 'data-hascbtooltip="true" data-cbtooltip-position-my="bottom center" data-cbtooltip-position-at="top center" data-cbtooltip-classes="qtip-simple" aria-label="' . htmlspecialchars( ( $row->getRead() ? CBTxt::T( 'Read' ) : CBTxT::T( 'Unread ' ) ) ) . '"' );

				$avatar				=	$row->getTo( 'avatar' );
				$name				=	$row->getTo( 'name' );
				$status				=	$row->getTo( 'status' );
			} else {
				$read				=	$row->getRead( $user->get( 'user_id', 0, GetterInterface::INT ) );
				$readTooltip		=	cbTooltip( null, ( $read ? CBTxt::T( 'Mark Unread' ) : CBTxT::T( 'Mark Read' ) ), null, 'auto', null, null, null, 'data-hascbtooltip="true" data-cbtooltip-position-my="bottom center" data-cbtooltip-position-at="top center" data-cbtooltip-classes="qtip-simple" aria-label="' . htmlspecialchars( ( $read ? CBTxt::T( 'Mark Unread' ) : CBTxT::T( 'Mark Read' ) ) ) . '"' );

				$avatar				=	$row->getFrom( 'avatar' );
				$name				=	$row->getFrom( 'name' );
				$status				=	$row->getFrom( 'status' );
			}

			if ( $status ) {
				$status				=	' <span class="text-small">' . $status . '</span>';
			}

			$_PLUGINS->trigger( 'pm_onDisplayMessage', array( &$row, &$avatar, &$name, &$menu, $user ) );

			if ( ( $row->get( 'from_user', 0, GetterInterface::INT ) == $user->get( 'user_id', 0, GetterInterface::INT ) ) || ( $row->get( 'to_user', 0, GetterInterface::INT ) == $user->get( 'user_id', 0, GetterInterface::INT ) ) || Application::MyUser()->isGlobalModerator() ) {
				$menu[]				=	'<li class="pmMessagesMenuItem" role="presentation"><a href="javascript: void(0);" onclick="cbjQuery.cbconfirm( \'' . addslashes( CBTxt::T( 'Are you sure you want to delete this message?' ) ) . '\' ).done( function() { window.location.href = \'' . addslashes( $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => 'delete', 'id' => $row->get( 'id', 0, GetterInterface::INT ), cbSpoofField() => cbSpoofString( null, 'plugin' ), 'return' => $returnUrl ) ) ) . '\'; })" class="dropdown-item" role="menuitem"><span class="fa fa-trash-o"></span> ' . CBTxt::T( 'Delete' ) . '</a></li>';
			}

			if ( $menu ) {
				$menuItems			=	'<ul class="list-unstyled dropdown-menu d-block position-relative m-0 pmMessagesMenuItems" role="menu">'
									.		implode( '', $menu )
									.	'</ul>';

				$menuAttr			=	cbTooltip( null, $menuItems, null, 'auto', null, null, null, 'class="text-body cbDropdownMenu pmMessagesMenu" data-cbtooltip-menu="true" data-cbtooltip-classes="qtip-nostyle" data-cbtooltip-open-classes="active" aria-label="' . htmlspecialchars( CBTxt::T( 'Message Options' ) ) . '"' );
			}
		?>
		<?php if ( ( $i > 1 ) || ( ( $i > 1 ) && ( $i == count( $rows ) ) ) ) { ?>
		<hr class="mt-1 mb-1" role="presentation" />
		<?php } ?>
		<div class="media pmMessagesRow <?php echo ( $read ? 'pmMessagesRowRead' : 'pmMessagesRowUnread' ); ?>" data-pm-url="<?php echo $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => 'show', 'id' => $row->get( 'id', 0, GetterInterface::INT ), 'return' => $returnUrl ) ); ?>" role="row">
			<div class="media-left pmMessagesRowImg" role="gridcell">
				<?php echo $avatar; ?>
			</div>
			<div class="pl-2 media-body pmMessagesRowMsg" role="gridcell">
				<div class="row no-gutters">
					<div class="text-wrap col pmMessagesRowMsgUser">
						<?php if ( $row->get( 'from_user', 0, GetterInterface::INT ) == $user->get( 'user_id', 0, GetterInterface::INT ) ) { ?>
						<span class="fa fa-envelope<?php echo ( $read ? '-open text-muted' : ' text-primary' ); ?>"<?php echo $readTooltip; ?>></span>
						<?php } else { ?>
						<a href="<?php echo $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => ( $read ? 'unread' : 'read' ), 'id' => $row->get( 'id', 0, GetterInterface::INT ), 'return' => $returnUrl ) ); ?>"<?php echo $readTooltip; ?>><span class="fa fa-envelope<?php echo ( $read ? '-open text-muted' : ' text-primary' ); ?>"></span></a>
						<?php } ?>
						<a href="<?php echo $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => 'show', 'id' => $row->get( 'id', 0, GetterInterface::INT ), 'return' => $returnUrl ) ); ?>"><?php echo $name; ?></a>
						<?php echo $status; ?>
					</div>
					<?php if ( $menu ) { ?>
					<div class="col-auto pmMessagesRowMsgMenu">
						<span class="d-none d-sm-inline pmMessagesRowDate"><?php echo cbFormatDate( $row->get( 'date', null, GetterInterface::STRING ), true, false ); ?></span>
						<a href="javascript: void(0);" <?php echo trim( $menuAttr ); ?>><span class="ml-2 fa fa-ellipsis-v"></span></a>
					</div>
					<?php } ?>
				</div>
				<div class="row no-gutters">
					<div class="col-sm text-wrap pmMessagesRowMsgIntro" tabindex="0">
						<a href="<?php echo $_CB_framework->pluginClassUrl( $this->element, true, array( 'action' => 'message', 'func' => 'show', 'id' => $row->get( 'id', 0, GetterInterface::INT ), 'return' => $returnUrl ) ); ?>" class="text-inherit text-plain"><?php echo $row->getMessage( 100 ); ?></a>
					</div>
					<div class="col-sm-auto d-block d-sm-none pmMessagesRowDate">
						<?php echo cbFormatDate( $row->get( 'date', null, GetterInterface::STRING ), true, false ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php } else { ?>
		<div class="pmMessagesRow pmMessagesRowEmpty" role="row">
		<?php if ( $searching ) { ?>
			<?php echo CBTxt::T( 'No message search results found.' ); ?>
		<?php } else { ?>
			<?php echo CBTxt::T( 'You currently have no messages.' ); ?>
		<?php } ?>
		</div>
		<?php } ?>
	</div>
	<?php if ( $this->params->get( 'messages_paging', true, GetterInterface::BOOLEAN ) && ( $pageNav->total > $pageNav->limit ) ) { ?>
	<div class="<?php echo ( $type == 'modal' ? 'm-2' : 'mt-2' ); ?> pmMessagesPaging">
		<?php echo $pageNav->getListLinks(); ?>
	</div>
	<?php } ?>
	<?php echo implode( '', $_PLUGINS->trigger( 'pm_onAfterDisplayMessages', array( $rows, $input, $type, $user ) ) ); ?>
</div>
<?php if ( $type != 'modal' ) { ?>
	<?php $_CB_framework->setMenuMeta(); ?>
<?php } ?>