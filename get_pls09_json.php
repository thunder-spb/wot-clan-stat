<?php
/////Список топ-техники.json. абсолют
include error_reporting(0);
include('settings.kak');
$idc = $_GET["idc"];
$level= $_GET["level"];
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');

$result = mysql_query("SELECT count(*) as cnt FROM `cat_tanks` where level=$level");
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count_t = $row['cnt']; 
//$message="<table  border='1' width='900px'>";
$sql="SELECT * FROM `cat_tanks` where level=$level  ORDER BY class,nation desc";
$result = mysql_query( $sql,$connect ) or die("Couldn t execute query.".mysql_error()); 

	$data->page       = 1;
	$data->total      = 1;
	$data->records    = $count_t;
	$i = 0;

while($row = mysql_fetch_assoc($result)) {
	$idt=$row['wotidt'];
	$tnation=$row['nation'];
			if ($tnation=='ussr') $n='<img src="images/stickers/ussr.png" style="width: 30px; height:30px;" align="absmiddle"/>'; else
			if ($tnation=='germany') $n='<img src="images/stickers/germany.png" style="width: 30px; height:30px;" align="absmiddle"/>'; else
			if ($tnation=='usa') $n='<img src="images/stickers/usa.png" style="width: 30px; height:30px;" align="absmiddle"/>'; else
			if ($tnation=='france') $n='<img src="images/stickers/france.png" style="width: 30px; height:30px;" align="absmiddle"/>'; else
			if ($tnation=='uk') $n='<img src="images/stickers/uk.png" style="width: 30px; height:30px;" align="absmiddle"/>'; else
			if ($tnation=='china') $n='<img src="images/stickers/china.png" style="width: 30px; height:30px;" align="absmiddle"/>'; else
			$n=" ";
	
	$sql2="SELECT pl.name,pb.idt as wotidt, pb.idp,max(pb.battle_count) as battle_count ,max(pb.win_count) as win_count FROM `player_btl` pb, `player` pl,`clan` cl WHERE pl.idp=pb.idp and pb.idt=$idt and cl.idp=pl.idp and cl.idc=$idc group by idp order by battle_count desc";
	$result2 = mysql_query( $sql2,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	$cnt=0;
	$img='<img src="'.$row['image_url'].'" width="70px" />'; 
	
	while($row2 = mysql_fetch_assoc($result2)) {
		$proc=round(($row2['win_count']*100/$row2['battle_count']),2);
		$cnt++;
	}
	$a11=min($timetolife+30,60);
	$date1=date("Y-m-d",strtotime(' -'.$a11.' day '.$hosttime));
	$sql3="select count(*) as cnt1 from 
		(select player_btl.idp as  cnt from player_btl join clan on player_btl.idp=clan.idp where  player_btl.date > '$date1' and idt=$idt and idc=$idc group by player_btl.idp) pl
		where 1";
    $result3 = mysql_query( $sql3,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	$rowc=mysql_fetch_array($result3,MYSQL_ASSOC);
	
	
	$col1=$img;
	$col2=$row['localized_name'];
	$col3=$rowc['cnt1']." из ".$cnt;
	$wotidt=$row['wotidt'];
	if ($cnt>0) {
		$data->rows[$i]['cell'] = array($wotidt,$row['class'],$n,$col1,$col2,$row['level'],$col3);
		$i++;
	}
}
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>
