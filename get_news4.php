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
//$SQL="SELECT distinct (a.id_et),a.type,b.class as classt, b.localized_name,b.level,b.nation,  a.date, d.name FROM event_tank a,cat_tanks b,player d  WHERE a.idc = $idc and b.id_t=a.idt  and a.type>0 and d.idp=a.idp  ORDER BY $sidx DESC LIMIT $start , $limit";
$SQL="SELECT  a.id_e,a.type as type,a.idpr as idpr,a.time,b.id,b.name as name,b.type as typepr FROM wm_event a, province b WHERE a.idc = $idc and a.idpr=b.id  ORDER BY $sidx DESC LIMIT $start , $limit";

$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce=new stdclass;
$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 
for($i=0;$i<$count;$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$amessage=$messagetype=$typea="";
	$date=date("Y-m-d",$row["time"]);
	$typepr=$row['typepr'];
	if ($typepr=="start"){$messagetype='<img src="images/province_type_start.png" style="width: 20px; height:20px;" align="absmiddle"/>';$amessage=" высадка ";}
	if ($typepr=="gold"){$messagetype='<img src="images/province_type_gold.png" style="width: 20px; height:20px;" align="absmiddle"/>';$amessage=" столица ";}
	if ($typepr=="normal"){$messagetype='<img src="images/province_type_normal.png" style="width: 20px; height:20px;" align="absmiddle"/>';$amessage=" провинция ";}
	$atype=$row['type'];
	$sp5="";$sp6="";
	if ($atype==0){$typea=" Потряна ";$sp5="<span style='color: red;'><b>"; $sp6="</b></span> ";}
	if ($atype==1){
		$typea=" Захвачена ";
		$sp5="<span style='color: green;'><b>"; $sp6="</b></span> ";
		if ($typepr=="gold"){$sp5="<span style='color: blue;'><b>"; $sp6="</b></span> ";}
	}
	if ($atype==2){$typea=" Передана союзником ";$sp5="<span style='color: green;'><b>"; $sp6="</b></span> ";}
	if ($atype==3){$typea=" Отдана союзнику  ";}
	$name=$row['name'];
	$idpr=$row['idpr'];
	$name = "<a href='http://worldoftanks.ru/uc/clanwars/maps/?province=$idpr' target='_blank'>$name</a>";
	$amessage=$messagetype.$typea.$amessage."<b>".$name."</b>";
	$amessage=$sp5.$amessage.$sp6;
	$responce->rows[$i]['cell']=array($row['id_e'],$date,$amessage); 
} 
//header("Content-type: text/script;charset=UTF-8");
echo json_encode($responce);
?>
