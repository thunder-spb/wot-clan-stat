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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php  
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
// $clanlist = mysql_query("select cl.tag as tag, cl.name as name, rate, firepower, skill, position,smallimg,alliances.name as aname from clan_info as cl left join alliances on cl.alliansid=alliances.ida  where idc='$idc'",$connect);
// if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
// $clanrow=mysql_fetch_array($clanlist,MYSQL_ASSOC);
$validclan=0;
$user1=1;
if (isset ($alliansid)){
	$al = mysql_query("select tag,name from alliances  where ida='$alliansid'",$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$ida=mysql_fetch_array($al,MYSQL_ASSOC);
	echo $ida["tag"];
}
if (isset($_COOKIE['user'])){
	$user=$_COOKIE['user'];
	$pl = mysql_query("select name,idc from player  where idp='$user' order by `date` desc",$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$userd=mysql_fetch_array($pl,MYSQL_ASSOC);
	$token=@$_COOKIE['atoken'];
	$ip = mysql_query("select * from access_log where idp='$user' and token='$token'",$connect);
    if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
    $ip=mysql_fetch_array($ip,MYSQL_ASSOC);
	if (($ip['token']==NULL)or (!isset($_COOKIE['atoken']))){$user1=0;}
	// foreach ($clan_array as $clan_i) {
		// $idc_temp = $clan_i["clan_id"];
		// if ($userd['idc'] == $idc_temp) {
			// $validclan=1;
		// }
	// }
	if (isset ($alliansid)){
		$idc_temp=$_COOKIE['idc'];
		$al = mysql_query("select allians from  clan_info where idc='$idc_temp'",$connect);
		if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
		$al=mysql_fetch_array($al,MYSQL_ASSOC);
		if ($al['allians']==1){$validclan=1;}
	}
}
?>
</title>
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
<script type="text/javascript" src="js/allians.js"></script>
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
  echo "Добро пожаловать, ".$userd['name'].". ";

		if ($validclan==0){
			echo " Сожалеем, но вы не являетесь бойцом из нашего альянса. " ; 
		}
?>
<a href="exit.php">Выход</a>
<?php
	}
?>
<header>
	<h1>
<?php
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
	echo $ida["name"];
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
	<div  align="center">
		<table id="all" align="center"></table>
	</div>
</body>
</html>
