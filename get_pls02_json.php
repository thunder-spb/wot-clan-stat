<?php
//header('Content-Type: text/html; charset=UTF-8');
//include error_reporting(0);
include('settings.kak');
//function get_max_amount($idplayer,$id_ach){
//	$a=array("16"=>40, "19"=>41, "5"=>42, "33"=>43, "30"=>44);
//	$idf=$a[$id_ach];
//     $resultkosa = mysql_query("SELECT amount as amnt from `player_ach` where idp='$idplayer' and ida='$idf'");
//		$rowkosa = mysql_fetch_array($resultkosa,MYSQL_ASSOC); 
//		$u = $rowkosa['amnt']; 
//		return $u;
//}
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');
if($_REQUEST['filterBy'] != 'null'){
	$idac = $_REQUEST['filterBy'];
}

$result = mysql_query("SELECT count(*) as cnt from `player_ach` where idp='$idac' group by idp");
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['cnt']; 
$sql="SELECT c.idp,a.type,a.medal_ru,a.img,a.id_ac from `player_ach` c, `cat_achiev` a where idp='$idac' and c.ida=a.id_ac and type>0 group by c.ida order by type asc,id_ac asc";
$result = mysql_query( $sql,$connect ) or die("Couldn t execute query.".mysql_error()); 
$message="<table border='0'><tr>";
$newtype=0;
$cnt=0;
$st=0;
for($i=0;$i<$count;$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$mdl_id=$row['id_ac'];
	$SQL33="SELECT amount from player_ach where idp='$idac' and ida='$mdl_id' and id_pa in (select max(id_pa) from player_ach where idp='$idac' and ida='$mdl_id')";
	$qt33 = mysql_query($SQL33, $connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$qqtt33 = mysql_fetch_array($qt33);
	$a_co=$qqtt33['amount'];
	if (($row['type']<>10)and ($a_co<>0)){ // 10-максимальная серия для других медалек
	$cnt=$cnt+1;
	if(($newtype<>$row['type'])or($cnt>10)){
		if ($newtype>0)
			$message="</tr>".$message."<tr>";
		else
			$message=$message."<tr>";
		$newtype=$row['type'];
		$cnt=0;
		$st=+1;
	}
	if($row['type']==1) {
		$img="<img src='images/".$row['img'].$a_co.".png'/>"; 
		$value=$a_co." степень";
	}
	else {
		
		$img='<img alt="'.$row['medal_ru'].'" src="images/'.$row['img'].'.png" />';
		//$img='<img src="images/stickers/ussr.png" style="width: 20px; height:20px;" align="absmiddle"/>';
		$value=$a_co;
		if(($row['type']==3)and($a_co==1)) { $value="";}
		if($row['type']==5) { $value="";}
	}
	if (($row['id_ac']==16) or ($row['id_ac']==19) or ($row['id_ac']==5) or ($row['id_ac']==33) or ($row['id_ac']==30)){ //медальки с макс сериями требует особого подхода
		
		$value=get_max_amount($idac,$row['id_ac']);
	}
	$message=$message."<td  width='91' align='right'><center>".$img."<br> ".$row['medal_ru']."<br> ".$value."</center></td>";
	}
}
if ($st==3) $message=str_replace("</tr></tr></tr>","",$message."</table>");
if ($st==2) $message=str_replace("</tr></tr>","",$message."</table>");
if ($st==1) $message=str_replace("</tr>","",$message."</table>");
if ($st==0) $message=$message."</table>";
$responce->rows[0]['cell']=array($message);
header("Content-type: text/script;charset=utf-8");
function get_max_amount($idplayer,$id_ach){
	$a=array("16"=>40, "19"=>41, "5"=>42, "33"=>43, "30"=>44);
	$idf=$a[$id_ach];
      $resultkosa = mysql_query("SELECT amount as amnt from `player_ach` where idp='$idplayer' and ida='$idf'");
		$rowkosa = mysql_fetch_array($resultkosa,MYSQL_ASSOC); 
		$u = $rowkosa['amnt']; 
		return $u;
}
echo json_encode($responce);
?>