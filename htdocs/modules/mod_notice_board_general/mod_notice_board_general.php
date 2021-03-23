<?php
/*
* @name CB_notice_board 1.01
* Created By Guarneri Iacopo
* http://www.iacopo-guarneri.me/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// Direct Acess not allowed!
// no direct access
	defined('_JEXEC') or die(dirname(__FILE__).DS.'Restricted access');

	if(class_exists('NoticeBoard')){$cmt=new NoticeBoard();}else{require_once("components/com_noticeboard/core.php");$cmt=new NoticeBoard();}
    
	$cmt->foglio_stile=JURI::base()."components/com_noticeboard/notice_board_style.css";
	$cmt->mail_admin=$params->get('admin_mail','');
	$cmt->mail_user=$params->get('user_mail','');
	$cmt->pagination=$params->get('pagination','');
	//$cmt->style_pagination=$params->get('pagination','');
	$cmt->style_pagination=$params->get('style_pagination','style2');
	$cmt->user_template=$params->get('cb_template','');
	$cmt->jquery=$params->get('jquery',1);
	$cmt->show_link=$params->get('show_link',"no");
	//*********************************************************
	// INIZIO MODIFICA IACOPO
	//*********************************************************
	$cmt->show_smile=$params->get('show_smile',"no");
	$cmt->show_avatar=$params->get('show_avatar',"no");
	$cmt->not_friend=$params->get('not_friend',"all");
	//*********************************************************
	// FINE MODIFICA IACOPO
	//*********************************************************
	
	if($cmt->show_avatar=$params->get('use_community',"yes")=="yes"){	
		$cmt->selector="NoticeBoard";
	}else{
		$menu = @JSite::getMenu();
		$menuItem = $menu->getActive();
		$cmt->selector=$menuItem->id;
	}
	if(($params->get('show_avatar','yes')=="yes")||($params->get('show_avatar','yes')=="both")){
		$cmt->avatar=Array($params->get('maxw','50'),$params->get('maxh','50'));
	}
	$form=$cmt->start("ALL");
	echo $form;
?>