<?php
/////Ангар за 7 дней
//include error_reporting(0);
include('settings.kak');
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
header('Content-Type: text/html; charset=UTF-8');
if($_REQUEST['filterBy'] != 'null'){
	$idac = $_REQUEST['filterBy'];
}
$minDate=date("Y-m-d",strtotime(' -7 day '.$hosttime));// за 7 дней
//$minDate=date("Y-m-d",strtotime(' -1 day'));// за седня
//$idac=259339;
$result = mysql_query("SELECT count(*) as cnt from cat_tanks a,player_btl b where a.id_t=b.idt and b.idp='$idac' and b.date>'$minDate'");
$row = mysql_fetch_array($result,MYSQL_ASSOC); 
$count = $row['cnt']; 

$sql="SELECT max(c.battle_count)as b_c,max(c.win_count) as w_c, c.idt as idtr, a.level,a.class,a.image_url,a.localized_name from `player_btl` c, cat_tanks a where idp='$idac' and c.idt=a.id_t and date>='$minDate' group by c.idt order by level desc,class desc";
$result = mysql_query( $sql,$connect ) or die("Couldn t execute query.".mysql_error()); 
$message="<table border='0'><tr>";
//echo $message;
$newtype="";
$br=0;
$st=0;
$brr="";
for($i=0;$i<$count;$i++) { 
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$id_tank=$row['idtr'];
	$sqlbefore="SELECT max(battle_count) as bc_before, max(win_count) as wc_before from `player_btl` where idp='$idac' and idt='$id_tank' and date<'$minDate'";
	$resultbefore = mysql_query( $sqlbefore,$connect ) or die("Couldn t execute query.".mysql_error()); 
	$bef = mysql_fetch_array($resultbefore);
	$befb_c = $bef['bc_before'];
	$befw_c = $bef['wc_before'];
	$diffb_c=$row['b_c']-$befb_c;
	$diffw_c=$row['w_c']-$befw_c;
	if(($row['b_c']!=NULL)and ($diffb_c>0)){
		if($newtype<>$row['class']) {
			if ($newtype>0)
				$message="</tr>".$message."<tr>";
			else
				$message=$message."<tr>";
			$newtype=$row['class'];
			$st=$st+1;
			$br=0; $brr="";
		}
		$img="<img src='http://".$wot_host.$row['image_url']."'/>"; 
		$sp1=""; $sp2="";$sp3="";$sp4="";$sp5="";$sp6="";
		if($row['class']=='SPG') $color='ffdab9';
		if($row['class']=='AT-SPG') $color='c6efef';
		if($row['class']=='mediumTank') $color='d0f0c0';
		if($row['class']=='heavyTank') $color='98ff98';
		if($row['class']=='lightTank') $color='ffffff';
		$procdiff=round(($diffw_c*100/$diffb_c),2);
		$proc=round(($row['w_c']*100/$row['b_c']),2);
		if ($befb_c<>0){
	 	    $procbef=round(($befw_c*100/$befb_c),2);
		}else{
		     $procbef=0;
		}
		$deltaproc=round($proc-$procbef,2);
		if($proc<48) {$sp1="<span style='color: red;'>"; $sp2="</span>";}
		if($proc>51) {$sp1="<span style='color: green;'>"; $sp2="</span>";}
		if($proc>54) {$sp1="<span style='color: blue;'>"; $sp2="</span>";}
		$deltamessage="n/a";
		if ($row['b_c']!=$diffb_c){
			if($deltaproc<0) {$sp5="<span style='color: red;'>"; $sp6="</span>";}
			if($deltaproc>0) {$sp5="<span style='color: green;'>+"; $sp6="</span>";}
			if($deltaproc>5) {$sp5="<span style='color: blue;'>+"; $sp6="</span>";}
			$deltamessage=$sp5.$deltaproc."%".$sp6;
		}
		if($procdiff<48) {$sp3="<span style='color: red;'>"; $sp4="</span>";}
		if($procdiff>51) {$sp3="<span style='color: green;'>"; $sp4="</span>";}
		if($procdiff>54) {$sp3="<span style='color: blue;'>"; $sp4="</span>";}
		if($br==3) {$brr="</tr><tr>"; $br=0;} else $brr="";
		$message=$message.$brr."<td width='250' align='center' style='border: 1px solid #faf0e6;background: #".$color."'><table><tr><td><center>".$img."<br>".$row['level']." lvl.</center></td><td><table><tr><td>".$row['localized_name']."</td></tr><tr><td>+".$diffb_c."(".$sp3.$procdiff."%".$sp4.")/".$row['b_c']." (".$sp1.$proc."%".$sp2.")</td></tr><tr><td>".$deltamessage."</td></tr></table></td></tr></table></td>";
		$br=$br+1;
	}
}
$st2="";
for($i=0;$i<$st;$i++) $st2=$st2."</tr>";
if ($st>0) $message=str_replace($st2,"",$message."</table>");
if ($st==0) $message=$message."</table>";
//echo $message;
$responce->rows[0]['cell']=array($message);
header("Content-type: text/script;charset=utf-8");
echo json_encode($responce);
?>
