<?php
/////Статистика: Общая /за 30дней /за 7дней
include error_reporting(0);
include('settings.kak');
header('Content-Type: text/html; charset=UTF-8'); 
if($_REQUEST['filterBy'] != 'null'){
	$idac = $_REQUEST['filterBy'];
}
//$page = $_GET['page']; // get the requested page 
//$limit = $_GET['rows']; // get how many rows we want to have into the grid 
//$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
//$sord = $_GET['sord']; // get the direction if(!$sidx) 
$limit=100;
$page=1;

//$idac=259339;

$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Не удалось подключиться к базе данных!!!dump_wot_stat");
$setnames = mysql_query( 'SET NAMES utf8' );
$count = 3;
// $SQL="SELECT battles_count,wins,ROUND((wins*100/battles_count),2) as proc,hits_percents,frags,ROUND((frags/battles_count),2) as akillm,spotted,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt, ROUND((damage_dealt/battles_count),2) as adamagem,name from player";
// $SQL.=" WHERE idp=$idac and id_p in (select max(id_p) FROM `player` WHERE idp=$idac)";

$SQL="SELECT battles_count,wins,round((wins*100/battles_count),2) AS proc,hits_percents,frags,round((frags/battles_count),2) AS akillm,spotted,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt,ROUND((damage_dealt/battles_count),2) AS adamagem,pl.name FROM player pl,(SELECT max(id_p) AS maxid,name FROM player GROUP BY name) lastresults WHERE idp =$idac AND pl.id_p = lastresults.maxid;"; 


$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 

$minDate=date("Y-m-d",strtotime(' -30 day '.$hosttime));// за 30 дней
// $SQL="SELECT battles_count,wins,ROUND((wins*100/battles_count),2) as proc,hits_percents,frags,ROUND((frags/battles_count),2) as akillm,spotted,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt, ROUND((damage_dealt/battles_count),2) as adamagem,name from player";
// $SQL.=" WHERE idp=$idac and id_p in (select min(id_p) FROM `player` WHERE idp=$idac and date>='$minDate')";
$SQL="SELECT battles_count,wins,round((wins*100/battles_count),2) AS proc,hits_percents,frags,round((frags/battles_count),2) AS akillm,spotted,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt,ROUND((damage_dealt/battles_count),2) AS adamagem,pl.name FROM player pl,(SELECT max(id_p) AS maxid,name FROM player WHERE date<'$minDate' GROUP BY name) lastresults WHERE idp =$idac AND pl.id_p = lastresults.maxid;"; 

$result2 = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 

//date("Y-m-d",strtotime($hosttime));
$minDate=date("Y-m-d",strtotime(' -7 day '.$hosttime));// за 7 дней

// $SQL="SELECT battles_count,wins,ROUND((wins*100/battles_count),2) as proc,hits_percents,frags,ROUND((frags/battles_count),2) as akillm,spotted,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt, ROUND((damage_dealt/battles_count),2) as adamagem,name from player";
// $SQL.=" WHERE idp=$idac and id_p in (select min(id_p) FROM `player` WHERE idp=$idac and date>='$minDate')";
$SQL="SELECT battles_count,wins,round((wins*100/battles_count),2) AS proc,hits_percents,frags,round((frags/battles_count),2) AS akillm,spotted,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt,ROUND((damage_dealt/battles_count),2) AS adamagem,pl.name FROM player pl,(SELECT max(id_p) AS maxid,name FROM player WHERE date<'$minDate' GROUP BY name) lastresults WHERE idp =$idac AND pl.id_p = lastresults.maxid;"; 
$result3 = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce= new stdclass;
$responce->page = $page; 
$responce->total = 1; 
$responce->records = $count; 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
	$row3 = mysql_fetch_array($result3,MYSQL_ASSOC);
//	$responce->rows[0]['idp']=$row['idp'];
	$responce->rows[0]['cell']=array($row['battles_count'],$row['wins'],$row['proc'],$row['hits_percents'],$row['frags'],$row['akillm'],$row['spotted'],$row['battle_avg_xp'],$row['xp'],$row['max_xp'],$row['capture_points'],$row['dropped_capture_points'],$row['damage_dealt'],$row['adamagem'],$row['name']); 
	$battles_count=$row['battles_count']-$row2['battles_count'];
	$wins=$row['wins']-$row2['wins'];
	$proc=round(($row['proc']-$row2['proc']),2);
	$hits_percents=$row['hits_percents']-$row2['hits_percents'];
	$frags=$row['frags']-$row2['frags'];
	$akillm=round(($row['akillm']-$row2['akillm']),2);
	$spotted=$row['spotted']-$row2['spotted'];
	$battle_avg_xp=$row['battle_avg_xp']-$row2['battle_avg_xp'];
	$xp=$row['xp']-$row2['xp'];
	$max_xp=$row['max_xp']-$row2['max_xp'];
	$capture_points=$row['capture_points']-$row2['capture_points'];
	$dropped_capture_points=$row['dropped_capture_points']-$row2['dropped_capture_points'];
	$damage_dealt=$row['damage_dealt']-$row2['damage_dealt'];
	$adamagem=round(($row['adamagem']-$row2['adamagem']),2);

	if($proc>0) $proc="<span style='color: green;'>+".$proc."<span>"; else
		if($proc<0) $proc="<span style='color: red;'>".$proc."<span>"; else 
			$proc="<span style='color: #faf0e6;'>".$proc."<span>";
	if($akillm>0) $akillm="<span style='color: green;'>+".$akillm."<span>"; else
		if($akillm<0) $akillm="<span style='color: red;'>".$akillm."<span>"; else
			$akillm="<span style='color: #faf0e6;'>".$akillm."<span>";
	if($battle_avg_xp>0) $battle_avg_xp="<span style='color: green;'>+".$battle_avg_xp."<span>"; else
		if($battle_avg_xp<0) $battle_avg_xp="<span style='color: red;'>".$battle_avg_xp."<span>"; else
			$battle_avg_xp="<span style='color: #faf0e6;'>".$battle_avg_xp."<span>";
	if($adamagem>0) $adamagem="<span style='color: green;'>+".$adamagem."<span>"; else
		if($adamagem<0) $adamagem="<span style='color: red;'>".$adamagem."<span>"; else
			$adamagem="<span style='color: #faf0e6;'>".$adamagem."<span>";
	if($max_xp>0) $max_xp="<span style='color: green;'>+".$max_xp."<span>"; else
		if($max_xp<0) $max_xp="<span style='color: red;'>".$max_xp."<span>"; else
			$max_xp="<span style='color: #faf0e6;'>".$max_xp."<span>";
	if($battles_count==0) $battles_count="<span style='color: #faf0e6;'>".$battles_count."<span>";
	if($wins==0) $wins="<span style='color: #faf0e6;'>".$wins."<span>";
	if($hits_percents==0) $hits_percents="<span style='color: #faf0e6;'>".$hits_percents."<span>";
	if($frags==0) $frags="<span style='color: #faf0e6;'>".$frags."<span>";
	if($spotted==0) $spotted="<span style='color: #faf0e6;'>".$spotted."<span>";
	if($xp==0) $xp="<span style='color: #faf0e6;'>".$xp."<span>";
	if($capture_points==0) $capture_points="<span style='color: #faf0e6;'>".$capture_points."<span>";
	if($dropped_capture_points==0) $dropped_capture_points="<span style='color: #faf0e6;'>".$dropped_capture_points."<span>";
	if($damage_dealt==0) $damage_dealt="<span style='color: #faf0e6;'>".$damage_dealt."<span>";
	

	$responce->rows[1]['cell']=array($battles_count,$wins,$proc,$hits_percents,$frags,$akillm,$spotted,$battle_avg_xp,$xp,$max_xp,$capture_points,$dropped_capture_points,$damage_dealt,$adamagem,$row2['name']); 
	
	$battles_count2=$row['battles_count']-$row3['battles_count'];
	$wins2=$row['wins']-$row3['wins'];
	$proc2=round(($row['proc']-$row3['proc']),2);
	$hits_percents2=$row['hits_percents']-$row3['hits_percents'];
	$frags2=$row['frags']-$row3['frags'];
	$akillm2=round(($row['akillm']-$row3['akillm']),2);
	$spotted2=$row['spotted']-$row3['spotted'];
	$battle_avg_xp2=$row['battle_avg_xp']-$row3['battle_avg_xp'];
	$xp2=$row['xp']-$row3['xp'];
	$max_xp2=$row['max_xp']-$row3['max_xp'];
	$capture_points2=$row['capture_points']-$row3['capture_points'];
	$dropped_capture_points2=$row['dropped_capture_points']-$row3['dropped_capture_points'];
	$damage_dealt2=$row['damage_dealt']-$row3['damage_dealt'];
	$adamagem2=round(($row['adamagem']-$row3['adamagem']),2);
	if($proc2>0) $proc2="<span style='color: green;'>+".$proc2."<span>"; else
		if($proc2<0) $proc2="<span style='color: red;'>".$proc2."<span>"; else 
			$proc2="<span style='color: #faf0e6;'>".$proc2."<span>";
	if($akillm2>0) $akillm2="<span style='color: green;'>+".$akillm2."<span>"; else
		if($akillm2<0) $akillm2="<span style='color: red;'>".$akillm2."<span>"; else
			$akillm2="<span style='color: #faf0e6;'>".$akillm2."<span>";
	if($battle_avg_xp2>0) $battle_avg_xp2="<span style='color: green;'>+".$battle_avg_xp2."<span>"; else
		if($battle_avg_xp2<0) $battle_avg_xp2="<span style='color: red;'>".$battle_avg_xp2."<span>"; else
			$battle_avg_xp2="<span style='color: #faf0e6;'>".$battle_avg_xp2."<span>";
	if($adamagem2>0) $adamagem2="<span style='color: green;'>+".$adamagem2."<span>"; else
		if($adamagem2<0) $adamagem2="<span style='color: red;'>".$adamagem2."<span>"; else
			$adamagem2="<span style='color: #faf0e6;'>".$adamagem2."<span>";
	if($max_xp2>0) $max_xp2="<span style='color: green;'>+".$max_xp2."<span>"; else
		if($max_xp2<0) $max_xp2="<span style='color: red;'>".$max_xp2."<span>"; else
			$max_xp2="<span style='color: #faf0e6;'>".$max_xp2."<span>";
	if($battles_count2==0) $battles_count2="<span style='color: #faf0e6;'>".$battles_count2."<span>";
	if($wins2==0) $wins2="<span style='color: #faf0e6;'>".$wins2."<span>";
	if($hits_percents2==0) $hits_percents2="<span style='color: #faf0e6;'>".$hits_percents2."<span>";
	if($frags2==0) $frags2="<span style='color: #faf0e6;'>".$frags2."<span>";
	if($spotted2==0) $spotted2="<span style='color: #faf0e6;'>".$spotted2."<span>";
	if($xp2==0) $xp2="<span style='color: #faf0e6;'>".$xp2."<span>";
	if($capture_points2==0) $capture_points2="<span style='color: #faf0e6;'>".$capture_points2."<span>";
	if($dropped_capture_points2==0) $dropped_capture_points2="<span style='color: #faf0e6;'>".$dropped_capture_points2."<span>";
	if($damage_dealt2==0) $damage_dealt2="<span style='color: #faf0e6;'>".$damage_dealt2."<span>";
	if($row3['battles_count']==0) $responce->rows[2]['cell']=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0); 
	else $responce->rows[2]['cell']=array($battles_count2,$wins2,$proc2,$hits_percents2,$frags2,$akillm2,$spotted2,$battle_avg_xp2,$xp2,$max_xp2,$capture_points2,$dropped_capture_points2,$damage_dealt2,$adamagem2,$row3['name']); 
//} 
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
