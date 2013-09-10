<?php
/////Развернутая статистика по боям за 30дней
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
$minDate=date("Y-m-d",strtotime(' -30 day '.$hosttime));
$sql="SELECT count(*) as cnt from `player_btl` c, cat_tanks a WHERE idp='$idac' and c.idt=a.wotidt and date>='$minDate'";
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
$sql="SELECT c.idt,a.localized_name,c.battle_count as minb_c,c.win_count as minw_c,c.date,c.time from `player_btl` c, cat_tanks a WHERE idp='$idac' and c.idt=a.wotidt and date>='$minDate' order by date desc,time desc,c.battle_count desc";
$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
$data=new stdclass;
	$data->page       = $page;
	$data->total      = $total_pages;
	$data->records    = $count;
	$i = 0;
	while($row = mysql_fetch_assoc($result)) {
		$sql = "SELECT max(c.battle_count) as maxb_c, max(c.win_count) as maxw_c,max(id_pb) as max_id_pb FROM `player_btl` c WHERE idp='$idac' and c.battle_count<$row[minb_c] and c.idt=$row[idt]";
       	$last_results = mysql_fetch_row(mysql_query($sql));
		$maxb_c=$last_results[0];
		$maxw_c=$last_results[1];
		if ($maxb_c==NULL) {
		$maxb_c=0;
		$maxw_c=0;
		}
		$diffb_c=$row['minb_c']-$maxb_c;
		$diffw_c=$row['minw_c']-$maxw_c;
		$procA=round(($diffw_c*100/$diffb_c),2);
		$procOld=round(($row['minw_c']*100/$row['minb_c']),2);
		$data->rows[$i]['cell'] = array($row['localized_name'],"+ ".$diffb_c." / ".$diffw_c." (".$row['minb_c'].")",$procA." (".$procOld.")",$row['date'],$row['time']);
		$i++;
	}

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>