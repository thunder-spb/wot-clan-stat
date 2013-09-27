<?php
//Владения на ГК
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
$idc = $_GET['idc'];
$sql="SELECT count(*) as cnt from `btl` where idc='$idc'";
$res = mysql_query($sql,$connect);
$row = mysql_fetch_array($res,MYSQL_ASSOC); 
$count = $row['cnt'];
$responce = new stdclass;
$responce->page = 1;
$responce->total = 1;
$responce->records = $count;
$i=0;
$SQL2 = "select skill  from clan_info where idc='$idc'";
$claneresult1 = mysql_query( $SQL2,$connect );
$rowclan1=mysql_fetch_array($claneresult1,MYSQL_ASSOC);
$SQL = "select id_b, idb, type,time, id_prov, prov,  started, arena  from btl where idc='$idc' group by idb order by time";
$result2 = mysql_query( $SQL,$connect );
while($row = mysql_fetch_array($result2,MYSQL_ASSOC)) { 
	$provm=$row['type'];
	$timem="";
	$clane="";
	$idb=$row['idb'];
	$idpr=$row['id_prov'];
	$pr = $row["prov"];
	$arena=$row["arena"];
	$pr = "<a href='http://worldoftanks.ru/uc/clanwars/maps/?province=$idpr' target='_blank'>$pr</a>";
	if ($row['type']=="landing"){$provm="Высадка";}
		$SQL2 = "select * from possession where idpr='$idpr'and idc='$idc'";
		$result22 = mysql_query( $SQL2,$connect );
		if (mysql_fetch_array($result22,MYSQL_ASSOC)){
			$provm=$provm." (удержание)";
		}
	if ($row['type']=="for_province"){
		$provm="За провинцию";
		$SQL2 = "select * from possession where idpr='$idpr'and idc='$idc'";
		$result22 = mysql_query( $SQL2,$connect );
		if (!mysql_fetch_array($result22,MYSQL_ASSOC)){
			$provm=$provm." (атака)";
		}else{
			$provm=$provm." (защита)";
		}
		$SQL2 = "select idb,idc,time from btl where idb='$idb'and idc<>'$idc'";
		$result22 = mysql_query( $SQL2,$connect );
		$clane="";
		$t=time()-604800;
		while($row3=mysql_fetch_array($result22,MYSQL_ASSOC)){
		//if ($row3<>NULL){
			$clansa=$row3['idc'];
			$timem=date("H:i",$row3['time']);
			$SQL2 = "select idc,tag, color, name, allians, actdate,skill,rate,smallimg  from clan_info where idc='$clansa'";
			$claneresult = mysql_query( $SQL2,$connect );
			$rowclan=mysql_fetch_array($claneresult,MYSQL_ASSOC);
			//"<span style='color: blue;'><b>"; $sp6="</b></span> "
			$clanname=$clann=$rowclan['name'];
			$actclane=$rowclan['actdate'];
			$simg=$rowclan['smallimg'];
			$skillb=$rowclan['skill'];
			$skilla=$rowclan1['skill'];
			$stra="";
			if ($skillb<>0 and $skilla<>0){
				$chans=round(($skilla/($skilla+$skillb))*100,0);
				$stra="| сила-".$rowclan['rate'] ." | шанс-".$chans."% |";
			}
			if ($t<$actclane){
			$clann=	"<a href='clanstat.php?idc=$clansa#tab-6' target='_blank'>$clanname</a>";
			if ($rowclan['allians']==1){
				$clann="<a href='wotstat.php?idc=$clansa#tab-6' target='_blank'>$clanname</a>";
			}}
			$timema="";
			if ($row['started']==1){
				$timema="<b>[".$timem."]</b>";
			}
			//$pr = "<a href='http://worldoftanks.ru/uc/clanwars/maps/?province=$idpr' target='_blank'>$pr</a>";
			$clane=$clane."<span style='background-color:". $rowclan['color'].";'>"."    "."</span> ".'<img src="'.$simg.'" style="width: 20px; height:20px;" align="absmiddle"/>'.$timema." [".$rowclan['tag']."]".$stra."<b> ".$clann."</b><br>";
			//$clane=$clane."f,hf";
		}
	}
	if ($row['started']==1){
		$timem="<b>".date("H:i",$row['time'])."</b>";
	}else{
		$timem=date("H:i",$row['time'])." +";
	}
	if ($row['time']==0){ $timem="--:--";}
	$SQL2 = "select type, periphery from province where id='$idpr'";
	$result22 = mysql_query( $SQL2,$connect );
	$row3=mysql_fetch_array($result22,MYSQL_ASSOC);
	$periphery="Неизв.";
	if ($row3<>NULL){
		$periphery=$row3['periphery'];
		switch ($row3["type"]) {
		case "normal":
			$pr = '<img src="images/province_type_normal.png" style="width: 16px; height:16px;" align="absmiddle"/>'." ".$pr;// alt='Обычная провинция' >";
			break;
		case "gold":
			$pr = '<img src="images/province_type_gold.png" style="width: 16px; height:16px;" align="absmiddle"/>'." ".$pr; //alt='Ключевая провинция' >";
			break;
		case "start":
			$pr = '<img src="images/province_type_start.png" style="width: 16px; height:16px;" align="absmiddle"/>'." ".$pr;// alt='Стартовая провинция' >";
			break;
		}
	}
	if ($row['type']=="meeting_engagement"){
		$SQL = "select idb, idc,prov, id_prov,arena from btl where idb='$idb'and idc<>'$idc' and id_prov<>'$idpr'";
		$result22 = mysql_query( $SQL,$connect );
		$row100=mysql_fetch_array($result22,MYSQL_ASSOC);
		$provid=$row100['id_prov'];
		$provname=$row100['prov'];
		$periphery="---";
		$clansa=$row100['idc'];
			$clane="";
			// $SQL2 = "select idc,tag, color, name from clan_info where idc='$clansa'";
			// $claneresult = mysql_query( $SQL2,$connect );
			// $rowclan=mysql_fetch_array($claneresult,MYSQL_ASSOC);
			$SQL2 = "select idc,tag, color, name, allians, actdate,skill,rate,smallimg from clan_info where idc='$clansa'";
			$claneresult = mysql_query( $SQL2,$connect );
			
			$rowclan=mysql_fetch_array($claneresult,MYSQL_ASSOC);
			//"<span style='color: blue;'><b>"; $sp6="</b></span> http://5.19.254.43/stat/clanstat.php?idc=65317#tab-6"
			$clanname=$clann=$rowclan['name'];
			$simg=$rowclan['smallimg'];
			$actclane=$rowclan['actdate'];
			$t=time()-700000;
			$skillb=$rowclan['skill'];
			$skilla=$rowclan1['skill'];
			$stra="";
			if ($skillb<>0 and $skilla<>0){
				$chans=round(($skilla/($skilla+$skillb))*100,0);
				$stra="| сила-".$rowclan['rate'] ." | шанс-".$chans."% |";
			}
			if ($t<$actclane){
			$clann=	"<a href='clanstat.php?idc=$clansa#tab-6' target='_blank'>$clanname</a>";
			if ($rowclan['allians']==1){
				$clann="<a href='wotstat.php?idc=$clansa#tab-6' target='_blank'>$clanname</a>";
			}}
			//"<span style='color: blue;'><b>"; $sp6="</b></span> "
			
			$clane=$clane.'<span style="background-color:'. $rowclan['color'].';">'.'    '.'</span> '.'<img src="'.$simg.'" style="width: 20px; height:20px;" align="absmiddle"/>'.'['.$rowclan['tag'].']'.$stra.'<b> '.$clann.'</b><br>';
		if ($row100<>NULL){
			$SQL2 = "select type from province where id='$provid'";
			$result22 = mysql_query( $SQL2,$connect );
			$row3=mysql_fetch_array($result22,MYSQL_ASSOC);
			$pr1=$provname;
			if ($row3<>NULL){
				switch ($row3["type"]) {
					case "normal":
						$pr1 = '<img src="images/province_type_normal.png" style="width: 16px; height:16px;" align="absmiddle"/>'." ".$pr1;// alt='Обычная провинция' >";
						break;
					case "gold":
						$pr1 = '<img src="images/province_type_gold.png" style="width: 16px; height:16px;" align="absmiddle"/>'." ".$pr1; //alt='Ключевая провинция' >";
						break;
					case "start":
						$pr1 = '<img src="images/province_type_start.png" style="width: 16px; height:16px;" align="absmiddle"/>'." ".$pr1;// alt='Стартовая провинция' >";
						break;
				}
			}
		}
		$provm="Встречный бой";
		$pr=$pr.'<img src="images/icons/vs.png" align="absmiddle">'.$pr1;
		$arena1=$row100['arena'];
		 if ($arena<>$arena1){
			 $arena=$arena." или ".$arena1;
		 }
	}
	
	$responce->rows[$i]['cell']=array("<b>".$provm."</b>","<b>".$pr."</b>", "<b>".$arena."</b>",$timem,$clane,$periphery); //$clandays,$las_onl); 
	$i++; 
} 
echo json_encode($responce);
?>