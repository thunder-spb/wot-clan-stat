<?php
include('settings.kak');
//$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
//$sord = $_GET['sord']; // get the direction if(!$sidx) 
$idc = $_GET['idc'];
$limit=100;
$page=1;
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Не удалось подключиться к базе данных!!!dump_wot_stat");
$setnames = mysql_query( 'SET NAMES utf8' );
$result = mysql_query("SELECT COUNT(*) AS count FROM clan WHERE idc = '$idc'"); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
//$SQL="SELECT c.idp,c.name, a.role_localised, a.date as cldate,c.date as ldate, a.role from player c, clan a where c.in_clan>0 and c.idp=a.idp and id_p in (select max(id_p) FROM `player` WHERE idp=c.idp) ORDER BY $sidx $sord ,name";

//$SQL="SELECT pl.idp,pl.name,cl.date AS cldate,pl.date AS ldate FROM player pl,clan cl,(SELECT max(id_p) AS maxid, name FROM player GROUP BY name) lastresults WHERE pl.in_clan = 0 AND cl.idp=pl.idp AND pl.name=lastresults.name AND pl.id_p=lastresults.maxid ORDER BY name";

$SQL="SELECT  idp, name FROM player pl Where pl.idc='$idc' and pl.in_clan=0";
$result2 = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce->page = $page; 
$responce->total = 1; 
$responce->records = $count;
$i=0; 
while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) { 
	$link='<a href="http://worldoftanks.ru/community/accounts/'.$row[idp].'/" target="_blank">'.$row[idp].'</a>';
	//$las_onl=round(abs(strtotime(date("Y-m-d",strtotime($hosttime))) - strtotime($row[ldate]))/86400);
	$clandays = round(abs(strtotime(date("Y-m-d",strtotime($hosttime))) - strtotime($row[cldate]))/86400);
	
	$sql2 = "select ec.date as ldate,ec.reason from event_clan ec, (select max(id_ec) as midec from event_clan group by idp) as lreason where ec.idp=$row[idp] and ec.id_ec=lreason.midec";
	$q2 = mysql_query($sql2,$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$row2 = mysql_fetch_array($q2);
	$ldate = $row2['ldate']; // кол-во записей в достижениях

	$responce->rows[$i]['idp']=$row[idp]; 
	$responce->rows[$i]['cell']=array($row[idp],$i, $link,$ldate,$row2['reason']); //$clandays,$las_onl); 
	$i++; 
} 	
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
