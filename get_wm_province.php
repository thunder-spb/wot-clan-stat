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
$curr="";
$t = time()-700000;
$clanlist = mysql_query("select idc from clan_info where actdate>'$t'",$connect);
$clancnt=array();
mysql_query("update clan_info set allians='0' ",$connect);
foreach ($clan_array as $clan_i) {
		$idc=$clan_i["clan_id"];
		$clancnt[]=$clan_i["clan_id"];
		mysql_query("update clan_info set allians='1' where idc='$idc'",$connect);
}
while ($clanrow=mysql_fetch_array($clanlist,MYSQL_ASSOC)) {
	$clancnt[]=$clanrow["idc"];
 }
 
 $clancnt=array_unique($clancnt);
 
foreach ($clancnt as $idc) {
    $a=$a+1;
	$pageidp = "clans/".$idc."-"."/provinces/?type=table";
	$pageidp = "cw.".$wot_host.'/'.$pageidp;	
	$data = get_page($pageidp);
	$data = json_decode($data, true);
	$pageidp = "clans/".$idc."-"."/battles/?type=table";
	$pageidp = "cw.".$wot_host.'/'.$pageidp;
	$databtl = get_page($pageidp);
	echo "<br>-------------------------------------<br>";
	$databtl = json_decode($databtl, true);
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
			$type = $item["type"];
			$attacked = $item["attacked"];
			$occupancy_time = $item["occupancy_time"];
			$total_poss[] = $id;
			$prov = mysql_query("select id_pr,periphery, region, arena_id  from province where id='$id'",$connect);
			$result = mysql_fetch_array($prov,MYSQL_ASSOC);
			$succes=0;
			if (($result==NULL) or($result['periphery']==NULL) or($result['region']==NULL)or ($result['arena_id']==0)) {
				if ($result['region']==NULL){
					$all = mysql_query("select id_r, url  from wm_regions");
				}else{
					$region=$result['region'];
					$all = mysql_query("select id_r, url  from wm_regions where id_r='$region'");
				}
				while ($res = mysql_fetch_array($all,MYSQL_ASSOC)) {
					if ($curr<>$res['id_r']){
						$curr=$res['id_r'];
						$pageidp = "http://cw.worldoftanks.ru".$res['url']."?ct=json";
						$databt2 = get_page($pageidp);
						$data2 = json_decode($databt2, true);
					}
					$provdata =$data2['provinces'][$id];
					if ($provdata<>NULL){
						mysql_query("delete from province where id='$id'",$connect);
						print_r($provdata);
						$per=$provdata['periphery'];
						$landing_url=$provdata['landing_url'];
						$landing_final_battle_time=$provdata['landing_final_battle_time'];
						$arena=$provdata['mapId'];
						$arenaar=explode ( "_", $arena );
						$arena_id=$arenaar[1];
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
	// обрабатываем список боёв
	if ($databtl["result"]=="success"){
		$sql = mysql_query("select allians from clan_info where idc='$idc'",$connect);
		$resultt = mysql_fetch_array($sql,MYSQL_ASSOC);
		foreach($databtl["request_data"]["items"] as $item) {
			
			$provinces_name=$item["provinces"][0]["name"];
			$provinces_id=$id=$item["provinces"][0]["id"];
			$btlarena=$item["arenas"][0];
			$started=$item["started"];
			$type=$item["type"];
			$prov = mysql_query("select id_pr,periphery, region,arena_id,type  from province where id='$id'",$connect);
			$result = mysql_fetch_array($prov,MYSQL_ASSOC);
			$succes=0;
			//if (($result==NULL) or($result['periphery']==NULL) or($result['region']==NULL)or ($result['arena_id']==0) or ($result['type']==NULL)) {
			if ($result['region']==NULL){
				$all = mysql_query("select id_r, url  from wm_regions",$connect);
			}else{
				$region=$result['region'];
				$all = mysql_query("select id_r, url  from wm_regions where id_r='$region'",$connect);
			}
			//для каждого боя делаем подробную выборку по участвующим проинциям из ГК
			while ($res = mysql_fetch_array($all,MYSQL_ASSOC)) {
				if ($curr<>$res['id_r']){
					$curr=$res['id_r'];
					$pageidp = "http://cw.worldoftanks.ru".$res['url']."?ct=json";
					$databt2 = get_page($pageidp);
					$data2 = json_decode($databt2, true);
				}
				$provdata =$data2['provinces'][$id];
				if ($provdata<>NULL){
					$status=$provdata['status'];
					$combats=$provdata['combats'];
					mysql_query("delete from province where id='$id'",$connect);
					echo "<br>----Выборка боёв из данных по провинции----".$provinces_name."<br>";
					print_r($combats);
					$per=$provdata['periphery'];
					$landing_url=$provdata['landing_url'];
					$landing_final_battle_time=$provdata['landing_final_battle_time'];
					$arena=$provdata['mapId'];
					$revenue=$provdata['revenue'];
					$prime = $provdata["prime_time"];
					$landing_started=$provdata["landing_started"];
					$arenaar=explode ( "_", $arena );
					$arena_id=$arenaar[1];
					$region=$res['id_r'];
					$sql = "insert into province ( id, name, type,prime_time, revenue,  periphery,arena_id,landing_url,landing_final_battle_time,region)";
					$sql .= " values ('$id', '$provinces_name', '$status','$prime', '$revenue','$per', '$arena_id','$landing_url','$landing_final_battle_time','$region')";
					mysql_query($sql, $connect);
					$sql12 = "delete from `btl` where id_prov='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
					if ($status<>"start"){
						foreach ($combats as $cmb){
							echo "<br>";
							print_r($cmb);
							$ncombat=key($combats);
							$combatants=$combats["$ncombat"]['combatants'];
							foreach ($combatants as $btlclan){
								$clanb=key($combatants);
								echo "<br> клан ".$clanb;
								echo "<br> учавствует в битве N ".$ncombat;
								echo "<br> за провинцию ".$provinces_name;
								$type = $combats["$ncombat"]['type'];
								echo "<br> тип битвы ".$type;
								echo "<br> на сервере ".$combats["$ncombat"]['peripheryId'];
								//echo "<br> время ".$time;
								$started = $combats["$ncombat"]['started'];
								$bttime = $combats["$ncombat"]['at'];
								//if ($started==1){
									$bttime=$combatants["$clanb"]['at'];
								//}
								if ($bttime == NULL) {
									$bttime = $combats["$ncombat"]['at'];
									$started=0;
								}
								
								$time=strtotime(date("Y-m-d H:i:s",$bttime).$hosttime);
								$bttime=$time;	
								$sql = "insert into btl (idb, idc,  time, type, id_prov,prov, started, arena)";
								$sql .= " values ('$ncombat', '$clanb',  '$bttime', '$type', '$provinces_id','$provinces_name', '$started', '$btlarena')";
								mysql_query($sql, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								
								$sql = mysql_query("select idc, allians from clan_info where idc='$clanb'",$connect);
								$result = mysql_fetch_array($sql,MYSQL_ASSOC);
								if ($result == NULL){
									$dataclan=$data2['clans'][$clanb];
									$clanname=$dataclan['name'];
									$clancolor=$dataclan['color'];
									$clantag=$dataclan['tag'];
									$clanurl=$dataclan['url'];
									$t=time();
									if ($resultt['allians']<>1){
										$t=0;
									}
									$sql = "insert into clan_info ( idc,  name, color, tag, url,actdate,allians)";
									$sql .= " values ('$clanb', '$clanname',  '$clancolor', '$clantag', '$clanurl','$t',0)";
									mysql_query($sql, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								}else{
									if ($resultt['allians']==1){
										$t=time();
										mysql_query("update clan_info set actdate='$t' where idc='$clanb'",$connect);
									}
								}
								next($combatants);
							}
							$ncombat=key($combats);
							next($combats);
						}
					}else{
						// с высадками вообще всё просто.
						$sql = "insert into btl (idb, idc,  time, type, id_prov,prov, started, arena)";
						$sql .= " values ('0', '$idc',  '$landing_final_battle_time', 'landing', '$provinces_id','$provinces_name', '$landing_started', '$btlarena')";
						mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
					}
					$succes=1;
					break;
				}	
			}
			
			$btlchips=$item["chips"];
			$btlid=0;
			//$sql = "insert into btl (idb, idc, date, time, type, id_prov,prov, id_prov1,prov1, started, arena, arena1, chips)";
			//$sql .= " values ('$btlid', '$idc', '$btldate', '$btltime', '$type', '$provinces_id','$provinces_name', '$provinces_id1','$provinces_name1','$started', '$btlarena','$btlarena1','$btlchips')";
			//mysql_query($sql, $connect);
			//if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
			echo "<br> --------Данные из списка боёв----------<br>".$provinces_name." id=".$provinces_id."<br>".$started."<br>тип ".$type."<br>карта ".$btlarena."<br>номер ".$btlid."<br>фишки ".$btlchips."<br>";
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