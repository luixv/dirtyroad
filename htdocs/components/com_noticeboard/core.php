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

defined('DS')?  null :define('DS',DIRECTORY_SEPARATOR);

//tasto per ripubblicare sulla propria bacheca post di altri

class NoticeBoard {
    
	var $mail_admin;
    	var $mail_user;
   	var $pagination;
   	var $selector;
	var $avatar="";
	var $style_pagination;
	var $foglio_stile;
	var $user_template;
	var $jquery;
	var $show_link="yes";
	var $show_avatar;
	var $show_smile="no";
	var $antiflood=10;
	var $not_friend="all"; //all, nothing, read
	
	function jomail($destinatario, $oggetto, $corpo)
	{
		$mailer = JFactory::getMailer();
		
		//mittente
		$config = JFactory::getConfig();
		if(version_compare(JVERSION,'1.6.0') < 0){
			$mailfrom=$config->getValue( 'config.mailfrom' );
			$fromname=$config->getValue( 'config.fromname' );
		} else {
			$mailfrom=$config->get( 'config.mailfrom' );
			$fromname=$config->get( 'config.fromname' );
			if(is_null($mailfrom) || is_null($fromname)){
				$mailfrom=$config["mailfrom"];
				$fromname=$config["fromname"];
			}
		}
		$sender = array($mailfrom, $fromname);
 
		$mailer->setSender($sender);
		
		//destinatario o array destinatari
		$recipient = $destinatario;
		$mailer->addRecipient($recipient);
		
		//messaggio e oggetto
		$mailer->setSubject($oggetto);
		$mailer->setBody($corpo);
		
		//invia mail
		$send = $mailer->Send();
		/*if ( $send !== true ) {
			JError::raiseWarning( 100, 'Error sending email: ' . $send->message);
		} else {
			JFactory::getApplication()->enqueueMessage('Mail sent '.$destinatario);
		}*/
	}
	function lingua($indice)
	{
		//global $_CB_framework;
		//$lang =$_CB_framework->getCfg( 'lang' );
		$lang = JFactory::getLanguage();
		$lang = explode(" ",$lang->getName());
		$lang = strtolower($lang[0]);

		if(file_exists(dirname(__FILE__).DS."lang".DS.$lang.".php")){
			require_once(dirname(__FILE__).DS."lang".DS.$lang.".php");
		}
		else{
			require_once(dirname(__FILE__).DS."lang".DS."english.php");
		}
					
		$lingua_bacheca=new notice_board_language();
		return $lingua_bacheca->$indice;
	}
	
	function is_friend($user_id){
		if(!isset($user_id)){return false;}
		if(JFactory::getUser()->id==$user_id){
			return true;
		}
		$database = JFactory::getDBO();
		$database->setQuery('SELECT * FROM #__comprofiler_members WHERE referenceid='.JFactory::getUser()->id.' AND memberid='.$user_id.' AND accepted=1');
		$results = $database->loadAssocList();

		if(isset($results) && count($results)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function controllo_mail($id_mail, $database)//ritorna vero o falso a seconda se l'utente vuole o non vuole ricevere mail e se l'utente non è resente nel db lo aggiunge
	{
		$database->setQuery('SELECT * FROM #__cb_notice_board_mail WHERE userid ='.$id_mail);
		$results = $database->loadAssocList();

		if(count($results)==0)
		{
			$query='INSERT INTO #__cb_notice_board_mail (userid, sendmail) VALUES ('.$id_mail.',1)';
			$database->setQuery($query);
			$database->query(); //$results=$database->query();
			return true;
		}
		
		if($results[0]['sendmail']==1)
			return true;
		else
			return false;
	}
	
	function invia_mail($utente, $segnala, $parente, $database)
	{
		try{
			$config = JFactory::getConfig();
			$nome_sito=$config->get( 'sitename' );
			$admin_mail=$config->get( 'mailfrom' );
			$user_mail=$utente->email;
			//echo"*****".$utente->sendEmail;
							
			$params = @$this->params;
			$tic_admin= $this->mail_admin; //$params->get('admin_mail','');
			$tic_user= $this->mail_user; //$params->get('user_mail','');
			if($tic_admin==""){$tic_admin="yes";}
			if($tic_user==""){$tic_user="yes";}

			if($segnala!=-1)
			{
				$txt_segnalazione=str_replace("[site_name]", $nome_sito, $this->lingua("txt_segnalazione"));
				$txt_segnalazione=str_replace("[id]", $segnala, $txt_segnalazione);
				$this->jomail($admin_mail, str_replace("[site_name]", $nome_sito, $this->lingua("oggetto_segnalazione")), $txt_segnalazione);
			} 
			if($tic_admin=="yes")
			{
				$this->jomail($admin_mail, str_replace("[site_name]", $nome_sito, $this->lingua("oggetto_evento")), str_replace("[site_name]", $nome_sito, $this->lingua("txt_evento")));
			} 
			if($tic_user=="yes" && $segnala==-1)
			{ 
				$uri = JFactory::getURI();
				
				$url_profilo = $uri->toString();
				$url_profilo=explode("userprofile",$url_profilo);
				
				$uri->setVar( 'notice_board_mail_send', 0);
				$remove_mail = $uri->toString();
				
				$txt_mex=str_replace("[site_name]", $nome_sito, $this->lingua("txt_messaggio"));
				
				$txt_mex=str_replace("[remove_mail]", str_replace(" ","%20",$remove_mail), $txt_mex);
				$txt_mex=str_replace("[site_link]", str_replace(" ","%20",$url_profilo[0]), $txt_mex);

				if(strstr($txt_mex,"[from_user]")){$txt_mex=str_replace("[from_user]", JFactory::getUser()->username, $txt_mex);}
				if(strstr($txt_mex,"[from_first_name]")){
					$database->setQuery('SELECT firstname FROM #__comprofiler WHERE user_id='.JFactory::getUser()->id);
					$firstname = $database->loadAssocList();
					$txt_mex=str_replace("[from_first_name]", $firstname[0]["firstname"], $txt_mex);
				}
				if(strstr($txt_mex,"[from_last_name]")){
					$database->setQuery('SELECT lastname FROM #__comprofiler WHERE user_id='.JFactory::getUser()->id);
					$lastname = $database->loadAssocList();
					$txt_mex=str_replace("[from_last_name]", $lastname[0]["lastname"], $txt_mex);
				}

				if($this->controllo_mail($utente->id, $database))
				$this->jomail($user_mail, str_replace("[site_name]", $nome_sito, $this->lingua("oggetto_messaggio")), $txt_mex);
				//echo "mail inviata a: ".$user_mail."<br />";
				
				if($parente!=0)
				{
					$database->setQuery('SELECT * FROM #__cb_notice_board WHERE da !='.$utente->id.' AND published=1 AND selector="'.$this->selector.'" AND parent='.$parente);
					$results = $database->loadAssocList();
					$controllo=Array();
					for($i=0; $i<count($results); $i++)
					{
						$invia=1;
						for($j=0;$j<count($controllo);$j++)
						{
							if($controllo[$j]==JFactory::getUser($results[$i]['da'])->email)
							{
								$invia=0;
								$break;
							}
						}
						if($invia==1)
						{
							if($this->controllo_mail(JFactory::getUser($results[$i]['da'])->id, $database))
							$this->jomail(JFactory::getUser($results[$i]['da'])->email, str_replace("[site_name]", $nome_sito, $this->lingua("oggetto_messaggio")), $txt_mex);
							//echo "mail inviata a: ".JFactory::getUser($results[$i]['da'])->email."<br />";
							$controllo[]=JFactory::getUser($results[$i]['da'])->email;
						}
					}
				}
			}
		} catch (Exception $e) {
			return false;
		}
	}
	function count_like($postid, $database){
		//conto quanti mi piace / non mi piace ha questo post
		$database->setQuery('SELECT username FROM #__cb_notice_board_like as l, #__users as u WHERE l.userid=u.id AND l.liked=1 AND l.postid ='.$postid);
		$results = $database->loadAssocList();
		$piace_n=count($results);
		$piace="";

		if(count($results)){
			foreach($results as $result){
				$piace.=$result["username"].",";
			}
		}
		
		$database->setQuery('SELECT username FROM #__cb_notice_board_like as l, #__users as u WHERE l.userid=u.id AND l.liked=0 AND l.postid ='.$postid);
		$results = $database->loadAssocList();
		$nopiace_n=count($results);
		$nopiace="";
		
		if(count($results)){
			foreach($results as $result){
				$nopiace.=$result["username"].",";
			}
		}
		
		return(Array("like"=>Array("count"=>$piace_n, "val"=>substr($piace,0,-1)),"unlike"=>Array("count"=>$nopiace_n, "val"=>substr($nopiace,0,-1))));
	}
	function get_image_user($id_user){
		$database = JFactory::getDBO();
		$database->setQuery('SELECT avatar FROM #__comprofiler WHERE user_id ='.$id_user);
		$results = $database->loadAssocList();		

		if($results[0]['avatar'])
			//return JURI::base()."images/comprofiler/".$results[0]['avatar'];
			return JURI::base()."images/comprofiler/tn".$results[0]['avatar'];
		else{
			if($this->user_template!=""){
				$template=$this->user_template;
			}else{
				global $ueConfig;
				$template=@$ueConfig['templatedir'];
			}
			if($template==""){
				$template="default";
			}

			//return JURI::base()."components/com_comprofiler/plugin/templates/".$template."/images/avatar/nophoto_n.png";
			return JURI::base()."components/com_comprofiler/plugin/templates/".$template."/images/avatar/tnnophoto_n.png";
		}
	}
	function mostra_form($comment_id, $mostra, $a)
	{
		if(@$this->is_friend($a) || $this->not_friend=="all"){
			if($mostra==0){$classe_nascondi="class='div_form'";}else{$classe_nascondi="";}//il form di inserimento principale deve essere mostrato mentre quello per i commenti deve essere nascosto
			
			//*********************************************************
			// INIZIO MODIFICA IACOPO
			//*********************************************************
			$smile="";
			if($this->show_smile=="yes"){
				$smile="
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile1' alt=':)'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile2' alt=':D'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile3' alt=':*'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile4' alt=';)'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile5' alt='8)'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile6' alt=':P'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile7' alt=':$'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile8' alt=':-/'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile9' alt=':o'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile10' alt='O:)'></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile11' alt=':('></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile12' alt=':\"('></div>
				<div class='ins_smile idpostsmile_".$comment_id." ins_smile13' alt='>:('></div>
				";
			}
			//*********************************************************
			// FINE MODIFICA IACOPO
			//*********************************************************
			
			return "
			<div id='notice_board_comment_form_".$comment_id."' ".$classe_nascondi.">
				<div class='stile_form'>
				
					<div class='editor_cont'>
						<div title='".$this->lingua("title_link")."' class='ins_link idpostmedia_".$comment_id."'></div>
						<div title='".$this->lingua("title_img")."' class='ins_img idpostmedia_".$comment_id."'></div>
						<div title='".$this->lingua("title_youtube")."' class='ins_video idpostmedia_".$comment_id."'></div>".$smile
			/*
					<form method='post'>
						<textarea name='notice_board_txt' class='notice_board_txt' id='xtx".$comment_id."'></textarea>
						<input type='hidden' name='notice_board_parent' value='".$comment_id."'>
						<input type='hidden' name='notice_board_user' value='".$a."'>
						<input type='submit' name='button_".$this->selector."' class='invia_button' value='".$this->lingua("invia")."' class='notice_board_button'>
					</form>
			*/
	."				</div>
					
					<form>
						<textarea name='notice_board_txt' class='notice_board_txt' id='xtx".$comment_id."'></textarea>
						<input type='hidden' name='notice_board_parent' value='".$comment_id."' />
						<input type='hidden' name='notice_board_user' value='".$a."' />
						<input type='button' name='button_".$this->selector."' class='invia_button' value='".$this->lingua("invia")."' />
					</form>
				</div>
			</div>";
		}
	}
	//function impagina_commento($n_ris,$commenta,$id,$contatore,$da,$a,$date,$txt)
	function impagina_commento($n_ris,$commenta,$id,$contatore,$da,$a,$date,$txt,$database,$user)
	{
		if(@$this->is_friend($a) || $this->not_friend=="all" || $this->not_friend=="read"){
			$visualizza_avatar = $this->show_avatar;

			$par_disp="<div class='commenti_dispari'>";//cambia colore del box se è pari o dispari per alternare i colori
			if($contatore%2==0)
			{
				$par_disp="<div class='commenti_pari'>";
			}
			
			$piace=$this->count_like($id, $database);
			$likeunlike="<span class='like nopiace' id='nonpiace_".$id."' alt='".$piace["unlike"]["val"]."'>".$piace["unlike"]["count"]."</span><span class='like piace' id='piace_".$id."' alt='".$piace["like"]["val"]."'>".$piace["like"]["count"]."</span>";

			$condividi="<form method='post'><input type='hidden' value='".$id."' name='notice_board_condividi'><input class='notice_board_share' type='submit' value='".$this->lingua("condividi_but")."'></form>";
			
			$mostra_commenti=$likeunlike."<span class='notice_board_comment' id='notice_board_".$id."'>".$this->lingua("commenta")."(".$n_ris.")</span>".$condividi."
			
			<form method='post' onSubmit='if(!confirm(\"".$this->lingua("segnala_alert")."\")){return false;}'><input type='hidden' value='".$id."' name='notice_board_segnala'><input type='hidden' value='".$a."' name='notice_board_user'><input class='repo_button' type='submit' value='".$this->lingua("segnala")."'></form>
			
			".$this->mostra_form($id,0,$a);
			if($commenta==0)//i commenti non devono avere i bottoni mostra e commenta
			{
				$mostra_commenti=$likeunlike.$condividi."<form method='post' onSubmit='if(!confirm(\"".$this->lingua("segnala_alert")."\")){return false;}'><input type='hidden' value='".$id."' name='notice_board_segnala'><input type='hidden' value='".$a."' name='notice_board_user'><input class='repo_button' type='submit' value='".$this->lingua("segnala")."'></form>";
			}

			$database->setQuery('SELECT * FROM #__users WHERE id='.$da);
			$results = $database->loadAssocList();
			if(count($results)>0)
				$username_da=JFactory::getUser($da)->username;
			else
				$username_da="removed";
			
			$username_a=JFactory::getUser($a)->username;
				
			if(($visualizza_avatar=="both")&($this->avatar!="")){
				$img_da="<img class='avatar_da' title='".$username_da."' src='".$this->get_image_user($da)."' style='max-width:".$this->avatar[0]."px; max-height:".$this->avatar[1]."px;'> ".$username_da;
				$img_a="<img class='avatar_a' title='".$username_a."' src='".$this->get_image_user($a)."' style='max-width:".$this->avatar[0]."px; max-height:".$this->avatar[1]."px;'> ".$username_a;
			}elseif(($visualizza_avatar=="yes")&($this->avatar!="")){
				$img_da="<img class='avatar_da' title='".$username_da."' src='".$this->get_image_user($da)."' style='max-width:".$this->avatar[0]."px; max-height:".$this->avatar[1]."px;'>";
				$img_a="<img class='avatar_a' title='".$username_a."' src='".$this->get_image_user($a)."' style='max-width:".$this->avatar[0]."px; max-height:".$this->avatar[1]."px;'>";
			}else{
				$img_da=$username_da;
				$img_a=$username_a;
			}

			$img_a_style="";
			if($da==$a || $a=@$user->id){$img_a_style="style='display:none;'";}
			
			if($this->show_link=="yes"){
				$link_al_profilo="index.php?option=com_comprofiler&task=userprofile&user=";
			}else{
				$link_al_profilo="#";
			}
			
			//sostituisce i codici delle faccine con le faccine vere e proprie 
			$txt=str_replace("O:)","<span class='smile ins_smile10'></span>",$txt);
			$txt=str_replace(":)","<span class='smile ins_smile1'></span>",$txt);
			$txt=str_replace(":D","<span class='smile ins_smile2'></span>",$txt);
			$txt=str_replace(":*","<span class='smile ins_smile3'></span>",$txt);
			$txt=str_replace(";)","<span class='smile ins_smile4'></span>",$txt);
			$txt=str_replace("8)","<span class='smile ins_smile5'></span>",$txt);
			$txt=str_replace(":P","<span class='smile ins_smile6'></span>",$txt);
			$txt=str_replace(":$","<span class='smile ins_smile7'></span>",$txt);
			$txt=str_replace(":-/","<span class='smile ins_smile8'></span>",$txt);
			$txt=str_replace(":o","<span class='smile ins_smile9'></span>",$txt);
			$txt=str_replace(">:(","<span class='smile ins_smile13'></span>",$txt);
			$txt=str_replace(":(","<span class='smile ins_smile11'></span>",$txt);
			$txt=str_replace(':"(',"<span class='smile ins_smile12'></span>",$txt);

			return $par_disp."<form method='post'><input type='hidden' name='delete' value='".$id."'><input type='submit' class='close' value='x'></form><a href='".$link_al_profilo.$da."'>".
				$img_da.
			"</a> <span ".$img_a_style." class='notice_board_row'>></span> <a ".$img_a_style." class='notice_board_second_image' href='".$link_al_profilo.$a."'>".
				$img_a.
			"</a>
			<span class='notice_board_data'>".$this->lingua("il")." ".JHtml::_('date', $date, JText::sprintf('DATE_FORMAT_LC3'))." </span><span class='notice_board_ora'>".$this->lingua("alle")." ".JHtml::_('date', $date, 'G:i')."</span><p>".stripslashes(
			$txt)."</p>".$mostra_commenti."
			</div>";
		}else{
			return "";
		}
	}
	function mostra_post($user, $database)
	{
		$params = @$this->params;
		$paginazione = $this->pagination; //$params->get('pagination','');
		if($paginazione==""){$paginazione=10;}
		
		if(JRequest::getVar('page', '', 'get')!="" && is_numeric(JRequest::getVar('page', '', 'get'))){
			$page=JRequest::getVar('page', '', 'get');
		}else{$page=0;}

		$utenti_da_selezionare=""; if($user!="ALL"){$utenti_da_selezionare='a ='.$user->id.' AND ';}
		$database->setQuery('SELECT * FROM #__cb_notice_board WHERE '.$utenti_da_selezionare.'published=1 AND parent=0 AND selector="'.$this->selector.'" ORDER BY date DESC LIMIT '.$page.','.$paginazione);
		$results = $database->loadAssocList();
		$bacheca="";
		for($i=0; $i<count($results); $i++)//scorro tutti i post
		{
			//controllo se ci sono commenti al post attuale
			$database->setQuery('SELECT * FROM #__cb_notice_board WHERE parent ='.$results[$i]['id'].' AND published=1 AND selector="'.$this->selector.'" ORDER BY date DESC');
			$results_parent = $database->loadAssocList();
			
			//mostra post
			//$bacheca=$bacheca.$this->impagina_commento(count($results_parent),1,$results[$i]['id'],$i,$results[$i]['da'],$results[$i]['a'],$results[$i]['date'],$results[$i]['txt']);
			$bacheca=$bacheca.$this->impagina_commento(count($results_parent),1,$results[$i]['id'],$i,$results[$i]['da'],$results[$i]['a'],$results[$i]['date'],$results[$i]['txt'],$database,$user);
			
			//creo il div nascosto con tutti i commenti al post
			$bacheca=$bacheca."<div class='div_comment' id='div_comment_".$results[$i]['id']."'><div class='stile_comment'>";
			
			for($j=0; $j<count($results_parent); $j++)
			{
				//$bacheca=$bacheca.$this->impagina_commento(0,0,$results_parent[$j]['id'],$j,$results_parent[$j]['da'],$results_parent[$j]['a'],$results_parent[$j]['date'],$results_parent[$j]['txt']);
				$bacheca=$bacheca.$this->impagina_commento(0,0,$results_parent[$j]['id'],$j,$results_parent[$j]['da'],$results_parent[$j]['a'],$results_parent[$j]['date'],$results_parent[$j]['txt'],$database,$user);
			}
			$bacheca=$bacheca."</div></div>";
		}
		
		//paginazione
		$database->setQuery('SELECT COUNT(*) FROM #__cb_notice_board WHERE '.$utenti_da_selezionare.'published=1 AND parent=0 AND selector="'.$this->selector.'"');
		$results = $database->loadAssocList();
		$n_record_tot = $results[0]['COUNT(*)'];

		$uri = JFactory::getURI();
		$uri->setVar( 'page', $page-$paginazione);
		$ret = $uri->toString();
		
		$bacheca=$bacheca."<div id='paginazione_cont'>";
		
		if($this->style_pagination=="style1"){
			if($page-$paginazione>=0){$bacheca=$bacheca."<a class='paginazione' href='".$ret."'>".$this->lingua("pagina_precedente")."</a> ";}//link indietro
			for($i=0;$i<$n_record_tot/$paginazione;$i++)//link 1 2 3 4 5....
			{
				$i1=$i+1;
				$parte_da=$i*$paginazione;
				
				$uri = JFactory::getURI();
				$uri->setVar( 'page', $parte_da);
				$ret = $uri->toString();
				$bacheca=$bacheca."<a class='paginazione' href='".$ret."'>".$i1."</a> ";
			}
			
			$uri = JFactory::getURI();
			$uri->setVar( 'page', $page+$paginazione);
			$ret = $uri->toString();
			if($page+$paginazione<$n_record_tot){$bacheca=$bacheca."<a class='paginazione' href='".$ret."'>".$this->lingua("pagina_successiva")."</a> ";}//link avanti
		}else if($this->style_pagination=="style2"){
			$uri = JFactory::getURI();
			$uri->setVar( 'page', 0);
			$ret = $uri->toString();
			if($page-$paginazione>=0){$bacheca=$bacheca."<a class='paginazione' href='".$ret."'><<</a> ";}
			
			$uri = JFactory::getURI();
			$uri->setVar( 'page', $page-$paginazione);
			$ret = $uri->toString();
			if($page-$paginazione>=0){$bacheca=$bacheca."<a class='paginazione' href='".$ret."'><</a> ";}
			
			$bacheca=$bacheca."<a class='paginazione'>".(floor($page/$paginazione)+1)."</a> ";
			
			$uri = JFactory::getURI();
			$uri->setVar( 'page', $page+$paginazione);
			$ret = $uri->toString();
			if($page+$paginazione<$n_record_tot){$bacheca=$bacheca."<a class='paginazione' href='".$ret."'>></a> ";}

			for($i=0;$i<$n_record_tot/$paginazione;$i++){$parte_da=$i*$paginazione;}
			$uri = JFactory::getURI();
			@$uri->setVar('page', $parte_da);
			$ret = $uri->toString();
			if($page+$paginazione<$n_record_tot){$bacheca=$bacheca."<a class='paginazione' href='".$ret."'>>></a> ";}
		}
		
		$bacheca=$bacheca."</div>";
		
		return $bacheca;
	}
   
	function install($database) {
		
		$query = "
			CREATE TABLE IF NOT EXISTS `#__cb_notice_board` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`da` int(11) NOT NULL,
			`a` int(11) NOT NULL,
			`txt` text NOT NULL,
			`date` int(11) NOT NULL,
			`parent` int(11) NOT NULL,
			`published` int(1) NOT NULL,
			`report` int(1) NOT NULL,
			`report_by` int(11) NOT NULL,
			`selector` varchar(255) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
		";
		$database->setQuery($query);
		$result = $database->query();  
		
		$query = "
			CREATE TABLE IF NOT EXISTS `#__cb_notice_board_mail` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`userid` int(11) NOT NULL,
			`sendmail` int(1) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
		";
		$database->setQuery($query);
		$result = $database->query();  
		
		$query = "	
			CREATE TABLE IF NOT EXISTS `#__cb_notice_board_like` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`userid` int(11) NOT NULL,
			`postid` int(11) NOT NULL,
			`liked` int(1) NOT NULL,
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
		";
		$database->setQuery($query);
		$result = $database->query();  
	}
	
	function start($user) {
				
		$database = JFactory::getDBO();
		$user_me = JFactory::getUser();
 
        //controllo: se non ci sono le tabelle le creo
        $database->setQuery("SHOW TABLES LIKE '%cb_notice_board_like%'");
        $elenco_delle_tabelle = $database->loadAssocList();
        if(count($elenco_delle_tabelle)==0){$this->install($database);}
		
		if(JRequest::getVar('new_photo', '', 'get')=="1")//mostra il popup per l'inserimento immagine
		{
			if(JRequest::getVar('urlimg', '', 'post')!=""){
				die("<div id='img_panel_content' style='font-family:arial; font-size:14px;'>".$this->lingua('incolla_codice_immagine')."<br /><br /><i>[IMG]".JRequest::getVar('urlimg', '', 'post')."[/IMG]</i></div>");
			}
			if(JRequest::getVar('img_submit', '', 'post')!="" && $user_me->get('id')!=0){
				$lestensione_txt=           $this->lingua('lestensione_txt');
				$non_consentita_txt=        $this->lingua('non_consentita_txt');
				$la_cartella_txt=           $this->lingua('la_cartella_txt');
				$non_esiste_txt=            $this->lingua('non_esiste_txt');
				$errore_upload_txt=         $this->lingua('errore_upload_txt');
				$grandezza_minore_txt=      $this->lingua('grandezza_minore_txt');
				$grandezza_maggiore_txt=    $this->lingua('grandezza_maggiore_txt');
				$max_upload_param='2000000';

				$errori_upload=Array("lestensione_txt"=>$lestensione_txt, "non_consentita_txt"=>$non_consentita_txt, "la_cartella_txt"=>$la_cartella_txt, "non_esiste_txt"=>$non_esiste_txt, "errore_upload_txt"=>$errore_upload_txt, "grandezza_minore_txt"=>$grandezza_minore_txt, "grandezza_maggiore_txt"=>$grandezza_maggiore_txt);

				require_once(dirname(__FILE__).DS."upload".DS."clsUpload.php");
				require_once(dirname(__FILE__).DS."upload".DS."upload.php");
			}
			die("<div id='img_panel_content' style='font-family:arial; font-size:14px;'>".$this->lingua("inserisci_urlimg")."<br />
			<form method='post'>
				<input type='text' name='urlimg'>
				<input type='submit' value='".$this->lingua("title_img")."'>
			</form>
			<div style='width:100%; height:3px; background:#333; margin:15px 0;'></div>
			Upload<br />
			<form method='post' enctype='multipart/form-data'>
				<input type='file' name='fileimg'>
				<input type='submit' name='img_submit' value='".$this->lingua("title_img")."'>
			</form></div>
			");
		}
		
		if(JRequest::getVar('like', '', 'get')!="")//mette il mi piace / non mi piace
		{
			$database->setQuery('SELECT * FROM #__cb_notice_board WHERE id ='.JRequest::getVar('id_like', '', 'get'));
			$results = $database->loadAssocList();
			if($results[0]["da"]==$user_me->get('id')){
				die("[LIKE_PERSONAL_POST]");
			}else{
				$query='DELETE FROM #__cb_notice_board_like WHERE postid='.JRequest::getVar('id_like', '', 'get').' AND userid='.$user_me->get('id');
				$database->setQuery($query);
				$database->query();
				
				//inserisco nel database
				$like=0; if(JRequest::getVar('like', '', 'get')=="piace"){$like=1;}
				$query='INSERT INTO #__cb_notice_board_like (userid, postid, liked) VALUES ('.$user_me->get('id').', '.JRequest::getVar('id_like', '', 'get').', '.$like.')';
				$database->setQuery($query);
				$database->query();
				
				$piace=$this->count_like(JRequest::getVar('id_like', '', 'get'), $database);
				die("[LIKE]".$piace["like"]["count"]."[LIKE]".$piace["like"]["val"]."[LIKE]".$piace["unlike"]["count"]."[LIKE]".$piace["unlike"]["val"]);
			}
		}
		
		if(JRequest::getVar('delete', '', 'post')!="")//cancella post
		{
			if($user_me->get('id')==0){JError::raiseWarning( 100, 'Login required' );}
			else{
				$database->setQuery('SELECT * FROM #__cb_notice_board WHERE id ='.JRequest::getVar('delete', '', 'post'));
				$results = $database->loadAssocList();
				if(count($results)>0){
					if($results[0]['a']==$user_me->get('id') || $results[0]['da']==$user_me->get('id'))
					{
						$query='DELETE FROM #__cb_notice_board WHERE id='.JRequest::getVar('delete', '', 'post');
						$database->setQuery($query);
						$database->query(); //$results=$database->query();
					}
					else
					{
						echo"<script>alert('".$this->lingua("cancella_error")."');</script>";
					}
				}
			}
		}
				
		if(JRequest::getVar('notice_board_txt', '', 'post')!="" && JRequest::getVar('notice_board_parent', '', 'post')!="" && JRequest::getVar('button_'.$this->selector, '', 'post')!="" && JRequest::getVar('notice_board_user', '', 'post')!="" && (@$this->is_friend($user->user_id) || $this->not_friend=="all"))//inserisce nuovo post nel db
		{ 
			$session = JFactory::getSession();
			if(time()<($session->get('last_notice_board')+$this->antiflood)){die('AntiFlood');}
			if($user_me->get('id')==0){die('Login required');}
			else if(is_numeric(JRequest::getVar('notice_board_parent', '', 'post')) && is_numeric(JRequest::getVar('notice_board_user', '', 'post')))
			{ 
				$txt_post=JRequest::getVar('notice_board_txt', '', 'post');
				JRequest::setVar('notice_board_txt', '');
				$txt_post=addslashes($txt_post);
				
				$txt_post=str_replace("[IMG]", "<img class='user_img' src='" ,$txt_post);
				$txt_post=str_replace("[/IMG]", "' />" ,$txt_post);
				$txt_post=str_replace("[URL]", "<a target='_blank' href='" ,$txt_post);
				$txt_post=str_replace("[/URL]", "'>" ,$txt_post);
				$txt_post=str_replace("[NAME_URL]", "" ,$txt_post);
				$txt_post=str_replace("[/NAME_URL]", "</a>" ,$txt_post);
				$txt_post=str_replace("[YOUTUBE]", "<iframe width='420' height='315' src='http://www.youtube.com/embed/" ,$txt_post);
				
				$txt_post=str_replace("[/YOUTUBE]", "?wmode=transparent' frameborder='0' allowfullscreen></iframe>" ,$txt_post);
				$txt_post=str_replace("\n", "<br />" ,$txt_post);
				
				$session->set('last_notice_board', time());
				$query='INSERT INTO #__cb_notice_board (da, a, txt, date, parent, published, report, selector) VALUES ('.$user_me->get('id').', '.JRequest::getVar('notice_board_user', '', 'post').', "'.$txt_post.'", '.time().', '.JRequest::getVar('notice_board_parent', '', 'post').',1,0, "'.$this->selector.'")';
				$database->setQuery($query);
				$database->query(); //$results=$database->query();
				$last_id = $database->insertid();

				$this->invia_mail(JFactory::getUser(JRequest::getVar('notice_board_user', '', 'post')),-1,JRequest::getVar('notice_board_parent', '', 'post'),$database);
				
				die($this->impagina_commento(0,0,$last_id,JRequest::getVar('paridispari', '', 'post'),$user_me->get('id'),JRequest::getVar('notice_board_user', '', 'post'),time(),$txt_post,$database,$user));
			}
		} 
		if(JRequest::getVar('notice_board_condividi', '', 'post')!=""){ //condivide un post
			$database->setQuery('SELECT * FROM #__cb_notice_board WHERE id ='.JRequest::getVar('notice_board_condividi', '', 'post'));
			$results = $database->loadAssocList();

			$query='INSERT INTO #__cb_notice_board (da, a, txt, date, parent, published, report, selector) VALUES ('.$user_me->get('id').', '.$user_me->get('id').', "'.$this->lingua("condividi")." <a href='index.php?option=com_comprofiler&task=userprofile&user=".$user->id."'>".$user->username."</a><br />".$results[0]["txt"].'", '.time().', 0,1,0, "'.$this->selector.'")';
			$database->setQuery($query);
			$database->query();
		}
		if(JRequest::getVar('notice_board_segnala', '', 'post')!="" && JRequest::getVar('notice_board_user', '', 'post')!="")//inserisce segnalazione
		{
			if($user_me->get('id')==0){JError::raiseWarning( 100, 'Login required' );}
			else if(is_numeric(JRequest::getVar('notice_board_segnala', '', 'post')) && is_numeric(JRequest::getVar('notice_board_user', '', 'post')))
			{
				/*$params = @$this->params;
				$if_repo = $params->get('if_report','');
				if($if_repo==""){$if_repo="no";}
				if($if_repo=="yes"){$unpublished=", published=0";}
				else{$unpublished="";}*/
	
				$query='UPDATE #__cb_notice_board SET report_by='.$user_me->get('id').', report=1 WHERE id='.JRequest::getVar('notice_board_segnala', '', 'post');
				$database->setQuery($query);
				$database->query(); //$results=$database->query();
				
				$this->invia_mail(JFactory::getUser(JRequest::getVar('notice_board_user', '', 'post')),JRequest::getVar('notice_board_segnala', '', 'post'),0,$database);
				
				echo JFactory::getApplication()->enqueueMessage($this->lingua("segnalazione_inviata"), 'message');
			}
		}
		if(JRequest::getVar('notice_board_mail_send', '', 'get')!="" && is_numeric(JRequest::getVar('notice_board_mail_send', '', 'get')))//rimuove l'indirizzo mail dalle notifiche
		{
			$query='UPDATE #__cb_notice_board_mail SET sendmail='.JRequest::getVar('notice_board_mail_send', '', 'get').' WHERE userid='.$user_me->id;
			$database->setQuery($query);
			$database->query(); //$results=$database->query();
		}
		
		$form='';
		if(@$this->is_friend($user->user_id) || $this->not_friend=="all" || $this->not_friend=="read"){
		
		}else{
			$form='visible to friends (connections)';
		}
		
		if($this->jquery!=0){
			$form=$form.'<script src="http://code.jquery.com/jquery-latest.js"></script>';
		}
		
		//*********************************************************
		// INIZIO MODIFICA IACOPO
		//*********************************************************
		if(@$_GET["css_mode"]==1){
			echo'<link rel="stylesheet" href="'.$this->foglio_stile.'" type="text/css" />';
		}else{
			$document = JFactory::getDocument();
			$document->addStyleSheet($this->foglio_stile);
		}
		//*********************************************************
		// FINE MODIFICA IACOPO
		//*********************************************************
		
		if($user=="ALL"){$form=$form.$this->mostra_form(0,1,$user_me->id);}else{$form=$form.$this->mostra_form(0,1,$user->id);}
		
		$form=$form.$this->mostra_post($user, $database);

		//$current_url=JURI::getInstance()->toString();
		//$current_url=JURI::current();
		$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$iframe_url=$current_url;
		if(strstr($iframe_url,"?")){$iframe_url.="&new_photo=1";}else{$iframe_url.="?new_photo=1";}

		//animazione per espandere la finestra che visualizzerà il form dei commenti e anche l'elenco dei commenti
		$form=$form.
		'<script type="text/javascript">
		script_notice_board=0;
		
		( function($) {
		jQuery.noConflict();
		jQuery(document).ready(function($){

		$(".notice_board_comment").click(function(){
			var id_commento=$(this).attr("id");
			id_commento=id_commento.split("notice_board_");
			id_commento=id_commento[1];

			if($("#notice_board_comment_form_"+id_commento).height()>0)
			{
				$("#notice_board_comment_form_"+id_commento).animate({height: "0px"}, 800 );
			}
			else
			{
				$("#notice_board_comment_form_"+id_commento).css("height","auto");
				var alza_di=parseInt($("#notice_board_comment_form_"+id_commento).height());
				$("#notice_board_comment_form_"+id_commento).css("height","0px");
				$("#notice_board_comment_form_"+id_commento).animate({height: alza_di+"px"}, 800 );
			}
			
			if($("#div_comment_"+id_commento).height()>0)
			{
				$("#div_comment_"+id_commento).animate({height: "0px"}, 800 );
			}
			else if($("#div_comment_"+id_commento+" .stile_comment").html()!="")
			{
				var alza_di=0;
				/*$("#div_comment_"+id_commento+" .stile_comment div").each(function(indice,elemento){
					alza_di=alza_di+$(this).height()+parseInt($(this).css("paddingTop"))+parseInt($(this).css("paddingBottom"));
				});
				if(alza_di>$(".stile_comment").height()){alza_di=$(".stile_comment").height();}*/
				$("#div_comment_"+id_commento+", .stile_comment").css("height","auto");
				alza_di=$("#div_comment_"+id_commento).height()+"px";
				
				$("#div_comment_"+id_commento+", .stile_comment").css("height","0px");
				$("#div_comment_"+id_commento+", .stile_comment").animate({height: alza_di}, 800 );
			}

		});
		
		if(script_notice_board!=1){
			script_notice_board=1;
			$(".ins_link").click(function(){
				var url=prompt("'.$this->lingua("inserisci_url").'");
				var name_url=prompt("'.$this->lingua("inserisci_urlname").'");
				
				id_testo=$(this).attr("class").split(" ");
				id_testo=id_testo[1].split("_");
				id_testo=id_testo[1];
				
				var testo=$(".stile_form form #xtx"+id_testo).val();
				if(url!=null && name_url!=null){$(".stile_form form #xtx"+id_testo).val(testo+"[URL]"+url+"[/URL][NAME_URL]"+name_url+"[/NAME_URL]");}
			});
			
			$(".ins_img").click(function(){
				id_testo=$(this).attr("class").split(" ");
				id_testo=id_testo[1].split("_");
				id_testo=id_testo[1];
				
				$(this).append("<div id=\"ins_img_panel\"><div id=\"close_img_panel\">X</div><div id=\"img_panel_load\"></div><iframe width=\"430\" height=\"135\" src=\"'.$iframe_url.'\"></iframe></div>");

				setTimeout(function(){$("#img_panel_load").remove();},1500);
				
				$("#close_img_panel").click(function(e){
					e.stopPropagation();
					img_tag=$("#ins_img_panel iframe").contents().find("#img_panel_content").html();
					
					if(img_tag.indexOf("[IMG]")!=-1){
						img_tag=img_tag.split("[IMG]");
						img_tag=img_tag[1].split("[/IMG]");
						
						var testo=$(".stile_form form #xtx"+id_testo).val();
						$(".stile_form form #xtx"+id_testo).val(testo+"[IMG]"+img_tag[0]+"[/IMG]");
					}
					
					$("#ins_img_panel").remove();
				});
			});
			
			$(".ins_video").click(function(){
				var url=prompt("'.$this->lingua("inserisci_urlyoutube").'");
				
				id_testo=$(this).attr("class").split(" ");
				id_testo=id_testo[1].split("_");
				id_testo=id_testo[1];

				var testo=$(".stile_form form #xtx"+id_testo).val();
				if(url!=null){$(".stile_form form #xtx"+id_testo).val(testo+"[YOUTUBE]"+url+"[/YOUTUBE]");}
			});
		
			//*********************************************************
			// INIZIO MODIFICA IACOPO
			//*********************************************************
			$(".ins_smile").click(function(){
				id_testo=$(this).attr("class").split(" ");
				id_testo=id_testo[1].split("_");
				id_testo=id_testo[1];
				$(".stile_form form #xtx"+id_testo).val($(".stile_form form #xtx"+id_testo).val()+$(this).attr("alt"));
			});
			//*********************************************************
			// FINE MODIFICA IACOPO
			//*********************************************************
			
			$(document).delegate(".like","click",function(){
				id=$(this).attr("id").split("_");
				$(this).prepend("<div class=\'load_insert\' style=\'position:absolute; margin:5px 0 0 1px;\'></div>");
				$.ajax({
					url: "'.$current_url.'",
					type: "GET",
					data: {id_like : id[1], like : id[0]},
					success: function(e){
						if(e.indexOf("[LIKE_PERSONAL_POST]")!=-1){
							$(".load_insert").remove();
						}else{
							conteggio=e.split("[LIKE]");
							$("#piace_"+id[1]).html(conteggio[1]);
							$("#nonpiace_"+id[1]).html(conteggio[3]);
							$("#piace_"+id[1]).attr("alt",conteggio[2]);
							$("#nonpiace_"+id[1]).attr("alt",conteggio[4]);
						}
					}
				});
			});
			
			$(document).delegate(".like","mouseover",function(){
				if($(this).attr("alt")!=""){
					pos_like=$(this).offset();
					$("body").prepend("<div id=\"like_tooltip\" style=\"left:"+(pos_like.left)+"px; top:"+(pos_like.top+30)+"px;\">"+$(this).attr("alt").replace(/,/g,"<br />")+"</div>");
				}
			});
			
			$(document).delegate(".like","mouseleave",function(){
				$("#like_tooltip").remove();
			});

			$(".invia_button").click(function(evento){
				//creo la query
				querynsert="";
				$(this).parent().children().each(function(){
					querynsert+=$(this).attr("name")+"="+$(this).val()+"&";
					if($(this).attr("name")=="notice_board_parent"){parent_press=$(this).val();}
				});
				querynsert=querynsert.substr(0,querynsert.length-1);
				querynsert+="&paridispari=1";
				
				$("#notice_board_comment_form_"+parent_press).prepend("<div class=\'load_insert\'></div>");
				
				$.ajax({
					url: "'.$current_url.'",
					type: "POST",
					data: querynsert,
					success: function(e){
						if(e.indexOf("AntiFlood")!=-1){
							alert("'.$this->lingua("antiflood").' '.$this->antiflood.'\'");
						}else if(e=="Login required"){
							alert("Login required");
						}else{
							if(parent_press==0){
								$("#notice_board_comment_form_0").after(e);
							}else{
								$("#div_comment_"+parent_press+" .stile_comment").prepend(e);
								$("#div_comment_"+parent_press+", .stile_comment").css("height","auto");
								
								numero_commenti=$("#notice_board_"+parent_press+".notice_board_comment").html().split("(");
								numero_commenti=parseInt(numero_commenti[1].substr(0,numero_commenti[1].length-1))+1;
								$("#notice_board_"+parent_press+".notice_board_comment").html("'.$this->lingua("commenta").'("+numero_commenti+")");
							}
						}
						$(".load_insert").remove();
					}
				});
			});
		}
		});
		} ) ( jQuery );
		</script><br /><span style="font-size:10px;">Powered by <a href="http://www.iacopo-guarneri.me/" target="_blank">Iacopo Guarneri</a></span>';

		return $form;
	}

}
?>
