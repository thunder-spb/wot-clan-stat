<?
// выборка владений клана на ГК, обновление и удаление владений

include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8'); 

$total_poss = array();
foreach ($clan_array as $clan_i) {
	$idc = $clan_i["clan_id"];
	$pageidp = "community/clans/".$idc."/provinces/?type=table";
	$pageidp = $wot_host.'/'.$pageidp;	
	//$date = date("Y-m-d",strtotime($hosttime));
	//$time = date("H:i:s",strtotime($hosttime));
	$data = get_page($pageidp);
	$data = json_decode($data, true);
	$t = time();
	foreach($data["request_data"]["items"] as $item) {
		$prime_time = $item["prime_time"];
		$id = $item["id"];
		//echo $id."   ";
		$name = $item["name"];
		$arena_id = $item["arena_id"];
		$arena_name = $item["arena_name"];
		$revenue = $item["revenue"];
		$type = $item["type"];
		$attacked = $item["attacked"];
		$occupancy_time = $item["occupancy_time"];
		$total_poss[] = $id;
		$prov = mysql_query("select id_pr from province where id='$id'",$connect);
		//$result = mysql_fetch_array($prov,MYSQL_ASSOC);
		if (!mysql_fetch_array($prov,MYSQL_ASSOC)) {
			// провинции нет в таблице province
			$sql = "insert into province (prime_time, id, name, arena_id, arena_name, revenue, type)";
			$sql .= " values ('$prime_time', '$id', '$name', '$arena_id', '$arena_name', '$revenue', '$type')";
			mysql_query($sql, $connect);
			
		}
		$poss = mysql_query("select id_pos from possession where idpr='$id' and idc='$idc'",$connect);
		if (!mysql_fetch_array($poss,MYSQL_ASSOC)) {
			//новая провинция
			$poss1 = mysql_query("select idc from possession where idpr='$id'",$connect);
			if (mysql_fetch_array($poss1,MYSQL_ASSOC)) {
				//провинция получена от союзника
				$idc_old = $poss1["idc"];
				mysql_query("update possession set idc='$idc' where idpr='$id'",$connect);
				mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '2', '$t', '$idc')",$connect);
				mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '3', '$t', '$idc_old')",$connect);
			} else {
				//провинция захвачена у врага
				mysql_query("insert into possession (idc, idpr, attacked, occupancy_time) values ('$idc','$id','$attacked','$occupancy_time')",$connect);
				mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '1', '$t', '$idc')",$connect);
			}
		} else {
			//клан уже владеет провинцией
			mysql_query("update possession set attacked='$attacked', occupancy_time='$occupancy_time' where idpr='$id'",$connect);
		}
	}
}
$total_poss_old = array();
$all = mysql_query("select idpr from possession");
while ($res = mysql_fetch_array($all,MYSQL_ASSOC)) {
	$total_poss_old[] = $res["idpr"];
}
$lost = array_diff($total_poss_old,$total_poss);
foreach ($lost as $lost_prov) {
	$qidc = mysql_query("select idc from possession where idpr='$lost_prov'");
	$res = mysql_fetch_array($qidc,MYSQL_ASSOC);
	$idc_lost = $res["idc"];
	mysql_query("insert into wm_event (idpr, type, time, idc) values ('$lost_prov', '0', '$t', '$idc_lost')",$connect);
	mysql_query("delete from possession where idpr='$lost_prov'",$connect);
}

function get_page($url) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Requested-With: XMLHttpRequest'));
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HTTPGET, true);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
}	
mysql_close($connect);
