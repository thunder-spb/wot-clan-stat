var timeoutHnd;
var flAuto = true;

function gup( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}
var plotgraph = 0;
$(function() {
/*	tabs = function(options) {
		
		var defaults = {  
			selector: '.tabs',
			selectedClass: 'selected'
		};  
		
		if(typeof options == 'string') defaults.selector = options;
		var options = $.extend(defaults, options); 
	
		return $(options.selector).each(function(){
									
			var obj = this;	
			var targets = Array();
	
			function show(i){
				$.each(targets,function(index,value){
					$(value).hide();
				})
				$(targets[i]).fadeIn('fast');
				$(obj).children().removeClass(options.selectedClass);
				selected = $(obj).children().get(i);
				$(selected).addClass(options.selectedClass);
			};
	
			$('a',this).each(function(i){	
				targets.push($(this).attr('href'));
				$(this).click(function(e){
					e.preventDefault();
					show(i);
				});
			});
			
			show(0);
	
		});			
	} 
	// initialize the function
	// as a parameter we are sending a selector. For this particular script we must select the unordeblue (or ordeblue) list item element 
	tabs('nav ul');*/

	var idc = current_clan_id;
	if (idc == "") idc = "102"
	
	$( "#tabs" ).tabs({
        show: function(event, ui) { 
                if (ui.panel.id == "tab-8") {
                   drawGraph();     
                }
            }
        });
	$('#link').change(stickTogether);
//--------Новости
	var tnews1 = $("#news1").jqGrid({
		sortable: false,
		altRows: true,
		rowNum:10,
		url:'get_news1.php?idc='+idc,
		datatype: 'json',
		mtype: 'GET',
		postData: {'filterBy':null},
		colNames:['Id','Дата','Событие'],
		colModel :[
			{name:'id_ec', index:'id_ec', width:30, align:"center",sortable:false},
			{name:'date', index:'date', width:70, align:"center",sortable:false},
			{name:'message', index:'message', width:305, align:"left",sortable:false},
			],
		rowTotal: 10,
		pager: '#n1pager',
		scroll: true,
		rowList:[10],
		viewrecords: true,
		//sortname: 'id_ec',
		//sortorder: 'desc',
		width: 410,
		height: 200,
		caption: 'Личный состав',
	});

	var tnews2 = $("#news2").jqGrid({
		sortable: false,
		altRows: true,
		rowNum:10,
		url:'get_news2.php?idc='+idc,
		datatype: 'json',
		mtype: 'GET',
		postData: {'filterBy':null},
		colNames:['Id','Дата','Сообщение'],
		colModel :[
			{name:'id_et', index:'id_et', width:30, align:"center",sortable:false},
			{name:'date', index:'date', width:70, align:"center",sortable:false},
			{name:'message', index:'message', width:305, align:"left",sortable:false},
			],
		rowTotal: 10,
		rowList:[10],
		//sortname: 'role',
		viewrecords: true,
		//sortorder: 'desc',
		width: 410,
		height: 200,
		scroll: true,
		caption: 'Наградная ведомость',
		rownumbers: false,
		rownumWidth: 100,
		pager: '#n2pager',
		//onSelectRow: stickTogetherDMB,
				
	});

	var tnews3 = $("#news3").jqGrid({
		sortable: false,
		altRows: true,
		rowNum:10,
		url:'get_news3.php?idc='+idc,
		datatype: 'json',
		mtype: "GET",
		postData: {'filterBy':null},
		colNames:['Id','Дата','Тип','Ур.','Сообщение'],
		colModel :[
			{name:'id_et', index:'id_et', width:-1, align:"center",sortable:false},
			{name:'date', index:'date', width:50, align:"center",sortable:false},
			{name:'classt', index:'classt', width:20, align:"center",sortable:false},
			{name:'levelt', index:'levelt', width:20, align:"center",sortable:false},
			{name:'message', index:'message', width:0, align:"left",sortable:false},
			],
		rowTotal: 10,
		rowList:[10],
		//sortname: 'role',
		viewrecords: true,
		//sortorder: 'desc',
		width: 410,
		height: 200,
		scroll:true,
		caption: 'Пополнение ангара',
		rownumbers: false,
		rownumWidth: 100,
		pager: '#n3pager'
		//onSelectRow: stickTogetherDMB,
				
	});
		
	//---Клан


	var tall = $("#all").jqGrid({
		sortable: true,
		altRows: true,
		rowNum:200,
		url:'ui_main_json.php?sidx=2&sord=asc&idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['ID','Ник','Боёв','%%','%%-30','РЭ','РЭ-30','WN6','WN6-30','Воин', 'Урон ср.','Опыт.ср','Захватчик', 'Защитник','Светляк','Побед','Фрагов','Опыт','Опыт макс.','Урон'],
		colModel :[			
			{name:'idp', index:'idp', width:33, align:"center"},
			{name:'name', index:'name', width:45, align:"center"},
			{name:'battles_count', index:'battles_count', width:30, align:"center"},
			
			{name:'proc', index:'proc', width:20, align:"center"},
			{name:'win30', index:'win30', width:20, align:"center"},
            {name:'rating', index:'rating', width:25, align:"center"},
			{name:'rating30', index:'rating30', width:25, align:"center"},
			{name:'wn6', index:'wn6', width:25, align:"center"},
			{name:'wn630', index:'wn630', width:25, align:"center"},
			{name:'akillsm', index:'akillsm', width:30, align:"center"},
			{name:'adamagem', index:'adamagem', width:25, align:"center"},
			{name:'battle_avg_xp', index:'battle_avg_xp', width:30, align:"center"},
			{name:'capture_p', index:'capture_p', width:25, align:"center"},
			{name:'dropped_capture_p', index:'dropped_capture_p', width:25, align:"center"},
			{name:'spotted_p', index:'spotted_p', width:25, align:"center"},
			{name:'wins', index:'wins', width:30, align:"center"},
			{name:'frags', index:'frags', width:30, align:"center"},
			{name:'xp', index:'xp', width:40, align:"center"},
			{name:'max_xp', index:'max_xp', width:30, align:"center"},
			
			{name:'damage_dealt', index:'damage_dealt', width:30, align:"center"}
                ],
		pager: '#allpager',
		rowTotal: 100,
		rowList:[100],
		sortname: 'name',
		viewrecords: true,
		//loadonce: true,
		sortorder: 'desc',
		width: 1250,
		height: 450,
		scroll:true,
		caption: 'Общий обзор клана',
		rownumbers: false,
		rownumWidth: 20,
		
		onSortCol: function(name,index,sortorder)
		{	$("#all").jqGrid('setGridParam',{url:"ui_main_json.php?idc="+idc+"&sidx="+(index+1)+"&sord="+sortorder}).trigger("reloadGrid");
			var col=$('#all').jqGrid('getGridParam','colNames');
			var coln=(col[index]);
			$("#all").jqGrid('setCaption',"Общий обзор клана, сортировка по полю '"+coln+"'");
		}, 
	});
	
	//---Бойцы
	var tplayers = $("#players_table").jqGrid({
		sortable: true,
		altRows: true,
		rowNum:100,
		url:'ui_boicy_json.php?sidx=2&sord=asc&idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['i','ID','Ник','Звание','В клане','Offline'],
		colModel :[
			{name:'id_', index:'id_', width:-1, align:"center"},
			{name:'idp', index:'idp', width:30, align:"center"},
			{name:'name', index:'name', width:48, align:"left"},
			{name:'role_localised', index:'role', width:62, align:"left"},
			{name:'mem_since', index:'mem_since', width:24, align:"center"},
			{name:'off', index:'off', width:24, align:"center"}
			],
		pager: '#playerspager',
		rowTotal: 100,
		rowList:[100],
		sortname: 'name',
		viewrecords: true,
		sortorder: 'desc',
		width: 323,
		height: 750,
		scroll: true,
		caption: 'Бойцы клана',
		rownumbers: false,
		rownumWidth: 20,
		onSelectRow: stickTogether,
		onSortCol: function(name,index,sortorder)
		{	
			$("#players_table").jqGrid('setGridParam',{url:"ui_boicy_json.php?sidx="+(index)+"&sord="+sortorder+"&idc="+idc}).trigger("reloadGrid");
			var col=$('#players_table').jqGrid('getGridParam','colNames');
			var coln=(col[index]);
			$("#players_table").jqGrid('setCaption',"Бойцы, сортировка по полю '"+coln+"'");
		}
			
	});
	
	
	//---Бойцы
		
	var tmstat = $("#pl_summary_table").jqGrid({
		sortable: false,
		url:'get_pls01_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Боёв','Побед','%%','Точность %','Фрагов', 'Фр/бой', 'Обнаружено','Опыт.ср','Опыт','Опыт.макс','Захват','Защита','Урон','Урон.ср','name'],
		colModel :[
			{name:'battles_count', index:'battles_count', width:25, align:"center"},
			{name:'wins', index:'wins', width:30, align:"center"},
			{name:'proc', index:'proc', width:20, align:"center"},
			{name:'hits_percents', index:'hits_percents', width:30, align:"center"},
			{name:'frags', index:'frags', width:30, align:"center"},
			{name:'akillsm', index:'akillsm', width:30, align:"center"},
			{name:'spotted', index:'spotted', width:40, align:"center"},
			{name:'battle_avg_xp', index:'battle_avg_xp', width:30, align:"center"},
			{name:'xp', index:'xp', width:35, align:"center"},
			{name:'max_xp', index:'max_xp', width:30, align:"center"},
			{name:'capture_points', index:'capture_points', width:25, align:"center"},
			{name:'dropped_capture_points', index:'dropped_capture_points', width:25, align:"center"},
			{name:'damage_dealt', index:'damage_dealt', width:30, align:"center"},
			{name:'adamagem', index:'adamagem', width:25, align:"center"},
			{name:'name', index:'name', width:-1, align:"right",hidden: "true"}],
		rowNum: 200,
		sortname: 'wins',
		viewrecords: true,
		sortorder: 'desc',
		width: 915,
		height: "100%",
		caption: 'Статистика: Общая /за 30дней /за 7дней',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
	});
	var plnews1 = $("#pl_summary_table81").jqGrid({
		sortable: false,
		altRows: false,
		url:'get_plnews1.php',
		datatype: 'json',
		mtype: 'POST',
		postData: {'filterBy':null},
		colNames:['Id','Дата','Событие'],
		colModel :[
			{name:'id_ec', index:'id_ec', width:30, align:"center",sortable:false},
			{name:'date', index:'date', width:70, align:"center",sortable:false},
			{name:'message', index:'message', width:305, align:"left",sortable:false},
			],
		pager: '#pl_summary_pager81',
		rowNum:10,
		rowList:[10],
		viewrecords: true,
		//sortname: 'id_ec',
		//sortorder: 'desc',
		width: 450,
		height: "100%",
		caption: 'Игровые события',
	});
	var plnews2 = $("#pl_summary_table82").jqGrid({
		sortable: false,
		altRows: false,
		url:'get_plnews2.php',
		datatype: 'json',
		mtype: 'POST',
		postData: {'filterBy':null},
		colNames:['Id','Дата','Тип','Ур.','Сообщение'],
		colModel :[
			{name:'id_et', index:'id_et', width:-1, align:"center",sortable:false},
			{name:'date', index:'date', width:50, align:"center",sortable:false},
			{name:'classt', index:'classt', width:20, align:"center",sortable:false},
			{name:'levelt', index:'levelt', width:20, align:"center",sortable:false},
			{name:'message', index:'message', width:0, align:"left",sortable:false},
			],
		pager: '#pl_summary_pager82',
		rowNum:10,
		rowList:[10],
		viewrecords: true,
		//sortname: 'id_ec',
		//sortorder: 'desc',
		width: 450,
		height: "100%",
		caption: 'Танки',
	});
	var tmstat2 = $("#pl_summary_table2").jqGrid({
		sortable: false,
		url:'get_pls02_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:[' '],
		colModel :[
			{name:'medal', index:'medal', width:910, align:"center"}],
		viewrecords: true,
		width: 915,
		height: 217,
		caption: 'Достижения и медали',
		rownumbers: false,
		rownumWidth: "100%",
		grouping: false,
		toolbar: false,
	});	
		
	var tmstat3 = $("#pl_summary_table3").jqGrid({
		sortable: true,
		altRows: false,
		url:'get_pls03_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Танк', 'Боёв','%%','Уровень','Тип','Страна'],
		colModel :[
			{name:'localized_name', index:'localized_name', width:30, align:"center"},			
			{name:'battle_count', index:'battle_count', width:40, align:"center"},
			{name:'proc', index:'proc', width:30, align:"center"},
			{name:'level', index:'level', width:20, align:"center"},
			{name:'cls', index:'cls', width:30, align:"center"},
			{name:'nation', index:'nation', width:30, align:"center"}			
			],
		pager: '#pl_summary_pager3',
		rowNum: 10,
		rowList:[10,20,30,200],
		sortname: 'level',
		viewrecords: true,
		sortorder: 'desc',
		width: 915,
		height: "100%",
		hiddengrid:true,
		caption: 'Общая статистика по танкам',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		onSortCol: function(name,index,sortorder)
		{	$("#pl_summary_table3").jqGrid('setGridParam',{url:"get_pls03_json.php?sidx="+(index)+"&sord="+sortorder+"&idc="+idc}).trigger("reloadGrid");
			var col=$('#pl_summary_table3').jqGrid('getGridParam','colNames');
			var coln=(col[index]);
			$("#pl_summary_table3").jqGrid('setCaption',"Статистика по танкам, сортировка по полю '"+coln+"'");
		},
		afterInsertRow: function(row_id, row_data){
			if (row_data.proc < 50){
				$('#pl_summary_table3').jqGrid('setCell',row_id,'proc','',{'color':'red'});
			}
			if (row_data.proc > 52){
				$('#pl_summary_table3').jqGrid('setCell',row_id,'proc','',{'color':'green'});
			}
			if (row_data.proc > 55){
				$('#pl_summary_table3').jqGrid('setCell',row_id,'proc','',{'color':'blue'});
			}
			if (row_data.cls == 'SPG'){
				$('#pl_summary_table3').jqGrid('setCell',row_id,'localized_name','',{'background-color':'#ffdab9'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'battle_count','',{'background-color':'#ffdab9'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'proc','',{'background-color':'#ffdab9'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'cls','САУ',{'background-color':'#ffdab9'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'nation','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#pl_summary_table3').jqGrid('setCell',row_id,'localized_name','',{'background-color':'#c6efef'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'battle_count','',{'background-color':'#c6efef'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'proc','',{'background-color':'#c6efef'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'cls','ПТ',{'background-color':'#c6efef'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'nation','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#pl_summary_table3').jqGrid('setCell',row_id,'localized_name','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'battle_count','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'proc','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'cls','СТ',{'background-color':'#d0f0c0'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'nation','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#pl_summary_table3').jqGrid('setCell',row_id,'localized_name','',{'background-color':'#98ff98'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'battle_count','',{'background-color':'#98ff98'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'proc','',{'background-color':'#98ff98'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'cls','ТТ',{'background-color':'#98ff98'});
				$('#pl_summary_table3').jqGrid('setCell',row_id,'nation','',{'background-color':'#98ff98'});
			}
			if (row_data.cls == 'lightTank'){
				$('#pl_summary_table3').jqGrid('setCell',row_id,'cls','ЛТ','');
			}
		},
	});	
	
	var tmstat41 = $("#pl_summary_table41").jqGrid({
		sortable: true,
		altRows: false,
		url:'get_pls041_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Тип', 'Боёв','% побед'],
		colModel :[
			{name:'cls', index:'cls', width:30, align:"center"},
			{name:'b_c', index:'b_c', width:30, align:"center"},
			//{name:'proca', index:'proca', width:30, align:"center"},			
			{name:'proc', index:'proc', width:30, align:"center"}
			],
		//pager: '#pl_summary_pager41',
		rowNum: 10,
		//rowList:[10,20,30,200],
		sortname: 'b_c',
		viewrecords: true,
		sortorder: 'desc',
		width: 450,
		height: "100%",
		hiddengrid:true,
		caption: 'Статистика по типам танков',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		afterInsertRow: function(row_id, row_data){
			if (row_data.proc < 50){
				$('#pl_summary_table41').jqGrid('setCell',row_id,'proc','',{'color':'red'});
			}
			if (row_data.proc > 52){
				$('#pl_summary_table41').jqGrid('setCell',row_id,'proc','',{'color':'green'});
			}
			if (row_data.proc > 55){
				$('#pl_summary_table41').jqGrid('setCell',row_id,'proc','',{'color':'blue'});
			}
			
			if (row_data.cls == 'SPG'){
				$('#pl_summary_table41').jqGrid('setCell',row_id,'proc','',{'background-color':'#ffdab9'});
				//$('#pl_summary_table41').jqGrid('setCell',row_id,'proca','',{'background-color':'#ffdab9'});
				$('#pl_summary_table41').jqGrid('setCell',row_id,'b_c','',{'background-color':'#ffdab9'});
				$('#pl_summary_table41').jqGrid('setCell',row_id,'cls','САУ',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#pl_summary_table41').jqGrid('setCell',row_id,'proc','',{'background-color':'#c6efef'});
				//$('#pl_summary_table41').jqGrid('setCell',row_id,'proca','',{'background-color':'#c6efef'});
				$('#pl_summary_table41').jqGrid('setCell',row_id,'b_c','',{'background-color':'#c6efef'});
				$('#pl_summary_table41').jqGrid('setCell',row_id,'cls','ПТ',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#pl_summary_table41').jqGrid('setCell',row_id,'proc','',{'background-color':'#d0f0c0'});
				//$('#pl_summary_table41').jqGrid('setCell',row_id,'proca','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table41').jqGrid('setCell',row_id,'b_c','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table41').jqGrid('setCell',row_id,'cls','СТ',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#pl_summary_table41').jqGrid('setCell',row_id,'proc','',{'background-color':'#98ff98'});
				//$('#pl_summary_table41').jqGrid('setCell',row_id,'proca','',{'background-color':'#98ff98'});
				$('#pl_summary_table41').jqGrid('setCell',row_id,'b_c','',{'background-color':'#98ff98'});
				$('#pl_summary_table41').jqGrid('setCell',row_id,'cls','ТТ',{'background-color':'#98ff98'});
			}
			if (row_data.cls == 'lightTank'){
				$('#pl_summary_table41').jqGrid('setCell',row_id,'cls','ЛТ','');
			}
		},
	});	
	
	var tmstat42 = $("#pl_summary_table42").jqGrid({
		sortable: true,
		altRows: true,
		url:'get_pls042_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Страна', 'Боёв','% побед'],
		colModel :[
			{name:'nation', index:'nation', width:30, align:"center"},
			{name:'b_c', index:'b_c', width:30, align:"center"},	
			//{name:'proca', index:'proca', width:30, align:"center"},
			{name:'proc', index:'proc', width:30, align:"center"}
			],
		//pager: '#pl_summary_pager41',
		rowNum: 10,
		//rowList:[10,20,30,200],
		sortname: 'b_c',
		viewrecords: true,
		sortorder: 'desc',
		width: 450,
		height: "100%",
		hiddengrid:true,
		caption: 'Статистика по танкам разных стран',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		afterInsertRow: function(row_id, row_data){
			if (row_data.proc < 50){
				$('#pl_summary_table42').jqGrid('setCell',row_id,'proc','',{'color':'red'});
			}
			if (row_data.proc > 52){
				$('#pl_summary_table42').jqGrid('setCell',row_id,'proc','',{'color':'green'});
			}
			if (row_data.proc > 55){
				$('#pl_summary_table42').jqGrid('setCell',row_id,'proc','',{'color':'blue'});
			}
			
		},
	});	
	
	var tmstat5 = $("#pl_summary_table5").jqGrid({
		sortable: false,
		url:'get_pls05_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:[' '],
		colModel :[
			{name:'hangar', index:'hangar', width:910, align:"center"}],
		viewrecords: true,
		width: 915,
		height: 237,
		caption: 'Ангар за 7 дней',
		rownumbers: false,
		rownumWidth: "100%",
		grouping: false,
		toolbar: false,
		scroll: true,
	});
	var tmstat6 = $("#pl_summary_table6").jqGrid({
		sortable: true,
		altRows: false,
		url:'get_pls06_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		hiddengrid:true,
		colNames:['Танк', 'Боёв / Побед (Всего боёв)','%% (за период/ всего)','Дата','Время'],
		colModel :[
			//{name:'cls', index:'cls', width:-1, align:"center"},
			{name:'localized_name', index:'localized_name', width:27, align:"center"},		
			{name:'battle_count', index:'battle_count', width:37, align:"center"},
			{name:'proc', index:'proc', width:27, align:"center"},
			{name:'date', index:'date', width:20, align:"center"},
			{name:'time', index:'time', width:30, align:"center"}			
			],
		pager: '#pl_summary_pager6',
		rowNum: 2000,
		//rowList:[10,20,30,200],
		sortname: 'date',
		viewrecords: true,
		sortorder: 'desc',
		width: 915,
		height: "100%",
		hiddengrid:true,
		caption: 'Развернутая статистика по боям за 30дней',
		rownumbers: false,
		rownumWidth: 40,
		grouping: true,
		groupingView : {
			groupField : ['date'],
			groupColumnShow : [false],
			groupText : ['<b>{0} - {1} шт.</b>'],
			groupCollapse : true,
			groupOrder: ['desc']
		},
	});	
		var tmstat7 = $("#pl_summary_table7").jqGrid({
		sortable: true,
		altRows: false,
		url:'get_pls07_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:[' ','Танк', 'Боёв / Побед (Всего боёв)','%% (за период/ всего)','Обнаружено за бой','Урон за бой','Выжил %','Фрагов за бой'],
		colModel :[
			{name:'cls', index:'cls', width:-1, align:"center",sortable:false},
			{name:'localized_name', index:'localized_name', width:27, align:"center",sortable:false},	
			{name:'maxb_c', index:'maxb_c', width:37, align:"center"},
			{name:'proc', index:'proc', width:27, align:"center"},
                        {name:'procAs', index:'procAs', width:27, align:"center"},
                        {name:'procAD', index:'procAD', width:27, align:"center"},
                        {name:'procAsur', index:'procAsur', width:27, align:"center"},
                        {name:'procAf', index:'procAf', width:27, align:"center"}
			],
		pager: '#pl_summary_pager7',
		rowNum: 10,
		rowList:[10,20,30,200],
		sortname: 'proc',
		viewrecords: true,
		sortorder: 'desc',
		width: 915,
		height: "100%",
		hiddengrid:true,
		caption: 'Общая статистика по боям за 30дней',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		onSortCol: function(name,index,sortorder)
		{	$("#pl_summary_table7").jqGrid('setGridParam',{url:"get_pls07_json.php?sidx="+(index)+"&sord="+sortorder+"&idc="+idc}).trigger("reloadGrid");
			var col=$('#pl_summary_table7').jqGrid('getGridParam','colNames');
			var coln=(col[index]);
			$("#pl_summary_table7").jqGrid('setCaption',"Статистика по танкам за 30 дней, сортировка по полю '"+coln+"'");
		},
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#pl_summary_table7').jqGrid('setCell',row_id,'localized_name','',{'background-color':'#ffdab9'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'maxb_c','',{'background-color':'#ffdab9'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'proc','',{'background-color':'#ffdab9'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAs','',{'background-color':'#ffdab9'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAD','',{'background-color':'#ffdab9'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAsur','',{'background-color':'#ffdab9'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAf','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#pl_summary_table7').jqGrid('setCell',row_id,'localized_name','',{'background-color':'#c6efef'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'maxb_c','',{'background-color':'#c6efef'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'proc','',{'background-color':'#c6efef'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAs','',{'background-color':'#c6efef'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAD','',{'background-color':'#c6efef'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAsur','',{'background-color':'#c6efef'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAf','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#pl_summary_table7').jqGrid('setCell',row_id,'localized_name','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'maxb_c','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'proc','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAs','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAD','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAsur','',{'background-color':'#d0f0c0'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAf','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#pl_summary_table7').jqGrid('setCell',row_id,'localized_name','',{'background-color':'#98ff98'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'maxb_c','',{'background-color':'#98ff98'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'proc','',{'background-color':'#98ff98'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAs','',{'background-color':'#98ff98'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAD','',{'background-color':'#98ff98'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAsur','',{'background-color':'#98ff98'});
				$('#pl_summary_table7').jqGrid('setCell',row_id,'procAf','',{'background-color':'#98ff98'});
			}
		}
	});	
	tmstat9 = $('#cn').jqGrid({
		sortable: false,
		altRows: true,
		url:'get_cnews_json.php',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Дата','Событие'],
		colModel :[
			{name:'date', index:'date', width:30, align:"center"},	
			{name:'message', index:'message', width:148, align:"left"}
			
			],
		pager: '#cn_pager',
		sortname: 'date',
		viewrecords: true,
		sortorder: 'desc',
		width: 340,
		height: 137,
		rowNum: 100,
		//hiddengrid:true,
		caption: ' ',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		
	});
	tmstat10 = $('#techABS').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_pls09_json.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','Танк','Ур.','Шт.','в Ангаре у бойцов ( %% / всего боёв )'],
		colModel :[
			{name:'cls', index:'cls', width:-1, align:"left"},
			{name:'col1', index:'col1', width:40, align:"center"},
			{name:'col2', index:'col2', width:50, align:"center"},
			{name:'level', index:'level', width:20, align:"center"},
			{name:'col3', index:'col3', width:20, align:"center"},
			{name:'col4', index:'col4', width:600, align:"left"}
												
			],
		pager: '#techABSpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 1250,
		height: "100%",
		rowNum: 100,
		hiddengrid:true,
		caption: 'Абсолют / Глобалка',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: true,
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techABS').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'col4','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#techABS').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'col4','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#techABS').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'col4','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#techABS').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'col4','',{'background-color':'#98ff98'});
			}
		}
		
	});
	
	tmstat10 = $('#techCHM').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_pls10_json.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','Танк','Ур.','Шт.','в Ангаре у бойцов ( %% / всего боёв )'],
		colModel :[
			{name:'cls', index:'cls', width:-1, align:"left"},
			{name:'col1', index:'col1', width:40, align:"center"},
			{name:'col2', index:'col2', width:50, align:"center"},
			{name:'level', index:'level', width:20, align:"center"},
			{name:'col3', index:'col3', width:20, align:"center"},
			{name:'col4', index:'col4', width:600, align:"left"}
												
			],
		pager: '#techCHMpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 1250,
		height: "100%",
		rowNum: 100,
		hiddengrid:true,
		caption: 'Чемпионка',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: true,
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techCHM').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'col4','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#techCHM').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'col4','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#techCHM').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'col4','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#techCHM').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'col4','',{'background-color':'#98ff98'});
			}
		}
		
	});
	tmstat10 = $('#techHT').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_tech01.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','Танк','Ур.','Шт.','в Ангаре у бойцов ( %% / всего боёв )'],
		colModel :[
			{name:'cls', index:'cls', width:-1, align:"left"},
			{name:'col1', index:'col1', width:40, align:"center"},
			{name:'col2', index:'col2', width:50, align:"center"},
			{name:'level', index:'level', width:20, align:"center"},
			{name:'col3', index:'col3', width:20, align:"center"},
			{name:'col4', index:'col4', width:600, align:"left"}
												
			],
		pager: '#techHTpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 1250,
		height: "100%",
		rowNum: 100,
		hiddengrid:true,
		caption: '<img src="images/icons/ht.png" style="width: 16px; height:15px;" align="absmiddle"/> Тяжёлые танки',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: true,
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techHT').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techHT').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techHT').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techHT').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techHT').jqGrid('setCell',row_id,'col4','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#techHT').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techHT').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techHT').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techHT').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techHT').jqGrid('setCell',row_id,'col4','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#techHT').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techHT').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techHT').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techHT').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techHT').jqGrid('setCell',row_id,'col4','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#techHT').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techHT').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techHT').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techHT').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techHT').jqGrid('setCell',row_id,'col4','',{'background-color':'#98ff98'});
			}
		}
		
	});
	tmstat10 = $('#techMT').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_tech02.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','Танк','Ур.','Шт.','в Ангаре у бойцов ( %% / всего боёв )'],
		colModel :[
			{name:'cls', index:'cls', width:-1, align:"left"},
			{name:'col1', index:'col1', width:40, align:"center"},
			{name:'col2', index:'col2', width:50, align:"center"},
			{name:'level', index:'level', width:20, align:"center"},
			{name:'col3', index:'col3', width:20, align:"center"},
			{name:'col4', index:'col4', width:600, align:"left"}
												
			],
		pager: '#techMTpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 1250,
		height: "100%",
		rowNum: 100,
		hiddengrid:true,
		caption: '<img src="images/icons/mt.png" style="width: 16px; height:15px;" align="absmiddle"/> Средние танки',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: true,
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techMT').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techMT').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techMT').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techMT').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techMT').jqGrid('setCell',row_id,'col4','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#techMT').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techMT').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techMT').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techMT').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techMT').jqGrid('setCell',row_id,'col4','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#techMT').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techMT').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techMT').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techMT').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techMT').jqGrid('setCell',row_id,'col4','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#techHT').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techHT').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techHT').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techHT').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techHT').jqGrid('setCell',row_id,'col4','',{'background-color':'#98ff98'});
			}
		}
		
	});	
	tmstat10 = $('#techLT').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_tech03.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','Танк','Ур.','Шт.','в Ангаре у бойцов ( %% / всего боёв )'],
		colModel :[
			{name:'cls', index:'cls', width:-1, align:"left"},
			{name:'col1', index:'col1', width:40, align:"center"},
			{name:'col2', index:'col2', width:50, align:"center"},
			{name:'level', index:'level', width:20, align:"center"},
			{name:'col3', index:'col3', width:20, align:"center"},
			{name:'col4', index:'col4', width:600, align:"left"}
												
			],
		pager: '#techLTpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 1250,
		height: "100%",
		rowNum: 100,
		hiddengrid:true,
		caption: '<img src="images/icons/lt.png" style="width: 16px; height:15px;" align="absmiddle"/> Лёгкие танки',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: true,
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techLT').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techLT').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techLT').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techLT').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techLT').jqGrid('setCell',row_id,'col4','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#techLT').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techLT').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techLT').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techLT').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techLT').jqGrid('setCell',row_id,'col4','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#techLT').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techLT').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techLT').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techLT').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techLT').jqGrid('setCell',row_id,'col4','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#techLT').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techLT').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techLT').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techLT').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techLT').jqGrid('setCell',row_id,'col4','',{'background-color':'#98ff98'});
			}
		}
		
	});
	tmstat10 = $('#techSAU').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_tech04.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','Танк','Ур.','Шт.','в Ангаре у бойцов ( %% / всего боёв )'],
		colModel :[
			{name:'cls', index:'cls', width:-1, align:"left"},
			{name:'col1', index:'col1', width:40, align:"center"},
			{name:'col2', index:'col2', width:50, align:"center"},
			{name:'level', index:'level', width:20, align:"center"},
			{name:'col3', index:'col3', width:20, align:"center"},
			{name:'col4', index:'col4', width:600, align:"left"}
												
			],
		pager: '#techSAUpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 1250,
		height: "100%",
		rowNum: 100,
		hiddengrid:true,
		caption: '<img src="images/icons/spg.png" style="width: 16px; height:15px;" align="absmiddle"/> САУ',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: true,
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techSAU').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techSAU').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techSAU').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techSAU').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techSAU').jqGrid('setCell',row_id,'col4','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#techSAU').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techSAU').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techSAU').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techSAU').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techSAU').jqGrid('setCell',row_id,'col4','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#techSAU').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techSAU').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techSAU').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techSAU').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techSAU').jqGrid('setCell',row_id,'col4','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#techSAU').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techSAU').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techSAU').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techSAU').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techSAU').jqGrid('setCell',row_id,'col4','',{'background-color':'#98ff98'});
			}
		}
		
	});
	tmstat10 = $('#techAT').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_tech05.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','Танк','Ур.','Шт.','в Ангаре у бойцов ( %% / всего боёв )'],
		colModel :[
			{name:'cls', index:'cls', width:-1, align:"left"},
			{name:'col1', index:'col1', width:40, align:"center"},
			{name:'col2', index:'col2', width:50, align:"center"},
			{name:'level', index:'level', width:20, align:"center"},
			{name:'col3', index:'col3', width:20, align:"center"},
			{name:'col4', index:'col4', width:600, align:"left"}
												
			],
		pager: '#techATpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 1250,
		height: "100%",
		rowNum: 100,
		hiddengrid:true,
		caption: '<img src="images/icons/at.png" style="width: 16px; height:15px;" align="absmiddle"/> ПТ-САУ',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: true,
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techAT').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techAT').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techAT').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techAT').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techAT').jqGrid('setCell',row_id,'col4','',{'background-color':'#ffdab9'});
			}
			if (row_data.cls == 'AT-SPG'){
				$('#techAT').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techAT').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techAT').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techAT').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techAT').jqGrid('setCell',row_id,'col4','',{'background-color':'#c6efef'});
			}
			if (row_data.cls == 'mediumTank'){
				$('#techAT').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techAT').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techAT').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techAT').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techAT').jqGrid('setCell',row_id,'col4','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
				$('#techAT').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techAT').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techAT').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techAT').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techAT').jqGrid('setCell',row_id,'col4','',{'background-color':'#98ff98'});
			}
		}
		
	});
	var twm1 = $('#wmProvinces').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_wm1_json.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Тип','?','Название','Карта','Прайм-тайм','Доход','Время владения'],
		colModel :[
			{name:'type', index:'type', width:40, align:"center"},
			{name:'status', index:'status', width:25, align:"center"},
			{name:'name', index:'name', width:200, align:"left"},
			{name:'map', index:'map', width:140, align:"left"},
			{name:'prime_time', index:'prime_time', width:100, align:"center"},
			{name:'revenue', index:'revenue', width:60, align:"left"},
			{name:'occ_time', index:'occ_time', width:140, align:"center"}
												
			],
		pager: '#wmProvPager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 500,
		height: "100%",
		rowNum: 100,
		//hiddengrid:true,
		caption: 'Владения на ГК',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: false
		
	});
/*
 * 19.11.2012 / thunder
 * commented out since we do not have such file
 * 
 * 
	var twm2 = $('#wmWinProvinces').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_wm2_json.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Название','Результат','Карта','Дата'],
		colModel :[
			{name:'name', index:'name', width:200, align:"center"},
			{name:'result', index:'result', width:200, align:"left"},
			{name:'map', index:'map', width:140, align:"left"},
			{name:'date', index:'date', width:100, align:"left"}
												
			],
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 500,
		height: "100%",
		rowNum: 100,
		//hiddengrid:true,
		caption: 'Изменения Владений на ГК',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: false
		
	});
*/
	var tmstat20 = $('#techABS2').jqGrid({
		sortable: true,
		altRows: true,
		url:'get_techABS2_json.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Ник','ИС 7','T110E5','ИС 4','Bat Chatillon 25 t','T62A','M48A1','E-100','Maus','T110E4','Объект 261','GW Typ E','T92','Bat Chatillon 155'],
		colModel :[
			{name:'name', index:'name', width:200, align:"center"},
			{name:'IS-7', index:'IS-7', width:80, align:"center"},
			{name:'T110', index:'T110', width:80, align:"center"},
			{name:'IS-4', index:'IS-4', width:80, align:"center"},
			{name:'Bat_Chatillon25t', index:'Bat_Chatillon25t', width:80, align:"center"},
			{name:'T62A', index:'T62A', width:80, align:"center"},
			{name:'M48A1', index:'M48A1', width:80, align:"center"},
			{name:'E-100', index:'E-100', width:80, align:"center"},
			{name:'Maus', index:'Maus', width:80, align:"center"},
			{name:'T110E4', index:'T110E4', width:80, align:"center"},
			{name:'Object_261', index:'Object_261', width:80, align:"center"},
			{name:'G_E', index:'G_E', width:80, align:"center"},
			{name:'T92', index:'T92', width:80, align:"center"},
			{name:'Bat_Chatillon155', index:'Bat_Chatillon155', width:80, align:"center"}
												
			],
		sortname: 'nick',
		viewrecords: true,
		sortorder: 'desc',
		width: 1250,
		height: "100%",
		rowNum: 100,
		//hiddengrid:true,
		caption: 'Техника ГК',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: false,
                hiddengrid:true,
                onSortCol: function(name,index,sortorder)
		{	$("#techABS2").jqGrid('setGridParam',{url:"get_techABS2_json.php?idc="+idc+"&sidx="+(index)+"&sord="+sortorder}).trigger("reloadGrid");
		}
		
	});

	var tmstat21 = $('#techCHAMP2').jqGrid({
		sortable: true,
		altRows: true,
		url:'get_techCHAMP2_json.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Ник','ИС 3','T32','AMX 13 90','GW Panther','Lorraine 155 50'],
		colModel :[
			{name:'name', index:'name', width:200, align:"center"},
			{name:'IS-3', index:'IS-3', width:80, align:"center"},
			{name:'T32', index:'T32', width:80, align:"center"},
			{name:'AMX_13_90', index:'AMX_13_90', width:80, align:"center"},
			{name:'G_Panther', index:'G_Panther', width:80, align:"center"},
			{name:'Lorraine155_50', index:'Lorraine155_50', width:80, align:"center"}
			],
		sortname: 'name',
		viewrecords: true,
		sortorder: 'desc',
		width: 650,
		height: "100%",
		rowNum: 100,
		caption: 'Техника чемпионских рот',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		toolbar: false,
		scroll: false,
                hiddengrid:true,
                onSortCol: function(name,index,sortorder)
		{	$("#techCHAMP2").jqGrid('setGridParam',{url:"get_techCHAMP2_json.php?idc="+idc+"&sidx="+(index)+"&sord="+sortorder}).trigger("reloadGrid");
		}
		
	});
var tplayers = $("#players_table_2").jqGrid({
		sortable: true,
		altRows: true,
		rowNum:100,
		url:'ui_boicy_json.php?sidx=2&sord=asc&idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['i','ID','Ник','Звание','В клане','Offline'],
		colModel :[
			{name:'id_', index:'id_', width:-1, align:"center"},
			{name:'idp', index:'idp', width:30, align:"center"},
			{name:'name', index:'name', width:48, align:"left"},
			{name:'role_localised', index:'role', width:32, align:"left"},
			{name:'mem_since', index:'mem_since', width:32, align:"center"},
			{name:'off', index:'off', width:32, align:"center"}
			],
		pager: '#playerspager',
		rowTotal: 100,
		rowList:[100],
		sortname: 'name',
		viewrecords: true,
		sortorder: 'desc',
		width: 360,
		height: 750,
		scroll: true,
		caption: 'Бойцы клана',
		rownumbers: false,
		rownumWidth: 20,
                onSelectRow: drawGraph,
		onSortCol: function(name,index,sortorder)
		{	
			$("#players_table_2").jqGrid('setGridParam',{url:"ui_boicy_json.php?sidx="+(index)+"&sord="+sortorder+"&idc="+idc}).trigger("reloadGrid");
			var col=$('#players_table_2').jqGrid('getGridParam','colNames');
			var coln=(col[index]);
			$("#players_table_2").jqGrid('setCaption',"Бойцы, сортировка по полю '"+coln+"'");
		}
			
	});

$(function(){
var btnUpload=$('#upload');
var status=$('#status');
new AjaxUpload(btnUpload, {
action: 'upload.php',
//Имя файлового поля ввода
name: 'uploadfile',
onSubmit: function(file, ext){
if (! (ext && /^(wotreplay)$/.test(ext))){
// Валидация расширений файлов
status.text('Только wotreplay файлы');
return false;
 
}
status.text('Загрузка...');
},
onComplete: function(file, response){
//Очищаем текст статуса
status.text(response);
//Добавляем загруженные файлы в лист
}
});
});


});

$("#pl_summary_table3").jqGrid('navGrid','#pl_summary_pager3',{edit:false,add:false,del:false});

function gridReload(){
var startdate = $("#startdate").val();
var enddate = $("#enddate").val();
var nm_mask = jQuery("#search_name").val();
var id_mask = jQuery("#search_id").val();
}

function enableAutosubmit(state){
	flAuto = state;
	jQuery("#submitButton").attr("disabled",state);
}

function doSearch(ev){
	if(!flAuto)
		return;
//	var elem = ev.target||ev.srcElement;
	if(timeoutHnd)
		clearTimeout(timeoutHnd)
	timeoutHnd = setTimeout(gridReload,500)
}
function drawGraph() {
        var masterId        = $('#players_table_2');
        var masterPostData  = $(masterId).jqGrid('getGridParam','postData');
        var selId = $(masterId).jqGrid('getGridParam','selrow');
	var selI = $(masterId).jqGrid('getCell',selId,'id_');
        var ajaxDataRenderer = function(url, plot, options) {
		var ret = null;
		$.ajax({
		// have to use synchronous here, else the function
		// will return before the data is fetched
			async: false,
                        type:"POST",
                        data:"filterBy="+selI,
			url: url,
			dataType:"json",
			success: function(data) {
				ret = data;
			}
		});
		return ret;
	}; 
       $('#chart1').empty();
       $('#chart2').empty();
       $('#chart3').empty();
       $('#chart4').empty();
 $.jqplot('chart1', "chart1_json.php" ,
		{ title:'Процент побед',
		dataRenderer: ajaxDataRenderer,
                axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer}},
                seriesDefaults: {
                           showMarker:false
                }
	});
 $.jqplot('chart2', "chart2_json.php" ,
		{ title:'Рейтинг Эффективности',
		dataRenderer: ajaxDataRenderer,
                axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer}},
                seriesDefaults: {
                           showMarker:false
                }
	});
 $.jqplot('chart3', "chart3_json.php" ,
		{ title:'Фрагов за бой',
		dataRenderer: ajaxDataRenderer,
                axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer}},
                seriesDefaults: {
                           showMarker:false
                }
	});
 $.jqplot('chart4', "chart4_json.php" ,
		{ title:'Урон за бой',
		dataRenderer: ajaxDataRenderer,
                axes:{xaxis:{renderer:$.jqplot.DateAxisRenderer}},
                seriesDefaults: {
                           showMarker:false
                }
	});
} 


function stickTogether(){
        var masterId        = $('#players_table');
        var masterPostData  = $(masterId).jqGrid('getGridParam','postData');
        var selId = $(masterId).jqGrid('getGridParam','selrow');
		var selI = $(masterId).jqGrid('getCell',selId,'id_');
         var selName =$(masterId).jqGrid('getCell',selId,'name');
         $('#name').html('<h1>'+selName+'</h1>');
      
        if(masterPostData.filterBy){
            $(masterId).jqGrid('setGridParam',{'postData':{'filterBy':null}});
            $(masterId).trigger('reloadGrid');
        }
        var slaveId   = $('#pl_summary_table'); 
		var slaveId2  = $('#pl_summary_table2'); 
		var slaveId3  = $('#pl_summary_table3'); 
		var slaveId41  = $('#pl_summary_table41'); 
		var slaveId42  = $('#pl_summary_table42');
		var slaveId5  = $('#pl_summary_table5');
		var slaveId6  = $('#pl_summary_table6');
		var slaveId7  = $('#pl_summary_table7');
		var slaveId8  = $('#pl_summary_table8');
		var slaveId81  = $('#pl_summary_table81');
		var slaveId82  = $('#pl_summary_table82');
        $(slaveId).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId2).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
        $(slaveId3).jqGrid('setGridParam',{'postData':{'filterBy':selI}});    
		$(slaveId41).jqGrid('setGridParam',{'postData':{'filterBy':selI}}); 
		$(slaveId42).jqGrid('setGridParam',{'postData':{'filterBy':selI}}); 
		$(slaveId5).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId6).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId7).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId8).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId81).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId82).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
        $(slaveId).trigger('reloadGrid');  
		$(slaveId2).trigger('reloadGrid');
		$(slaveId3).trigger('reloadGrid');
		$(slaveId41).trigger('reloadGrid');
		$(slaveId42).trigger('reloadGrid');
		$(slaveId5).trigger('reloadGrid');
		$(slaveId6).trigger('reloadGrid');
		$(slaveId7).trigger('reloadGrid');
		$(slaveId8).trigger('reloadGrid');
		$(slaveId81).trigger('reloadGrid');
		$(slaveId82).trigger('reloadGrid');
}

function stickTogetherDMB(){
        var masterId        = $('#players_dmb_table');
        var masterPostData  = $(masterId).jqGrid('getGridParam','postData');
        var selId = $(masterId).jqGrid('getGridParam','selrow');
		var selI = $(masterId).jqGrid('getCell',selId,'id_');
        if(masterPostData.filterBy){
            $(masterId).jqGrid('setGridParam',{'postData':{'filterBy':null}});
            $(masterId).trigger('reloadGrid');
        }
        var slaveId   = $('#pl_summary_table'); 
		var slaveId2  = $('#pl_summary_table2'); 
		var slaveId3  = $('#pl_summary_table3'); 
		var slaveId41  = $('#pl_summary_table41'); 
		var slaveId42  = $('#pl_summary_table42');
		var slaveId5  = $('#pl_summary_table5');
		var slaveId6  = $('#pl_summary_table6');
		var slaveId7  = $('#pl_summary_table7');
		var slaveId8  = $('#pl_summary_table8');
        $(slaveId).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId2).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
        $(slaveId3).jqGrid('setGridParam',{'postData':{'filterBy':selI}});    
		$(slaveId41).jqGrid('setGridParam',{'postData':{'filterBy':selI}}); 
		$(slaveId42).jqGrid('setGridParam',{'postData':{'filterBy':selI}}); 
		$(slaveId5).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId6).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId7).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
		$(slaveId8).jqGrid('setGridParam',{'postData':{'filterBy':selI}});
        $(slaveId).trigger('reloadGrid');  
		$(slaveId2).trigger('reloadGrid');
		$(slaveId3).trigger('reloadGrid');
		$(slaveId41).trigger('reloadGrid');
		$(slaveId42).trigger('reloadGrid');
		$(slaveId5).trigger('reloadGrid');
		$(slaveId6).trigger('reloadGrid');
		$(slaveId7).trigger('reloadGrid');
		$(slaveId8).trigger('reloadGrid');
}

