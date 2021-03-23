<?php
/*
* @name CB_notice_board 1.0
* Created By Guarneri Iacopo
* http://www.iacopo-guarneri.me/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// Direct Acess not allowed!
// no direct access
defined('_JEXEC') or die(dirname(__FILE__).DS.'Restricted access');

//tasto per ripubblicare sulla propria bacheca post di altri
//creare componente/modulo per mostrare la bacheca generale

class cbnotice_boardTab extends cbTabHandler {
	function getDisplayTab($tab,$user,$ui) {
        
        if(class_exists('NoticeBoard')){$cmt=new NoticeBoard();}else{require_once("components/com_noticeboard/core.php");$cmt=new NoticeBoard();}
        $cmt=new NoticeBoard();
		
        $params = $this->params;
		$cmt->foglio_stile=JURI::base()."components/com_noticeboard/notice_board_style.css";
        $cmt->mail_admin=$params->get('admin_mail','yes');
        $cmt->mail_user=$params->get('user_mail','yes');
        $cmt->pagination=$params->get('pagination','10');
		$cmt->style_pagination=$params->get('style_pagination','style2');
		$cmt->jquery=$params->get('jquery',1);
		$cmt->show_smile=$params->get('show_smile',"no");
		$cmt->show_avatar=$params->get('show_avatar',"no");
		$cmt->not_friend=$params->get('not_friend',"all");
		
		$cmt->selector="NoticeBoard";
		if(($params->get('show_avatar','yes')=="yes")||($params->get('show_avatar','yes')=="both")){
			$cmt->avatar=Array($params->get('maxw','50'),$params->get('maxh','50'));
		}

        $form=$cmt->start($user);
        return $form;
	}
}
?>
