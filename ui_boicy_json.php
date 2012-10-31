<?php
include('settings.kak');
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
//$SQL="SELECT c.idp,c.name, a.role_localised, a.date as cldate, as ldate from player c, clan a where c.in_clan>0 and c.idp=a.idp and id_p in (select max(id_p) FROM `player` WHERE idp=c.idp) ORDER BY $sidx $sord ,name";
$SQL="SELECT pl.idp,pl.name,cl.role_localised,cl.date AS cldate, pl.date AS ldate FROM player pl,clan cl,(SELECT max(id_p) AS maxid, name FROM player GROUP BY name) lastresults WHERE pl.idc = '$idc' AND pl.in_clan > 0 AND cl.idp=pl.idp AND pl.name=lastresults.name AND pl.id_p=lastresults.maxid ORDER BY $sidx $sord ,name";
$result2 = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce->page = $page; 
$responce->total = 1; 
$responce->records = $count;
$i=0; 
while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) { 
	$link='<a href="http://worldoftanks.ru/community/accounts/'.$row[idp].'/" target="_blank">'.$row[name].'</a>';
	$las_onl=round(abs(strtotime(date("Y-m-d",strtotime($hosttime))) - strtotime($row[ldate]))/86400);
	$clandays = round(abs(strtotime(date("Y-m-d",strtotime($hosttime))) - strtotime($row[cldate]))/86400);
	$las_onl=min($clandays,$las_onl);
	$responce->rows[$i]['idp']=$row[idp]; 
	$s=$i+1;
	$responce->rows[$i]['cell']=array($row[idp],$s, $link, $row[role_localised], $clandays, $las_onl); 
	$i++; 
} 	
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
