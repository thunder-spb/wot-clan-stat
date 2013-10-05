<?php
/////ТАнки во вкладке техника
include error_reporting(0);
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');
$idc = $_REQUEST["idc"];
$wotidt=$_REQUEST["wotidt"];

// $result = mysql_query("SELECT count(*) as cnt FROM `player_btl` where class='mediumTank'");
// $row = mysql_fetch_array($result,MYSQL_ASSOC); 
// $count_t = $row['cnt']; 
//$message="<table  border='1' width='900px'>";

$idt=$wotidt;
$sql2="SELECT pl.idp,pl.name,max(pb.battle_count) as battle_count ,max(pb.win_count) as win_count FROM `player_btl` pb, `player` pl,`clan` cl WHERE pl.idp=pb.idp and pb.idt=$idt and cl.idp=pl.idp and cl.idc=$idc group by idp order by name asc";
$result2 = mysql_query( $sql2,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
$i=0;
 while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) { 
	$proc=round(($row['win_count']*100/$row['battle_count']),2);
	$sp1=""; $sp2="";
	if($proc<50) {$sp1="<span style='color: red;'>"; $sp2="</span>";}
	if($proc>52) {$sp1="<span style='color: green;'>"; $sp2="</span>";}
	if($proc>55) {$sp1="<span style='color: blue;'>"; $sp2="</span>";}
	$pmessage=$sp1.$proc."%".$sp2;
	$responce->rows[$i]['id']=$idt; 
	$responce->rows[$i]['cell']=array($i+1,$row['name'],$row['battle_count'],$pmessage);
	$i++; 
}
 echo json_encode($responce);
?>
