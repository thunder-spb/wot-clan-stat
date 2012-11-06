<?php include('settings.kak'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>
<?php  
	if (array_key_exists("idc",$_GET)) {
		$idc = $_GET['idc'];
		} else  {
			$idc = "102";
		}
		foreach ($clan_array as $clan_i) {
			$idc_temp = $clan_i["clan_id"];
			if ($idc == $idc_temp) {
				echo $clan_i["clan_tag"];
			}
		}
?>
</title>

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
</style>
<script type="text/javascript" src="js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="js/i18n/grid.locale-ru.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="js/ajaxupload.3.5.js"></script>

<script type="text/javascript">
	jQuery.jgrid.no_legacy_api = true;
</script>
<script type="text/javascript" src="js/jquery.jqGrid.min.js"></script>
<script type="text/javascript" src="js/wot_tables.js"></script>
</head>
<body>
 <a href="https://github.com/thunder-spb/wot-clan-stat">Скачать статистику себе c github'a</a>
<header>
	<h1><?php  
	if (array_key_exists("idc",$_GET)) {
		$idc = $_GET['idc'];
		} else  {
			$idc = "102";
		}
		foreach ($clan_array as $clan_i) {
			$idc_temp = $clan_i["clan_id"];
			if ($idc == $idc_temp) {
				echo $clan_i["clan_tag"];
				echo '  -   ';
				echo $clan_i["clan_name"];
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

<table><tr><td valign="top">
<div class="tables">
<div id="tabs">
	<ul>
	    <li><a href="#tab-1">Клан</a></li>
		<li><a href="#tab-2">Бойцы</a></li>
		<li><a href="#tab-3">Техника</a></li>

		<li><a href="#tab-6">ГК</a></li>
		<li><a href="#tab-7">Техника 2</a></li>
                <li><a href="#tab-8">Графики</a></li>
                <li><a href="#tab-9">Реплеи</a></li>
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
		<table id="all"></table>
		
	</div>
	<div id="tab-2">
		
		<table>
			<tr valign="top">
				<td>
					<div class="grid" id="pl">
					<table id="players_table"></table>
					
					</div>
				</td>
				<td>
                                        <div id="name"></div>
					<div class="grid" id="pls2">
					<table id="pl_summary_table2"></table>
					<div class="grid" id="pls">
					<table id="pl_summary_table"></table>
					<table id="pl_summary_table7"></table>
					<div id="pl_summary_pager7"></div>
					<table id="pl_summary_table6"></table>
					<table id="pl_summary_table5"></table>
					<div id="pl_summary_pager5"></div>

					<div class="grid" id="pls3">
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
                                        <div id="graphs"></div>
					<table id="pl_summary_table8"></table>
				</td>
			</tr>
			<tr valign="top">				
				<td>
					<table id="boysd"></table>
					<div id="boydpager"></div>
				</td>
			</tr>
		</table>
	</div>
	<div id="tab-3">		
		<table id="techABS"></table>
		<div id="techABSpager"></div>	
		<br>
		<table id="techCHM"></table>
		<div id="techCHMpager"></div>	
		<br>
		<table id="techHT"></table>
		<div id="techHTpager"></div>	
		<br>
		<table id="techMT"></table>
		<div id="techMTpager"></div>	
		<br>
		<table id="techLT"></table>
		<div id="techLTpager"></div>	
		<br>
		<table id="techSAU"></table>
		<div id="techSAUpager"></div>
		<br>
		<table id="techAT"></table>
		<div id="techATpager"></div>				
	</div>
	<div id="tab-6">

		<table id="wmProvinces"></table>
                <br>
                <table id="wmWinProvinces"></table>
	</div>
        <div id="tab-7">
                <table id="techABS2"></table>
<table> <tr>
                <td><table id="techCHAMP2"></table></td>
                <td><table id="techMED2"></table></td>
</tr></table>
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
 <div id="tab-9">
             <div id="upload" >Загрузить</div>
             <div id="status" ></div>
      </div>
</div>
</div>



</td>
</tr>

</table>
</body>
</html>
