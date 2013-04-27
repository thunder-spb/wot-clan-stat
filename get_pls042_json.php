<?php
/////Статистика по танкам разных стран
//include error_reporting(0);
include('settings.kak');

$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');
$page = $_REQUEST['page']; // get the requested page
$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
$sord = $_REQUEST['sord']; // get the direction
if($_REQUEST['filterBy'] != 'null'){
$idac = $_REQUEST['filterBy'];
}
if(!$limit) $limit =10;
if(!$sidx) $sidx =3;
if(!$page) $page =1;
$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {
	$limit = $totalrows;
}

$sql="SELECT count(*) as cnt from `player_btl` where idp='$idac' group by idp";
$result = mysql_query($sql); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['cnt']; 
if(($count>0) and ($limit>0)) { 
	$total_pages = ceil($count/$limit); 
} else { 
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit;
if($start <0) $start = 0;

//SELECT a.localized_name,c.battle_count,ROUND((win_count*100/battle_count),2) as proc,a.level,a.class as cls,a.nation from `player_btl` c, cat_tanks a where idp='4479693' and c.idt=a.id_t and time in (select max(time) FROM `player_btl` WHERE idp=c.idp and date in (select max(date) FROM `player_btl` WHERE idp=c.idp))
// выборка изменившихся


//$sql="SELECT a.localized_name,c.battle_count,ROUND((win_count*100/battle_count),2) as proc,a.level,a.class as cls,a.nation from `player_btl` c, cat_tanks a where idp='$idac' and c.idt=a.id_t and time in (select max(time) FROM `player_btl` q WHERE q.idp=c.idp and date in (select max(date) FROM `player_btl` w WHERE w.idp=c.idp)) ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
//$sql="SELECT sum(battle_count) as b_c,sum(win_count) as w_c,nation from `player_btl` c, `cat_tanks` a  where c.idp='$idac' and c.idt=a.id_t and id_pb in (select max(id_pb) from `player_btl` where idp='$idac' and idt=c.idt) group by nation order by b_c desc";
//$sql="SELECT sum(battle_count) as b_c,sum(win_count) as w_c,nation from `player_btl` c, `cat_tanks` a  where c.idp='$idac' and c.idt=a.id_t and id_pb in (select max(id_pb) from `player_btl` where idp='$idac' and idt=c.idt) group by nation order by b_c desc";
$sql="SELECT sum(bc) AS b_c,sum(wc) AS w_c, nation FROM (SELECT max(battle_count) AS bc, max(win_count) AS wc, nation FROM `player_btl` pl,`cat_tanks` ct WHERE idp='$idac' AND  pl.idt=ct.id_t GROUP BY idt) ts GROUP BY nation ORDER BY b_c DESC";
//SELECT * from `player_btl` c, `cat_tanks` a where c.idp='259339' and c.idt=a.id_t and id_pb in (select max(id_pb) from `player_btl` where idp='259339' and idt=c.idt)
//SELECT * from `player_btl` c, `cat_tanks` a where c.idp='4479693' and c.idt=a.id_t and id_pb in (select max(id_pb) from `player_btl` where idp='4479693' and idt=c.idt) //список последней техники

$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
$data=new stdclass;
	$data->page       = $page;
	$data->total      = $total_pages;
	$data->records    = $count;
	$i = 0;
	while($row = mysql_fetch_assoc($result)) {
		//                                a.localized_name,  c.battle_count,      proc,    a.level,cls,a.nation
		$proc=$row['w_c']*100/$row['b_c'];
		//$proca=$row[b_c]*100/$row[battles_count];
		$data->rows[$i]['cell'] = array($row['nation'],$row['b_c'],round($proc,2));
		$i++;
	}


header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>