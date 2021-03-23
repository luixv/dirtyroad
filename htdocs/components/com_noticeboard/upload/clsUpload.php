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

Class Upload
{
	var $maxupload_size;
	var $extensions_allowed;
	
	var $HTTP_POST_FILES;
	var $errori_upload;
	var $errors="";
	
	var $percorso;
	var $ext;
	
	function Upload($HTTP_POST_FILES, $errori_upload){
		$this->HTTP_POST_FILES = $HTTP_POST_FILES;
		$this->errori_upload=$errori_upload;
	}
	
	function save($directory, $field)
	{
		//controlla l'estensione del file
		$estensione=explode(".",$this->HTTP_POST_FILES[$field]['name']);
		$estensione=$estensione[count($estensione)-1];
		$this->ext=$estensione;
		
		if(!in_array($estensione,$this->extensions_allowed)){
			$this->errors .= $this->errori_upload['lestensione_txt'].$estensione.$this->errori_upload['non_consentita_txt']."<br />";
			return false;
		}
			
		//se la dimensione del file è maggiore di zero e minore del massimo stabilito
		if ($this->HTTP_POST_FILES[$field]['size'] < $this->maxupload_size && $this->HTTP_POST_FILES[$field]['size'] >0)
		{		
			// Get names
			$filename=$this->HTTP_POST_FILES[$field]['name'];
			$filename=explode(".",$this->HTTP_POST_FILES[$field]['name']);
			$filename[count($filename)-1]="";
			$filename=implode($filename);
			
			$tempName  = $this->HTTP_POST_FILES[$field]['tmp_name'];
			$all       = $directory.$filename.".".$estensione;
			
			//rinomina il file se esiste già
			for($i=0;file_exists($all);$i++){
				$tempName  = $this->HTTP_POST_FILES[$field]['tmp_name'];
				$all       = $directory.$filename."(".$i.").".$estensione;
			}
			
			//setto la variabile globale con il nuovo nome del file
			$this->percorso=$all;
			$this->percorso=explode($directory,$this->percorso);
			$this->percorso=$this->percorso[1];
			
			if(!file_exists($directory)){$this->errors.=$this->errori_upload['la_cartella_txt'].$directory.$this->errori_upload['non_esiste_txt']."<br />";}
			if(!@copy($tempName,$all)){$this->errors  .= $this->errori_upload['errore_upload_txt'].$filename.".".$estensione."<br />";}
			
		} elseif ($this->HTTP_POST_FILES[$field]['size'] > $this->maxupload_size) {
			$this->errors .= $this->errori_upload['grandezza_minore_txt'].$this->maxupload_size." bytes (".($this->maxupload_size/1000000)."MB)<br />";
			return false;
		} elseif ($this->HTTP_POST_FILES[$field]['size'] == 0) {
			$this->errors .= $this->errori_upload['grandezza_maggiore_txt'];
			return false;
		}
	}
	
	function get_name(){
		return $this->percorso;
	}
	function get_ext(){
		return $this->ext;
	}
}

?>
