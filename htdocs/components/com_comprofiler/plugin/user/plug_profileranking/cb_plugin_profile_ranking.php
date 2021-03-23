<?php
/*
* @name CB_profile_ranking 1.0
* Created By Guarneri Iacopo
* http://www.the-html-tool.com/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// Direct Acess not allowed!
// no direct access
defined('_JEXEC') or die(dirname(__FILE__).DS.'Restricted access');

//tasto per ripubblicare sulla propria bacheca post di altri
//creare componente/modulo per mostrare la bacheca generale

class cbprofile_rankingTab extends cbTabHandler {
	function install($database) {
		$query = "
			CREATE TABLE IF NOT EXISTS `#__cb_profile_ranking` (
			`id_pr` int(11) NOT NULL AUTO_INCREMENT,
			`da` int(11) NOT NULL,
			`a` int(11) NOT NULL,
			`liked` int(1) NOT NULL,
			`date` datetime,
			PRIMARY KEY  (`id_pr`)
			) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
		";
		$database->setQuery($query);
		$result = $database->query();  
	}
	function set_vote($user, $vot){
		$user_me = JFactory::getUser();
		
		if($user_me->get('id')==0){
			JError::raiseWarning( 100, 'Login required' );
		}else if($user_me->get('id')==$user->get('id')){
			JError::raiseWarning( 100, 'you can not put "like" to your profile' );
		}else{
			$database = JFactory::getDBO();

			$database->setQuery('SELECT id_pr FROM #__cb_profile_ranking WHERE da='.$user_me->get('id').' AND a='.$user->get('id'));
			$count_rank = $database->loadAssocList();
			if(count($count_rank)==0){
				$database->setQuery('INSERT INTO #__cb_profile_ranking (da, a, liked, date) VALUES ('.$user_me->get('id').', '.$user->get('id').', '.$vot.', NOW())');
				$database->query();
			}else{
				$database->setQuery('UPDATE #__cb_profile_ranking SET liked='.$vot.', date=NOW() WHERE id_pr='.$count_rank[0]["id_pr"].' AND MONTH(date) = MONTH(CURDATE())');
				$database->query();
			}

			JFactory::getApplication()->enqueueMessage('Rating added');
	
			//echo 'Errore: ', $database->getErrorNum(), '<br />';
			//echo $database->getErrorMsg();
		}
	}
	function getDisplayTab($tab,$user,$ui) {
		$database = JFactory::getDBO();

		/*$query = "DROP TABLE IF EXISTS `#__cb_profile_ranking`;";
		$database->setQuery($query);
		$result = $database->query();*/

        //controllo: se non ci sono le tabelle le creo
        $database->setQuery("SHOW TABLES LIKE '%cb_profile_ranking%'");
        $elenco_delle_tabelle = $database->loadAssocList();
        if(count($elenco_delle_tabelle)==0){$this->install($database);}
		
		if(JRequest::getVar('set_like', '', 'post')!='')
			$this->set_vote($user, 1);
		if(JRequest::getVar('set_unlike', '', 'post')!='')
			$this->set_vote($user, 0);
		
		//prelevo i like dell'utente corrente
		$database->setQuery('SELECT * FROM #__cb_profile_ranking WHERE a ='.$user->get('id').' AND liked=1');
		$pos = $database->loadAssocList();
		$database->setQuery('SELECT * FROM #__cb_profile_ranking WHERE a ='.$user->get('id').' AND liked=0');
		$neg = $database->loadAssocList();
		
		//visualizzo i like
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::base()."/components/com_comprofiler/plugin/user/plug_profileranking/cb_plugin_profile_ranking.css");
		
        return '
		<table class="cbFieldsContentsTab cbFields" id="cb_profile_ranking_tab">
			<tr class="sectiontableentry2 cbft_counter" id="cb_profile_ranking_row">
				<td class="titleCell"><label id="cb_profile_ranking_col1">My Like</label></td>
				<td class="fieldCell" id="cb_profile_ranking_col2">
					<form method="post">
						<input type="submit" value="'.count($neg).'" name="set_unlike" id="set_unlike_rank" class="like_rank" />
						<input type="submit" value="'.count($pos).'" name="set_like" id="set_like_rank" class="like_rank" />
					</form>
				</td>
			</tr>
		</table>';
	}
}
?>
