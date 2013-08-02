<?php
// выборка владений клана на ГК, обновление и удаление владений

include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8'); 
$t=time();
$actwmdatesql = mysql_query("select lasthourwm from tech",$connect);
$actwmdate=mysql_fetch_array($actwmdatesql,MYSQL_ASSOC);
//$hour=date("H",strtotime($hosttime));
// Проверяем на актуальность и обеспечиваем 2 прохода.
$curr="reg_01";
$allgk =array();
echo PHP_EOL."Подгружаем данные из ГК... регион-".$curr.PHP_EOL;
$pageidp = "http://cw.worldoftanks.ru/clanwars/maps/provinces/regions/1/?ct=json";
$databt2 = get_page($pageidp);
$allgk[$curr] = json_decode($databt2, true);
$gkturn=$allgk[$curr]['turn']['id'];
if ($gkturn<>NULL){
	echo "текущий ход на ГК ".$gkturn;
	$gkturn=$gkturn*2+1;
	if ($actwmdate['lasthourwm']<>NULL){
		if ($actwmdate['lasthourwm']==$gkturn){
			die ();
		}
		$e=$gkturn-$actwmdate['lasthourwm'];
		if ($e==2){
			$gkturn=$gkturn-1;
		}
	}
}else{
	die("Не удалось загрузить данные из ГК");
}
$total_poss = array();
//счётчики для проверки валиности данных
$a=0;
$b=0;
//$curr="";
$nlanding=500000;
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
	$total_poss = array();
	$sql = mysql_query("select allians from clan_info where idc='$idc'",$connect);
	$resultt = mysql_fetch_array($sql,MYSQL_ASSOC); 
    $a=$a+1;
	$pageidp = "clans/".$idc."-"."/provinces/?type=table";
	$pageidp = "cw.".$wot_host.'/'.$pageidp;	
	$data = get_page($pageidp);
	$data = json_decode($data, true);
	$pageidp = "clans/".$idc."-"."/battles/?type=table";
	$pageidp = "cw.".$wot_host.'/'.$pageidp;
	$databtl = get_page($pageidp);
	echo PHP_EOL."-------------обрабатываем клан ".$idc.PHP_EOL;
	$databtl = json_decode($databtl, true);
	$t = time();
	echo "Подгружаем данные по провинциям...".PHP_EOL;
	if ($data["result"]=="success"){
		$b=$b+1;
		echo "Успешно".PHP_EOL;
		$total_count=$data["request_data"]['total_count'];
		echo "У клана ".$total_count." провинций".PHP_EOL;
		if (($total_count==0) and ($resultt['allians']==0)){
			if ($resultt['allians']<>NULL){
				echo "чистка данных...".PHP_EOL;
				mysql_query("delete from btl where idc='$idc'",$connect);
				mysql_query("delete from possession where idc='$idc'",$connect);
				mysql_query("delete from wm_event where idc='$idc'",$connect);
				mysql_query("update clan_info set actdate=0 where idc='$idc'",$connect);
				continue;
			}	
		}
		$total_poss_old = array();
		$all = mysql_query("select idpr from possession where idc='$idc'");
		while ($res = mysql_fetch_array($all,MYSQL_ASSOC)) {
			$total_poss_old[] = $res["idpr"];
		}
		foreach($data["request_data"]["items"] as $item) {
			$prime_time = $item["prime_time"];
			$id = $item["id"];
			echo "клану принадлежит провинция   ".$id.PHP_EOL;
			$name = $item["name"];
			$arena_id = $item["arena_id"];
			$arena_name = $item["arena_name"];
			$arenadb = mysql_query("select * from arenas where id='$arena_id'",$connect);
			if (!mysql_fetch_array($arenadb,MYSQL_ASSOC)) {
				echo "   OOOPS! New map... add this to db".PHP_EOL;
				$sql = "insert into arenas (id, name)";
				$sql .= " values ('$arena_id', '$arena_name')";
				mysql_query($sql, $connect);
			}
			$revenue = $item["revenue"];
		//	echo "___revenue: ".$revenue.PHP_EOL;
			$capital=$item["capital"];
			$type = $item["type"];
		//	echo "___type: ".$type.PHP_EOL;
			$attacked = $item["attacked"];
			$occupancy_time = $item["occupancy_time"];
			$total_poss[] = $id;
			$prov = mysql_query("select id_pr,periphery, region,neighbours, arena_id  from province where id='$id'",$connect);
			$result = mysql_fetch_array($prov,MYSQL_ASSOC);
			$succes=1;
				if ($result['region']==NULL){
					$all = mysql_query("select id_r, url  from wm_regions",$connect);
				}else{
					$region=$result['region'];
					$all = mysql_query("select id_r, url  from wm_regions where id_r='$region'",$connect);
				}
				$succes=0;
				while ($res = mysql_fetch_array($all,MYSQL_ASSOC)) {
					if ($curr<>$res['id_r']){
						$curr=$res['id_r'];
						if (!(array_key_exists($curr, $allgk))){
							echo PHP_EOL."Подгружаем данные из ГК... регион-".$curr.PHP_EOL;
							$pageidp = "http://cw.worldoftanks.ru".$res['url']."?ct=json";
							$databt2 = get_page($pageidp);
							$allgk[$curr] = json_decode($databt2, true);
						}else{
							echo PHP_EOL."Данные из ГК уже подгружены ранее, регион-".$curr.PHP_EOL;
						}
						
					}
					if ($allgk[$curr]['provinces']<>null){
						if (array_key_exists($id, $allgk[$curr]['provinces'])) {
							$provdata =$allgk[$curr]['provinces'][$id];
							//if ($provdata<>NULL){
						//	echo PHP_EOL."<b> OK !</b>";
							mysql_query("delete from province where id='$id'",$connect);
							//print_r($provdata);
							$per=$provdata['periphery'];
							$landing_url=$provdata['landing_url'];
							$neighbours=$provdata['neighbours'];
							$neighbours=implode(";",array_keys($neighbours));
							$landing_final_battle_time=0;
							if (array_key_exists('landing_final_battle_time', $provdata)) {
								$landing_final_battle_time=$provdata['landing_final_battle_time'];
							}
							// $landing_final_battle_time=$provdata['landing_final_battle_time'];
							$arena=$provdata['mapId'];
							$arenaar=explode ( "_", $arena );
							$arena_id=$arenaar[1];
							$region=$res['id_r'];
							$mutiny=$provdata['mutiny'];
							$mutinye=$provdata['mutiny_expected'];
							$sql = "insert into province (prime_time, neighbours, id, name, arena_id,  revenue, type, periphery,landing_url,landing_final_battle_time,region)";
							$sql .= " values ('$prime_time','$neighbours', '$id', '$name', '$arena_id',  '$revenue', '$type','$per', '$landing_url','$landing_final_battle_time','$region')";
							mysql_query($sql, $connect);
							$succes=1;
							break;
						}
					}
				}
			if ($succes<>1){
				die("Не удалось загрузить данные из ГК");
			}
			mysql_query("update province set  revenue='$revenue', prime_time='$prime_time', name='$name', arena_id='$arena_id', type='$type' where id='$id'",$connect);
			$poss = mysql_query("select id_pos, mutiny from possession where idpr='$id' and idc='$idc'",$connect);
			echo "Updating province data".PHP_EOL;
			$sel=mysql_fetch_array($poss,MYSQL_ASSOC);
			if ($sel==NULL) {
				//новая провинция
				echo "New possession".PHP_EOL;
				$poss1 = mysql_query("select idc from possession where idpr='$id'",$connect);
				if ($poss1=mysql_fetch_array($poss1,MYSQL_ASSOC)) {
					//провинция получена от клана в списке
					$idc_old = $poss1['idc'];
					$sql = mysql_query("select allians from clan_info where idc='$idc_old'",$connect);
					$resultold = mysql_fetch_array($sql,MYSQL_ASSOC); 
					echo  "old clan ".$idc_old." in allians ".$resultold['allians'].PHP_EOL;
					mysql_query("update possession set idc='$idc',mutiny='$mutiny', capital='$capital' where idpr='$id'",$connect);
					if (($resultt['allians']==1)and($resultold['allians']==1)){
						echo "передача провинции внутри альянса".PHP_EOL;
						mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '2', '$t', '$idc')",$connect);
						mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '3', '$t', '$idc_old')",$connect);
					}else{
						echo "захват провинции".PHP_EOL;
						mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '1', '$t', '$idc')",$connect);
						mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '0', '$t', '$idc_old')",$connect);
					}
				} else {
					//провинция захвачена у клана не из списка  clan_info или новые данные.
					echo "добавляем данные".PHP_EOL;
					mysql_query("insert into possession (idc, idpr, attacked, occupancy_time, capital) values ('$idc','$id','$attacked','$occupancy_time','$capital')",$connect);
					mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '1', '$t', '$idc')",$connect);
				}
			} else {
				
				if (($mutiny==1) and ($sel['mutiny']==0)){
					echo "МЯТЕЖ!!!!".PHP_EOL;
					mysql_query("insert into wm_event (idpr, type, time, idc) values ('$id', '4', '$t', '$idc')",$connect);
				}
				//клан уже владеет провинцией
				mysql_query("update possession set mutiny='$mutiny', attacked='$attacked', occupancy_time='$occupancy_time', capital='$capital' where idpr='$id'",$connect);
			}
		}
		
	}else{
		die("Не удалось загрузить данные о провинциях");
	}
	// обрабатываем список боёв
	if ($databtl["result"]=="success"){
		 $sql12 = "delete from `btl` where idc='$idc'"; 
		 $qq2 = mysql_query($sql12,$connect);
		 if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."";
		
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
					echo "Подгружаем данные из ГК...";
					$curr=$res['id_r'];
					if (!(array_key_exists($curr, $allgk))){
							echo PHP_EOL."Подгружаем данные из ГК... регион-".$curr.PHP_EOL;
							$pageidp = "http://cw.worldoftanks.ru".$res['url']."?ct=json";
							$databt2 = get_page($pageidp);
							$allgk[$curr] = json_decode($databt2, true);
							//$allgk[$curr]=$data2;
						}else{
							echo PHP_EOL."Данные из ГК уже подгружены ранее, регион-".$curr.PHP_EOL;
						}
					// $pageidp = "http://cw.worldoftanks.ru".$res['url']."?ct=json";
					// $databt2 = get_page($pageidp);
					// $data2 = json_decode($databt2, true);
					// echo " регион ".$curr.PHP_EOL;
				}
				
				if (array_key_exists($id, $allgk[$curr]['provinces'])) {
					$provdata =$allgk[$curr]['provinces'][$id];
					//if ($provdata<>NULL){
					$status=$provdata['status'];
					$combats=$provdata['combats'];
					mysql_query("delete from province where id='$id'",$connect);
					//echo "----Выборка боёв из данных по провинции----".$provinces_name.PHP_EOL;
					//print_r($combats);
					$per=$provdata['periphery'];
					$landing_url=$provdata['landing_url'];
					$landing_final_battle_time=0;
					if (array_key_exists('landing_final_battle_time', $provdata)) {
						$landing_final_battle_time=$provdata['landing_final_battle_time'];
					}
					$arena=$provdata['mapId'];
					$revenue=$provdata['revenue'];
					$prime = $provdata["prime_time"];
					$landing_started=$provdata["landing_started"];
					$arenaar=explode ( "_", $arena );
					$arena_id=$arenaar[1];
					$region=$res['id_r'];
					$neighbours=$provdata['neighbours'];
						$neighbours=implode(";",array_keys($neighbours));
					$sql = "insert into province ( id, name,neighbours, type,prime_time, revenue,  periphery,arena_id,landing_url,landing_final_battle_time,region)";
					$sql .= " values ('$id', '$provinces_name','$neighbours', '$status','$prime', '$revenue','$per', '$arena_id','$landing_url','$landing_final_battle_time','$region')";
					mysql_query($sql, $connect);
					$sql12 = "delete from `btl` where id_prov='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."";
					if ($status<>"start"){
						foreach ($combats as $cmb){
							$ncombat=key($combats);
							$combatants=$combats["$ncombat"]['combatants'];
							foreach ($combatants as $btlclan){
								$clanb=key($combatants);
								$type = $combats["$ncombat"]['type'];
								$started = $combats["$ncombat"]['started'];
								$bttime = $combats["$ncombat"]['at'];
								$bttime=$combatants["$clanb"]['at'];
								if ($bttime == NULL) {
									$bttime = $combats["$ncombat"]['at'];
									$started=0;
								}
								
								$time=strtotime(date("Y-m-d H:i:s",$bttime).$hosttime);
								$bttime=$time;	
								$sql = "insert into btl (idb, idc,  time, type, id_prov,prov, started, arena)";
								$sql .= " values ('$ncombat', '$clanb',  '$bttime', '$type', '$provinces_id','$provinces_name', '$started', '$btlarena')";
								mysql_query($sql, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."";
								
								$sql = mysql_query("select idc, allians from clan_info where idc='$clanb'",$connect);
								$result = mysql_fetch_array($sql,MYSQL_ASSOC);
								if ($result == NULL){
									$dataclan=$allgk[$curr]['clans'][$clanb];
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
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."";
									mysql_query("update tech set lastiv=0",$connect);
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
						$sql .= " values ('$nlanding', '$idc',  '$landing_final_battle_time', 'landing', '$provinces_id','$provinces_name', '$landing_started', '$btlarena')";
						mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."";
						$nlanding=$nlanding+1;
					}
					$succes=1;
					break;
				}	
			}
			if ($succes<>1){
				die("Не удалось загрузить данные о боях из ГК".PHP_EOL);
			}
			$btlchips=$item["chips"];
			$btlid=0;
		//	echo PHP_EOL." --------Данные из списка боёв----------".PHP_EOL.$provinces_name." id=".$provinces_id.PHP_EOL.$started.PHP_EOL." тип ".$type.PHP_EOL."карта ".$btlarena.PHP_EOL."номер ".$btlid.PHP_EOL." фишки ".$btlchips.PHP_EOL;
		}
	}else{
		die (PHP_EOL."Не удалось загрузить список боёв из данных о клане");
	}
	
		$lost = array_diff($total_poss_old,$total_poss);
		
		foreach ($lost as $lost_prov) {
			$qidc = mysql_query("select idc from possession where idpr='$lost_prov'");
			$res = mysql_fetch_array($qidc,MYSQL_ASSOC);
			$idc_lost = $res["idc"];
			mysql_query("insert into wm_event (idpr, type, time, idc) values ('$lost_prov', '0', '$t', '$idc_lost')",$connect);
			mysql_query("delete from possession where idpr='$lost_prov'",$connect);
		}
		unset($total_poss);
		unset($total_poss_old);
	
}
mysql_query("update tech set lasthourwm='$gkturn'",$connect);
function get_page($url) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Accept: application/json, text/javascript, text/html, */*',
												'X-Requested-With: XMLHttpRequest'));
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HTTPGET, true);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
}	
mysql_close($connect);
?>