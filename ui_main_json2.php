<?php
include('settings.kak');
header('Content-Type: text/html; charset=UTF-8'); 
//$page = $_GET['page']; // get the requested page 
//$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction if(!$sidx) 
$limit=100;
$page=1;
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Не удалось подключиться к базе данных!!!dump_wot_stat");
$setnames = mysql_query( 'SET NAMES utf8' );
$result = mysql_query("SELECT COUNT(*) AS count FROM clan"); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
$SQL="SELECT idp,name,battles_count,wins,ROUND((wins*100/battles_count),2) as proc,frags,ROUND((frags/battles_count),2) as akillm,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt, ROUND((damage_dealt/battles_count),2) as adamagem  from player c where idp = c.idp and in_clan>0 and id_p in (select max(id_p) FROM `player` WHERE idp=c.idp) ORDER BY $sidx $sord ,name";
$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce->page = $page; 
$responce->total = 1; 
$responce->records = $count; 
for($i=0;$i<$count;$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$link='<a href="http://worldoftanks.ru/community/accounts/'.$row[idp].'/" target="_blank">'.$row[idp].'</a>';
	$responce->rows[$i]['idp']=$row[idp]; 
	$responce->rows[$i]['cell']=array($link,$row[name],$row[battles_count],$row[wins],$row[proc],$row[frags],$row[akillm],$row[battle_avg_xp],$row[xp],$row[max_xp],$row[capture_points],$row[dropped_capture_points],$row[damage_dealt],$row[adamagem]); 
} 
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
