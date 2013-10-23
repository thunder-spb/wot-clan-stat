<?php
//include error_reporting(0);
include('settings.kak');

$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("");
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
$db = mysql_select_db($dbname, $connect) or die("!!!dump_wot_stat");
$setnames = mysql_query( 'SET NAMES utf8' );
$result = mysql_query("SELECT COUNT(*) AS count FROM event_tank WHERE idp = '$idac'"); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['count']; 
if( $count >0 ) { $total_pages = ceil($count/$limit); } else { $total_pages = 0; }
//$SQL="SELECT id_et,type, message, date FROM event_tank WHERE idp = $idac ORDER BY $sidx DESC LIMIT $start , $limit";
$SQL="SELECT distinct (a.id_et),a.type,b.class as classt, b.localized_name,b.level,b.nation,  a.date, d.name FROM event_tank a,cat_tanks b,player d  WHERE a.idp = $idac and b.wotidt=a.idt  and d.idp=a.idp  ORDER BY $sidx DESC LIMIT $start , $limit";

$result = mysql_query( $SQL,$connect ) or die("Couldn t execute query.".mysql_error()); 
$responce=new stdclass;
$responce->page = $page; 
$responce->total = $total_pages; 
$responce->records = $count; 
for($i=0;$i<$count;$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$class=$row['classt'];
		if ($class=='heavyTank') $loclass='<img src="images/icons/ht.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
		if ($class=='mediumTank') $loclass='<img src="images/icons/mt.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
		if ($class=='lightTank') $loclass='<img src="images/icons/lt.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
		if ($class=='SPG') $loclass='<img src="images/icons/spg.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
		if ($class=='AT-SPG') $loclass='<img src="images/icons/at.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
		$loclass=$class;
	$tname=$row['localized_name'];
	$pname=$row['name'];
	$a=$row['type'];
	$tnation=$row['nation'];
	if ($tnation=='ussr') $n='<img src="images/stickers/ussr.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
	if ($tnation=='germany') $n='<img src="images/stickers/germany.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
	if ($tnation=='usa') $n='<img src="images/stickers/usa.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
	if ($tnation=='france') $n='<img src="images/stickers/france.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
	if ($tnation=='uk') $n='<img src="images/stickers/uk.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
	if ($tnation=='china') $n='<img src="images/stickers/china.png" style="width: 20px; height:20px;" align="absmiddle"/>'; else
	$n=" ";
	if ($a<10){
		// $tlevel=$row[level];
		 
		// $amessage=$tname.' ('.$classRu.' '.$tlevel.' ур. '.$nation.') у '.$pname;
		 $amessage=$n.' '.$tname;
		 //$amessage=$row[message];
		 $sp5="<b>";$sp6="</b>";
		 if($a==1) {$sp5="<span style='color: blue;'><b>"; $sp6="</b></span>";}
		 if($a==2) {$sp5="<span style='color: green;'><b>"; $sp6="</b></span>";}
		$amessage=$sp5.$amessage.$sp6;
		if ($tnation==NULL) $amessage="";
	}else{
		$x=$a-10;
		$amessage='<img src="images/MarkOfMastery'.$x.'.png" style="width: 20px; height:20px;" align="absmiddle"/> на '.$tname;
		$sp5="<b>";$sp6="</b>";
		if(($row['level']==10)and ($x==4)) {$sp5="<span style='color: blue;'><b>"; $sp6="</b></span> ";}
		if(($row['level']==9)and ($x==4)) {$sp5="<span style='color: green;'><b>"; $sp6="</b></span> ";}
		$amessage=$sp5.$amessage.$sp6;
	}
	// $procmessage=$sp1.$proc.$sp2;
	//$s=$i+1;
	//$responce->rows[$i]['id_ec']=$s;
	$responce->rows[$i]['cell']=array($row['id_et'],$row['date'],$loclass,$row['level'],$amessage); 
} 
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>