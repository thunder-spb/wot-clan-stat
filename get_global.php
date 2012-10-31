<?
// выборка данных игрока. анализ, внесение изменений, запись в лог-таблицу

include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8'); 

foreach ($clan_array as $clan_i) {
	$idc = $clan_i["clan_id"];
	$ida = array();
	$clan_list = mysql_query("select idp from clan where idc='$idc'",$connect); // получение списка игроков клана из бд
	while ($members = mysql_fetch_array($clan_list)) {	$ida[]=$members[0];	}
	$sql = "select count(*) as cntN from player_ach";
	$q2 = mysql_query($sql,$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$row = mysql_fetch_array($q2);
	$cntN = $row['cntN']; // кол-во записей в достижениях
	
	$sql = "select count(*) as cntt from cat_tanks";
	$q2 = mysql_query($sql,$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$row = mysql_fetch_array($q2);
	$cntT = $row['cntt']; // кол-во записей в достижениях
	
	for ($mm=0;$mm<count($ida);$mm++)
	{	$id = $ida[$mm];
	
			$pageidp = "community/accounts/".$id."/api/1.2/?source_token=WG-WoT_Assistant-test";		
			$pageidp = $wot_host.'/'.$pageidp;	
			$date = date("Y-m-d",strtotime($hosttime));
			$time = date("H:i:s",strtotime($hosttime));
			//$date = date("Y-m-d");
			//$time = date("H:i:s");
			$data = get_page($pageidp);
			$data = json_decode($data, true);
	
			if ($data['status'] == 'ok') {   // основной блок обработки инфы
				if ( $data['data']['clan']['clan'] == null) {  				// игрок уже не в клане			
					$sql12 = "delete from `clan` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$account_name=$data['data']['name'];
					$message="Покинул клан ".$account_name;
					$sql = "INSERT INTO event_clan (idp, idc, message, reason, date, time)";
					$sql.= " VALUES ('$id', '$idc', '$message', NULL, '$date', '$time')";
					$q = mysql_query($sql, $connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql="UPDATE `player` SET `in_clan`='0' WHERE `idp`='$id'";
					mysql_query($sql, $connect);
					if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				}
				else {
					if ( $data['data']['clan']['clan']['id'] != $idc) { // игрок уже вступил в другой клан
						$sql12 = "delete from `clan` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$account_name=$data['data']['name'];
						$abbreviation=$data['data']['clan']['clan']['abbreviation'];
						$idcl=$data['data']['clan']['clan']['id'];
						$namecl=$data['data']['clan']['clan']['name'];
						$link='<a href="http://worldoftanks.ru/community/clans/'.$idcl.'/" target="_blank">['.$abbreviation.']:'.$namecl.'</a>';
						//echo $link;
						//$message="Покинул клан ".$account_name." и перешел в ".$abbreviation;
						$message="Покинул клан ".$account_name." и перешел в ".$link;
						$sql = "INSERT INTO event_clan (idp, idc, message, reason, date, time)";
						$sql.= " VALUES ('$id', '$idc', '$message', NULL, '$date', '$time')";
						$q = mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql="UPDATE `player` SET `in_clan`='0' WHERE `idp`='$id'";
						mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						}
					else {  												// игрок в клане
						// блок общей статы
						$sql="UPDATE `player` SET `in_clan`='1' WHERE `idp`='$id'";
						mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$newtankist=0;
						$sql = "select max(battles_count) as mbattles from player where idp='$id'";
						$q = mysql_query($sql,$connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$rGPL = mysql_fetch_array($q);
						if 	($rGPL['mbattles'] == NULL ) $newtankist=1;
						
						$sql = "select max(battles_count) as mbattles from player where idp='$id'";
						$q = mysql_query($sql,$connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$rGPL = mysql_fetch_array($q);
						if 	(($rGPL['mbattles']<>$data['data']['summary']['battles_count']) or $rGPL['mbattles'] == NULL ){ 
							$pname=$data['data']['name'];
							$created_at=date("Y-m-d",$data['data']['created_at']);
							$spotted=$data['data']['battles']['spotted'];
							$hits_percents=trim(str_replace("%"," ",$data['data']['battles']['hits_percents']));
							$capture_points=$data['data']['battles']['capture_points'];
							$damage_dealt=$data['data']['battles']['damage_dealt'];
							$frags=$data['data']['battles']['frags'];
							$dropped_capture_points=$data['data']['battles']['dropped_capture_points'];
							$wins=$data['data']['summary']['wins'];
							$losses=$data['data']['summary']['losses'];
							$battles_count=$data['data']['summary']['battles_count'];
							$survived_battles=$data['data']['summary']['survived_battles'];
							$xp=$data['data']['experience']['xp'];
							$battle_avg_xp=$data['data']['experience']['battle_avg_xp'];
							$max_xp=$data['data']['experience']['max_xp'];
							
							$sql = "INSERT INTO player (idp, idc, name, created_at, spotted,hits_percents,capture_points,damage_dealt,frags,dropped_capture_points,wins,losses,battles_count,survived_battles,xp,battle_avg_xp,max_xp,in_clan, date, time)";
							$sql.= " VALUES ('$id', '$idc','$pname','$created_at', '$spotted','$hits_percents','$capture_points','$damage_dealt','$frags','$dropped_capture_points','$wins','$losses','$battles_count','$survived_battles','$xp','$battle_avg_xp','$max_xp','1', '$date', '$time')";
							$q = mysql_query($sql, $connect);
							if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
							
							// опись медалей и достижений
								$sql = "select count(*) as cnt from cat_achiev";
								$q2 = mysql_query($sql,$connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$row = mysql_fetch_array($q2);
								$cnt = $row['cnt']; 
								
								$sql = "select * from cat_achiev";
								$result = mysql_query($sql,$connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								for($i2=0;$i2<$cnt;$i2++) { 
									$row = mysql_fetch_array($result,MYSQL_ASSOC);
									$mdl=$row['medal'];
									$mdl_ru=$row['medal_ru'];
									$mdl_id=$row['id_ac'];
									$mdlamount=$data['data']['achievements'][$mdl];//['$mdl'];
									$type=$row['type'];
									$SQL33="SELECT amount from player_ach where idp='$id' and ida='$mdl_id' and id_pa in (select max(id_pa) from player_ach where idp='$id' and ida='$mdl_id')";
									$qt33 = mysql_query($SQL33, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$qqtt33 = mysql_fetch_array($qt33);
									$a_co=$qqtt33['amount'];
									if ($a_co<>$mdlamount){
										$sql11 = "INSERT INTO player_ach (idp, ida, amount, date, time)";
										$sql11.= " VALUES ('$id', '$mdl_id', '$mdlamount', '$date', '$time')";
										$q11 = mysql_query($sql11, $connect);
										if(($cntN>0) and ($newtankist!=1)){
											if(($type!=1)and($type>0)) $message='+ '.$mdl_ru.' у '.$pname;
												else  $message='+ '.$mdl_ru.' '.$mdlamount.' ст. у '.$pname;
											if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
											$sql5 = "INSERT INTO event_clan (idp, idc, message, reason, date, time)";
											$sql5.= " VALUES ('$id', '$idc', '$message', NULL, '$date', '$time')";
											$q5 = mysql_query($sql5, $connect);
											if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
										}
										/// внесение можно в лог клана о получении медали
									}
									///////SELECT count(*) FROM `player_ach` c where ida='25' and amount>0
									///////SELECT sum(amount) FROM `player_ach` c where ida='25'
								}
							// работа со списком техники
							for($i=0;$i<count($data['data']['vehicles']);$i++){
								// проверка на новый танк в клане
								$tname=$data['data']['vehicles'][$i]['name'];
								$nation=$data['data']['vehicles'][$i]['nation']; 
								$sqlt = "select id_t from cat_tanks where name='$tname' and nation='$nation'";
								$qt = mysql_query($sqlt, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$qqtt = mysql_fetch_array($qt);
								$localized_name=$data['data']['vehicles'][$i]['localized_name']; 
								$image_url=$data['data']['vehicles'][$i]['image_url']; 
								$level=$data['data']['vehicles'][$i]['level']; 
								$class=$data['data']['vehicles'][$i]['class'];
								$battle_count=$data['data']['vehicles'][$i]['battle_count'];
								$win_count=$data['data']['vehicles'][$i]['win_count'];
								$newtankexist=0;
								if ($class=='heavyTank') $classRu='ТТ'; else
									if ($class=='mediumTank') $classRu='СТ'; else
									if ($class=='lightTank') $classRu='ЛТ'; else
									if ($class=='SPG') $classRu='САУ'; else
									if ($class=='AT-SPG') $classRu='ПТ-САУ'; else
										$classRu=$class;
								if($qqtt['id_t']==NULL){  // это новый танк в клане					
									$sqlt = "INSERT INTO cat_tanks (localized_name, image_url, name, level, nation, class)";
									$sqlt.= " VALUES ('$localized_name', '$image_url', '$tname', '$level', '$nation', '$class')";
									$qt = mysql_query($sqlt, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									
									$sqlt = "select id_t from cat_tanks where name='$tname'";
									$qt = mysql_query($sqlt, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$qqtt = mysql_fetch_array($qt);
									
									$idt=$qqtt['id_t'];
									if ($cntT>0){
										$message='!Новинка!'.$localized_name.' ('.$classRu.' '.$level.' ур. '.$nation.'). у '.$pname;
										$sqlt = "INSERT INTO event_tank (idp, idt, message, date, time)";
										$sqlt.= " VALUES ('$id', '$idt', '$message', '$date', '$time')";
										$qt = mysql_query($sqlt, $connect);
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
									$newtankexist=1;
								}
								$sqlt = "select id_t from cat_tanks where name='$tname'";
								$qt = mysql_query($sqlt, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$qqtt = mysql_fetch_array($qt);
								$idt=$qqtt['id_t'];
								//проверка на изменение ангара у игрока + исключение повторной записи в лог танков
								if ($newtankexist!=1){
									$sqlt2 = "select count(*) as cnt2 from player_btl where idt='$idt' and idp='$id'";
									$qt2 = mysql_query($sqlt2, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$qqtt2 = mysql_fetch_array($qt2);
									if(($qqtt2['cnt2']==NULL) and ($newtankist!=1) and ($cntT>0)){
										$message='++ '.$localized_name.' ('.$classRu.' '.$level.' ур. '.$nation.') у '.$pname;
										$sqlt = "INSERT INTO event_tank (idp, idt, message, date, time)";
										$sqlt.= " VALUES ('$id', '$idt', '$message', '$date', '$time')";
										$qt = mysql_query($sqlt, $connect);
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
								}
								// запись о текущих боях, есс число боев на танке увеличилось
								$SQL3="SELECT max(battle_count) as ba_co from player_btl where idp=$id and idt=$idt";
								$qt3 = mysql_query($SQL3, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$qqtt3 = mysql_fetch_array($qt3);
								$wi_co=$qqtt3['ba_co'];
								if ($wi_co<>$battle_count){
									$sqlt = "INSERT INTO player_btl (idp, idt, date, time, battle_count, win_count)";
									$sqlt.= " VALUES ('$id', '$idt', '$date', '$time', '$battle_count', '$win_count')";
									$qt = mysql_query($sqlt, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								}
							}
						}
					}
				}
			}
	}
}
function get_page($url) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HTTPGET, true);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
}	
mysql_close($connect);
echo "Done global update";
?>
