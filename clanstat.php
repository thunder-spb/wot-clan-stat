<?php 

require_once('settings.kak');  

if (array_key_exists("idc",$_GET)) {
	$idc = filter_input(INPUT_GET, "idc", FILTER_VALIDATE_INT);
} else  {
	$idc = $clan_array[0]['clan_id'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>
<?php  
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
$clanlist = mysql_query("select cl.tag as tag, cl.name as name, rate, firepower, skill, position,smallimg,alliances.name as aname from clan_info as cl left join alliances on cl.alliansid=alliances.ida  where idc='$idc'",$connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$clanrow=mysql_fetch_array($clanlist,MYSQL_ASSOC);
echo $clanrow['tag'];
$user=@$_COOKIE['user'];
$pl = mysql_query("select player.idp,player.name as name,player.idc as idc, clan.role_localised as role,player.date from player  join clan on player.idp=clan.idp  where player.idp='$user' order by player.date desc ");
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$pl=mysql_fetch_array($pl,MYSQL_ASSOC);
$token=@$_COOKIE['atoken'];
$ip = mysql_query("select * from access_log where idp='$user' and token='$token'",$connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$ip=mysql_fetch_array($ip,MYSQL_ASSOC);
$validclan=0;
$user1=1;
if (($ip['token']==NULL)or (!isset($_COOKIE['atoken']))){$user1=0;}
 foreach ($clan_array as $clan_i) {
	 $idc_temp = $clan_i["clan_id"];
	 if ($pl['idc'] == $idc_temp) {
		 $validclan=1;
	 }
 }
if (isset ($alliansid)){
	if (isset ($alliansid)){
		$al = mysql_query("select tag from alliances  where ida='$alliansid'",$connect);
		if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
		$ida=mysql_fetch_array($al,MYSQL_ASSOC);
	}	
	$idc_temp=$_COOKIE['idc'];
	$al = mysql_query("select allians from  clan_info where idc='$idc_temp'",$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$al=mysql_fetch_array($al,MYSQL_ASSOC);
	if ($al['allians']==1){$validclan=1;}
	}

//echo $idc;
//}
?>
</title>
<?php
echo '<link rel="icon" type="image/png" href="'.$clanrow['smallimg'].'" />';
?>
<link type="text/css" href="css/blitzer/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/jquery.jqplot.min.css" />
	<style>
html, body {
	margin: 0;
	padding: 0;
	font-size: 80%;
}
body {
	overflow: scroll;
}

</style>

<script type="text/javascript">
	var current_clan_id = <?php echo $idc ?>;
	var role = "<?php echo $pl['role'] ?>";
	var useridc = <?php echo $pl['idc'] ?>;
</script>

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

<script type="text/javascript" src="js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>

<script type="text/javascript" src="js/jqgrid/js/i18n/grid.locale-ru.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="js/clanstat.js"></script>
<script type="text/javascript">
//	jQuery.jgrid.no_legacy_api = true;
</script>
</head>
<body>
<?php if (!isset($_COOKIE['user'])or ($user1==0)) {
?>
<div id="login">
 <a href="login.php">Вход</a>
</div>
<?php 
}else{
  echo "Добро пожаловать, ".$pl['name'].". ";

		if ($validclan==0){
			echo " Сожалеем, но вы не являетесь бойцом из нашего альянса. " ; 
		}
?>
<a href="exit.php">Выход</a>
<?php
	}
?>
<header>
	<h1><?php  
	if (($validclan==0)or($user1==0)){
		echo "Вы не авторизованны ";
		if (!isset($_COOKIE['user'])or ($user==0)) {
		//echo '<div id="login">';
		//print_r($_SERVER);
		echo '<a href="login.php'.'?backcall='.$_SERVER['REQUEST_URI'].'">ВХОД</a>';
		//echo '</div>';			
		}
		exit();
	}
		$aname="";
		if ($clanrow["aname"]<>NULL){
			$aname="'".$clanrow["aname"]."'";
		}
		echo '<img src="'.$clanrow['smallimg'].'" style="width: 24px; height:24px;" align="absmiddle"/> ';
		echo "<b>".$clanrow["name"]."</b> [".$clanrow["tag"]."] <b>".$aname."</b>";
		//
		echo " | место № ". $clanrow["position"]." | сила - ".$clanrow["rate"]. " | огн. мощь - ".$clanrow["firepower"]." | скилл - ".$clanrow["skill"];
	

//echo $clan_i['tag'];
?></h1>
</header>
<nav> 
    <ul>
<?php
if (isset ($alliansid)){
			echo "<li><a href=\"allians.php\">[".$ida["tag"]."]</a></li>";
		}
           foreach ($clan_array as $clan_i) {
			echo "<li><a href=\"wotstat.php?idc=".$clan_i["clan_id"]."\">".$clan_i["clan_tag"]."</a></li>";
		}
		
?>	
    </ul>
</nav>
<table align="center"><tr><td valign="top">
<div class="tables">
<div id="tabs">
	<ul>
	    <li><a href="#tab-1">Клан</a></li>
		<li><a href="#tab-3">Техника</a></li>
		<li><a href="#tab-6">ГК</a></li>
	</ul>
	
	<div id="tab-1">
            <table> <td valign="top">  
                <table id="news1"></table>
				<div id="n1pager"></div>
                </td>
				<td valign="top">  
				 <table id="news3"></table>
				 <div id="n3pager"></div>
                 </td>
               </table>
		<table id="all"></table>
		
	</div>
	<div id="tab-3">		
		<table>
			<tr>
				<td valign="top">
					<table id="techABS"></table>
					<div id="techABSpager"></div>	
				</td>
				<td valign="top">
					<table id="techCHM"></table>
					<div id="techCHMpager"></div>	
				</td>
			</tr>			
		</table>
						
	</div>
	<div id="tab-6">
		<table>
			<td valign="top">  
				<table id="wmProvinces"></table>
			</td>
			<td valign="top">  
				<table id="news4"></table>
				<div id="n4pager"></div>
             </td>
		</table>
        <table id="battles1"></table>
		<div id="battles1pager"></div>
     </div>
	       
</div>
</div>



</td>
</tr>

</table>
</body>
</html>
