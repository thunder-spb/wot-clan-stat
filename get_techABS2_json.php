<?
/////Ñïèñîê òîï-òåõíèêè.json. àáñîëþò
include error_reporting(0);
include('settings.kak');
$idc = $_GET["idc"];
$sidx = $_GET['sidx']; 
$sord = $_GET['sord'];
if (!$sidx) $sidx=0;
if (!$sord) $sord="asc";
$sidx = $sidx * 2 + 2;
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Îøèáêà ïîäêëþ÷åíèÿ ê ÁÄ");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');

$result = mysql_query("SELECT count(*) as cnt FROM `clan` where idc=$idc");
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count_t = $row['cnt']; 
$data->page       = 1;
$data->total      = 1;
$data->records    = $count_t;

$tech_list = array("IS-7","T110","IS-4","Bat_Chatillon25t","T62A","M48A1","E-100","Maus","T110E4","Object_261","G_E","T92","Bat_Chatillon155");

$sql = "SELECT id_t,name from cat_tanks where name in ( '".implode("','",$tech_list)."')
	ORDER BY FIELD(name,'".implode("','",$tech_list)."')";
$result = mysql_query($sql);
$idt = array();
while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	$idt[] = $row['id_t'];
}

$sql2 = "SELECT * from (select idp, name from player where idc=$idc group by idp) a1 ";
$i=1;
foreach ($tech_list as $tech) {
	$sql2 .= " left join (SELECT pb.idp,max(pb.battle_count) as `$tech` FROM `player_btl` pb, player p where idt='".$idt[$i-1]."' and p.idp=pb.idp and idc=$idc group by pb.idp) a".($i+1)." on a1.idp=a".($i+1).".idp";
	$i++;
}
$sql2 .= " order by $sidx $sord";
#echo $sql2;
$result2 = mysql_query( $sql2,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 

$i=0;
while($row2 = mysql_fetch_assoc($result2)) {
	$arr = array($row2['name']);
	#print_r($row2);
	foreach ($tech_list as $tech) {
		$arr[] = $row2[$tech];
	}
	$data->rows[$i]['cell'] = $arr;
	$i++;
}
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>
