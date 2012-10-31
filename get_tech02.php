<?
/////Список ТТ.json.
include error_reporting(0);
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');
$idc = $_GET["idc"];


$result = mysql_query("SELECT count(*) as cnt FROM `cat_tanks` where class='mediumTank'");
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count_t = $row['cnt']; 
//$message="<table  border='1' width='900px'>";
$sql="SELECT * FROM `cat_tanks` where class='mediumTank' ORDER BY level desc,nation";
$result = mysql_query( $sql,$connect ) or die("Couldn t execute query.".mysql_error()); 

	$data->page       = 1;
	$data->total      = 1;
	$data->records    = $count_t;
	$i = 0;

while($row = mysql_fetch_assoc($result)) {
	$idt=$row['id_t'];$br=0;	
	$sql2="SELECT pl.name,pb.idp,max(pb.battle_count) as battle_count ,max(pb.win_count) as win_count FROM `player_btl` pb, `player` pl,`clan` cl WHERE pl.idp=pb.idp and pb.idt=$idt and cl.idp=pl.idp and cl.idc=$idc group by idp order by battle_count desc";
	$result2 = mysql_query( $sql2,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	$cnt=0;
	$player="";
	$img="<img src='http://".$wot_host.$row['image_url']."'/>"; 
	
	while($row2 = mysql_fetch_assoc($result2)) {
		$proc=round(($row2['win_count']*100/$row2['battle_count']),2);
		$br_tag="";
		if($br==7){$br_tag="<br>";$br=0;}
		$player=$player." ".$br_tag.$row2['name']." (".$proc."% / ".$row2['battle_count'].")".", ";
		$cnt++;
		$br++;
	}
	$col1=$img;
	$col2=$row['localized_name'];
	$col3=$cnt;
	$col4=$player;
	$data->rows[$i]['cell'] = array($row['class'],$col1,$col2,$row['level'],$col3,$col4);
	$i++;
}
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>
