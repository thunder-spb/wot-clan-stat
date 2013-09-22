<?php
// выборка данных игрока. анализ, внесение изменений, запись в лог-таблицу
include('settings.kak');
$starttime=time();
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8'); 
//print_r ($_SERVER);
$myeol=PHP_EOL;
if (!(isset($appid))){
 $appid="demo";
}

if (array_key_exists("SERVER_NAME", $_SERVER)){
	$myeol="<br>";
}
//$clan_array[] = array("clan_id" => "12638", "clan_tag" => "[SMPLC]",  "clan_name" => "Sample clan");
$allpl=1;
$count1=0; //счётчик от дурака
//$cntmaxpl=10;
$sql = "select * from tech";
$q = mysql_query($sql, $connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$qqt = mysql_fetch_array($q);
$start=$qqt['current'];
$cntmaxpl=$qqt['cntmaxpl'];
$max_player_request=$qqt['maxplreq'];
$req_freq=$qqt['reqtarget'];
if ($cntmaxpl<1){
	$cntmaxpl=1;
}
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
$t = time()-604800;

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
$idlist="";
foreach ($ida as $id) {
 $idlist=$idlist.$id.",";
}
print_r ($idlist);
$pageidp = "2.0/account/info/?application_id=".$appid."&account_id=".$idlist;		
$pageidp = "api.".$wot_host.'/'.$pageidp;
$data2 = get_page($pageidp);
$data2 = json_decode($data2, true);
// $pageidp = "2.0/account/tanks/?application_id=".$appid."&account_id=".$idlist;		
// $pageidp = "api.".$wot_host.'/'.$pageidp;
// $data2pt = get_page($pageidp);
// $data2pt = json_decode($data2pt, true);
// print_r ($data2pt);
foreach ($ida as $id) {
		$sql = "select * from clan where idp='$id'";
		$q = mysql_query($sql, $connect);
		if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
		$qqt = mysql_fetch_array($q);
		$idc=$qqt['idc'];
		$freq=$qqt['freq'];
		$target=$qqt['target'];
		if ($force==1) $target=$freq;
		if ($freq >= $target) {
			if ($count1>=$cntmaxpl){
				$allpl=0;
				break;//не стоит злоупотреблять доверием
			}
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
			echo $myeol."---------------------------------".$myeol." checking $id from $clantag1".$myeol;
			$date = date("Y-m-d",strtotime($hosttime));
			$date1=$date;
			$date30=date("Y-m-d",strtotime(' -30 day '.$hosttime));
			$time = date("H:i:s",strtotime($hosttime));
			// $data = get_page($pageidp);
			// $data = json_decode($data, true);
			// $pageidp = "2.0/account/info/?application_id=".$appid."&account_id=".$id;		
			// $pageidp = "api.".$wot_host.'/'.$pageidp;
			// $data2 = get_page($pageidp);
			// $data2 = json_decode($data2, true);
			//print_r($data2);
			
			if ($data2['status'] == 'ok') {   // основной блок обработки инфы
								//echo "тест \n";
				$account_name=$data2['data'][$id]['nickname'];
				//print_r($account_name);
				if (( $data2['data'][$id]['clan'] == null) or ($inclan==0) ) { // игрок уже не в клане или клан не в альянсе			
										 echo "<b>NOT in clan $id ".$myeol;
										 echo " deleting </b>".$account_name.$myeol;
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
					$sql12 = "delete from `player_clan` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `player_company` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `player_ach` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					$sql12 = "delete from `player_btl` where idp='$id'"; 
					$qq2 = mysql_query($sql12,$connect);
					if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
					if ($inclan != 0){ 
					$account_name='<a href="http://worldoftanks.ru/community/accounts/'.$id.'/" target="_blank">'.$account_name.'</a>';
					$message="Покинул клан ".$clantag1." боец ".$account_name;
					$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
					$sql.= " VALUES (1,'$id', '$idc', '$message', NULL, '$date', '$time')";
					$q = mysql_query($sql, $connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";}
					
				}
				else {
					if ( $data2['data'][$id]['clan']['clan_id'] != $idc) { // игрок уже вступил в другой клан
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
						$sql12 = "delete from `player_clan` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql12 = "delete from `player_company` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql12 = "delete from `player_ach` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$sql12 = "delete from `player_btl` where idp='$id'"; 
						$qq2 = mysql_query($sql12,$connect);
						if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
						$account_name='<a href="http://worldoftanks.ru/community/accounts/'.$id.'/" target="_blank">'.$account_name.'</a>';
						$message="Покинул клан ".$clantag1." боец ".$account_name;
						$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
						$sql.= " VALUES (1,'$id', '$idc','$message', NULL, '$date', '$time')";
						$q = mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
						}
					else {  												// игрок в клане
						$newtankist=0;
						//$sql = "select max(battles_count) as mbattles, max(date) as mdate, name from player where idp='$id' group by idp" ;
						$sql = "select max(pl.battles_count) as mbattles, max(pl.date) as mdate, pl.name,max(plc.battles_count_clan) as maxbcclan,max(plr.battles_count_company) as maxbccompany  from player pl  left join  player_clan plc on plc.idp=pl.idp  left join player_company plr on plr.idp=pl.idp where pl.idp='$id' group by pl.idp";
						$q = mysql_query($sql,$connect);
						if (mysql_errno() <> 0) echo "MySQL Error 1 ".mysql_errno().": ".mysql_error()."\n";
						$rGPL = mysql_fetch_array($q);
						if 	($rGPL['mbattles'] == NULL ){ 
								$newtankist=1;
								$a11=$timetolife+1;
								$date1=date("Y-m-d",strtotime(' -'.$a11.' day '.$hosttime));	//Для корректного отображения  статистики записи новых бойцов делаются задним числом
						}
						$dolgnDB=$qqt['role_localised'];
						$role_lo=$data2['data'][$id]['clan']['role'];
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
					 	$pname=$data2['data'][$id]['nickname'];
						$pnameDB=$rGPL['name'];
						//Смена ника
						if (($newtankist==0) and ($pname<>$pnameDB)){
							$message="Боец ".$pnameDB." cменил ник на ".$pname;
							$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
							$sql.= " VALUES (10,'$id', '$idc', '$message', NULL, '$date', '$time')";
							$q = mysql_query($sql, $connect);
							if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";					
						}
						echo "___Name:   $pname".$myeol;
						//all
						$created_at=date("Y-m-d",$data2['data'][$id]['created_at']);
						$spotted=$data2['data'][$id]['statistics']['all']['spotted'];
						$hits_percents=$data2['data'][$id]['statistics']['all']['hits_percents'];
						$capture_points=$data2['data'][$id]['statistics']['all']['capture_points'];
						$damage_dealt=$data2['data'][$id]['statistics']['all']['damage_dealt'];
						$frags=$data2['data'][$id]['statistics']['all']['frags'];
						$dropped_capture_points=$data2['data'][$id]['statistics']['all']['dropped_capture_points'];
						$wins=$data2['data'][$id]['statistics']['all']['wins'];
						$losses=$data2['data'][$id]['statistics']['all']['losses'];
						$battles_count=$data2['data'][$id]['statistics']['all']['battles'];
						$survived_battles=$data2['data'][$id]['statistics']['all']['survived_battles'];
						$xp=$data2['data'][$id]['statistics']['all']['xp'];
						$battle_avg_xp=$data2['data'][$id]['statistics']['all']['battle_avg_xp'];
						//clan
						$spotted_clan=$data2['data'][$id]['statistics']['clan']['spotted'];
						$hits_percents_clan=$data2['data'][$id]['statistics']['clan']['hits_percents'];
						$capture_points_clan=$data2['data'][$id]['statistics']['clan']['capture_points'];
						$damage_dealt_clan=$data2['data'][$id]['statistics']['clan']['damage_dealt'];
						$frags_clan=$data2['data'][$id]['statistics']['clan']['frags'];
						$dropped_capture_points_clan=$data2['data'][$id]['statistics']['clan']['dropped_capture_points'];
						$wins_clan=$data2['data'][$id]['statistics']['clan']['wins'];
						$losses_clan=$data2['data'][$id]['statistics']['clan']['losses'];
						$battles_count_clan=$data2['data'][$id]['statistics']['clan']['battles'];
						$survived_battles_clan=$data2['data'][$id]['statistics']['clan']['survived_battles'];
						$xp_clan=$data2['data'][$id]['statistics']['clan']['xp'];
						$battle_avg_xp_clan=$data2['data'][$id]['statistics']['clan']['battle_avg_xp'];
						//company
						$spotted_company=$data2['data'][$id]['statistics']['company']['spotted'];
						$hits_percents_company=$data2['data'][$id]['statistics']['company']['hits_percents'];
						$capture_points_company=$data2['data'][$id]['statistics']['company']['capture_points'];
						$damage_dealt_company=$data2['data'][$id]['statistics']['company']['damage_dealt'];
						$frags_company=$data2['data'][$id]['statistics']['company']['frags'];
						$dropped_capture_points_company=$data2['data'][$id]['statistics']['company']['dropped_capture_points'];
						$wins_company=$data2['data'][$id]['statistics']['company']['wins'];
						$losses_company=$data2['data'][$id]['statistics']['company']['losses'];
						$battles_count_company=$data2['data'][$id]['statistics']['company']['battles'];
						$survived_battles_company=$data2['data'][$id]['statistics']['company']['survived_battles'];
						$xp_company=$data2['data'][$id]['statistics']['company']['xp'];
						$battle_avg_xp_company=$data2['data'][$id]['statistics']['company']['battle_avg_xp'];
						$max_xp=$data2['data'][$id]['statistics']['max_xp'];
						$wn6=0;
						$rating=0;
						echo " targetcf:  $target".$myeol;
						
						// опись медалей и достижений, обрабатывается только для основного состава альянса.
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
								$mdlamount=$data2['data'][$id]['achievements'][$mdl];
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
										echo $mdl." ".$mdlamount.$myeol;
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
						if 	(($rGPL['mbattles']<>$battles_count) or ($rGPL['mbattles'] == NULL) or ($newtankist==1)) {

							$pageidp = "2.0/account/tanks/?application_id=".$appid."&account_id=".$id;		
							$pageidp = "api.".$wot_host.'/'.$pageidp;
							$data2pt = get_page($pageidp);
							$data2pt = json_decode($data2pt, true);
							if ($data2pt['status'] == "ok"){
								$date2=date("Y-m-d",strtotime(' -'.$timetolife.' day '.$hosttime));
								$battles30t=0;
								$level_avg = 0;
								$level_avg_all=0;
								$wins30t=0;
								foreach ($data2pt['data'][$id] as $pltank){
									$wotidt=$pltank['tank_id'];
									// проверка на новый танк в клане
									$sqlt = "select * from cat_tanks where `wotidt`='$wotidt'";
									$qt = mysql_query($sqlt, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$qqtt = mysql_fetch_array($qt);
									$newtankexist=0;
									if($qqtt['id_t']==NULL){
										
										$pageidp = "2.0/encyclopedia/tankinfo/?application_id=".$appid."&tank_id=".$wotidt;		
										$pageidp = "api.".$wot_host.'/'.$pageidp;
										$data2tank = get_page($pageidp);
										$data2tank = json_decode($data2tank, true);
										if ($data2tank['status'] == "ok"){
											$tname=$data2tank['data'][$wotidt]['name'];
											echo "обновляем данные о танке ".$tname.$myeol;
											$level=$data2tank['data'][$wotidt]['level'];
											$nation=$data2tank['data'][$wotidt]['nation'];
											$class=$data2tank['data'][$wotidt]['type'];
											$localized_name=$data2tank['data'][$wotidt]['localized_name'];
											$image_url=$data2tank['data'][$wotidt]['image'];
											$sqltn = "select * from cat_tanks where `name`='$tname'";
											$qtn = mysql_query($sqltn, $connect);
											if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
											$qqttn = mysql_fetch_array($qtn);
											if ($qqttn['id_t']==NULL){
												$newtankexist=1;
												$sqltank= "INSERT INTO cat_tanks (wotidt,localized_name,image_url,name,level,nation,class)";
												$sqltank.= " VALUES ('$wotidt','$localized_name','$image_url','$tname','$level','$nation','$class')";
											}else{
												$sqltank="UPDATE cat_tanks SET `wotidt`='$wotidt', `localized_name`='$localized_name', `image_url`='$image_url', `level`='$level', `nation`='$nation', `class`='$class' where `name`='$tname'";
											}
											$q = mysql_query($sqltank, $connect);
											if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
										}else{
											echo " Не удалось получить данные о технике".$myeol;
											die;
										}
									}else{
										
										$tname=$qqtt['name'];
										$nation=$qqtt['nation']; 
										$level=$qqtt['level'];
										$localized_name=$qqtt['localized_name']; 
										$image_url=$qqtt['image_url']; 
										$class=$qqtt['class'];
										// $battle_count=$data['data']['vehicles'][$i]['battle_count'];
										// $win_count=$data['data']['vehicles'][$i]['win_count'];
									}
									$markm=$pltank['mark_of_mastery']; 
									$garage=$pltank['in_garage']; 
									$battle_count=$pltank['statistics']['all']['battles'];
									$win_count=$pltank['statistics']['all']['wins'];
									$level_avg_all += $battle_count*$level;
									$fragst=$pltank['statistics']['all']['frags'];
									$spottedt=$pltank['statistics']['all']['spotted'];
									$survivedBattles=$pltank['statistics']['all']['survived_battles'];
									$damageDealt=$pltank['statistics']['all']['damage_dealt'];
									
									// $sqlt = "select id_t from cat_tanks where `wotidt`='$wotidt'";
									// $qt = mysql_query($sqlt, $connect);
									// if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									// $qqtt = mysql_fetch_array($qt);
									// $idt=$qqtt['id_t'];
									//проверка на изменение ангара у игрока + исключение повторной записи в лог танков
									if ($newtankexist!=1){
										$sqlt2 = "select count(*) as cnt2 from player_btl where idt='$wotidt' and idp='$id'";
										$qt2 = mysql_query($sqlt2, $connect);
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
										$qqtt2 = mysql_fetch_array($qt2);
										if((($qqtt2['cnt2']==NULL) or ($qqtt2['cnt2']==0)) and ($newtankist!=1) and ($cntT>0)){
											$type=0;
											if ($level==10){$type=1;}
											if ($level==9){$type=2;}
											$sqlt = "INSERT INTO event_tank (idp,type,idc, idt,  date, time)";
											$sqlt.= " VALUES ('$id','$type','$idc', '$wotidt',  '$date', '$time')";
											$qt = mysql_query($sqlt, $connect);
											if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
										}
									}
									// запись о текущих боях, есс число боев на танке увеличилось
									$SQL3="SELECT max(battle_count) as ba_co, max(master) as maxmaster, max(date) as datebat, max(damageDealt) as dammax from player_btl where idp=$id and idt=$wotidt";
									$qt3 = mysql_query($SQL3, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$qqtt3 = mysql_fetch_array($qt3);
									$wi_co=$qqtt3['ba_co']; 
									$dateb=$qqtt3['datebat'];
									$damagem=$qqtt3['dammax'];
									$maxmaster=$qqtt3['maxmaster'];
									if (($markm>$maxmaster)and($allians==1)and($newtankist!=1)){
										echo "          master - ".$markm.$myeol;
										$type=10+$markm;
										$sqlt = "INSERT INTO event_tank (idp,type,idc, idt,  date, time)";
										$sqlt.= " VALUES ('$id','$type','$idc', '$wotidt',  '$date', '$time')";
										$qt = mysql_query($sqlt, $connect);
										if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
									if ($wi_co<>$battle_count){ 
										$x1=$battle_count-$wi_co;
										echo "     $localized_name  +$x1 ".$myeol;
										if ($dateb==$date){
											$sqlt = "UPDATE player_btl SET `master`='$markm',`garage`='$garage',`time`='$time', `battle_count`='$battle_count', `win_count`='$win_count',`frags`='$fragst', `spotted`='$spottedt', `survivedBattles`='$survivedBattles', `damageDealt`='$damageDealt' WHERE `idp`='$id' and `idt`='$wotidt' and`date`='$date' ";
										}
										else{
											$sqlt = "INSERT INTO player_btl (idp, idt, date, time, battle_count, win_count, frags, spotted, survivedBattles, damageDealt,master,garage)";
											$sqlt.= " VALUES ('$id', '$wotidt', '$date1', '$time', '$battle_count', '$win_count', '$fragst', '$spottedt', '$survivedBattles', '$damageDealt','$markm','$garage')";
										}	
									}
									else{ 
										 // //привет варгеймингу, из-за сбоев в отдаче статистики приходится добавлять этот костыль
										 $sqlt = "UPDATE player_btl SET `time`='$time',`master`='$markm',`garage`='$garage', `battle_count`='$battle_count', `win_count`='$win_count',`frags`='$fragst', `spotted`='$spottedt', `survivedBattles`='$survivedBattles', `damageDealt`='$damageDealt' WHERE `idp`='$id' and `idt`='$wotidt' and`date`='$dateb' ";
									}
									$qt = mysql_query($sqlt, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									//удаляем старые выборки
									$sqldelstart="SELECT max(date) as maxidpb FROM `player_btl` where idp=$id and idt=$wotidt and date<'$date2'";
									$delstart1 = mysql_query($sqldelstart, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$delstart = mysql_fetch_array($delstart1);
									if ($delstart['maxidpb']<>NULL){
										$idpb=$delstart['maxidpb'];
										$sql12 = "delete from `player_btl` where idp='$id' and idt='$wotidt' and date<'$idpb'"; 
										$qq2 = mysql_query($sql12,$connect);
										if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
									// удаляем старые выборки по player_c*
									$sqldelstart="SELECT max(date) as maxidpb FROM player_clan where idp=$id and  date<'$date2'";
									$delstart1 = mysql_query($sqldelstart, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$delstart = mysql_fetch_array($delstart1);
									if ($delstart['maxidpb']<>NULL){
										$idpb=$delstart['maxidpb'];
										$sql12 = "delete from `player_clan` where idp='$id'  and date<'$idpb'"; 
										$qq2 = mysql_query($sql12,$connect);
										if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
									$sqldelstart="SELECT max(date) as maxidpb FROM player_company where idp=$id and  date<'$date2'";
									$delstart1 = mysql_query($sqldelstart, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$delstart = mysql_fetch_array($delstart1);
									if ($delstart['maxidpb']<>NULL){
										$idpb=$delstart['maxidpb'];
										$sql12 = "delete from `player_company` where idp='$id'  and date<'$idpb'"; 
										$qq2 = mysql_query($sql12,$connect);
										if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
									}
									//
									$sqlstart="SELECT min(battle_count) as bcbefore, min(win_count)as winbefore FROM `player_btl` where idp=$id and idt=$wotidt and date<'$date2'";
									$bc30tsql = mysql_query($sqlstart, $connect);
									if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
									$bc = mysql_fetch_array($bc30tsql);
									$bcdelta=$battle_count-$bc['bcbefore'];
									$windelta=$win_count-$bc['winbefore'];
									if ($bcdelta<>0){
										$wins30t+=$windelta;
										$battles30t+=$bcdelta;
										//echo "     $tname -- $bcdelta wins $windelta".$myeol;
										$level_avg += $bcdelta*$level;
									}
								}
								if ($battles_count<>0){
									$level_avg_all /= $battles_count;	
								}	
								echo "средний уровень танков - ".$level_avg_all.$myeol;
								//rating
								 $rating=0;
								 if ($battles_count<>0){
									
									 $rating = round($frags/$battles_count*250
											 +$damage_dealt/$battles_count*(10/($level_avg_all+2))*(0.23+2*$level_avg_all/100)
											 +$spotted/$battles_count*150
											 +$dropped_capture_points/$battles_count*150
											 +(log($capture_points/$battles_count+1,1.732))*150);
									echo " Rating newEFF:  ".$rating.$myeol;	
									$wn6=round((1240-1040/pow(min($level_avg_all,6), 0.164))*$frags/$battles_count
											+$damage_dealt/$battles_count*530/(184*exp(0.24*$level_avg_all)+130)
											+$spotted/$battles_count*125
											+min($dropped_capture_points/$battles_count,2.2)*100
											+((185/(0.17+exp(($wins*100/$battles_count-35)*-0.134)))-500)*0.45
											+(6-min($level_avg_all,6))*-60);
									 echo " Rating WN6:  ".$wn6.$myeol;	  
									 $wn7=round((1240-1040/pow(min($level_avg_all,6), 0.164))*$frags/$battles_count
											+$damage_dealt/$battles_count*530/(184*exp(0.24*$level_avg_all)+130)
											+$spotted/$battles_count*125
											+min($dropped_capture_points/$battles_count,2.2)*100
											+((185/(0.17+exp(($wins*100/$battles_count-35)*-0.134)))-500)*0.45
											-((5-min($level_avg_all,5))*125)/(1+exp(($level_avg_all-pow($battles_count/220,3/$level_avg_all)*1.5))));
										   // //-[(5 - MIN(TIER,5))*125] / [1 + e^( ( TIER - (GAMESPLAYED/220)^(3/TIER) )*1.5 )] 
									 echo " Rating WN7: ".$wn7.$myeol;	
								 }
								 if ($battles_count<100) {
									$wn6=0;
									$rating=0;
									$dropped_capture_points=0;
									$capture_points=0;
									$frags=0;
									$spotted=0;
								}
								$rgpldate=$rGPL['mdate'];
								$sqlpl="UPDATE player SET `time`='$time', `spotted`='$spotted', `hits_percents`='$hits_percents', `capture_points`='$capture_points', `damage_dealt`='$damage_dealt', `frags`='$frags', `dropped_capture_points`='$dropped_capture_points',wins='$wins',losses='$losses',battles_count='$battles_count',survived_battles='$survived_battles',xp='$xp',battle_avg_xp='$battle_avg_xp',
									max_xp='$max_xp', rating='$rating', wn6='$wn6' WHERE `idp`='$id' and`date`='$rgpldate' ";
								$sqlpl_clan="UPDATE player_clan SET `time`='$time',`spotted_clan`='$spotted_clan', `hits_percents_clan`='$hits_percents_clan', 
									`capture_points_clan`='$capture_points_clan', `damage_dealt_clan`='$damage_dealt_clan', `frags_clan`='$frags_clan', 
									`dropped_capture_points_clan`='$dropped_capture_points_clan',wins_clan='$wins_clan',losses_clan='$losses_clan',
									battles_count_clan='$battles_count_clan',survived_battles_clan='$survived_battles_clan',xp_clan='$xp_clan',
									battle_avg_xp_clan='$battle_avg_xp_clan'  WHERE `idp`='$id' and`date`='$rgpldate' ";	
								$sqlpl_company="UPDATE player_company SET `time`='$time',`spotted_company`='$spotted_company', `hits_percents_company`='$hits_percents_company', 
									`capture_points_company`='$capture_points_company', `damage_dealt_company`='$damage_dealt_company', `frags_company`='$frags_company', 
									`dropped_capture_points_company`='$dropped_capture_points_company',wins_company='$wins_company',losses_company='$losses_company',
									battles_count_company='$battles_count_company',survived_battles_company='$survived_battles_company',xp_company='$xp_company',
									battle_avg_xp_company='$battle_avg_xp_company'  WHERE `idp`='$id' and`date`='$rgpldate' ";								
								if 	(($rGPL['mbattles']<>$battles_count) or ($rGPL['mbattles'] == NULL)or ($rGPL['maxbcclan'] == NULL)or ($rGPL['maxbccompany'] == NULL) or ($newtankist==1)) {
									//echo "<br>\n\nNew data!!!\n--------------------";
									if (($rGPL['mbattles']<>$battles_count) and ($rGPL['mbattles'] != NULL) and ($newtankist!=1)){
										if ($target>1) $target=(int)($target/2);
									}
									echo "New Target : $target".$myeol;
									if ($rGPL['mdate']<>$date) {
										$sqlpl = "INSERT INTO player (idp,idc,name,created_at,spotted, hits_percents,capture_points,damage_dealt,frags,dropped_capture_points,
										 wins,losses,battles_count,survived_battles,xp,battle_avg_xp,
										  max_xp,in_clan, date, time, rating,wn6)";
										$sqlpl.= " VALUES ('$id','$idc','$pname','$created_at', 
										 '$spotted','$hits_percents','$capture_points','$damage_dealt','$frags','$dropped_capture_points','$wins','$losses','$battles_count','$survived_battles','$xp','$battle_avg_xp',
										 '$max_xp','1', '$date1', '$time', '$rating','$wn6')";
									}
									echo "боев в клане: ".$battles_count_clan." Было: ".$rGPL['maxbcclan'].$myeol;
									if ($rGPL['maxbcclan']<>$battles_count_clan){
										 $sqlpl_clan = "INSERT INTO player_clan (idp, spotted_clan, hits_percents_clan,capture_points_clan,damage_dealt_clan,frags_clan,dropped_capture_points_clan,wins_clan,losses_clan,battles_count_clan,survived_battles_clan,xp_clan,battle_avg_xp_clan,
											date, time, rating,wn6)";
										$sqlpl_clan.= " VALUES ('$id',
										 '$spotted_clan','$hits_percents_clan','$capture_points_clan','$damage_dealt_clan','$frags_clan','$dropped_capture_points_clan','$wins_clan','$losses_clan','$battles_count_clan','$survived_battles_clan','$xp_clan','$battle_avg_xp_clan',
										 '$date1', '$time', 0,0)";
									}
									if ($rGPL['maxbccompany']<>$battles_count_company){
										$sqlpl_company = "INSERT INTO player_company (idp, spotted_company, hits_percents_company,capture_points_company,damage_dealt_company,frags_company,dropped_capture_points_company,wins_company,losses_company,battles_count_company,survived_battles_company,xp_company,battle_avg_xp_company,
											date, time, rating,wn6)";
										$sqlpl_company.= " VALUES ('$id',
										 '$spotted_company','$hits_percents_company','$capture_points_company','$damage_dealt_company','$frags_company','$dropped_capture_points_company','$wins_company','$losses_company','$battles_count_company','$survived_battles_company','$xp_company','$battle_avg_xp_company',
										 '$date1', '$time', 0,0)";
									}
								}else{
									if ($target<$req_freq){
										if ($allians==0){
											$target=$target+1;
										}	
										$target=$target+1;
									}else {
										$target=$req_freq;
									}
								}
								$q = mysql_query($sqlpl, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$q = mysql_query($sqlpl_clan, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								$q = mysql_query($sqlpl_company, $connect);
								if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
								if ($battles30t<>0){
									$level_avg /= $battles30t;	
								}
								echo "battles/month: $battles30t \navg.level/month: $level_avg ".$myeol;
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
								$winsdelta=$wins-$databefore['wins'];
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
									echo "боёв за месяц=$battlesdelta дамаг $damagedelta обнаружено $spotteddelta зб $dropdelta зах $captdelta".$myeol;
									$rating30 = round($fragsdelta/$battlesdelta*250
											+$damagedelta/$battlesdelta*(10/($level_avg+2))*(0.23+2*$level_avg/100)
											+$spotteddelta/$battlesdelta*150
											+$dropdelta/$battlesdelta*150
											+(log($captdelta/$battlesdelta+1,1.732))*150);
									echo " Рейтинг30 - ".$rating30.$myeol; 	
									
									$win30=round($winsdelta*100/$battlesdelta,2);
									
									$wn630=round((1240-1040/pow(min($level_avg,6), 0.164))*$fragsdelta/$battlesdelta
										   +$damagedelta/$battlesdelta*530/(184*exp(0.24*$level_avg)+130)
										   +$spotteddelta/$battlesdelta*125
										   +min($dropdelta/$battlesdelta,2.2)*100
										   +((185/(0.17+exp(($wins30t*100/$battlesdelta-35)*-0.134)))-500)*0.45
										   +(6-min($level_avg,6))*-60);
									echo " Рейтинг wn630 - ".$wn630.$myeol;
									$wn730=round((1240-1040/pow(min($level_avg,6), 0.164))*$fragsdelta/$battlesdelta
										   +$damagedelta/$battlesdelta*530/(184*exp(0.24*$level_avg)+130)
										   +$spotteddelta/$battlesdelta*125
										   +min($dropdelta/$battlesdelta,2.2)*100
										   +((185/(0.17+exp(($wins30t*100/$battlesdelta-35)*-0.134)))-500)*0.45
										   -((5-min($level_avg,5))*125)/(1+exp(($level_avg-pow($battlesdelta/220,3/$level_avg)*1.5))));
										   //+(6-min($level_avg,6))*-60);
									echo " Рейтинг wn730 - ".$wn730.$myeol;
									echo " % побед 30 - ".$win30.$myeol;
									echo " Разница побед - ".$winsdelta.$myeol;
								}
								$sql="UPDATE `player` SET `in_clan`='1', `name`='$pname', `rating30`='$rating30',`wn630`='$wn630', `win30`= '$win30' WHERE `idp`='$id'";
								 mysql_query($sql, $connect);
								 if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
							}
						}
					}
				}
			}
		}
		
	$freq=$freq+1;
	$sql="UPDATE `clan` SET `freq`='$freq',`target`='$target' WHERE `idp`='$id'";
	mysql_query($sql, $connect);
	if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
	
}
//далее набор непонятных вычислений.
//нужных только для саморегуляции нагрузки 
if (($force<>1)and ($offs<>0)){
	$sql = "select count(*) as cntlog, min(`date`) as mindate from tech_log where date>'$t'";
	$q = mysql_query($sql,$connect);
	if (mysql_errno() <> 0) echo "MySQL Error 1 ".mysql_errno().": ".mysql_error()."\n";
	$log = mysql_fetch_array($q);
	$cntlog = $log['cntlog'];
	$sql = "select min(`date`) as mindate,max(`date`) as maxdate ,avg(`players`) as avgplayers,sum(`players`) as sumpl, avg(`cntplayers`) as avgcntplayers,sum(`all`) as sumall,avg(`timer`) as atimer from tech_log where date>'$t'";
	$q = mysql_query($sql,$connect);
	if (mysql_errno() <> 0) echo "MySQL Error 1 ".mysql_errno().": ".mysql_error()."\n";
	$log = mysql_fetch_array($q);
	print_r($log);
	$mindate = $log['mindate'];
	$maxdate=$log['maxdate'];
	$sumall=$log['sumall'];
	$atimer=$log['atimer'];
	$avgcntplayers=$log['avgcntplayers'];
	$diffdate=$maxdate-$mindate;
	$a=round($sumall/$cntlog,2);
	echo "коэфициент наполнения - ".$a.$myeol."с последнего сброса прошло - ".$diffdate." секунд".$myeol;
	if (($diffdate>87000)or(($cntlog>10)and(($atimer>25)or($a<0.7)))){
		
		if ($a<0.9){
			echo "Слишком мало".$myeol;
				if ($atimer<20){
					$cntmaxpl+=1;
					echo "Увеличиваем всякие-разные коэфициенты -".$cntmaxpl.$myeol;
				}else{
					$max_player_request-=1;
					echo "Уменьшаем количество запросов- ".$max_player_request.$myeol;
					
				}
		}else{
			echo "слишком много".$myeol;
			if ($atimer<20){
				$max_player_request+=1;
				echo "Увеличиваем количество запросов - ".$max_player_request.$myeol;
			}else{
				$cntmaxpl-=1;
				echo "Уменьшаем всякие-разные коэфициенты -".$cntmaxpl.$myeol;
			}
		}
		//24/((ОбщееЧислоБойцов/max_player_request)/КоличествоЗапросовВЧасПоКрону)
		$req_freq=round(((24*(3600/($diffdate/$cntlog)))/($avgcntplayers/$max_player_request)),0);
		$sql="TRUNCATE TABLE `tech_log`";
		mysql_query($sql, $connect);
		if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
	}
	$sql="UPDATE `tech` SET `cntmaxpl`='$cntmaxpl',`maxplreq`='$max_player_request',`reqtarget`='$req_freq'";
	mysql_query($sql, $connect);
	if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
	echo "за последнее время было сделано ".$cntlog." запусков сборщика ".$myeol;
	$endtime=time()-$starttime;
	$sql = "INSERT INTO `tech_log` (`date`, `players`, `all`, `cntplayers`, `timer`)";
	$sql.= " VALUES ('$starttime','$count1', '$allpl', '$cntplayer', '$endtime')";	
	$q = mysql_query($sql, $connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	echo "Обработано всего: ".$count1." бойцов\n<br>";
}
function get_page($url) {
	$ch = curl_init();
			//array('Accept-Language: ru-ru,ru'
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_HTTPGET, true);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
$a11=$timetolife+1;
$date1=date("Y-m-d",strtotime(' -'.$a11.' day '.$hosttime));	
$sql12 = "delete from `event_tank` where (`type`<>14) and (`type`<>1) and (`type`<>2) and (`date`<'$date1')"; 
$qq2 = mysql_query($sql12,$connect);
if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
$sql12 = "delete from `event_clan` where (`type`=0) and (`date`<'$date1')"; 
$qq2 = mysql_query($sql12,$connect);
if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
mysql_close($connect);
echo "<br>get_global_mm done!"
?>
