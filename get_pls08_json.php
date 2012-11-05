<?
/////События
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
if(!$limit) $limit =50;
if(!$sidx) $sidx =3;
if(!$page) $page =1;
$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {
	$limit = $totalrows;
}

$sql="SELECT count(*) as cnt FROM `event_clan` where idp='$idac'";
$result = mysql_query($sql); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['cnt']; 
$sql="SELECT count(*) as cnt2 FROM `event_tank` where idp='$idac'";
$result = mysql_query($sql); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count2 = $row['cnt2']; 
$count=$count+$count2;

if(($count>0) and ($limit>0)) { 
	$total_pages = ceil($count/$limit); 
} else { 
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit;
if($start <0) $start = 0;

//$minDate=date("Y-m-d",strtotime(' -3 day '.$hosttime));
$i = 0;
	$data->page       = $page;
	$data->total      = $total_pages;
	$data->records    = $count;
	
$sql="SELECT * FROM `event_tank` where idp='$idac' order by date desc,time desc";
$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	while($row = mysql_fetch_assoc($result)) {
		//if($row[reason]!=NULL) $message=$row[message].", Причина: ".$row[reason];
		$message=$row[message];
		$data->rows[$i]['cell'] = array($message,$row[date],$row[time]);
		$i++;
}
	
$sql="SELECT * FROM `event_clan` where idp='$idac' order by date desc,time desc";
$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	while($row = mysql_fetch_assoc($result)) {
		if($row[reason]!=NULL) $message=$row[message].", Причина: ".$row[reason];
			else $message=$row[message];
		$data->rows[$i]['cell'] = array($message,$row[date],$row[time]);
		$i++;
	}
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>