<?
/////Общая статистика по боям за 30дней
include error_reporting(0);
include('settings.kak');

$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
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
if(!$sidx) $sidx =4;
if(!$page) $page =1;
$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {
	$limit = $totalrows;
}
$minDate=date("Y-m-d",strtotime(' -30 day '.$hosttime));
$sql="SELECT count(distinct `idt`) as cnt from `player_btl`  WHERE idp='$idac'and date>='$minDate'";
//SELECT count(*) as cnt from `player_btl` c, cat_tanks a WHERE idp='$idac' and c.idt=a.id_t and date>='$minDate'";
$result = mysql_query($sql); 
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['cnt']; 
if(($count>0) and ($limit>0)) { 
	$total_pages = ceil($count/$limit); 
} else { 
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit;
if($start <0) $start = 0;
//$sql="SELECT a.localized_name,a.class as cls,max(c.battle_count) as maxb_c,max(c.win_count) as maxw_c,min(c.battle_count) as minb_c,min(c.win_count) as minw_c, max(c.spotted) as maxs, min(c.spotted) as mins, max(c.damageDealt) as maxD, min(c.damageDealt) as minD, max(c.survivedBattles) as maxsur, min(c.survivedBattles) as minsur, max(c.frags) as maxf, min(c.frags) as minf, (max(c.battle_count)-min(c.battle_count)) as diffb_c,(max(c.win_count)-min(c.win_count)) as diffw_c,c.date,c.time, a.level from `player_btl` c, cat_tanks a WHERE idp='$idac' and c.idt=a.id_t and date>='$minDate' group by idt having (max(c.battle_count)-min(c.battle_count))>0 order by diffb_c desc,date desc,time desc,maxb_c desc LIMIT $start , $limit";
$sql="SELECT c.idt as idt, a.localized_name,a.class as cls,max(c.battle_count) as maxb_c, ROUND((max(c.win_count)*100/max(c.battle_count)),2) as proc, max(c.win_count) as maxw_c, max(c.spotted) as maxs,ROUND((max(c.spotted)/max(c.battle_count)),2) as procAs, max(c.damageDealt) as maxD, ROUND((max(c.damageDealt)/max(c.battle_count)),2) as procAD,  max(c.survivedBattles) as maxsur, ROUND((max(c.survivedBattles)*100/max(c.battle_count)),2) as procAsur,  max(c.frags) as maxf, ROUND((max(c.frags)/max(c.battle_count)),2) as procAf, c.date,c.time, a.level from `player_btl` c, cat_tanks a WHERE idp='$idac' and c.idt=a.id_t and date>='$minDate' group by idt order by $sidx $sord LIMIT $start , $limit";
$result = mysql_query( $sql,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
	$data->page       = $page;
	$data->total      = $total_pages;
	$data->records    = $count;
	$i = 0;
	while($row = mysql_fetch_assoc($result)) {
			$idtank=$row['idt'];
			$sqlmax="SELECT a.localized_name,a.class as cls,max(c.battle_count) as maxb_c,max(c.win_count) as maxw_c, max(c.spotted) as maxs,   max(c.damageDealt) as maxD,  max(c.survivedBattles) as maxsur,  max(c.frags) as maxf,  c.date,c.time, a.level from `player_btl` c, cat_tanks a WHERE idp='$idac' and c.idt=a.id_t and c.idt='$idtank' and date<'$minDate' group by idt order by date desc,time desc,maxb_c desc";
			$resultmax = mysql_query( $sqlmax,$connect ) or die("<br>Couldn t execute query.".mysql_error()); 
			$rowmax = mysql_fetch_array($resultmax,MYSQL_ASSOC); 
			$diffb_c= $row['maxb_c']-$rowmax['maxb_c'];
			//if ($diffb_c<>0){
				$diffw_c=$row['maxw_c']-$rowmax['maxw_c'];
				$diffs=$row['maxs']-$rowmax['maxs'];
				$diffD=$row['maxD']-$rowmax['maxD'];
				$diffsur=$row['maxsur']-$rowmax['maxsur'];
				$difff=$row['maxf']-$rowmax['maxf'];
				$proc=round(($diffw_c*100/$diffb_c),2);
				$procA=$row['proc'];
				$procs=round((double)($diffs)/$diffb_c,2);
				$procAs=$row['procAs'];
				$procD=round($diffD/$diffb_c);
				$procAD=$row['procAD'];
				$procsur=round($diffsur*100/$diffb_c,2);
				$procAsur=$row['procAsur'];
				$procf = round((double)($difff)/$diffb_c,2);
				$procAf = $row['procAf'];
				if (($rowmax['maxD']==0) and ($rowmax['maxb_c']<>0)){
				$procs="*";
				$procD="*";
				$procsur="*";
				$procf="*";
				}
				if ($diffb_c<>$row['maxb_c']){
					$data->rows[$i]['cell'] = array($row['cls'],$row['localized_name']." (".$row['level']." lvl)","+ ".$diffb_c." / ".$diffw_c." (".$row['maxb_c'].")",$proc." (".$procA.")", $procs." (".$procAs.")", $procD." (".$procAD.")", $procsur." (".$procAsur.")", $procf." (".$procAf.")");
				}else{
					$data->rows[$i]['cell'] = array($row['cls'],$row['localized_name']." (".$row['level']." lvl)","+ ".$diffb_c." / ".$diffw_c,$proc, $procAs,$procAD,$procAsur, $procAf);
				}
				$i++;
		//}
	}

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
?>
