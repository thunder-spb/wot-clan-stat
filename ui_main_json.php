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
$a11=$timetolife+1;
$date1=date("Y-m-d",strtotime(' -'.$a11.' day '.$hosttime));	
//$SQL="SELECT idp,name,battles_count,wins,ROUND((wins*100/battles_count),2) as proc,frags,ROUND((frags/battles_count),2) as akillm,battle_avg_xp,xp,max_xp,capture_points,dropped_capture_points,damage_dealt, ROUND((damage_dealt/battles_count),2) as adamagem  from player c where idp = c.idp and in_clan>0 and id_p in (select max(id_p) FROM `player` WHERE idp=c.idp) ORDER BY $sidx $sord ,name";
if ($type==1){
	$SQL="SELECT pl.idp,pl.name,pl.battles_count,round((pl.wins*100/pl.battles_count),2) AS proc,
		pl.win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,round((pl.frags/pl.battles_count),2) AS akillm,
		round((pl.damage_dealt/pl.battles_count),2) AS adamagem, 
		pl.battle_avg_xp, 
		round((pl.capture_points/pl.battles_count),2) as capture_p,round((pl.dropped_capture_points/pl.battles_count),2) as dropped_capture_p,
		round((pl.spotted/pl.battles_count),2) as spotted_p, 
		pl.wins,pl.frags,
		pl.xp,pl.max_xp,pl.damage_dealt FROM player pl,(select max(id_p) as maxid, name from player group by name) lastresults WHERE idc = '$idc' and in_clan > 0  and pl.name = lastresults.name and pl.id_p = lastresults.maxid ORDER BY $sidx $sord";
	$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
}elseif ($type==2){
	// $SQL="SELECT pl.idp as idp,pl.name,plc.battles_count_clan as battles_count,
	// round((plc.wins_clan*100/plc.battles_count_clan),2) AS proc, lastclan.bcdelta as win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,
	// round((plc.frags_clan/max(plc.battles_count_clan)),2) AS akillm,round((plc.damage_dealt_clan/max(plc.battles_count_clan)),2) AS adamagem, 
	// plc.battle_avg_xp_clan as battle_avg_xp, round((plc.capture_points_clan/max(plc.battles_count_clan)),2) as capture_p,
	// round((plc.dropped_capture_points_clan/max(plc.battles_count_clan)),2) as dropped_capture_p,
	// round((plc.spotted_clan/max(plc.battles_count_clan)),2) as spotted_p, max(plc.wins_clan) as wins,plc.frags_clan as frags,plc.xp_clan as xp,
	// pl.max_xp,plc.damage_dealt_clan as damage_dealt 
	// FROM player pl, (select max(id_p) as maxid, 
		// name from player group by name) lastresults,
		// player_clan as plc right join (select max(id_p) as maxid, idp, max(battles_count_clan)-min(battles_count_clan) as bcdelta from player_clan where `date`>'$date1' group by idp) as lastclan on  plc.id_p = lastclan.maxid 
	// WHERE idc = '$idc' and in_clan > 0 and pl.idp=plc.idp and pl.name = lastresults.name and pl.id_p = lastresults.maxid 
		
	// group by pl.idp 
	// ORDER BY $sidx $sord";
	$SQL="SELECT pl.idp as idp,pl.name,plc.battles_count_clan as battles_count,
	round((plc.wins_clan*100/plc.battles_count_clan),2) AS proc, plc.battles_count_clan-IFNULL(beforeresult.minbc,0) as win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,
	round((plc.frags_clan/max(plc.battles_count_clan)),2) AS akillm,round((plc.damage_dealt_clan/max(plc.battles_count_clan)),2) AS adamagem, 
	plc.battle_avg_xp_clan as battle_avg_xp, round((plc.capture_points_clan/max(plc.battles_count_clan)),2) as capture_p,
	round((plc.dropped_capture_points_clan/max(plc.battles_count_clan)),2) as dropped_capture_p,
	round((plc.spotted_clan/max(plc.battles_count_clan)),2) as spotted_p, max(plc.wins_clan) as wins,plc.frags_clan as frags,plc.xp_clan as xp,
	pl.max_xp,plc.damage_dealt_clan as damage_dealt 
	FROM player as pl, 
		(select max(id_p) as maxid, idp from player_clan group by idp) as lastresults  left join
		(select max(battles_count_clan) as minbc, idp from player_clan where `date`<='$date1' group by idp) as beforeresult
		on lastresults.idp=beforeresult.idp,
		player_clan as plc 
	WHERE idc = '$idc' and in_clan > 0 and pl.idp=plc.idp and plc.id_p = lastresults.maxid 
	group by pl.idp 
order by $sidx $sord";

	$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error());
}elseif ($type==3){
	// $SQL="SELECT pl.idp as idp,pl.name,plc.battles_count_company as battles_count,
	// round((plc.wins_company*100/plc.battles_count_company),2) AS proc, lastcompany.bcdelta as win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,
	// round((plc.frags_company/plc.battles_count_company),2) AS akillm,
	// round((plc.damage_dealt_company/plc.battles_count_company),2) AS adamagem, plc.battle_avg_xp_company as battle_avg_xp, 
	// round((plc.capture_points_company/plc.battles_count_company),2) as capture_p,
	// round((plc.dropped_capture_points_company/plc.battles_count_company),2) as dropped_capture_p,
	// round((plc.spotted_company/plc.battles_count_company),2) as spotted_p, plc.wins_company as wins,plc.frags_company as frags,
	// plc.xp_company as xp,pl.max_xp,plc.damage_dealt_company as damage_dealt 
	// FROM 
		// player pl,(select max(id_p) as maxid, name from player group by name) lastresults,
		// player_company as plc right join (select max(id_p) as maxid, idp, max(battles_count_company)-min(battles_count_company) as bcdelta from player_company where `date`>'$date1' group by idp) as lastcompany on  plc.id_p = lastcompany.maxid 
	// WHERE idc = '$idc' and in_clan > 0 and pl.idp=plc.idp and pl.name = lastresults.name and pl.id_p = lastresults.maxid 
		// group by pl.idp ORDER BY $sidx $sord";
	$SQL="SELECT pl.idp as idp,pl.name,plc.battles_count_company as battles_count,
	round((plc.wins_company*100/plc.battles_count_company),2) AS proc, plc.battles_count_company-IFNULL(beforeresult.minbc,0) as win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,
	round((plc.frags_company/max(plc.battles_count_company)),2) AS akillm,round((plc.damage_dealt_company/max(plc.battles_count_company)),2) AS adamagem, 
	plc.battle_avg_xp_company as battle_avg_xp, round((plc.capture_points_company/max(plc.battles_count_company)),2) as capture_p,
	round((plc.dropped_capture_points_company/max(plc.battles_count_company)),2) as dropped_capture_p,
	round((plc.spotted_company/max(plc.battles_count_company)),2) as spotted_p, max(plc.wins_company) as wins,plc.frags_company as frags,plc.xp_company as xp,
	pl.max_xp,plc.damage_dealt_company as damage_dealt 
	FROM player as pl, 
		(select max(id_p) as maxid, idp from player_company group by idp) as lastresults  left join
		(select max(battles_count_company) as minbc, idp from player_company where `date`<='$date1' group by idp) as beforeresult
		on lastresults.idp=beforeresult.idp,
		player_company as plc 
	WHERE idc = '$idc' and in_clan > 0 and pl.idp=plc.idp and plc.id_p = lastresults.maxid 
	group by pl.idp 
order by $sidx $sord";
	$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error());
}
elseif ($type==4){
	$SQL="SELECT pl.idp,pl.name,pl.battles_count,round((pl.wins*100/pl.battles_count),2) AS proc,
		pl.win30, pl.rating,pl.rating30,pl.wn6,pl.wn630,round((pl.frags/pl.battles_count),2) AS akillm,
		pl.battle_avg_xp, 
		pl.capture_points as capture_p,
		pl.dropped_capture_points as dropped_capture_p,
		pl.spotted as spotted_p, 
		pl.wins as wins, 
		pl.frags as frags,
		pl.xp,
		pl.max_xp,
		pl.damage_dealt as damage
		 FROM player pl,
		 (select max(id_p) as maxid, name from player group by name) as lastresults 
		 WHERE idc = '$idc' and in_clan > 0  and pl.name = lastresults.name and pl.id_p = lastresults.maxid ORDER BY $sidx $sord";
	$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
	
}
$responce=new stdclass;
$responce->page = $page; 
$responce->total = 1; 
$responce->records = $count; 
// $result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
for($i=0;$i<$count;$i++) { 
	if ($type<>4){
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$proc=$row['proc'];
		$bc=$row['battles_count'];
		$fragsb=$row['akillm'];
		$winsb=$row['wins'];
		$damageb=$row['adamagem'];
		$avgxp=$row['battle_avg_xp'];
		$cpt=$row['capture_p'];
		$dcpt=$row['dropped_capture_p'];
		$spottedb=$row['spotted_p'];
		$frags=$row['frags'];
		$xp=$row['xp'];
		$damage_dealt=$row['damage_dealt'];
	}
	else{
		$row = mysql_fetch_array($result,MYSQL_ASSOC);
		$idp=$row['idp'];
			
		$SQL="SELECT plc.battles_count_clan as battles_countc,
			plc.frags_clan as fragsc,
			plc.wins_clan as winsc,
			plc.capture_points_clan as cptc,
			plc.damage_dealt_clan as dmgc,
			plc.dropped_capture_points_clan as dcptc,
			plc.spotted_clan as sptc, 
			plc.xp_clan as xpc
		FROM player_clan plc,(select max(id_p) as maxid from player_clan where idp='$idp') as lastclan 
		WHERE plc.idp = '$idp' 	and plc.id_p = lastclan.maxid ";
		$resultclan = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error());
		$rowclan = mysql_fetch_array($resultclan,MYSQL_ASSOC);
		$SQL="SELECT plc.battles_count_company as battles_countc,
			plc.frags_company as fragsc,
			plc.wins_company as winsc,
			plc.capture_points_company as cptc,
			plc.damage_dealt_company as dmgc,
			plc.dropped_capture_points_company as dcptc,
			plc.spotted_company as sptc, 
			plc.xp_company as xpc
		FROM player_company plc,(select max(id_p) as maxid from player_company where idp='$idp') as lastcompany 
		WHERE plc.idp = '$idp' 	and plc.id_p = lastcompany.maxid ";
		$resultcompany = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error());
		$rowcompany = mysql_fetch_array($resultcompany,MYSQL_ASSOC);
		$bc=$row['battles_count']-$rowclan['battles_countc']-$rowcompany['battles_countc'];
		$winsb=$row['wins']-$rowclan['winsc']-$rowcompany['winsc'];
		$damage_dealt=$row['damage']-$rowclan['dmgc']-$rowcompany['dmgc'];
		$proc=round(($winsb*100/$bc),2);
		$frags=$row['frags']-$rowclan['fragsc']-$rowcompany['fragsc'];
		$fragsb=round($frags/$bc,2);
		$damageb=round(($damage_dealt/$bc),2);
		$xp=$row['xp']-$rowclan['xpc']-$rowcompany['xpc'];
		$avgxp=round(($xp/$bc),2);
		$cpt=round((($row['capture_p']-$rowclan['cptc']-$rowcompany['cptc'])/$bc),2);
		$dcpt=round((($row['dropped_capture_p']-$rowclan['dcptc']-$rowcompany['dcptc'])/$bc),2);
		$spottedb=round((($row['spotted_p']-$rowclan['sptc']-$rowcompany['sptc'])/$bc),2);
		
		
	}
	$link='<a href="http://worldoftanks.ru/community/accounts/'.$row['idp'].'/" target="_blank">'.$row['name'].'</a>';
	$rating=$row['rating'];
	$rating30=$row['rating30'];
	$wn6=$row['wn6'];
	$wn630=$row['wn630'];
	
	$win30=$row['win30'];
	if ($win30==0) {$win30="--";}
	$win30mess=$win30;
	$wn6mess=$wn6;
	$wn630mess=$wn630;
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

	if($rating30<1100) {$sp7="<span style='color: red;'><b>";  }
	if($rating30>1200) {$sp7="<span style='color: green;'><b>";  }
	if($rating30>1500) {$sp7="<span style='color: blue;'><b>";  }
	if($wn6<1100) {$sp3="<span style='color: red;'><b>";  }
	if($wn6>1200) {$sp3="<span style='color: green;'><b>";  }
	if($wn6>1500) {$sp3="<span style='color: blue;'><b>";  }
	if($wn630<1100) {$sp9="<span style='color: red;'><b>";  }
	if($wn630>1200) {$sp9="<span style='color: green;'><b>";  }
	if ($type==1){
		if($win30<50) {$sp2="<span style='color: red;'><b>";  }
		if($win30>55) {$sp2="<span style='color: green;'><b>";  }
		if($win30>60) {$sp2="<span style='color: blue;'><b>";  }
		if($rating30<100){$rating30message="";$wn630mess="";$win30mess="";}
	}
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
	$responce->rows[$i]['cell']=array($s,$link,$bc,$procmessage,$win30mess,$ratingmessage,$rating30message,$wn6mess,$wn630mess,$fragsb,$damageb,$avgxp,$cpt,$dcpt,$spottedb,$winsb,$frags,$xp,$row['max_xp'],$damage_dealt); 
} 

header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
