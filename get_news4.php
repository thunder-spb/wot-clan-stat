<?php
include('settings.kak');
header('Content-Type: text/html; charset=UTF-8'); 

$page = $_GET['page']; // get the requested page 
$limit = $_GET['rows']; // get how many rows we want to have into the grid 
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
//$sord = $_GET['sord']; // get the direction if(!$sidx)
$idc = $_GET['idc'];
if(!$sidx) $sidx =1;
//if(!$limit) $limit =20;
//if(!$page) $page =1;
$start = $limit*$page - $limit;
if($start <0) $start = 0;
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Не удалось подключиться к базе данных!!!dump_wot_stat");
$setnames = mysql_query( 'SET NAMES utf8' );
$result = mysql_query("SELECT COUNT(*) AS count FROM wm_event WHERE idc = '$idc'"); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
if( $count >0 ) { $total_pages = ceil($count/$limit); } else { $total_pages = 0; }
//$SQL="SELECT id_et,type, message, date FROM event_tank WHERE idc = $idc and type>0 ORDER BY $sidx DESC LIMIT $start , $limit";
//$SQL="SELECT distinct (a.id_et),a.type,b.class as classt, c.mutiny as mutiny, b.localized_name,b.level,b.nation,  a.date, d.name FROM event_tank a,cat_tanks b,player d  WHERE a.idc = $idc and b.id_t=a.idt  and a.type>0 and d.idp=a.idp  ORDER BY $sidx DESC LIMIT $start , $limit";
$SQL="SELECT  a.id_e,a.type as type,a.idpr as idpr,a.time as time,b.id,b.name as name,b.type as typepr, c.mutiny as mutiny,a.cw as cwq FROM wm_event a, province b
LEFT OUTER JOIN possession c ON b.id = c.idpr and b.cw =c.cw  WHERE a.idc = $idc and a.idpr=b.id and a.cw=b.cw ORDER BY $sidx DESC LIMIT $start , $limit";
//echo $SQL;
$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce=new stdclass;
$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 
$q=0;
for($i=0;$i<min($count,101);$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$amessage=$messagetype=$typea="";
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
	$a=$row['time'];
	
	$remtime = new DateTime('@'.$a);
	$remtime->setTimezone($remtz);
	$date=$remtime->format('Y-m-d H:i');
	$typepr=$row['typepr'];
	$mutiny=$row['mutiny'];
	if (($q<>$row['cwq']) and($q<>0)){
		$loc="'Глобальная Карта'";
		if ($row['cwq']==1) {
			$loc="'Вторая Компания'";
		}
		$responce->rows[$i]['cell']=array("","","<b>Клан сменил локацию на ".$loc."</b>"); 
		$i+=1;
	}
	$q=$row['cwq'];
	if ($typepr=="start"){ 
		$messagetype='<img src="images/province_type_start.png" style="width: 16px; height:16px;" align="absmiddle"/>';$amessage=" высадка ";
		if ($mutiny<>0){
			$messagetype='<img src="images/province_type_normal.png" style="width: 16px; height:16px;" align="absmiddle"/>';$amessage=" провинция ";
		}
	}
	if ($typepr=="gold"){$messagetype='<img src="images/province_type_gold.png" style="width: 16px; height:16px;" align="absmiddle"/>';$amessage=" ключевая провинция ";}
	if ($typepr=="normal"){$messagetype='<img src="images/province_type_normal.png" style="width: 16px; height:16px;" align="absmiddle"/>';$amessage=" провинция ";}
	$atype=$row['type'];
	$sp5="<b>";$sp6="</b>";
	if ($atype==0){$typea=" Потеряна ";$sp5="<span style='color: red;'><b>"; $sp6="</b></span> ";}
	if ($atype==1){
		$typea=" Захвачена ";
		$sp5="<span style='color: green;'><b>"; $sp6="</b></span> ";
		if ($typepr=="gold"){$sp5="<span style='color: blue;'><b>"; $sp6="</b></span> ";}
	}
	if ($atype==2){$typea=" Передана союзником ";$sp5="<span style='color: green;'><b>"; $sp6="</b></span> ";}
	if ($atype==3){$typea=" Отдана союзнику ";}
	if ($atype==4){
		$typea=" Возник мятеж в провинции ";
		$sp5="<span style='color: red;'><b>";
		$sp6="</b></span> ";
		$messagetype='<img src="images/icons/mutiny.png" style="width: 16px; height:16px;" align="absmiddle"/>';
		$amessage="";
		
	}
	$name=$row['name'];
	$idpr=$row['idpr'];
	$name = "<a href='http://cw".$q.".worldoftanks.ru/clanwars/maps/?province=$idpr' target='_blank'>$name</a>";
	$amessage=$messagetype.$typea.$amessage.$name;
	$amessage=$sp5.$amessage.$sp6;
	if ($row==NULL){
		$date="";
		$amessage="";
	}
	$responce->rows[$i]['cell']=array($row['id_e'],$date,$amessage); 
} 
//header("Content-type: text/script;charset=UTF-8");
echo json_encode($responce);
?>
