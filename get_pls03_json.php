<?
/////Общая статистика по танкам
include error_reporting(0);
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
if(!$sidx) $sidx =2;
if(!$page) $page =1;
$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {
	$limit = $totalrows;
}

$sql="SELECT count(distinct idt) as cnt from `player_btl` where idp='$idac'";
$result = mysql_query($sql); 
//echo $sql.'<br>';
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['cnt']; 
if(($count>0) and ($limit>0)) { 
	$total_pages = ceil($count/$limit); 
} else { 
	$total_pages = 0;
}
//$total_pages=11;
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit;
if($start <0) $start = 0;
//echo $total_pages;

//SELECT a.localized_name,c.battle_count,ROUND((win_count*100/battle_count),2) as proc,a.level,a.class as cls,a.nation from `player_btl` c, cat_tanks a where idp='4479693' and c.idt=a.id_t and time in (select max(time) FROM `player_btl` WHERE idp=c.idp and date in (select max(date) FROM `player_btl` WHERE idp=c.idp))
// выборка изменившихся


//$sql="SELECT a.localized_name,c.battle_count,ROUND((win_count*100/battle_count),2) as proc,a.level,a.class as cls,a.nation from `player_btl` c, cat_tanks a where idp='$idac' and c.idt=a.id_t and time in (select max(time) FROM `player_btl` q WHERE q.idp=c.idp and date in (select max(date) FROM `player_btl` w WHERE w.idp=c.idp)) ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
$sql="SELECT a.localized_name ,max(c.battle_count) as battle_count,ROUND((max(c.win_count)*100/max(c.battle_count)),2) as proc,a.level,a.class as cls,a.nation from `player_btl` c, cat_tanks a where idp='$idac' and c.idt=a.id_t group by c.idt ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

//echo $sql.'<br>';
$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	$data->page       = $page;
	$data->total      = $total_pages;
	$data->records    = $count;
	$i = 0;
	while($row = mysql_fetch_assoc($result)) {
		//                                a.localized_name,  c.battle_count,      proc,    a.level,cls,a.nation
		$data->rows[$i]['cell'] = array($row[localized_name],$row[battle_count],$row[proc],$row[level],$row[cls],$row[nation]);
		$i++;
	}


header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>
