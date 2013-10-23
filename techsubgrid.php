<?php
/////ТАнки во вкладке техника
//include error_reporting(0);
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');
$idc = $_REQUEST["idc"];
$wotidt=$_REQUEST["wotidt"];
$sidx=$_REQUEST["sidx"];
$sord=$_REQUEST["sord"];
// $result = mysql_query("SELECT count(*) as cnt FROM `player_btl` where class='mediumTank'");
// $row = mysql_fetch_array($result,MYSQL_ASSOC); 
// $count_t = $row['cnt']; 
//$message="<table  border='1' width='900px'>";

$idt=$wotidt;
$sql2="SELECT pl.idp,pl.name,max(pb.battle_count) as battle_count ,round((max(pb.win_count)*100/max(pb.battle_count)),2) as proc FROM `player_btl` pb, `player` pl,`clan` cl WHERE pl.idp=pb.idp and pb.idt=$idt and cl.idp=pl.idp and cl.idc=$idc group by idp order by $sidx $sord";
$result2 = mysql_query( $sql2,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 

$i=0;
 while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) { 
	$proc=$row['proc'];
	$sp1=""; $sp2="";
	$a1="";
	$a2="";
	$idp=$row['idp'];
	if ($_COOKIE['user']==$row['idp']){
	  $a1="<span style='color: maroon;'><b>";
	  $a2="</b></span>";
	}
	if($proc<50) {$sp1="<span style='color: red;'>"; $sp2="</span>";}
	if($proc>52) {$sp1="<span style='color: green;'>"; $sp2="</span>";}
	if($proc>55) {$sp1="<span style='color: blue;'>"; $sp2="</span>";}
	$pmessage=$sp1.$proc."%".$sp2;
	$sql3="select date from player_btl where  idt=$idt and idp=$idp order by id_pb desc limit 1";
    $result3 = mysql_query( $sql3,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	$rowc=mysql_fetch_array($result3,MYSQL_ASSOC);
	$date=$rowc['date'];
	$a11=min($timetolife+30,60);
	$date1=date("Y-m-d",strtotime(' -'.$a11.' day '.$hosttime));
	if (($date1>$date)){
		$check='<img src="images/icons/delete.png" style="width: 16px; height:16px;"/>';
	} else{$check='<img src="images/icons/check.png" style="width: 16px; height:16px;"/>';}
	$responce->rows[$i]['id']=$idt; 
	$responce->rows[$i]['cell']=array($a1.($i+1).$a2,$wotidt,$idp,$a1.$row['name'].$a2,$a1.$row['battle_count'].$a2,$pmessage,$check);
	$i++; 
}
 echo json_encode($responce);
?>
