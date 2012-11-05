<?
// выборка данных игрока. анализ, внесение изменений, запись в лог-таблицу

include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8'); 

foreach ($clan_array as $clan_i) {
	$idc = $clan_i["clan_id"];
	$clan_list = mysql_query("select idp from clan where idc='$idc' order by idp",$connect); // получение списка игроков клана из бд
	$ida = array();
	while ($members = mysql_fetch_array($clan_list)) {	$ida[]=$members[0];	}
	//$sql = "select count(*) as cntN from player_ach";
	//$q2 = mysql_query($sql,$connect);
	//if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	//$row = mysql_fetch_array($q2);
	//$cntN = $row['cntN']; // кол-во записей в достижениях 
	
	$sql = "select count(*) as cntt from cat_tanks";
	$q2 = mysql_query($sql,$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$row = mysql_fetch_array($q2);
	$cntT = $row['cntt']; // кол-во записей в достижениях
	
	$n = getdate();
	$m = $n['minutes'];
	$hourstest=$n['hours'];
	//$m = $n-5;
	$clantag1=$clan_i["clan_tag"];
	$m = $m*2;
        echo ' '.count($ida)." бойцов в клане $clantag1 \n";
	if (($m+1 <= count ($ida)) and ($m>=0))
		{
		for ($mm=$m;$mm<=$m+1;$mm++)
			{	$id = $ida[$mm];
			//echo "checking $id from $idc \n";
			$pageidp = "community/accounts/".$id."/api/1.8/?source_token=WG-WoT_Assistant-test";		
			$pageidp = $wot_host.'/'.$pageidp;	
			$date = date("Y-m-d",strtotime($hosttime));
			$date1=$date;
			$time = date("H:i:s",strtotime($hosttime));
			//$date = date("Y-m-d");
			//$time = date("H:i:s");
			$data = get_page($pageidp);
			$data = json_decode($data, true);
			if ($data['status'] == 'ok') {   // основной блок обработки инфы
                                //echo "тест \n";
				$account_name=$data['data']['name'];
				if ( $data['data']['clan']['clan'] == null) {  				// игрок уже не в клане			
                     //                    echo "NOT in clan \n";
					$sql12 = "delete from `clan` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `event_clan` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `event_tank` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `player` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `player_ach` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `player_btl` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$account_name=$data['data']['name'];
					$message="Покинул клан ".$clantag1." боец ".$account_name;
					$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
					$sql.= " VALUES (1,'$id', '$idc', '$message', NULL, '$date', '$time')";
					$q = mysql_query($sql, $connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
					//$sql="UPDATE `player` SET `in_clan`='0' WHERE `idp`='$id'";
					//mysql_query($sql, $connect);
					//if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				}
				else {
					if ( $data['data']['clan']['clan']['id'] != $idc) { // игрок уже вступил в другой клан
						$sql12 = "delete from `clan` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql12 = "delete from `event_clan` where idp='$id'"; 
					    $qq2 = mysql_query($sql12,$connect);
					    if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					    $sql12 = "delete from `event_tank` where idp='$id'"; 
					    $qq2 = mysql_query($sql12,$connect);
					    if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql12 = "delete from `player` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql12 = "delete from `player_ach` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql12 = "delete from `player_btl` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						//$account_name=$data['data']['name'];
						$abbreviation=$data['data']['clan']['clan']['abbreviation'];
						$idcl=$data['data']['clan']['clan']['id'];
						$namecl=$data['data']['clan']['clan']['name'];
						$link='<a href="http://worldoftanks.ru/community/clans/'.$idcl.'/" target="_blank">['.$abbreviation.']:'.$namecl.'</a>';
						//echo $link;
						//$message="Покинул клан ".$account_name." и перешел в ".$abbreviation;
						$message="Покинул клан ".$clantag1." боец ".$account_name." и перешел в ".$link;
						$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
						$sql.= " VALUES (1,'$id', '$idc','$message', NULL, '$date', '$time')";
						$q = mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						//$sql="UPDATE `player` SET `in_clan`='0' WHERE `idp`='$id'";
						//mysql_query($sql, $connect);
						//if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						}
					else {  												// игрок в клане
						// блок общей статы
						$sql="UPDATE `player` SET `in_clan`='1' WHERE `idp`='$id'";
						mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						
						$sql = "select * from clan where idp='$id'";
						$q = mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
				        $qqt = mysql_fetch_array($q);
				        $dolgnDB=$qqt['role_localised'];
				        $role_lo=$data['data']['clan']['member']['role'];
						if ($dolgnDB<>$role_lo) {
							$message="Изменение должности ".$account_name." c ".$dolgnDB." на ".$role_lo;
							$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
							$sql.= " VALUES (4,'$id', '$idc', '$message', NULL, '$date', '$time')";
							$q = mysql_query($sql, $connect);
							if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
							$sql="UPDATE clan SET `role_localised`='$role_lo' WHERE `idp`='$id'";
							mysql_query($sql, $connect);
							if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						}
						$newtankist=0;
						$sql = "select max(battles_count) as mbattles, max(date) as mdate from player where idp='$id'";
						$q = mysql_query($sql,$connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$rGPL = mysql_fetch_array($q);
						if 	($rGPL['mbattles'] == NULL ){ 
							$newtankist=1;
							$date1=date("Y-m-d",strtotime(' -31 day '.$hosttime));	//Для корректного отображения  статистики записи новых бойцов делаются задним числом
						}
						//$sql = "select max(battles_count) as mbattles from player where idp='$id'";
						//$q = mysql_query($sql,$connect);
						//if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						//$rGPL = mysql_fetch_array($q);
						if 	(($rGPL['mbattles']<>$data['data']['summary']['battles_count']) or $rGPL['mbattles'] == NULL or $hourstest==3) { 
                                                        
							$pname=$data['data']['name'];
                                                        echo "обработка бойца $pname\n";
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
							//rating
							$level_avg = 0;
							for($i=0;$i<count($data['data']['vehicles']);$i++){
								$b_count=$data['data']['vehicles'][$i]['battle_count'];				
								$lev=$data['data']['vehicles'][$i]['level'];
								$level_avg += $b_count*$lev;
							}
							$rating=0;
							if ($battles_count<>0){
								$level_avg /= $battles_count;
								$rating = round($frags/$battles_count*(350-20*$level_avg)+$damage_dealt/$battles_count*(0.2+1.5/$level_avg)
										+$spotted/$battles_count*200+$dropped_capture_points/$battles_count*150
										+$capture_points/$battles_count*150);
							}
							if 	(($rGPL['mbattles']<>$data['data']['summary']['battles_count']) or $rGPL['mbattles'] == NULL or $newtankist==1) {
								if ($rGPL['mdate']<>$date) {
									$sql = "INSERT INTO player (idp,idc,name,created_at,spotted, hits_percents,capture_points,damage_dealt,frags,dropped_capture_points,wins,losses,battles_count,survived_battles,xp,battle_avg_xp,max_xp,in_clan, date, time, rating)";
									$sql.= " VALUES ('$id','$idc','$pname','$created_at', '$spotted','$hits_percents','$capture_points','$damage_dealt','$frags','$dropped_capture_points','$wins','$losses','$battles_count','$survived_battles','$xp','$battle_avg_xp','$max_xp','1', '$date1', '$time', '$rating')";
								}else{
									$sql="UPDATE player SET `time`='$time', `spotted`='$spotted', `hits_percents`='$hits_percents', `capture_points`='$capture_points', `damage_dealt`='$damage_dealt', `frags`='$frags', `dropped_capture_points`='$dropped_capture_points',wins='$wins',losses='$losses',battles_count='$battles_count',survived_battles='$survived_battles',xp='$xp',battle_avg_xp='$battle_avg_xp',max_xp='$max_xp', rating='$rating' WHERE `idp`='$id' and`date`='$date' ";
								}
								$q = mysql_query($sql, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
							}
							
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
								// $mdlamount=$data['data']['achievements'][$mdl];//['$mdl'];
								$type=$row['type'];
								$type_ach=3;
								$mdlamount=0;
								if ($type==5) {
									if ($mdl=="tankExpertsUsa"){if ($data['data']['achievements']['tankExperts']['usa']=='true'){$mdlamount=1;};}
									if ($mdl=="tankExpertsFrance"){if ($data['data']['achievements']['tankExperts']['france']=='true'){$mdlamount=1;};}
									if ($mdl=="tankExpertsUssr"){if ($data['data']['achievements']['tankExperts']['ussr']=='true'){$mdlamount=1;};}
									if ($mdl=="tankExpertsGermany"){if ($data['data']['achievements']['tankExperts']['germany']=='true'){$mdlamount=1;};}
									if ($mdl=="tankExpertsChina"){if ($data['data']['achievements']['tankExperts']['china']=='true'){$mdlamount=1;};}
									if ($mdl=="tankExpertsUK"){if ($data['data']['achievements']['tankExperts']['uk']=='true'){$mdlamount=1;};}
                                    if ($mdl=="mechanicEngineersUSA"){if ($data['data']['achievements']['mechanicEngineers']['usa']=='true'){$mdlamount=1;};}
									if ($mdl=="mechanicEngineersFrance"){if ($data['data']['achievements']['mechanicEngineers']['france']=='true'){$mdlamount=1;};}
									if ($mdl=="mechanicEngineersUssr"){if ($data['data']['achievements']['mechanicEngineers']['ussr']=='true'){$mdlamount=1;};}
									if ($mdl=="mechanicEngineersGermany"){if ($data['data']['achievements']['mechanicEngineers']['germany']=='true'){$mdlamount=1;};}
									if ($mdl=="mechanicEngineersChina"){if ($data['data']['achievements']['mechanicEngineers']['china']=='true'){$mdlamount=1;};}
									if ($mdl=="mechanicEngineersUK"){if ($data['data']['achievements']['mechanicEngineers']['uk']=='true'){$mdlamount=1;};}
									if ($mdl=="mechanicEngineer"){if ($data['data']['achievements']['mechanicEngineer']=='true'){$mdlamount=1;};}
									if ($mdl=="tankExpert"){$mdlamount=$data['data']['achievements'][$mdl];}
								} else {
									$mdlamount=$data['data']['achievements'][$mdl];
								}
								if ($type==4){
								$type_ach=0;
								}
								$SQL33="SELECT amount from player_ach where idp='$id' and ida='$mdl_id'";
								$qt33 = mysql_query($SQL33, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$qqtt33 = mysql_fetch_array($qt33);
								$a_co=$qqtt33['amount'];
								if ($a_co==NULL){
									$sql11 = "INSERT INTO player_ach (idp, ida, amount)";
									$sql11.= " VALUES ('$id', '$mdl_id', '$mdlamount')";
								}
								else {
									$sql11 = "UPDATE player_ach SET `amount`='$mdlamount' where `idp`='$id' and `ida`='$mdl_id'";
								}
								$q11 = mysql_query($sql11, $connect);
								if ($a_co<>$mdlamount){
									if(($newtankist!=1) and ($mdlamount<>NULL)){
										$message='<'.$mdl_ru.'> '.$mdlamount.' у '.$pname;
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
										$sql5 = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
										$sql5.= " VALUES ('$type_ach','$id', '$idc', '$message', NULL, '$date', '$time')";
										$q5 = mysql_query($sql5, $connect);
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
								}
								///////SELECT count(*) FROM `player_ach` c where ida='25' and amount>0
								///////SELECT sum(amount) FROM `player_ach` c where ida='25'
							}
							// работа со списком техники
							$date2=date("Y-m-d",strtotime(' -30 day '.$hosttime));
							for($i=0;$i<count($data['data']['vehicles']);$i++){
								//$date2=date("Y-m-d",strtotime(' -30 day '.$hosttime));
								// проверка на новый танк в клане
								$tname=$data['data']['vehicles'][$i]['name'];
								$nation=$data['data']['vehicles'][$i]['nation']; 
								$level=$data['data']['vehicles'][$i]['level'];
								$sqlt = "select id_t from cat_tanks where name='$tname' and nation='$nation' and level='$level'";
								$qt = mysql_query($sqlt, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$qqtt = mysql_fetch_array($qt);
								$localized_name=$data['data']['vehicles'][$i]['localized_name']; 
								$image_url=$data['data']['vehicles'][$i]['image_url']; 
								//$level=$data['data']['vehicles'][$i]['level']; 
								$class=$data['data']['vehicles'][$i]['class'];
								$battle_count=$data['data']['vehicles'][$i]['battle_count'];
								$win_count=$data['data']['vehicles'][$i]['win_count'];
								$frags=$data['data']['vehicles'][$i]['frags'];
								$spotted=$data['data']['vehicles'][$i]['spotted'];
								$survivedBattles=$data['data']['vehicles'][$i]['survivedBattles'];
								$damageDealt=$data['data']['vehicles'][$i]['damageDealt'];
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
									
									$sqlt = "select id_t from cat_tanks where name='$tname' and nation='$nation' and level='$level'";
									$qt = mysql_query($sqlt, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$qqtt = mysql_fetch_array($qt);
									$type=0;
									if ($level==10  or ($level==8 and $class=='SPG')){
									$type=1;
									}
									if ($level==9  or ($level==7 and $class=='SPG')){$type=2;}
									$idt=$qqtt['id_t'];
									if ($cntT>0){
										$message='!Новинка!'.$localized_name.' ('.$classRu.' '.$level.' ур. '.$nation.'). у '.$pname;
										$sqlt = "INSERT INTO event_tank (idp,type, idc, idt, message, date, time)";
										$sqlt.= " VALUES ('$id','$type', '$idc','$idt', '$message', '$date', '$time')";
										$qt = mysql_query($sqlt, $connect);
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
									$newtankexist=1;
								}
								$sqlt = "select id_t from cat_tanks where name='$tname' and nation='$nation' and level='$level'";
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
									if((($qqtt2['cnt2']==NULL) or ($qqtt2['cnt2']==0)) and ($newtankist!=1) and ($cntT>0)){
										$message=$localized_name.' ('.$classRu.' '.$level.' ур. '.$nation.') у '.$pname;
                                                                                echo "добавлен танк $localized_name \n";
										$type=0;
										if ($level==10  or ($level==8 and $class=='SPG')){$type=1;}
										if ($level==9  or ($level==7 and $class=='SPG')){$type=2;}
										$sqlt = "INSERT INTO event_tank (idp,type,idc, idt, message, date, time)";
										$sqlt.= " VALUES ('$id','$type','$idc', '$idt', '$message', '$date', '$time')";
										$qt = mysql_query($sqlt, $connect);
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
								}
								// запись о текущих боях, есс число боев на танке увеличилось
								$SQL3="SELECT max(battle_count) as ba_co, max(date) as datebat, max(damageDealt) as dammax from player_btl where idp=$id and idt=$idt";
								$qt3 = mysql_query($SQL3, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$qqtt3 = mysql_fetch_array($qt3);
								$wi_co=$qqtt3['ba_co']; 
								$dateb=$qqtt3['datebat'];
								$damagem=$qqtt3['dammax'];
								if ($wi_co<>$battle_count){ 
									if ($dateb==$date){
										$sqlt = "UPDATE player_btl SET `time`='$time', `battle_count`='$battle_count', `win_count`='$win_count',`frags`='$frags', `spotted`='$spotted', `survivedBattles`='$survivedBattles', `damageDealt`='$damageDealt' WHERE `idp`='$id' and `idt`='$idt' and`date`='$date' ";
									}
									else{
										$sqlt = "INSERT INTO player_btl (idp, idt, date, time, battle_count, win_count, frags, spotted, survivedBattles, damageDealt)";
										$sqlt.= " VALUES ('$id', '$idt', '$date1', '$time', '$battle_count', '$win_count', '$frags', '$spotted', '$survivedBattles', '$damageDealt')";
									}	
								}
								else
								{ 
									 //привет варгеймингу, из-за сбоев в отдаче статистики приходится добавлять этот костыль
									$sqlt = "UPDATE player_btl SET `time`='$time', `battle_count`='$battle_count', `win_count`='$win_count',`frags`='$frags', `spotted`='$spotted', `survivedBattles`='$survivedBattles', `damageDealt`='$damageDealt' WHERE `idp`='$id' and `idt`='$idt' and`date`='$dateb' ";
								}
								$qt = mysql_query($sqlt, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								//удаляем старые выборки
								//SELECT `id_pb` FROM `player_btl` where `date`<'2012-08-12' and `idp`=1674264 and `idt`=19
								$sqldelstart="SELECT max(date) as maxidpb FROM `player_btl` where idp=$id and idt=$idt and date<'$date2'";
								$delstart1 = mysql_query($sqldelstart, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$delstart = mysql_fetch_array($delstart1);
								if ($delstart['maxidpb']<>NULL){
									$idpb=$delstart['maxidpb'];
									$sql12 = "delete from `player_btl` where idp='$id' and idt='$idt' and date<'$idpb'"; 
									$qq2 = mysql_query($sql12,$connect);
									if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
								}
							}	
							$sqldelstart="SELECT max(date) as maxidpb FROM `player` where idp=$id and date<'$date2'";
							$delstart1 = mysql_query($sqldelstart, $connect);
							if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
							$delstart = mysql_fetch_array($delstart1);
							if ($delstart['maxidpb']<>NULL){
								$idpb=$delstart['maxidpb'];
								$sql12 = "delete from `player` where idp='$id' and date<'$idpb'"; 
								$qq2 = mysql_query($sql12,$connect);
								if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
							}
							
						}
					}
				}
			}
		//	}
		}
	}
}

function get_page($url) {
		$ch = curl_init();
                //array('Accept-Language: ru-ru,ru'
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
echo "get_global_mm done!"
?>
