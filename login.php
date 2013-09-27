<?php
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
$pageidp = "2.0/auth/login/?application_id=".$appidlogin."&redirect_uri=".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ;	
if (!isset($_REQUEST['status'])){
	Header("Location: https://api.{$wot_host}/{$pageidp}");
	exit();
}
if ($_REQUEST['status']=="ok"){
	$user=$_REQUEST['account_id'];
	setcookie("user",$user);
	$pl = mysql_query("select name, idc from player  where idp='$user' order by `date` desc",$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$userd=mysql_fetch_array($pl,MYSQL_ASSOC);
	$idc=$userd['idc'];
	if ($idc<>NULL){
		foreach ($clan_array as $clan_i) {
			$idc_temp = $clan_i["clan_id"];
			if ($idc == $idc_temp) {
				setcookie("idc",$idc);
				Header("Location: wotstat.php?idc={$idc}");
				exit();	
			}
		}
		
	}else{
		setcookie("idc");
	}
}
Header("Location: wotstat.php");
exit();	
?>