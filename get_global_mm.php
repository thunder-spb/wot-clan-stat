<?php
// выборка данных игрока. анализ, внесение изменений, запись в лог-таблицу
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8'); 
//$clan_array[] = array("clan_id" => "12638", "clan_tag" => "[SMPLC]",  "clan_name" => "Sample clan");

$count1=0; //счётчик от дурака
$sql = "select * from tech";
$q = mysql_query($sql, $connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$qqt = mysql_fetch_array($q);
$start=$qqt['current'];
$sql = "select count(*) as cntpl from clan";
$q2 = mysql_query($sql,$connect);
if (mysql_errno() <> 0) echo "MySQL Error 1 ".mysql_errno().": ".mysql_error()."\n";
$row = mysql_fetch_array($q2);
$cntplayer = $row['cntpl'];
$clan_list = mysql_query("select idp from clan order by idp LIMIT $start,$max_player_request",$connect); // получение списка игроков клана из бд
$offs=$start+$max_player_request;
if (($offs)>=$cntplayer){
	$offs=0;
}
$sql="UPDATE `tech` SET `current`='$offs'";
mysql_query($sql, $connect);
if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
$ida = array();
$force=0;
if (isset($_REQUEST['idp'])) {
	$ida[]=(int)($_REQUEST['idp']);
	$force=1;
}else{
	while ($members = mysql_fetch_array($clan_list)) {	$ida[]=$members['idp'];	}
}
$sql = "select count(*) as cntt from cat_tanks";
$q2 = mysql_query($sql,$connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$row = mysql_fetch_array($q2);
$cntT = $row['cntt'];
$t = time()-700000;
$clanlist = mysql_query("select idc from clan_info where actdate>'$t'",$connect);
$clancnt=array();
foreach ($clan_array as $clan_i) {
	//$idc1=$clan_i["clan_id"];
	$clancnt[]=$clan_i["clan_id"];
}
while ($clanrow=mysql_fetch_array($clanlist,MYSQL_ASSOC)) {
	$clancnt[]=$clanrow["idc"];
}
$clancnt=array_unique($clancnt);

foreach ($ida as $id) {
		$sql = "select * from clan where idp='$id'";
		$q = mysql_query($sql, $connect);
		if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
		$qqt = mysql_fetch_array($q);
		$idc=$qqt['idc'];
		$freq=$qqt['freq'];
		$target=$qqt['target'];
		if ($force==1) $target=$freq;
		$force=0;
		if ($freq >= $target) {
			if ($count1>=10) break;//не стоит злоупотреблять доверием
			$count1=$count1+1;
			$freq=0;
			$inclan=0;//проверка на изменения списка альянса
			$clantag1="";
			reset($clancnt);
			foreach ($clancnt as $idct) {
				// $idct = $clan_i["clan_id"];
				if ( $idct == $idc){
					$inclan=1;//клан в альянсе-всё в порядке
					$clantgs = mysql_query("select tag, allians from clan_info where idc='$idc'",$connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$clnt= mysql_fetch_array($clantgs);
					if ($clnt['tag']<>NULL){
						$clantag1=$clnt['tag'];
						$allians=$clnt['allians'];
					}else{
						foreach ($clan_array as $clan_i) {
							$idct = $clan_i["clan_id"];
							if ( $idct == $idc){
								$inclan=1;//клан в альянсе-всё в порядке
								$clantag1 = $clan_i["clan_tag"];
								$allians=1;
								break;
							}	
						}
					}
					break;
				}
				
			}
			echo "<br>---------------------------------<br>\n\nchecking $id from $clantag1 \n";
			$pageidp = "community/accounts/".$id."/api/1.9/?source_token=WG-WoT_Assistant-test";		
			$pageidp = "api.".$wot_host.'/'.$pageidp;	
			$date = date("Y-m-d",strtotime($hosttime));
			$date1=$date;
			$date30=date("Y-m-d",strtotime(' -30 day '.$hosttime));
			$time = date("H:i:s",strtotime($hosttime));
			$data = get_page($pageidp);
			$data = json_decode($data, true);
			if ($data['status'] == 'ok') {   // основной блок обработки инфы
								//echo "тест \n";
				$account_name=$data['data']['name'];
				if (( $data['data']['clan']['clan'] == null) or ($inclan==0) ) { // игрок уже не в клане или клан не в альянсе			
										 echo "<br><b>\nNOT in clan $id \n";
										 echo "<br>\ndeleting \n</b>";
					$sql12 = "delete from `clan` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `event_clan` where idp='$id' and type<>2 and type<>1"; 
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
					if ($inclan != 0){ 
					$account_name='<a href="http://worldoftanks.ru/community/accounts/'.$id.'/" target="_blank">'.$account_name.'</a>';
					$message="Покинул клан ".$clantag1." боец ".$account_name;
					$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
					$sql.= " VALUES (1,'$id', '$idc', '$message', NULL, '$date', '$time')";
					$q = mysql_query($sql, $connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";}
					
				}
				else {
					if ( $data['data']['clan']['clan']['id'] != $idc) { // игрок уже вступил в другой клан
						$sql12 = "delete from `clan` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql12 = "delete from `event_clan` where idp='$id' and type<>2 and type<>1"; 
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
						$abbreviation=$data['data']['clan']['clan']['abbreviation'];
						$idcl=$data['data']['clan']['clan']['id'];
						$namecl=$data['data']['clan']['clan']['name'];
						$account_name='<a href="http://worldoftanks.ru/community/accounts/'.$id.'/" target="_blank">'.$account_name.'</a>';
						$link='<a href="http://worldoftanks.ru/community/clans/'.$idcl.'/" target="_blank">['.$abbreviation.']:'.$namecl.'</a>';
						$message="Покинул клан ".$clantag1." боец ".$account_name." и перешел в ".$link;
						$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
						$sql.= " VALUES (1,'$id', '$idc','$message', NULL, '$date', '$time')";
						$q = mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						}
					else {  												// игрок в клане
						$newtankist=0;
						$sql = "select max(battles_count) as mbattles, max(date) as mdate, name from player where idp='$id' group by idp" ;
						$q = mysql_query($sql,$connect);
						if (mysql_errno() <> 0) echo "MySQL Error 1 ".mysql_errno().": ".mysql_error()."\n";
						$rGPL = mysql_fetch_array($q);
						if 	($rGPL['mbattles'] == NULL ){ 
								$newtankist=1;
								$a11=$timetolife+1;
								$date1=date("Y-m-d",strtotime(' -'.$a11.' day '.$hosttime));	//Для корректного отображения  статистики записи новых бойцов делаются задним числом
						}
						$dolgnDB=$qqt['role_localised'];
						$role_lo=$data['data']['clan']['member']['role'];
						$role1=$clanrange[$dolgnDB];
						$role2=$clanrange[$role_lo];
						if ($dolgnDB<>$role_lo) {
							if ($newtankist==0) {
								$message="Изменение должности ".$account_name." c ".$role1." на ".$role2;
								$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
								$sql.= " VALUES (4,'$id', '$idc', '$message', NULL, '$date', '$time')";
								$q = mysql_query($sql, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
							};
							$sql="UPDATE clan SET `role_localised`='$role_lo' WHERE `idp`='$id'";
							mysql_query($sql, $connect);
							if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						}
					 	$pname=$data['data']['name'];
						$pnameDB=$rGPL['name'];
						//Смена ника
						if (($newtankist==0) and ($pname<>$pnameDB)){
							$message="Боец ".$pnameDB." cменил ник на ".$pname;
							$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
							$sql.= " VALUES (10,'$id', '$idc', '$message', NULL, '$date', '$time')";
							$q = mysql_query($sql, $connect);
							if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";					
						}
						echo "<br>\nName:  <b>  $pname</b>";
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
								//$rating = round($frags/$battles_count*(350-20*$level_avg)+$damage_dealt/$battles_count*(0.2+1.5/$level_avg)
								//		+$spotted/$battles_count*200+$dropped_capture_points/$battles_count*150
								//		+$capture_points/$battles_count*150);
								$rating = round($frags/$battles_count*250
										+$damage_dealt/$battles_count*(10/($level_avg+2))*(0.23+2*$level_avg/100)
										+$spotted/$battles_count*150
										+$dropped_capture_points/$battles_count*150
										+(log($capture_points/$battles_count+1,1.732))*150);
								echo "<br>\nRating newEFF:  ".$rating;	
								$wn6=round((1240-1040/pow(min($level_avg,6), 0.164))*$frags/$battles_count
									   +$damage_dealt/$battles_count*530/(184*exp(0.24*$level_avg)+130)
									   +$spotted/$battles_count*125
									   +min($dropped_capture_points/$battles_count,2.2)*100
									   +((185/(0.17+exp(($wins*100/$battles_count-35)*-0.134)))-500)*0.45
									   +(6-min($level_avg,6))*-60);
								echo "<br>\nRating WN6:  ".$wn6;	  
								$wn7=round((1240-1040/pow(min($level_avg,6), 0.164))*$frags/$battles_count
									   +$damage_dealt/$battles_count*530/(184*exp(0.24*$level_avg)+130)
									   +$spotted/$battles_count*125
									   +min($dropped_capture_points/$battles_count,2.2)*100
									   +((185/(0.17+exp(($wins*100/$battles_count-35)*-0.134)))-500)*0.45
									   -((5-min($level_avg,5))*125)/(1+exp(($level_avg-pow($battles_count/220,3/$level_avg)*1.5))));
									   //-[(5 - MIN(TIER,5))*125] / [1 + e^( ( TIER - (GAMESPLAYED/220)^(3/TIER) )*1.5 )] 
								echo "<br>\nRating WN7: ".$wn7;	
							}
							if ($battles_count<100) {
								$wn6=0;
								$rating=0;
								$dropped_capture_points=0;
								$capture_points=0;
								$frags=0;
								$spotted=0;
							}
							echo "<br>\ntargetcf:  $target";
							$rgpldate=$rGPL['mdate'];
							$sql="UPDATE player SET `time`='$time', `spotted`='$spotted', `hits_percents`='$hits_percents', `capture_points`='$capture_points', `damage_dealt`='$damage_dealt', `frags`='$frags', `dropped_capture_points`='$dropped_capture_points',wins='$wins',losses='$losses',battles_count='$battles_count',survived_battles='$survived_battles',xp='$xp',battle_avg_xp='$battle_avg_xp',max_xp='$max_xp', rating='$rating', wn6='$wn6' WHERE `idp`='$id' and`date`='$rgpldate' ";
							if 	(($rGPL['mbattles']<>$data['data']['summary']['battles_count']) or $rGPL['mbattles'] == NULL or $newtankist==1) {
								echo "<br>\n\nNew data!!!\n--------------------";
								if (($rGPL['mbattles']<>$data['data']['summary']['battles_count']) and ($rGPL['mbattles'] != NULL) and ($newtankist!=1)){
									if ($target>1) $target=(int)($target/2);
								}
								echo "<br>\n newtargetcf: $target";
								 if ($rGPL['mdate']<>$date) {
									 $sql = "INSERT INTO player (idp,idc,name,created_at,spotted, hits_percents,capture_points,damage_dealt,frags,dropped_capture_points,wins,losses,battles_count,survived_battles,xp,battle_avg_xp,max_xp,in_clan, date, time, rating,wn6)";
									 $sql.= " VALUES ('$id','$idc','$pname','$created_at', '$spotted','$hits_percents','$capture_points','$damage_dealt','$frags','$dropped_capture_points','$wins','$losses','$battles_count','$survived_battles','$xp','$battle_avg_xp','$max_xp','1', '$date1', '$time', '$rating','$wn6')";
								 }
								//else{
									// $sql="UPDATE player SET `time`='$time', `spotted`='$spotted', `hits_percents`='$hits_percents', `capture_points`='$capture_points', `damage_dealt`='$damage_dealt', `frags`='$frags', `dropped_capture_points`='$dropped_capture_points',wins='$wins',losses='$losses',battles_count='$battles_count',survived_battles='$survived_battles',xp='$xp',battle_avg_xp='$battle_avg_xp',max_xp='$max_xp', rating='$rating', wn6='$wn6' WHERE `idp`='$id' and`date`='$date' ";
								// }
								// $q = mysql_query($sql, $connect);
								// if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
							}else{
								if ($target<$req_freq){
									if ($allians==0){
										$target=$target+1;
									}	
									$target=$target+1;
								}
							}
							// $rgpldate=$rGPL['mdate'];
							// if ($rGPL['mdate']<$date) {
									// $sql = "INSERT INTO player (idp,idc,name,created_at,spotted, hits_percents,capture_points,damage_dealt,frags,dropped_capture_points,wins,losses,battles_count,survived_battles,xp,battle_avg_xp,max_xp,in_clan, date, time, rating,wn6)";
									// $sql.= " VALUES ('$id','$idc','$pname','$created_at', '$spotted','$hits_percents','$capture_points','$damage_dealt','$frags','$dropped_capture_points','$wins','$losses','$battles_count','$survived_battles','$xp','$battle_avg_xp','$max_xp','1', '$date1', '$time', '$rating','$wn6')";
								// }else{
									// $sql="UPDATE player SET `time`='$time', `spotted`='$spotted', `hits_percents`='$hits_percents', `capture_points`='$capture_points', `damage_dealt`='$damage_dealt', `frags`='$frags', `dropped_capture_points`='$dropped_capture_points',wins='$wins',losses='$losses',battles_count='$battles_count',survived_battles='$survived_battles',xp='$xp',battle_avg_xp='$battle_avg_xp',max_xp='$max_xp', rating='$rating', wn6='$wn6' WHERE `idp`='$id' and`date`='$rgpldate' ";
								// }
								$q = mysql_query($sql, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
							
							// опись медалей и достижений
							if ($allians==1){
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
									if (($type==4) or ($type==6)){
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
											echo "<br>\n ".$mdl." ".$mdlamount;
											if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
											$sql5 = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
											$sql5.= " VALUES ('$type_ach','$id', '$idc', '$message', NULL, '$date', '$time')";
											$q5 = mysql_query($sql5, $connect);
											if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
										}
									}
									
								}
							}
							// работа со списком техники
							$date2=date("Y-m-d",strtotime(' -'.$timetolife.' day '.$hosttime));
							$battles30t=0;
							$level_avg = 0;
							$wins30t=0;
							for($i=0;$i<count($data['data']['vehicles']);$i++){
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
								$class=$data['data']['vehicles'][$i]['class'];
								$battle_count=$data['data']['vehicles'][$i]['battle_count'];
								$win_count=$data['data']['vehicles'][$i]['win_count'];
								$fragst=$data['data']['vehicles'][$i]['frags'];
								$spottedt=$data['data']['vehicles'][$i]['spotted'];
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
									
									$sqlt = "select id_t from cat_tanks where name='$tname' and nation='$nation'";
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
										$sqlt = "INSERT INTO event_tank (idp,type, idc, idt,  date, time)";
										$sqlt.= " VALUES ('$id','$type', '$idc','$idt',  '$date', '$time')";
										$qt = mysql_query($sqlt, $connect);
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
									$newtankexist=1;
								}
								$sqlt = "UPDATE cat_tanks SET  `level`='$level', `image_url`='$image_url',`class`='$class' WHERE name='$tname' and nation='$nation'";
								$qt = mysql_query($sqlt, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$sqlt = "select id_t from cat_tanks where name='$tname' and nation='$nation'";
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
										$type=0;
										if ($level==10  or ($level==8 and $class=='SPG')){$type=1;}
										if ($level==9  or ($level==7 and $class=='SPG')){$type=2;}
										$sqlt = "INSERT INTO event_tank (idp,type,idc, idt,  date, time)";
										$sqlt.= " VALUES ('$id','$type','$idc', '$idt',  '$date', '$time')";
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
										$sqlt = "UPDATE player_btl SET `time`='$time', `battle_count`='$battle_count', `win_count`='$win_count',`frags`='$fragst', `spotted`='$spottedt', `survivedBattles`='$survivedBattles', `damageDealt`='$damageDealt' WHERE `idp`='$id' and `idt`='$idt' and`date`='$date' ";
									}
									else{
										$sqlt = "INSERT INTO player_btl (idp, idt, date, time, battle_count, win_count, frags, spotted, survivedBattles, damageDealt)";
										$sqlt.= " VALUES ('$id', '$idt', '$date1', '$time', '$battle_count', '$win_count', '$fragst', '$spottedt', '$survivedBattles', '$damageDealt')";
									}	
								}
								 else
								 { 
								 // //привет варгеймингу, из-за сбоев в отдаче статистики приходится добавлять этот костыль
								 $sqlt = "UPDATE player_btl SET `time`='$time', `battle_count`='$battle_count', `win_count`='$win_count',`frags`='$frags', `spotted`='$spotted', `survivedBattles`='$survivedBattles', `damageDealt`='$damageDealt' WHERE `idp`='$id' and `idt`='$idt' and`date`='$dateb' ";
								 }
								$qt = mysql_query($sqlt, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								//удаляем старые выборки
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
								$sqlstart="SELECT min(battle_count) as bcbefore, min(win_count)as winbefore FROM `player_btl` where idp=$id and idt=$idt and date<'$date2'";
								$bc30tsql = mysql_query($sqlstart, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$bc = mysql_fetch_array($bc30tsql);
								$bcdelta=$battle_count-$bc['bcbefore'];
								$windelta=$win_count-$bc['winbefore'];
								if ($bcdelta<>0){
									$wins30t+=$windelta;
									$battles30t+=$bcdelta;
									echo "<br>\n $tname -- $bcdelta wins $windelta";
									$level_avg += $bcdelta*$level;
								}
															
							}
							if ($battles30t<>0){
								$level_avg /= $battles30t;	
							}
							echo "<br>\nbattles/month: $battles30t \navg.level/month: $level_avg ";
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
						 $sql="UPDATE `player` SET `in_clan`='1', `name`='$pname' WHERE `idp`='$id'";
						 mysql_query($sql, $connect);
						 if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						 //данные для  30д.рейтинга
						$sql = "select  * FROM `player` where idp='$id' order by id_p limit 1";
						$q = mysql_query($sql,$connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$databefore = mysql_fetch_array($q);
						$battlesdelta=$battles_count-$databefore['battles_count'];
						$rating30=0;
						$wn630=0;
						$win30=0;
						if ($battlesdelta>100){
							$fragsdelta=$frags-$databefore['frags'];
							$damagedelta=$damage_dealt-$databefore['damage_dealt'];
							//echo "<br> боёв за месяц=$battlesdelta дамаг $damagedelta";
							$spotteddelta=$spotted-$databefore['spotted'];
							$dropdelta=$dropped_capture_points-$databefore['dropped_capture_points'];
							$captdelta=$capture_points-$databefore['capture_points'];
							echo "<br> боёв за месяц=$battlesdelta дамаг $damagedelta обнаружено $spotteddelta зб $dropdelta зах $captdelta";
							$rating30 = round($fragsdelta/$battlesdelta*250
									+$damagedelta/$battlesdelta*(10/($level_avg+2))*(0.23+2*$level_avg/100)
									+$spotteddelta/$battlesdelta*150
									+$dropdelta/$battlesdelta*150
									+(log($captdelta/$battlesdelta+1,1.732))*150);
							echo "<br> Рейтинг30 ".$rating30; 	
							
							$win30=round($wins30t*100/$battlesdelta,2);
							
							$wn630=round((1240-1040/pow(min($level_avg,6), 0.164))*$fragsdelta/$battlesdelta
								   +$damagedelta/$battlesdelta*530/(184*exp(0.24*$level_avg)+130)
								   +$spotteddelta/$battlesdelta*125
								   +min($dropdelta/$battlesdelta,2.2)*100
								   +((185/(0.17+exp(($wins30t*100/$battlesdelta-35)*-0.134)))-500)*0.45
								   +(6-min($level_avg,6))*-60);
							echo "<br> Рейтинг wn630 ".$wn630;
							$wn730=round((1240-1040/pow(min($level_avg,6), 0.164))*$fragsdelta/$battlesdelta
								   +$damagedelta/$battlesdelta*530/(184*exp(0.24*$level_avg)+130)
								   +$spotteddelta/$battlesdelta*125
								   +min($dropdelta/$battlesdelta,2.2)*100
								   +((185/(0.17+exp(($wins30t*100/$battlesdelta-35)*-0.134)))-500)*0.45
								   -((5-min($level_avg,5))*125)/(1+exp(($level_avg-pow($battlesdelta/220,3/$level_avg)*1.5))));
								   //+(6-min($level_avg,6))*-60);
							echo "<br> Рейтинг wn730 ".$wn730;
							echo "<br> % побед ".$win30;
							// $sql="UPDATE `player` SET `in_clan`='1', `name`='$pname', `rating30`='$rating30',`wn630`='$wn630', `win30`= '$win30' WHERE `idp`='$id'";
						 // mysql_query($sql, $connect);
						 // if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						}
						$sql="UPDATE `player` SET `in_clan`='1', `name`='$pname', `rating30`='$rating30',`wn630`='$wn630', `win30`= '$win30' WHERE `idp`='$id'";
						 mysql_query($sql, $connect);
						 if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					}
				}
			}
		}
		
	$freq=$freq+1;
	// if ($allans==0){
		// $freq=$freq+1;
	// }
	$sql="UPDATE `clan` SET `freq`='$freq',`target`='$target' WHERE `idp`='$id'";
	mysql_query($sql, $connect);
	if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
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
echo "<br>get_global_mm done!"
?>
