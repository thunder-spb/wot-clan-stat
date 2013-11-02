<?php
/////Список топ-техники.json. 8-level
include error_reporting(0);
include('settings.kak');
$sidx = $_REQUEST["sidx"];
$sord = $_REQUEST["sord"];
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');

$result = mysql_query("SELECT count(*) as cnt FROM `clan_info` where alliansid=$alliansid");
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count_t = $row['cnt']; 
//$message="<table  border='1' width='900px'>";
$sql="SELECT clan_info.idc as id1,tag,clan_info.name as clname,smallimg,position,rate,skill,firepower,cw,ps1.cnt as cnt, rev.revenue as revenue FROM `clan_info` 
left join 
	(SELECT idc,count(*) as cnt FROM possession group by idc) as ps1 on clan_info.idc=ps1.idc
left join 
	(SELECT ps.idc as idc1 ,sum(pr.revenue) as revenue FROM possession ps, province pr where ps.idpr=pr.id AND ps.cw = pr.cw group by ps.idc )as rev on clan_info.idc=rev.idc1 
	where `alliansid`='$alliansid'  ORDER BY $sidx $sord";
$result = mysql_query( $sql,$connect ) or die("Couldn t execute query.".mysql_error()); 

	$i = 0;

while($row = mysql_fetch_assoc($result)) {
	$a1="";
	$a2="";
	if ($_COOKIE['idc']==$row['id1']){
	  $a1='<b><span style="color:maroon">';
	  $a2="</span></b>";
	}
	$tagimg='<img src="'.$row['smallimg'].'" style="width: 20px; height:20px;" align="absmiddle"/> <b>['.$row['tag'].']</b>';
	$idc=$row['id1'];
	$name='<b><a href="wotstat.php?idc='.$idc.'" >'.$row['clname'].'</a></b>';
	$loc="Гл. Карта";
	
	if ($row['cw']==2) {
		$loc="2-я Компания";
	}
	$data->rows[$i]['cell'] = array($a1.($i+1).$a2,$a1.$tagimg.$a2,$a1.$name.$a2,$a1.$row['position'].$a2,$a1.$row['rate'].$a2,$a1.$row['skill'].$a2,$a1.$row['firepower'].$a2,$a1.$loc.$a2,$a1.$row['cnt'].$a2,$a1.$row['revenue'].$a2);
	$i++;

}
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>
