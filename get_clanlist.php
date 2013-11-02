<?php
// выборка списка клана. анализ, внесение изменений, запись в лог-таблицу

include('settings.kak');
// $region = geoip_record_by_name('178.165.42.37');
// print_r(date("Y:m:d H:i",'1383347770'));
// if (($region['region']<>NULL) and ($region['country_code']<>NULL)){
	// $remtz=new DateTimeZone(geoip_time_zone_by_country_and_region($region['country_code'],$region['region']));
	// print_r(geoip_time_zone_by_country_and_region($region['country_code'],$region['region']));
// } else{
	// $remtz=new DateTimeZone('Europe/Moscow');
// }
// $remtime = new DateTime('@1383347770');
// $remtime->setTimezone($remtz);
// $offset=$remtime->format('H:i');
// print_r($offset);die();
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8'); 
//$clan_array[] = array("clan_id" => "12638", "clan_tag" => "[SMPLC]",  "clan_name" => "Sample clan");
$actwmdatesql = mysql_query("select lasthourwm from tech",$connect);
$actwmdate=mysql_fetch_array($actwmdatesql,MYSQL_ASSOC);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$hour=date("H",strtotime($hosttime));
if ($actwmdate['lasthourwm']<>NULL){
	if ($actwmdate['lasthourwm']==$hour){
		//die ();
	}
}

if (!(isset($alliansid))){
	$alliansid=9999999999;
}
mysql_query("update clan_info set allians='0' where alliansid<>'$alliansid'",$connect);
mysql_query("update clan_info set allians='1' where alliansid='$alliansid'",$connect);
foreach ($clan_array as $clan_i) {
	$idc=$clan_i["clan_id"];
	$clancnt[]=$clan_i["clan_id"];
	mysql_query("update clan_info set allians='1' where idc='$idc'",$connect);
}
$t = time()-604800;
$clanlist = mysql_query("select idc from clan_info where actdate>'$t' or alliansid='$alliansid'",$connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$clancnt=array();
foreach ($clan_array as $clan_i) {
		$idc=$clan_i["clan_id"];
		$clancnt[]=$clan_i["clan_id"];
}
while ($clanrow=mysql_fetch_array($clanlist,MYSQL_ASSOC)) {
	$clancnt[]=$clanrow["idc"];
 }
 if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$clancnt=array_unique($clancnt);

$iv = mysql_query("select lastiv from tech",$connect);
$ivdb=mysql_fetch_array($iv,MYSQL_ASSOC);
$iv=$ivdb['lastiv'];
$dataiv=array();
$dataivall=array();
if ($iv<$t){
	$pageidc = "http://ivanerr.ru/lt/export.php?byclanid";		
	$dataiv1 = get_page($pageidc);
	$dataiv = json_decode($dataiv1,true);
	$pageidc = "http://ivanerr.ru/lt/export.php?alliances";		
	$dataiv1 = get_page($pageidc);
	$dataivall = json_decode($dataiv1, true);
	$a=json_last_error();
	
}

$sql = "select `idc` from clan_info where 1";
$q1 = mysql_query($sql, $connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$cnt=0;
if ($dataiv<>NULL){
	while ($clani=mysql_fetch_array($q1,MYSQL_ASSOC)) {
		$iidc=$clani['idc'];
		//echo $iidc." Клан для иванерра".PHP_EOL;
		if (array_key_exists($iidc, $dataiv)) {
			  //echo PHP_EOL.$dataiv["$iidc"]['totalrate']." Rating ".PHP_EOL;
			  $cnt+=1;
			  $totalrate=$dataiv["$iidc"]['totalrate'];
			  $firepower=$dataiv["$iidc"]['firepower'];
			  $skill=$dataiv["$iidc"]['skill'];
			  $position=$dataiv["$iidc"]['position'];
			  $alliansid1=$dataiv["$iidc"]['allianceid'];
			  $sql = "UPDATE `clan_info` SET `rate`='$totalrate', alliansid='$alliansid1', firepower='$firepower', skill='$skill',  position='$position' WHERE `idc`='$iidc'";
			  $q = mysql_query($sql, $connect);
			  if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
			  unset($dataiv["$iidc"]);
		}
		
	}
	foreach ($dataiv as $ida){
		if ($ida['allianceid']==$alliansid){
			$totalrate=$ida['totalrate'];
			$firepower=$ida['firepower'];
			$skill=$ida['skill'];
			$position=$ida['position'];
			$idciv=$ida['clanid'];
			$tag=$ida['clantag'];
			$sql= "INSERT INTO `clan_info`(tag,idc,rate,allians,alliansid, firepower, skill,position)";
			$sql.= " VALUES ('$tag','$idciv', '$totalrate',1, '$alliansid','$firepower','$skill','$position')";
			$q = mysql_query($sql, $connect);
			if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
		}
	}
	foreach ($dataivall as $ida){
		$id=$ida['id'];
		$name=$ida['name'];
		$tag=$ida['tag'];;
		$color=$ida['color'];
		$sql = "select `ida` from alliances where ida='$id'";
		$q1 = mysql_query($sql, $connect);
		if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n"; 
		$row=mysql_fetch_array($q1,MYSQL_ASSOC);
		if ($row['ida']<>NULL){
			  $sql = "UPDATE `alliances` SET `name`='$name', tag='$tag', color='$color' WHERE `ida`='$id'";
		}else{
			$sql= "INSERT INTO alliances (ida,name, tag, color)";
			$sql.= " VALUES ('$id', '$name', '$tag','$color')";
		}
		$q = mysql_query($sql, $connect);
		if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	}
}
if ($cnt<>0){
	$t1=time();
	mysql_query("update tech set lastiv='$t1'",$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
}
foreach ($clancnt as $idc) {
	$clanlist = mysql_query("select tag, allians from clan_info where idc='$idc'",$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$clanlist=mysql_fetch_array($clanlist,MYSQL_ASSOC);
	$clantag = $clanlist["tag"];
	$allians=$clanlist["allians"];
	if ($clantag==NULL){
		foreach ($clan_array as $clan_i) {
			$idct = $clan_i["clan_id"];
			if ( $idct == $idc){
				$clantag = $clan_i["clan_tag"];
				$allians=1;
				break;
			}	
		}
	}	
	echo $clantag;
	echo "<br>";
	echo "allians-".$allians;
	echo "<br>";
	$pageidc = "/2.0/clan/info/?application_id=".$appid."&clan_id=".$idc."&language=ru&fields=emblems.small,members,clan_color,name";		
	$pageidc = "api.".$wot_host.'/'.$pageidc;
	$date = date("Y-m-d",strtotime($hosttime));
	$time = date("H:i:s",strtotime($hosttime));
	//$date = date("Y-m-d");
	//$time = date("H:i:s");

	$data = get_page($pageidc);
	$data = json_decode($data, true);
	if ($data['status'] == 'ok') {
		echo "успешно загрузили данные...<br>";
		$data=$data['data'][$idc];
		// тут добавить сбор инфы о клане //
		$smallimg=$data['emblems']['small'];
		$color=$data['clan_color'];
		$name=addslashes( $data['name']);
		$sql = 'UPDATE `clan_info` SET `smallimg`="'.$smallimg.'" ,`color`="'.$color.'",`name`="'.$name.'" WHERE `idc`='.$idc;
		$q = mysql_query($sql, $connect);
		if (mysql_errno() <> 0) {
			echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
			print_r ($sql);
		}
		$sql = "select count(*) as cntpl from clan where idc='$idc'";
		$q = mysql_query($sql, $connect);
		if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
		$cntpl = mysql_fetch_array($q);
		$cntpl=$cntpl['cntpl'];
		echo "Бойцов в клане - ".$cntpl.PHP_EOL."<br>";
		$sql = "select clan.idp as idp, player.name as name from clan   join player on clan.idp=player.idp where  clan.idc='$idc' group by clan.idp";
		$q = mysql_query($sql, $connect);
		if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
		
		while  ($idpl = mysql_fetch_array($q)){
			$n=$idpl['idp'];
			$account_name=$idpl['name'];
			if (!(array_key_exists($n,$data['members']))){ 
				echo "удаляем ".$n."\n";
				$sql12 = "delete from `clan` where idp='$n'"; 
				$qq2 = mysql_query($sql12,$connect);
				if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$sql12 = "delete from `event_clan` where idp='$n' and type<>2 and type<>1"; 
				$qq2 = mysql_query($sql12,$connect);
				if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$sql12 = "delete from `event_tank` where idp='$n'"; 
				$qq2 = mysql_query($sql12,$connect);
				if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$sql12 = "delete from `player` where idp='$n'"; 
				$qq2 = mysql_query($sql12,$connect);
				if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$sql12 = "delete from `player_clan` where idp='$n'"; 
				$qq2 = mysql_query($sql12,$connect);
				if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$sql12 = "delete from `player_company` where idp='$n'"; 
				$qq2 = mysql_query($sql12,$connect);
				if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$sql12 = "delete from `player_ach` where idp='$n'"; 
				$qq2 = mysql_query($sql12,$connect);
				if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$sql12 = "delete from `player_btl` where idp='$n'"; 
				$qq2 = mysql_query($sql12,$connect);
				if (mysql_errno() <> 0) echo $sql12."\nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$account_name='<a href="http://worldoftanks.ru/community/accounts/'.$n.'/" target="_blank">'.$account_name.'</a>';
				$message="Покинул клан ".$clantag." боец ".$account_name;
				$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
				$sql.= " VALUES (1,'$n', '$idc', '$message', NULL, '$date', '$time')";
				$qqq = mysql_query($sql, $connect);
				if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
			}
			
		}
		foreach($data['members'] as $datapl){
			//проверка на "нового игрока в клане"
			$t=date("Y-m-d",($datapl['created_at']));
			$idp=$datapl['account_id'];
			$sql = "select id_c,role_localised from clan where idp='$idp' and idc='$idc'";
			$q = mysql_query($sql, $connect);
			if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
			$qqt = mysql_fetch_array($q);
			$newtankist=0;
			if($qqt['id_c']==NULL){ // игрока нет в данном клане	
				//проверка, что игрок был в другом клане альянса
				$sql = "select id_c from clan where idp='$idp'";
				$q = mysql_query($sql, $connect);
				if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
				$qqt = mysql_fetch_array($q);
				if($qqt['id_c'] != NULL) {
					if ($allians==1){
						$message=$datapl['account_name']." перешел в ".$clantag;
						$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
						$sql.= " VALUES (2,'$idp', '$idc', '$message', NULL, '$date', '$time')";
						$q = mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
					}
					$sql = "UPDATE `clan` SET `idc`='$idc' WHERE `idp`='$idp'";
					$q = mysql_query($sql, $connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
			
				} else {
					if ($cntpl<>0) {
						$newtankist=1;
						$message="Приветствуем ".$datapl['account_name'].' в '.$clantag;
						$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
						$sql.= " VALUES (2,'$idp', '$idc', '$message', NULL, '$date', '$time')";
						$q = mysql_query($sql, $connect);
						if (mysql_errno() <> 0) echo "MyQL Error ".mysql_errno().": ".mysql_error()."\n";
					}
					#=================== Insert into clan tables ==============#
					$created_at=date("Y-m-d",$datapl['created_at']); //дата вступления в клан
					//$role=$data['data']['members'][$i]['role'];
					$role_lo=$datapl['role'];
					$sql  = "insert into clan (idp, idc, date,role_localised)";
					$sql .=" values('$idp', '$idc', '$created_at','recruit')";
					//echo $sql.'<br>';
					mysql_query($sql, $connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
				}
			}
			$dolgnDB=$qqt['role_localised'];
			//echo "Должность из БД $dolgnDB \n";
			$role_lo=$datapl['role'];
			//echo "Должность из Вг $role_lo \n";
			if ($dolgnDB<>$role_lo) {
				if ($newtankist==0) {
					$role1=@$clanrange[$dolgnDB];
					$role2=$clanrange[$role_lo];
					$message="Изменение должности ".$account_name." c ".$role1." на ".$role2;
					$sql = "INSERT INTO event_clan (type,idp, idc, message, reason, date, time)";
					$sql.= " VALUES (4,'$idp', '$idc', '$message', NULL, '$date', '$time')";
				//print_r ($sql);
					$q = mysql_query($sql, $connect);
					if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
				};
				$sql="UPDATE clan SET `role_localised`='$role_lo' WHERE `idp`='$idp'";
				mysql_query($sql, $connect);
				if (mysql_errno() <> 0) echo "\n$sql \nMySQL Error ".mysql_errno().": ".mysql_error()."\n";
			}
		}
	}

}
function get_page($url) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_HEADER, 0);
                //curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Accept-Language: ru_ru,ru'));
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 200);
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HTTPGET, true);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
}
mysql_close($connect);
echo "Done"
?>

