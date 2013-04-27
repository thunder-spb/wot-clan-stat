<?php

error_reporting(0);
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Îøèáêà ïîäêëþ÷åíèÿ ê ÁÄ");
$setnames = mysql_query( 'SET NAMES utf8' );

if($_REQUEST['filterBy'] != 'null'){
	$idac = $_REQUEST['filterBy'];
}

$data = array();
$date2=date("Y-m-d",strtotime(' -30 day '.$hosttime));
$sql="SELECT rating,date,time FROM `player` where idp='$idac' and date>='$date2' order by date,time";
$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	while($row = mysql_fetch_assoc($result)) {
		//if($row[reason]!=NULL) $message=$row[message].", Ïðè÷èíà: ".$row[reason];
		$data[] = array($row['date']." ".$row['time'],(float)$row['rating']);
	}

$data = array($data);










header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>
