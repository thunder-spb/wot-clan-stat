<?php
//Владения на ГК
include('settings.kak');
$capital=0;
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
$idc = $_GET['idc'];
$capital = $_GET['capital'];
$sql="SELECT count(*) as cnt from `possession` where idc='$idc'";
$res = mysql_query($sql,$connect);
$row = mysql_fetch_array($res,MYSQL_ASSOC); 
$count = $row['cnt'];
$responce = new stdclass;
$responce->page = 1;
$responce->total = 1;
$responce->records = $count;
$i=0;

$SQL = "select idpr,cw, attacked, occupancy_time, capital , mutiny from possession where idc='$idc'";
$result2 = mysql_query( $SQL,$connect );
while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) { 
	$status="";
	$idpr = $row["idpr"];
	$q=$row["cw"];
	$sql2 = "select a.prime_time, a.name as name,  b.name as arena_name, a.revenue, a.type from province a,arenas b where a.id='$idpr'and a.arena_id=b.id ";
	$q2 = mysql_query($sql2,$connect);
	$row2 = mysql_fetch_array($q2,MYSQL_ASSOC);
	$responce->rows[$i]['id'] = $idpr;
	switch ($row2["type"]) {
		case "normal":
			$type = '<img src="images/province_type_normal.png" style="width: 16px; height:16px;" align="absmiddle"/>';// alt='Обычная провинция' >";
			break;
		case "gold":
			$type = '<img src="images/province_type_gold.png" style="width: 16px; height:16px;" align="absmiddle"/>'; //alt='Ключевая провинция' >";
			break;
		case "start":
			$type = '<img src="images/province_type_start.png" style="width: 16px; height:16px;" align="absmiddle"/>';// alt='Стартовая провинция' >";
			break;
	}
	if ($row["attacked"]==1){
	$status="<img src='images/icons/attacked.png'>";
	}
	$bank='<img src="images/icons/gold.png"  align="absmiddle"/> '.$row2["revenue"];
	$name = $row2["name"];
	$name = "<a href='http://cw".$q.".worldoftanks.ru/clanwars/maps/?province=$idpr' target='_blank'>$name</a> ";
	if (($row["capital"]==1)and($capital==1)){
		$name='<img src="images/icons/capital.png" style="width: 16px; height:16px;" align="absmiddle"/>'." ".$name;
	}
	if ($row["mutiny"]==1){
		$status='<img src="images/icons/mutiny.png" style="width: 16px; height:16px;" align="absmiddle"/>'." ".$status;
	}
	if (function_exists("geoip_record_by_name")){
		$region = geoip_record_by_name($_SERVER['REMOTE_ADDR']);
		if (($region['region']<>NULL) and ($region['country_code']<>NULL)){
			$remtz=new DateTimeZone(geoip_time_zone_by_country_and_region($region['country_code'],$region['region']));
		} else{
			if  ($region['country_code']=="UA"){
				$remtz=new DateTimeZone('Europe/Kiev');
			}else{
				$remtz=new DateTimeZone($tz);
			}
		}
	}else{	$remtz=new DateTimeZone($tz);}
	$a=$row2['prime_time'];
	$remtime = new DateTime("@$a");
	$remtime->setTimezone($remtz);
	$offset=$remtime->format('H:i');
	// $remdate(DATE_W3C, $row['prime_time'])
	$responce->rows[$i]['cell']=array($type,$status,$name, $row2["arena_name"],$offset,$bank,$row["occupancy_time"]); //$clandays,$las_onl); 
	$i++; 
} 
//header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>