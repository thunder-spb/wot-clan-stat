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
$pl = mysql_query("select name,idc from player  where idp='$user' order by `date` desc",$connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$user=mysql_fetch_array($pl,MYSQL_ASSOC);
$validclan=0;
foreach ($clan_array as $clan_i) {
	$idc_temp = $clan_i["clan_id"];
	if ($user['idc'] == $idc_temp) {
		$validclan=1;
	}
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

</style>

<script type="text/javascript">
	var current_clan_id = <?php echo $idc ?>;
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

<header>
	<h1><?php  
	if ($validclan==0){
		echo "Вы не авторизованны";
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
<table align="center"><tr><td valign="top">
<div class="tables">
<div id="tabs">
	<ul>
	    <li><a href="#tab-1">Клан</a></li>
		<li><a href="#tab-3">Техника</a></li>
		<li><a href="#tab-6">ГК</a></li>
		<li><a href="#tab-7">Техника 2</a></li>
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
		<table id="techABS"></table>
		<div id="techABSpager"></div>	
		<br>
		<table id="techCHM"></table>
		<div id="techCHMpager"></div>	
		<br>
		<table id="techHT"></table>
		<div id="techHTpager"></div>	
		<br>
		<table id="techMT"></table>
		<div id="techMTpager"></div>	
		<br>
		<table id="techLT"></table>
		<div id="techLTpager"></div>	
		<br>
		<table id="techSAU"></table>
		<div id="techSAUpager"></div>
		<br>
		<table id="techAT"></table>
		<div id="techATpager"></div>				
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
	<div id="tab-7">
		<table id="techABS2"></table>
		<table id="techCHAMP2"></table>
	</div>
       
</div>
</div>



</td>
</tr>

</table>
</body>
</html>
