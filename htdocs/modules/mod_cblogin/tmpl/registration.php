<?php
/**
 * Community Builder (TM)
 * @version $Id: $
 * @package CommunityBuilder
 * @copyright (C) 2004-2021 www.joomlapolis.com / Lightning MultiCom SA - and its licensors, all rights reserved
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
 */

use CBLib\Application\Application;
use CBLib\Language\CBTxt;
use CBLib\Registry\GetterInterface;

if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

$_CB_framework->document->outputToHeadCollectionStart();

$regErrorMSG		=	null;
$regIntegrations	=	$_PLUGINS->trigger( 'onBeforeRegisterFormDisplay', array( &$user, $regErrorMSG ) );

if ( $_PLUGINS->is_errors() ) {
	return;
}

$regTabs			=	new cbTabs( ( $layout != 'stepped' ), 1, null, ( $regTabbed != 'flat' ) );
$regContent			=	$regTabs->getEditTabs( $user, null, 'htmledit', $regFormatting, 'register', $regTabbed );

$regForm			=	'<form action="' . $_CB_framework->viewUrl( 'saveregisters', true, null, 'html', $secureForm ) . '" method="post" id="cbcheckedadminForm" name="adminForm" enctype="multipart/form-data" class="cb_form form-auto cbValidation">'
					.		'<input type="hidden" name="id" value="0" />'
					.		'<input type="hidden" name="gid" value="0" />'
					.		'<input type="hidden" name="emailpass" value="' . htmlspecialchars( ( isset( $ueConfig['emailpass'] ) ? $ueConfig['emailpass'] : 0 ) ) . '" />'
					.		cbGetSpoofInputTag( 'registerForm' )
					.		cbGetRegAntiSpamInputTag();

$return				=	$_PLUGINS->callTemplate( selectTemplate( 'dir' ), 'RegisterForm', 'drawProfile', array( &$user, $regContent, $regForm, null, null, null, CBTxt::Th( 'UE_REGISTER', 'Sign up' ), null, null, null, null, $regFormatting, $regIntegrations ), 'htmledit' )
					.	cbPoweredBy();

$_PLUGINS->trigger( 'onAfterRegisterFormDisplay', array( $user, $regContent, &$return ) );

$_CB_framework->getAllJsPageCodes();

if ( ( Application::Input()->get( 'no_html', 0, GetterInterface::INT ) != 1 ) || ( ! in_array( Application::Input()->get( 'format', null, GetterInterface::STRING ), array( 'raw', 'json' ) ) ) ) {
	echo $_CB_framework->document->outputToHead();
}

echo $return;