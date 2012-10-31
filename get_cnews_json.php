<?
include error_reporting(0);
include('settings.kak');

$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');
$minDate=date("Y-m-d",strtotime(' -1 day '.$hosttime));
$sql="SELECT count(*) as cnt FROM `event_clan` where date>='$minDate'";
$result = mysql_query($sql); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['cnt']; 
$sql="SELECT count(*) as cnt2 FROM `event_tank` where date>='$minDate'";
$result = mysql_query($sql); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count2 = $row['cnt2']; 
$count=$count+$count2;
$message="";
$colorS="";
$colorF="";
$sql="SELECT * FROM `event_clan` where date>='$minDate' order by date desc,time desc";
$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	$data->page       = 1;
	$data->total      = 1;
	$data->records    = $count;
	$i = 0;
	while($row = mysql_fetch_assoc($result)) {
		if($row[reason]!=NULL) {
		$message=$row[message].", YCTAB# ".$row[reason];
		}
		else 
			$message=$colorS.$row[message].$colorF;
		$data->rows[$i]['cell'] = array($row[date],$message);
		$i++;$color="";$color2="";
	}
	$sql="SELECT * FROM `event_tank` where date>='$minDate' order by date desc,time desc";
	$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	while($row = mysql_fetch_assoc($result)) {
		$data->rows[$i]['cell'] = array($row[date],$row[message]);
		$i++;
	}
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>