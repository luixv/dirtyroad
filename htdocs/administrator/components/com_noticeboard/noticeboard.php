<?php @session_start();

/*
* @name CB_notice_board 1.0
* Created By Guarneri Iacopo
* http://www.iacopo-guarneri.me/
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

	JToolBarHelper::title(
				'Post');

	/*JToolBarHelper::cancel( 'cancel', 'COM_PHOCAMAPS_CLOSE' );			

	JSubMenuHelper::addEntry(
			'Main',
			'index.php?option=com_noticeboard',
			$module == ''
		);*/	
	
	$lang = JFactory::getLanguage()->getName();
	$lang=explode(" (",$lang);
	$lang=$lang[0];

	if (file_exists("/lang/".$lang.".php"))
		require_once("../components/com_noticeboard/lang/".$lang.".php");
	else
		require_once("../components/com_noticeboard/lang/english.php");
			
	$lingua_bacheca=new notice_board_language();
	
	require_once("../configuration.php");
	$config=new JConfig();
	$nome_sito=$config->sitename;
	
	$database = JFactory::getDBO();
	$user = JFactory::getUser();
	
	if(class_exists('NoticeBoard')){$cmt=new NoticeBoard();}else{require_once("../components/com_noticeboard/core.php");$cmt=new NoticeBoard();}
	$cmt=new NoticeBoard();

	//controllo: se non ci sono le tabelle le creo
	$database->setQuery("SHOW TABLES LIKE '%cb_notice_board_like%'");
	$elenco_delle_tabelle = $database->loadAssocList();
	if(count($elenco_delle_tabelle)==0){$cmt->install($database);}
	
	if(isset($_GET['page']))
	{
		$page=$_GET['page'];
	}
	else{$page=0;}

	//pubblica non pubblica
	if(isset($_GET['published']) && isset($_GET['id']))
	{
		$query='UPDATE #__cb_notice_board SET published='.$_GET['published'].' WHERE id='.$_GET['id'];
		$database->setQuery($query);
		$results=$database->query();
		
		if($_GET['published']==0 && isset($_GET['published']) && $_GET['published']!="")
		{
			$database->setQuery('SELECT * FROM #__cb_notice_board WHERE id ='.$_GET['id']);
			$results = $database->loadAssocList();
			
			mail(JFactory::getUser(@$results[0]['da'])->email, str_replace("[site_name]", $nome_sito, $lingua_bacheca->oggetto_segnalato), str_replace("[site_name]", $nome_sito, $lingua_bacheca->txt_segnalato)."
			
			--------------------------------
			".@$results[0]['txt']);
		}
	}

	//cancella
	if(isset($_GET['delete']))
	{
		$query='DELETE FROM #__cb_notice_board WHERE id='.$_GET['delete'].' OR parent='.$_GET['delete'];
		$database->setQuery($query);
		$results=$database->query();
	}
	
	//ordinamento
	$as_id="ASC";
	$as_da="ASC";
	$as_a="ASC";
	$as_txt="ASC";
	$as_date="ASC";
	$as_parent="ASC";
	$as_published="ASC";
	$as_delete="ASC";
	$as_report="ASC";
	$as_report_by="ASC";
	if(isset($_GET['order']) && isset($_GET['ascdesc']))
	{
		$ordina=" ORDER BY ".$_GET['order']." ".$_GET['ascdesc'];
		if($_GET['ascdesc']=="ASC")
		{
			if($_GET['order']=="id"){$as_id="DESC";}
			if($_GET['order']=="da"){$as_da="DESC";}
			if($_GET['order']=="a"){$as_a="DESC";}
			if($_GET['order']=="txt"){$as_txt="DESC";}
			if($_GET['order']=="date"){$as_date="DESC";}
			if($_GET['order']=="parent"){$as_parent="DESC";}
			if($_GET['order']=="published"){$as_published="DESC";}
			if($_GET['order']=="delete"){$as_delete="DESC";}
			if($_GET['order']=="report"){$as_report="DESC";}
			if($_GET['order']=="report_by"){$as_report_by="DESC";}
		}
	}
	else{$ordina=" ORDER BY date DESC ";}
	
	//cerca
	if(@$_POST['cerca']!="" || @$_SESSION['cerca']!="")
	{
		if(isset($_POST['cerca']))
		{
			$_SESSION['cerca']=$_POST['cerca'];
		}
		$cerca=" WHERE txt LIKE '%".$_SESSION['cerca']."%'";
		$page=0;
	}
	else{$cerca=""; $_SESSION['cerca']="";}
	
	//limit
	if(@$_POST['filtro']!="" || @$_SESSION['filtro']!="")
	{
		if(isset($_POST['filtro']))
		{
			$_SESSION['filtro']=$_POST['filtro'];
		}
		$limit=" LIMIT ".$page.",".$_SESSION['filtro'];
		$page=0;
	}
	else{$limit=""; $_SESSION['filtro']=10;}

	$database->setQuery('SELECT * FROM #__cb_notice_board'.$cerca.$ordina.$limit);
	$results = $database->loadAssocList();	
	
	echo "
	<form method='post'>
		<label>Search in text </label> <input type'text' name='cerca' value='".$_SESSION['cerca']."'>
		<select name='filtro'>
			<option value='1'";if($_SESSION['filtro']==1){echo" selected='selected' ";}echo">1</option>
			<option value='2'";if($_SESSION['filtro']==2){echo" selected='selected' ";}echo">2</option>
			<option value='3'";if($_SESSION['filtro']==4){echo" selected='selected' ";}echo">4</option>
			<option value='5'";if($_SESSION['filtro']==5){echo" selected='selected' ";}echo">5</option>
			<option value='10'";if($_SESSION['filtro']==10){echo" selected='selected' ";}echo">10</option>
			<option value='15'";if($_SESSION['filtro']==15){echo" selected='selected' ";}echo">15</option>
			<option value='20'";if($_SESSION['filtro']==20){echo" selected='selected' ";}echo">20</option>
			<option value='30'";if($_SESSION['filtro']==30){echo" selected='selected' ";}echo">30</option>
			<option value='50'";if($_SESSION['filtro']==50){echo" selected='selected' ";}echo">50</option>
			<option value='100'";if($_SESSION['filtro']==100){echo" selected='selected' ";}echo">100</option>
			<option value='200'";if($_SESSION['filtro']==200){echo" selected='selected' ";}echo">200</option>
			<option value='300'";if($_SESSION['filtro']==300){echo" selected='selected' ";}echo">300</option>
			<option value='500'";if($_SESSION['filtro']==500){echo" selected='selected' ";}echo">500</option>
			<option value='1000'";if($_SESSION['filtro']==1000){echo" selected='selected' ";}echo">1000</option>
			<option value='2000'";if($_SESSION['filtro']==2000){echo" selected='selected' ";}echo">2000</option>
			<option value='5000'";if($_SESSION['filtro']==5000){echo" selected='selected' ";}echo">5000</option>
		</select>
		<input type='submit' value='filter'><br />
	</form>";
	
	echo"<br /><table class='adminlist table table-striped'>
	<tr>
		<td><a href='index.php?option=com_noticeboard&order=id&ascdesc=".$as_id."'>id</a></td>
		<td><a href='index.php?option=com_noticeboard&order=da&ascdesc=".$as_da."'>from</a></td>
		<td><a href='index.php?option=com_noticeboard&order=a&ascdesc=".$as_a."'>to</a></td>
		<td><a href='index.php?option=com_noticeboard&order=txt&ascdesc=".$as_txt."'>txt</a></td>
		<td><a href='index.php?option=com_noticeboard&order=date&ascdesc=".$as_date."'>date</a></td>
		<td><a href='index.php?option=com_noticeboard&order=parent&ascdesc=".$as_parent."'>parent</a></td>
		<td><a href='index.php?option=com_noticeboard&order=published&ascdesc=".$as_published."'>published</a></td>
		<td><a href='index.php?option=com_noticeboard&order=delete&ascdesc=".$as_delete."'>delete</a></td>
		<td><a href='index.php?option=com_noticeboard&order=report&ascdesc=".$as_report."'>report</a></td>
		<td><a href='index.php?option=com_noticeboard&order=report_by&ascdesc=".$as_report_by."'>report by</a></td>
	</tr>";
	for($i=0; $i<count($results); $i++)
	{
		if($results[$i]['published']==1)
		{
			$uri = JFactory::getURI();
			$uri->setVar( 'published', 0);
			$uri->setVar( 'id', $results[$i]['id']);
			$ret = $uri->toString();
			$pub="<a href='".$ret."'><img src='../components/com_comprofiler/images/tick.png'></a>";
		}
		else
		{
			$uri = JFactory::getURI();
			$uri->setVar( 'published', 1);
			$uri->setVar( 'id', $results[$i]['id']);
			$ret = $uri->toString();
			$pub="<a href='".$ret."'><img src='../components/com_comprofiler/images/publish_x.png'></a>";
		}
		
		if($results[$i]['report']==1){$rep="yes"; $rep_by=JFactory::getUser($results[$i]['report_by'])->username;}
		if($results[$i]['report']==0){$rep="no"; $rep_by="";}

		if($i%2==0){$cls_row="row1";}else{$cls_row="row0";}
		
		echo "<tr class='".$cls_row."'>
			<td>".$results[$i]['id']."</td>
			<td>".JFactory::getUser($results[$i]['da'])->username."</td>
			<td>".JFactory::getUser($results[$i]['a'])->username."</td>
			<td>".stripslashes($results[$i]['txt'])."</td>
			<td>il: ".date('j/n/Y ', $results[$i]['date'])." alle ".date('G:i', $results[$i]['date'])."</td>
			<td>".$results[$i]['parent']."</td>
			<td>".$pub."</td>
			<td><a href='index.php?option=com_noticeboard&delete=".$results[$i]['id']."'><img src='../components/com_comprofiler/images/delavatar.gif'></a></td>
			<td>".$rep."</td>
			<td>".$rep_by."</td>
		</tr>";
	}
	echo"</table>";
	$uri = JFactory::getURI();
	$uri->setVar( 'published', '');
	$uri->setVar( 'id', '');
	
	//paginazione
	echo"<br /><p style='font-size:18px; text-align:center;'>";
	$database->setQuery('SELECT COUNT(*) FROM #__cb_notice_board'.$cerca);
	$n_record_tot = $database->loadResult();

	$uri = JFactory::getURI();
	$uri->setVar( 'page', $page-$_SESSION['filtro']);
	$ret = $uri->toString();
	
	if($page-$_SESSION['filtro']>=0){echo " <a href='".$ret."'><</a> ";}//link indietro
	for($i=0;$i<$n_record_tot/$_SESSION['filtro'];$i++)//link 1 2 3 4 5....
	{
		$i1=$i+1;
		$parte_da=$i*$_SESSION['filtro'];
		
		$uri = JFactory::getURI();
		$uri->setVar( 'page', $parte_da);
		$ret = $uri->toString();
		echo " <a href='".$ret."'>".$i1."</a> ";
	}
	
	$uri = JFactory::getURI();
	$uri->setVar( 'page', $page+$_SESSION['filtro']);
	$ret = $uri->toString();
	if($page+$_SESSION['filtro']<$n_record_tot){echo " <a href='".$ret."'>></a> ";}//link avanti
	echo"</p>";
?>
