<?php
include('settings.kak');
header('Content-Type: text/html; charset=UTF-8'); 

//$page = $_GET['page']; // get the requested page 
//$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction if(!$sidx)
$idc = $_GET['idc'];
$limit=100;
$page=1;
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Не удалось подключиться к базе данных!!!dump_wot_stat");
$setnames = mysql_query( 'SET NAMES utf8' );
$result = mysql_query("SELECT COUNT(*) AS count FROM clan WHERE idc = '$idc'"); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
//$SQL="SELECT idp,name,battles_count,wins,ROUND((wins*100/battles_count),2) as proc,frags,ROUND((frags/battles_count),2) as akillm,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt, ROUND((damage_dealt/battles_count),2) as adamagem  from player c where idp = c.idp and in_clan>0 and id_p in (select max(id_p) FROM `player` WHERE idp=c.idp) ORDER BY $sidx $sord ,name";
$SQL="SELECT pl.idp,pl.name,pl.battles_count,round((pl.wins*100/pl.battles_count),2) AS proc, pl.rating,pl.wn6,round((pl.frags/pl.battles_count),2) AS akillm,round((pl.damage_dealt/pl.battles_count),2) AS adamagem, pl.battle_avg_xp, round((pl.capture_points/pl.battles_count),2) as capture_p,round((pl.dropped_capture_points/pl.battles_count),2) as dropped_capture_p,round((pl.spotted/pl.battles_count),2) as spotted_p, pl.wins,pl.frags,pl.xp,pl.max_xp,pl.damage_dealt FROM player pl,(select max(id_p) as maxid, name from player group by name) lastresults WHERE idc = '$idc' and in_clan > 0  and pl.name = lastresults.name and pl.id_p = lastresults.maxid ORDER BY $sidx $sord";
$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce->page = $page; 
$responce->total = 1; 
$responce->records = $count; 
for($i=0;$i<$count;$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$link='<a href="http://worldoftanks.ru/community/accounts/'.$row[idp].'/" target="_blank">'.$row[name].'</a>';
	$rating=$row[rating];
	$wn6=$row[wn6];
	$wn6mess=$wn6;
	$proc=$row[proc];
	$ratingmessage=$rating;
	$sp5="";$sp6="";
	$sp1="";$sp2="";
	$sp3="";$sp4="";
	if($rating<1100) {$sp5="<span style='color: red;'><b>"; $sp6="</b></span>";}
	if($rating>1200) {$sp5="<span style='color: green;'><b>"; $sp6="</b></span>";}
	if($rating>1500) {$sp5="<span style='color: blue;'><b>"; $sp6="</b></span>";}
	if($wn6<1100) {$sp3="<span style='color: red;'><b>"; $sp4="</b></span>";}
	if($wn6>1200) {$sp3="<span style='color: green;'><b>"; $sp4="</b></span>";}
	if($wn6>1500) {$sp3="<span style='color: blue;'><b>"; $sp4="</b></span>";}
	if($proc<50) {$sp1="<span style='color: red;'><b>"; $sp2="</b></span>";}
	if($proc>52) {$sp1="<span style='color: green;'><b>"; $sp2="</b></span>";}
	if($proc>55) {$sp1="<span style='color: blue;'><b>"; $sp2="</b></span>";}
	$wn6mess=$sp3.$wn6mess.$sp4;
	$ratingmessage=$sp5.$ratingmessage.$sp6;
	$procmessage=$sp1.$proc.$sp2;
	$s=$i+1;
	$responce->rows[$i]['idp']=$s;
	$responce->rows[$i]['cell']=array($s,$link,$row[battles_count],$procmessage,$ratingmessage,$wn6mess,$row[akillm],$row[adamagem],$row[battle_avg_xp],$row[capture_p],$row[dropped_capture_p],$row[spotted_p],$row[wins],$row[frags],$row[xp],$row[max_xp],$row[damage_dealt]); 
} 
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
