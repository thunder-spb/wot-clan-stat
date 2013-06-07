<?php
// выборка владений клана на ГК, обновление и удаление владений

include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8'); 

$total_poss = array();
//счётчики для проверки валиности данных
$a=0;
$b=0;
//echo $a;
foreach ($clan_array as $clan_i) {
    // $sql12 = "delete from `btl` where idc='$clan_i'"; 
	// $qq2 = mysql_query($sql12,$connect);
	// if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$a=$a+1;
	$idc = $clan_i["clan_id"];
	$pageidp = "clans/".$idc."-"."/provinces/?type=table";
	$pageidp = "cw.".$wot_host.'/'.$pageidp;	
	//$date = date("Y-m-d",strtotime($hosttime));
	//$time = date("H:i:s",strtotime($hosttime));
	$data = get_page($pageidp);
	$data = json_decode($data, true);
	$pageidp = "clans/".$idc."-"."/battles/?type=table";
	$pageidp = "cw.".$wot_host.'/'.$pageidp;
	$databtl = get_page($pageidp);
	print_r($databtl);
	echo "<br>-------------------------------------<br>";
	$databtl = json_decode($databtl, true);
	//$pageidp = "/clanwars/maps/provinces/467/";
	//$pageidp = "http://cw.worldoftanks.ru/clanwars/maps/provinces/regions/3/?ct=json";
//$pageidp = $wot_host.'/'.$pageidp;	
	// $databt2 = get_page($pageidp);
	// $data2 = json_decode($databt2, true);
	echo "<br>-------------------------------------<br>";
	//$provdata=$data2;//['provinces'];
	//print_r($provdata);
	$t = time();
	if ($data["result"]=="success"){
	$sql12 = "delete from `btl` where idc='$idc'"; 
	$qq2 = mysql_query($sql12,$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$b=$b+1;
	foreach($data["request_data"]["items"] as $item) {
		$prime_time = $item["prime_time"];
		$id = $item["id"];
		echo $id."   ";
		$name = $item["name"];
		$arena_id = $item["arena_id"];
		$arena_name = $item["arena_name"];
		$arenadb = mysql_query("select * from arenas where id='$arena_id'",$connect);
		if (!mysql_fetch_array($arenadb,MYSQL_ASSOC)) {
			$sql = "insert into arenas (id, name)";
			$sql .= " values ('$arena_id', '$arena_name')";
			mysql_query($sql, $connect);
		}
		$revenue = $item["revenue"];
		$capital=$item["capital"];
		//echo "<br> capital ".$capital."<br>";
		//echo $revenue;
		$type = $item["type"];
		$attacked = $item["attacked"];
		$occupancy_time = $item["occupancy_time"];
		$total_poss[] = $id;
		$prov = mysql_query("select id_pr,periphery, region  from province where id='$id'",$connect);
		$result = mysql_fetch_array($prov,MYSQL_ASSOC);
		$succes=0;
		if (($result==NULL) or($result['periphery']==NULL) or($result['region']==NULL)) {
			
			// провинции нет в таблице province
			//$sql = "insert into province (prime_time, id, name, arena_id,  revenue, type)";
			//$sql .= " values ('$prime_time', '$id', '$name', '$arena_id',  '$revenue', '$type')";
			//mysql_query($sql, $connect);
			$all = mysql_query("select id_r, url  from wm_regions");
			while ($res = mysql_fetch_array($all,MYSQL_ASSOC)) {
				print_r($res);
				//$pageidp = "http://cw.worldoftanks.ru/clanwars/maps/provinces/regions/3/?ct=json";
				$pageidp = "http://cw.worldoftanks.ru".$res['url']."?ct=json";
				$databt2 = get_page($pageidp);
				$data2 = json_decode($databt2, true);
				$provdata =$data2['provinces'][$id];
				if ($provdata<>NULL){
					mysql_query("delete from province where id='$id'",$connect);
					print_r($provdata);
					$per=$provdata['periphery'];
					$landing_url=$provdata['landing_url'];
					$landing_final_battle_time=$provdata['landing_final_battle_time'];
					$region=$res['id_r'];
					$sql = "insert into province (prime_time, id, name, arena_id,  revenue, type, periphery,landing_url,landing_final_battle_time,region)";
					$sql .= " values ('$prime_time', '$id', '$name', '$arena_id',  '$revenue', '$type','$per', '$landing_url','$landing_final_battle_time','$region')";
					mysql_query($sql, $connect);
					$succes=1;
					break;
				}
			}

			
		}
		mysql_query("update province set revenue='$revenue', prime_time='$prime_time', name='$name', arena_id='$arena_id', type='$type' where id='$id'",$connect);
		$poss = mysql_query("select id_pos from possession where idpr='$id' and idc='$idc'",$connect);
		if (!mysql_fetch_array($poss,MYSQL_ASSOC)) {
			//новая провинция
			$poss1 = mysql_query("select idc from possession where idpr='$id'",$connect);
			if (mysql_fetch_array($poss1,MYSQL_ASSOC)) {
				//провинция получена от союзника
				$idc_old = $poss1["idc"];
				mysql_query("update possession set idc='$idc', capital='$capital' where idpr='$id'",$connect);
				
				mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '2', '$t', '$idc')",$connect);
				mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '3', '$t', '$idc_old')",$connect);
			} else {
				//провинция захвачена у врага
				mysql_query("insert into possession (idc, idpr, attacked, occupancy_time, capital) values ('$idc','$id','$attacked','$occupancy_time','$capital')",$connect);
				mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '1', '$t', '$idc')",$connect);
			}
		} else {
			//клан уже владеет провинцией
			mysql_query("update possession set attacked='$attacked', occupancy_time='$occupancy_time', capital='$capital' where idpr='$id'",$connect);
		}
	}
	echo "<br>".$idc." Done ";
	}
	if ($databtl["result"]=="success"){
	// print_r($databtl);
	foreach($databtl["request_data"]["items"] as $item) {
		$provinces_name=$item["provinces"][0]["name"];
		$provinces_id=$id=$item["provinces"][0]["id"];
		$provinces_name1=$item["provinces"][0]["name"];
		$provinces_id1=$item["provinces"][0]["id"];
		$btlarena=$item["arenas"][0];
		$btlarena1=$item["arenas"][0];
		$started=$item["started"];
		$type=$item["type"];
		$prov = mysql_query("select id_pr,periphery, region  from province where id='$id'",$connect);
		$result = mysql_fetch_array($prov,MYSQL_ASSOC);
		$succes=0;
		if (($result==NULL) or($result['periphery']==NULL) or($result['region']==NULL)) {
			
			// провинции нет в таблице province
			//$sql = "insert into province (prime_time, id, name, arena_id,  revenue, type)";
			//$sql .= " values ('$prime_time', '$id', '$name', '$arena_id',  '$revenue', '$type')";
			//mysql_query($sql, $connect);
			$all = mysql_query("select id_r, url  from wm_regions");
			while ($res = mysql_fetch_array($all,MYSQL_ASSOC)) {
				print_r($res);
				//$pageidp = "http://cw.worldoftanks.ru/clanwars/maps/provinces/regions/3/?ct=json";
				$pageidp = "http://cw.worldoftanks.ru".$res['url']."?ct=json";
				$databt2 = get_page($pageidp);
				$data2 = json_decode($databt2, true);
				$provdata =$data2['provinces'][$id];
				if ($provdata<>NULL){
					mysql_query("delete from province where id='$id'",$connect);
					print_r($provdata);
					$per=$provdata['periphery'];
					$landing_url=$provdata['landing_url'];
					$landing_final_battle_time=$provdata['landing_final_battle_time'];
					$region=$res['id_r'];
					$sql = "insert into province ( id, name,  periphery,landing_url,landing_final_battle_time,region)";
					$sql .= " values ('$id', '$provinces_name',  '$per', '$landing_url','$landing_final_battle_time','$region')";
					mysql_query($sql, $connect);
					$succes=1;
					break;
				}
			}
		}
		if ($type=='meeting_engagement'){
			$provinces_name1=$item["provinces"][1]["name"];
			$provinces_id1=$id=$item["provinces"][1]["id"];
			$prov = mysql_query("select id_pr,periphery, region  from province where id='$id'",$connect);
			$result = mysql_fetch_array($prov,MYSQL_ASSOC);
			$succes=0;
			if (($result==NULL) or($result['periphery']==NULL) or($result['region']==NULL)) {
				
				// провинции нет в таблице province
				//$sql = "insert into province (prime_time, id, name, arena_id,  revenue, type)";
				//$sql .= " values ('$prime_time', '$id', '$name', '$arena_id',  '$revenue', '$type')";
				//mysql_query($sql, $connect);
				$all = mysql_query("select id_r, url  from wm_regions");
				while ($res = mysql_fetch_array($all,MYSQL_ASSOC)) {
					print_r($res);
					//$pageidp = "http://cw.worldoftanks.ru/clanwars/maps/provinces/regions/3/?ct=json";
					$pageidp = "http://cw.worldoftanks.ru".$res['url']."?ct=json";
					$databt2 = get_page($pageidp);
					$data2 = json_decode($databt2, true);
					$provdata =$data2['provinces'][$id];
					if ($provdata<>NULL){
						mysql_query("delete from province where id='$id'",$connect);
						print_r($provdata);
						$per=$provdata['periphery'];
						$landing_url=$provdata['landing_url'];
						$landing_final_battle_time=$provdata['landing_final_battle_time'];
						$region=$res['id_r'];
						$sql = "insert into province ( id, name,  periphery,landing_url,landing_final_battle_time,region)";
						$sql .= " values ('$id', '$provinces_name',  '$per', '$landing_url','$landing_final_battle_time','$region')";
						mysql_query($sql, $connect);
						$succes=1;
						break;
					}
				}
			}
			$btlarena1=$item["arenas"][1];
		}
		$time=strtotime(date("Y-m-d H:i:s",$item["time"]).$hosttime);
		$btldate=date("Y-m-d",$time);	
		$btltime=$time;	
		$btlarena=$item["arenas"][0];
		$btlchips=$item["chips"];
		$btlid=0;
		// if ($type<>"landing"){
			// $btlid=$item["id"];}
			// таблица с текущими битвами клана.
			$sql = "insert into btl (idb, idc, date, time, type, id_prov,prov, id_prov1,prov1, started, arena, arena1, chips)";
			$sql .= " values ('$btlid', '$idc', '$btldate', '$btltime', '$type', '$provinces_id','$provinces_name', '$provinces_id1','$provinces_name1','$started', '$btlarena','$btlarena1','$btlchips')";
			mysql_query($sql, $connect);
			if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
			
			
			echo $provinces_name." id=".$provinces_id."<br>".$started."<br>тип ".$type."<br>время".$btltime."<br>карта ".$btlarena."<br>номер ".$btlid."<br>фишки ".$btlchips."<br>";
		
	  //print_r($item);
	}
	}
}
if ($a==$b){
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
}

function get_page($url) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json, text/javascript, text/html, */*',
												'X-Requested-With: XMLHttpRequest'));
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HTTPGET, true);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
}	
mysql_close($connect);
?>