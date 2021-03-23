<?php
    /*
     * @name Photogalleryforcb 1.0
     * Created By Guarneri Iacopo
     * http://www.iacopo-guarneri.me/
     * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
     */
    // Direct Acess not allowed!
    // no direct access
    defined('_JEXEC') or die(dirname(__FILE__).DS.'Restricted access');
    
if(!file_exists(getcwd()."/images/notice_board")){
	mkdir(getcwd()."/images/notice_board", 0775);
}

//ini_set('memory_limit','250M');
$upload=new Upload($_FILES, $errori_upload);
$upload->maxupload_size=$max_upload_param; //2MB
$upload->extensions_allowed=Array("jpg","JPG","jpeg","JPEG","png","PNG","gif","GIF");
//(directory, name fild del form)
$upload->save(getcwd()."/images/notice_board/", "fileimg");
if($upload->errors!=""){
	echo "<span style='color:#f00;'>".$upload->errors."</span><br />";
}else{
	die("<div id='img_panel_content' style='font-family:arial; font-size:14px;'>".$this->lingua('incolla_codice_immagine')."<br /><br /><i>[IMG]".JURI::base()."images/notice_board/".$upload->get_name()."[/IMG]</i></div>");
}
?>