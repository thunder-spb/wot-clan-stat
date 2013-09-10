<?php
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
if(!$sidx) $sidx =1;
if(!$page) $page =1;
$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {
	$limit = $totalrows;
}

$start = $limit*$page - $limit;
if($start <0) $start = 0;
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Не удалось подключиться к базе данных!!!dump_wot_stat");
$setnames = mysql_query( 'SET NAMES utf8' );
$result = mysql_query("SELECT COUNT(*) AS count FROM event_clan WHERE idp = '$idac'"); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
if( $count >0 ) { $total_pages = ceil($count/$limit); } else { $total_pages = 0; }
$SQL="SELECT id_ec, message, type, date FROM event_clan WHERE idp = $idac  ORDER BY $sidx DESC LIMIT $start , $limit";
$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce=new stdclass;
$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 
for($i=0;$i<$count;$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	 $a=$row['type'];
	 $amessage=$row['message'];
	 $sp5="<b>";$sp6="</b>";
	 if($a==1) {$sp5="<span style='color: red;'><b>"; $sp6="</b></span>";}
	 if(($a==2) or ($a==10)) {$sp5="<span style='color: blue;'><b>"; $sp6="</b></span>";}
	 if($a==3) {$sp5="<span style='color: green;'><b>"; $sp6="</b></span>";}
	$amessage=$sp5.$amessage.$sp6;
	// $procmessage=$sp1.$proc.$sp2;
	//$s=$i+1;
	//$responce->rows[$i]['id_ec']=$s;
	$responce->rows[$i]['cell']=array($row['id_ec'],$row['date'],$amessage); 
} 
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
