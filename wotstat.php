<?php 

require_once('settings.kak');  

if (array_key_exists("idc",$_GET)) {
	$idc = filter_input(INPUT_GET, "idc", FILTER_VALIDATE_INT);
} else  {
	$idc = $clan_array[0]['clan_id'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php  
foreach ($clan_array as $clan_i) {
	$idc_temp = $clan_i["clan_id"];
	if ($idc == $idc_temp) {
		echo $clan_i["clan_tag"];
	}
}
$connect = mysql_connect($host, $account, $password);
$db = mysql_select_db($dbname, $connect) or die("Ошибка подключения к БД");
$setnames = mysql_query( 'SET NAMES utf8' );
$clanlist = mysql_query("select cl.tag as tag, cl.name as name, rate, firepower, skill, position,smallimg,alliances.name as aname from clan_info as cl left join alliances on cl.alliansid=alliances.ida  where idc='$idc'",$connect);
if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
$clanrow=mysql_fetch_array($clanlist,MYSQL_ASSOC);
$validclan=0;
$user=0;
if (isset($_COOKIE['user'])){
	$user=$_COOKIE['user'];
	$pl = mysql_query("select name,idc from player  where idp='$user' order by `date` desc",$connect);
	if (mysql_errno() <> 0) echo "MySQL Error ".mysql_errno().": ".mysql_error()."\n";
	$userd=mysql_fetch_array($pl,MYSQL_ASSOC);
	foreach ($clan_array as $clan_i) {
		$idc_temp = $clan_i["clan_id"];
		if ($userd['idc'] == $idc_temp) {
			$validclan=1;
		}
	}
}
?>
</title>
<?php
echo '<link rel="icon" type="image/png" href="'.$clanrow['smallimg'].'" />';
?>
<link type="text/css" href="css/blitzer/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/jquery.jqplot.min.css" />
	<style>
html, body {
	margin: 0;
	padding: 0;
	font-size: 80%;
}
body {

}
    </style>

<script type="text/javascript">
	var current_clan_id = <?php echo $idc ?>;
</script>

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>

<script type="text/javascript" src="js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>

<script type="text/javascript" src="js/jqgrid/js/i18n/grid.locale-ru.js"></script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="js/wot_tables.js"></script>
<script type="text/javascript">
//	jQuery.jgrid.no_legacy_api = true;
</script>
</head>
<body>
<?php if (!isset($_COOKIE['user'])or ($user==0)) {
?>
<div id="login">
 <a href="login.php">Вход</a>
</div>
<?php 
}else{
  echo "Добро пожаловать, ".$userd['name'].". ";

		if ($validclan==0){
			echo " Сожалеем, но вы не являетесь бойцом из нашего альянса. " ; 
		}
?>
<a href="exit.php">Выход</a>
<?php
	}
?>
<header>
	<h1><?php 
	if ($clanrow<>NULL){
		echo '<img src="'.$clanrow['smallimg'].'" style="width: 24px; height:24px;" align="absmiddle"/> ';
		echo "<b>".$clanrow["name"]."</b> [".$clanrow["tag"]."] <b>'".$clanrow["aname"]."'</b>";
		//
		echo " | место № ". $clanrow["position"]." | сила - ".$clanrow["rate"]. " | огн. мощь - ".$clanrow["firepower"]." | скилл - ".$clanrow["skill"];
	}else{
		foreach ($clan_array as $clan_i) {
			$idc_temp = $clan_i["clan_id"];
			if ($idc == $idc_temp) {
				echo $clan_i["clan_tag"];
				echo '  -   ';
				echo $clan_i["clan_name"];
			}
		}
	}
?></h1>
</header>
<nav> 
    <ul>
<?php
           foreach ($clan_array as $clan_i) {
			echo "<li><a href=\"wotstat.php?idc=".$clan_i["clan_id"]."\">".$clan_i["clan_tag"]."</a></li>";
		}
?>	
    </ul>
</nav>

<table align="center"><tr><td valign="top">
<div class="tables">
<div id="tabs">
	<ul>
	    <li><a href="#tab-1">Клан</a></li>
<?php 
	if ($validclan==1){
?>
		<li><a href="#tab-2">Бойцы</a></li>
		<li><a href="#tab-3">Техника</a></li>
		<li><a href="#tab-6">ГК</a></li>
		<li><a href="#tab-8">Графики</a></li>
<?php
	}
?>
	</ul>
	
	<div id="tab-1">
            <table> <td valign="top">  
                <table id="news1"></table>
				<div id="n1pager"></div>
                </td>
				<td valign="top"> 
				<table id="news2"></table>
				<div id="n2pager"></div>
                 </td>
                 <td valign="top">  
				 <table id="news3"></table>
				 <div id="n3pager"></div>
                 </td>
               </table>
			   <div id="stat">
				   <ul>
						<li><a href="#all1">Всё</a></li>
						<li><a href="#clan1">Клановая</a></li>
						<li><a href="#rota1">Ротная</a></li>
						<li><a href="#random1">Рандом(-клан и роты)</a></li>
						
					</ul>
					<div id="all1">
						<table id="all"></table>
					</div>	
					<div id="clan1">
						<table id="clan"></table>
					</div>	
					<div id="rota1">
						<table id="rota"></table>
					</div>	
					<div id="random1">
						<table id="random"></table>
					</div>	
				</div>
		
	</div>
<?php 
	if ($validclan==1){
?>
	<div id="tab-2">
		
		<table>
			<tr valign="top">
				<td>
					<div class="grid" id="pl">
					<table id="players_table"></table>
					
					</div>
				</td>
				<td>
                    <div id="name" style="padding: 10px 0 0 10px"></div>
					<table><tr>
						<td valign="top">
							<table id="pl_summary_table81"></table>
							<div id="pl_summary_pager81"></div>
						</td>
						<td valign="top">
							<table id="pl_summary_table82"></table>
							<div id="pl_summary_pager82"></div>
						</td>
					</tr></table>
					<table id="pl_summary_table2"></table>
					<table id="pl_summary_table"></table>
					<table id="pl_summary_table7"></table>
					<div id="pl_summary_pager7"></div>
					Примечание: * - неверные данные с сервера
					<table id="pl_summary_table6"></table>
					<table id="pl_summary_table5"></table>
					<div id="pl_summary_pager5"></div>
					<table id="pl_summary_table3"></table>
					<div id="pl_summary_pager3"></div>
					<table><tr>
						<td valign="top">
							<table id="pl_summary_table41"></table>
						</td>
						<td valign="top">
							<table id="pl_summary_table42"></table>
						</td>
					</tr></table>
				</td>
			</tr>
		</table>
	</div>
	<div id="tab-3">	
		<table>
			<tr>
				<td valign="top">
					<table id="techABS"></table>
					<div id="techABSpager"></div>	
				</td>
				<td valign="top">
					<table id="techCHM"></table>
					<div id="techCHMpager"></div>	
				</td>
			</tr>			
		</table>
					
	</div>
	<div id="tab-6">
		<table>
			<td valign="top">  
				<table id="wmProvinces"></table>
			</td>
			<td valign="top">  
				<table id="news4"></table>
				<div id="n4pager"></div>
             </td>
		</table>
        <table id="battles1"></table>
		<div id="battles1pager"></div>
     </div>
	<div id="tab-8">
                  <table>
                          <tr valign="top">
                                <td>
                                      <table id="players_table_2"></table>
                                 </td>
                                 <td>
                                        <table><tr> 
                                       <td><div id="chart1"></div></td>
                                       <td><div id="chart2"></div></td>
                                       </tr><tr>
                                       <td><div id="chart3"></div></td>
                                       <td><div id="chart4"></div></td>
                                        </tr></table>
                                 </td>
                           </tr>
               </table>
      </div>
<?php
	}
?>
</div>
</div>



</td>
</tr>

</table>

</body>
</html>
