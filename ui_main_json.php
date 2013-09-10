<?php
error_reporting(0);
include('settings.kak');
header('Content-Type: text/html; charset=UTF-8'); 

//$page = $_GET['page']; // get the requested page 
//$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
$sord = $_GET['sord']; // get the direction if(!$sidx)
$idc = $_GET['idc'];
$type = $_GET['type'];
if (!$sidx) $sidx=2;
if (!$sord) $sord="asc";
if (!$type) $type=1;
$limit=100;
$page=1;
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Не удалось подключиться к базе данных!!!dump_wot_stat");
$setnames = mysql_query( 'SET NAMES utf8' );
$result = mysql_query("SELECT COUNT(*) AS count FROM clan WHERE idc = '$idc'"); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
//$SQL="SELECT idp,name,battles_count,wins,ROUND((wins*100/battles_count),2) as proc,frags,ROUND((frags/battles_count),2) as akillm,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt, ROUND((damage_dealt/battles_count),2) as adamagem  from player c where idp = c.idp and in_clan>0 and id_p in (select max(id_p) FROM `player` WHERE idp=c.idp) ORDER BY $sidx $sord ,name";
if ($type==1){
	$SQL="SELECT pl.idp,pl.name,pl.battles_count,round((pl.wins*100/pl.battles_count),2) AS proc, pl.win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,round((pl.frags/pl.battles_count),2) AS akillm,round((pl.damage_dealt/pl.battles_count),2) AS adamagem, pl.battle_avg_xp, round((pl.capture_points/pl.battles_count),2) as capture_p,round((pl.dropped_capture_points/pl.battles_count),2) as dropped_capture_p,round((pl.spotted/pl.battles_count),2) as spotted_p, pl.wins,pl.frags,pl.xp,pl.max_xp,pl.damage_dealt FROM player pl,(select max(id_p) as maxid, name from player group by name) lastresults WHERE idc = '$idc' and in_clan > 0  and pl.name = lastresults.name and pl.id_p = lastresults.maxid ORDER BY $sidx $sord";
}elseif ($type==2){
	$SQL="SELECT pl.idp as idp,pl.name,plc.battles_count_clan as battles_count,round((plc.wins_clan*100/plc.battles_count_clan),2) AS proc, pl.win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,round((plc.frags_clan/plc.battles_count_clan),2) AS akillm,round((plc.damage_dealt_clan/plc.battles_count_clan),2) AS adamagem, plc.battle_avg_xp_clan as battle_avg_xp, round((plc.capture_points_clan/plc.battles_count_clan),2) as capture_p,round((plc.dropped_capture_points_clan/plc.battles_count_clan),2) as dropped_capture_p,round((plc.spotted_clan/plc.battles_count_clan),2) as spotted_p, plc.wins_clan as wins,plc.frags_clan as frags,plc.xp_clan as xp,pl.max_xp,plc.damage_dealt_clan as damage_dealt FROM player pl, player_clan plc,(select max(id_p) as maxid, name from player group by name) lastresults WHERE idc = '$idc' and in_clan > 0 and pl.idp=plc.idp and pl.name = lastresults.name and pl.id_p = lastresults.maxid group by pl.idp ORDER BY $sidx $sord";
}elseif ($type==3){
	$SQL="SELECT pl.idp as idp,pl.name,plc.battles_count_company as battles_count,round((plc.wins_company*100/plc.battles_count_company),2) AS proc, pl.win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,round((plc.frags_company/plc.battles_count_company),2) AS akillm,round((plc.damage_dealt_company/plc.battles_count_company),2) AS adamagem, plc.battle_avg_xp_company as battle_avg_xp, round((plc.capture_points_company/plc.battles_count_company),2) as capture_p,round((plc.dropped_capture_points_company/plc.battles_count_company),2) as dropped_capture_p,round((plc.spotted_company/plc.battles_count_company),2) as spotted_p, plc.wins_company as wins,plc.frags_company as frags,plc.xp_company as xp,pl.max_xp,plc.damage_dealt_company as damage_dealt FROM player pl, player_company plc,(select max(id_p) as maxid, name from player group by name) lastresults WHERE idc = '$idc' and in_clan > 0 and pl.idp=plc.idp and pl.name = lastresults.name and pl.id_p = lastresults.maxid group by pl.idp ORDER BY $sidx $sord";

	//$SQL="SELECT pl.idp,pl.name,pl.battles_count_company as battles_count,round((pl.wins_company*100/pl.battles_count_company),2) AS proc, pl.win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,round((pl.frags_company/pl.battles_count_company),2) AS akillm,round((pl.damage_dealt_company/pl.battles_count_company),2) AS adamagem, pl.battle_avg_xp_company as battle_avg_xp, round((pl.capture_points_company/pl.battles_count_company),2) as capture_p,round((pl.dropped_capture_points_company/pl.battles_count_company),2) as dropped_capture_p,round((pl.spotted_company/pl.battles_count_company),2) as spotted_p, pl.wins_company as wins,pl.frags_company as frags,pl.xp_company as xp,pl.max_xp,pl.damage_dealt_company as damage_dealt FROM player pl,(select max(id_p) as maxid, name from player group by name) lastresults WHERE idc = '$idc' and in_clan > 0  and pl.name = lastresults.name and pl.id_p = lastresults.maxid ORDER BY $sidx $sord";
}elseif ($type==4){
	$SQL="SELECT pl.idp,pl.name,
		(pl.battles_count-plr.battles_count_company-plc.battles_count_clan) as battles_count,
		round(((pl.wins-plr.wins_company-plc.wins_clan)*100/(pl.battles_count-plr.battles_count_company-plc.battles_count_clan)),2) AS proc,
		pl.win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,
		round(((pl.frags-plc.frags_clan-plr.frags_company)/(pl.battles_count-plr.battles_count_company-plc.battles_count_clan)),2) AS akillm,
		round(((pl.damage_dealt-plr.damage_dealt_company-plc.damage_dealt_clan)/(pl.battles_count-plr.battles_count_company-plc.battles_count_clan)),2) AS adamagem,
		round(((pl.xp-plr.xp_company-plc.xp_clan)/(pl.battles_count-plr.battles_count_company-plc.battles_count_clan)),0) as battle_avg_xp,
		round(((pl.capture_points-plr.capture_points_company-plc.capture_points_clan)/(pl.battles_count-plr.battles_count_company-plc.battles_count_clan)),2) as capture_p,
		round(((pl.dropped_capture_points-plr.dropped_capture_points_company-plc.dropped_capture_points_clan)/(pl.battles_count-plr.battles_count_company-plc.battles_count_clan)),2) as dropped_capture_p,
		round(((pl.spotted-plr.spotted_company-plc.spotted_clan)/(pl.battles_count-plr.battles_count_company-plc.battles_count_clan)),2) as spotted_p,
		(pl.wins-plr.wins_company-plc.wins_clan) as wins,
		(pl.frags-plc.frags_clan-plr.frags_company) as frags,
		(pl.xp-plr.xp_company-plc.xp_clan) as xp,
		pl.max_xp,
		(pl.damage_dealt-plr.damage_dealt_company-plc.damage_dealt_clan) as damage_dealt
		FROM player pl,player_clan  plc, player_company plr,(select max(id_p) as maxid, name from player group by name) lastresults WHERE idc = '$idc' and in_clan > 0  and  plc.idp=pl.idp and  plr.idp=pl.idp and pl.name = lastresults.name and pl.id_p = lastresults.maxid group by pl.idp ORDER BY $sidx $sord";
}
$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce=new stdclass;
$responce->page = $page; 
$responce->total = 1; 
$responce->records = $count; 
for($i=0;$i<$count;$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$link='<a href="http://worldoftanks.ru/community/accounts/'.$row['idp'].'/" target="_blank">'.$row['name'].'</a>';
	$rating=$row['rating'];
	$rating30=$row['rating30'];
	$wn6=$row['wn6'];
	$wn630=$row['wn630'];
	
	$win30=$row['win30'];
	$win30mess=$win30;
	$wn6mess=$wn6;
	$wn630mess=$wn630;
	$proc=$row['proc'];
	$ratingmessage=$rating;
	$rating30message=$rating30;
	$sp5="";$sp6="</b></span>";
	$sp1="";
	$sp2="";
	$sp3="";
	$sp7="";
	$sp9="";
	if($rating<100) { $ratingmessage="";$wn6mess="";}
	if($rating<1100) {$sp5="<span style='color: red;'><b>";  }
	if($rating>1200) {$sp5="<span style='color: green;'><b>";  }
	if($rating>1500) {$sp5="<span style='color: blue;'><b>";  }
	if($rating30<100){$rating30message="";$wn630mess="";$win30mess="";}
	if($rating30<1100) {$sp7="<span style='color: red;'><b>";  }
	if($rating30>1200) {$sp7="<span style='color: green;'><b>";  }
	if($rating30>1500) {$sp7="<span style='color: blue;'><b>";  }
	if($wn6<1100) {$sp3="<span style='color: red;'><b>";  }
	if($wn6>1200) {$sp3="<span style='color: green;'><b>";  }
	if($wn6>1500) {$sp3="<span style='color: blue;'><b>";  }
	if($wn630<1100) {$sp9="<span style='color: red;'><b>";  }
	if($wn630>1200) {$sp9="<span style='color: green;'><b>";  }
	if($wn630>1500) {$sp9="<span style='color: blue;'><b>";  }
	if($win30<50) {$sp2="<span style='color: red;'><b>";  }
	if($win30>55) {$sp2="<span style='color: green;'><b>";  }
	if($win30>60) {$sp2="<span style='color: blue;'><b>";  }
	if($proc<50) {$sp1="<span style='color: red;'><b>";  }
	if($proc>52) {$sp1="<span style='color: green;'><b>";  }
	if($proc>55) {$sp1="<span style='color: blue;'><b>";  }
	$wn6mess=$sp3.$wn6mess.$sp6;
	$ratingmessage=$sp5.$ratingmessage.$sp6;
	$wn630mess=$sp9.$wn630mess.$sp6;
	$win30mess=$sp2.$win30mess.$sp6;
	$rating30message=$sp7.$rating30message.$sp6;
	$procmessage=$sp1.$proc.$sp6;
	$s=$i+1;
	$responce->rows[$i]['idp']=$s;
	$responce->rows[$i]['cell']=array($s,$link,$row['battles_count'],$procmessage,$win30mess,$ratingmessage,$rating30message,$wn6mess,$wn630mess,$row['akillm'],$row['adamagem'],$row['battle_avg_xp'],$row['capture_p'],$row['dropped_capture_p'],$row['spotted_p'],$row['wins'],$row['frags'],$row['xp'],$row['max_xp'],$row['damage_dealt']); 
} 
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
