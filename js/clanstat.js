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

	var idc = current_clan_id;
	if (idc == "") idc = "102"
	
	$( "#tabs" ).tabs({
        show: function(event, ui) { 
                
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
			{name:'id_ec', index:'id_ec', width:-2, align:"center",hidden:true,sortable:false},
			{name:'date', index:'date', width:60, align:"center",sortable:false},
			{name:'message', index:'message', width:240, align:"left",sortable:false},
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
			{name:'id_et', index:'id_et', width:-2, align:"center",hidden:true,sortable:false},
			{name:'date', index:'date', width:60, align:"center",sortable:false},
			{name:'classt', index:'classt', width:20, align:"center",sortable:false},
			{name:'levelt', index:'levelt', width:20, align:"center",sortable:false},
			{name:'message', index:'message', width:225, align:"left",sortable:false},
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
	var tnews4 = $("#news4").jqGrid({
		sortable: false,
		altRows: true,
		rowNum:10,
		url:'get_news4.php?idc='+idc,
		datatype: 'json',
		mtype: "GET",
		postData: {'filterBy':null},
		colNames:['Id','Дата','Сообщение'],
		colModel :[
			{name:'id_e', index:'id_e', width:-2, align:"center",hidden:true,sortable:false},
			{name:'date', index:'date', width:90, align:"center",sortable:false},
			{name:'message', index:'message', width:355, align:"left",sortable:false},
			],
		rowTotal: 10,
		rowList:[10],
		//sortname: 'role',
		viewrecords: true,
		//sortorder: 'desc',
		width: 540,
		height: 200,
		scroll:true,
		caption: 'События ГК',
		rownumbers: false,
		rownumWidth: 100,
		pager: '#n4pager'
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
			{name:'win30', index:'win30', width:-1, align:"center",hidden:true},
            {name:'rating', index:'rating', width:25, align:"center"},
			{name:'rating30', index:'rating30', width:-1, align:"center",hidden:true},
			{name:'wn6', index:'wn6', width:25, align:"center"},
			{name:'wn630', index:'wn630', width:-1, align:"center",hidden:true},
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
		sortname: 'name',
		viewrecords: true,
		//loadonce: true,
		sortorder: 'desc',
		width: 1252,
		height: '100%',
		caption: 'Общий обзор клана',
		onSortCol: function(name,index,sortorder)
		{	$("#all").jqGrid('setGridParam',{url:"ui_main_json.php?idc="+idc+"&sidx="+(index+1)+"&sord="+sortorder}).trigger("reloadGrid");
			var col=$('#all').jqGrid('getGridParam','colNames');
			var coln=(col[index]);
			$("#all").jqGrid('setCaption',"Общий обзор клана, сортировка по полю '"+coln+"'");
		}, 
	});

	tmstat9 = $('#techABS').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_pls09_json.php?idc='+idc+'&level=10',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','','','Танк','Ур.','Шт.'],
		colModel :[
		    {name:'wotidt', index:'wotidt', width:40, align:"center",sortable:false,key:true,hidden:true},	
			{name:'cls', index:'cls', width:-1, align:"left",sortable:false,hidden:true},
			{name:'nation', index:'nation', width:30,sortable:false, align:"center"},
			{name:'img', index:'img', width:50,sortable:false, align:"center"},
			{name:'col2', index:'col2', width:50,sortable:false, align:"center"},
			{name:'level', index:'level', width:20,sortable:false, align:"center",hidden:true},
			{name:'col3', index:'col3', width:20,sortable:false, align:"center"},
			
												
			],
		pager: '#techABSpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 620,
		height: 'auto',
		rowNum: 100,
		caption: 'Абсолют / Глобалка',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		
		toolbar: false,
		//scroll: true,
		subGrid : true,
		subGridRowExpanded: function(subgrid_id, row_id) { 
			// we pass two parameters 
			// subgrid_id is a id of the div tag created whitin a table data 
			// the id of this elemenet is a combination of the "sg_" + id of the row 
			// the row_id is the id of the row 
			// If we wan to pass additinal parameters to the url we can use 
			// a method getRowData(row_id) - which returns associative array in type name-value 
			// here we can easy construct the flowing 
			var subgrid_table_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>"); 
			jQuery("#"+subgrid_table_id).jqGrid({ 
				url:"techsubgrid.php?idc="+idc+"&wotidt="+row_id+"&role="+role, 
				datatype: "json", 
				colNames: ['No','','','Имя','Боёв','%%','Активен'], 
				altRows: true,
				colModel: [ 
					{name:"id",index:"id",width:55,key:true,sortable:false}, 
					{name:"wotidt",index:"wotidt",hidden:true,editable: true}, 
					{name:"idp",index:"idp",hidden:true,editable: true}, 
					{name:"name",index:"name",width:200}, 
					{name:"battle_count",index:"battle_count",width:80,align:"right"}, 
					{name:"proc",index:"proc",width:80,align:"right"}, 
					{name:"garage",index:"garage",width:80,align:"center",sortable:false},
				], 
				rowNum:100, 
				sortname: 'name', 
				sortorder: "asc", 
				height: 'auto',
				
				// onSelectRow: function(name){ 
				    // if((role==='vice_leader' || role==='diplomat' || role==='recruiter'|| role==='cammander')&&(useridc===idc)){
						 // if(name && name!==lastsel2){ 
							// jQuery("#"+subgrid_table_id).jqGrid('saveRow',lastsel2,false);
							// jQuery("#"+subgrid_table_id).jqGrid('editRow',name,true);
							// lastsel2=name; 
						// }
					// }
				// },
				
				
				
			});
			if((role==='recruit')){
				jQuery("#"+subgrid_table_id).hideCol("garage").trigger("reloadGrid");
			}
		}, 
		subGridRowColapsed: function(subgrid_id, row_id) { 
			// this function is called before removing the data 
			//var subgrid_table_id; 
			//subgrid_table_id = subgrid_id+"_t"; 
			//jQuery("#"+subgrid_table_id).remove(); 
		},
		
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techABS').jqGrid('setCell',row_id,'img','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'nation','',{'background-color':'#ffdab9'});
				$('#techABS').jqGrid('setCell',row_id,'subgrid','',{'background-color':'#ffdab9'});
			}	
			if (row_data.cls == 'AT-SPG'){
			$('#techABS').jqGrid('setCell',row_id,'img','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'nation','',{'background-color':'#c6efef'});
				$('#techABS').jqGrid('setCell',row_id,'subgrid','',{'background-color':'#c6efef'});
			}	
			if (row_data.cls == 'mediumTank'){
			   $('#techABS').jqGrid('setCell',row_id,'img','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'nation','',{'background-color':'#d0f0c0'});
				$('#techABS').jqGrid('setCell',row_id,'subgrid','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
		    	$('#techABS').jqGrid('setCell',row_id,'img','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'nation','',{'background-color':'#98ff98'});
				$('#techABS').jqGrid('setCell',row_id,'subgrid','',{'background-color':'#98ff98'});
			}
		}
		
	}
	);
	

	tmstat10 = $('#techCHM').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_pls09_json.php?idc='+idc+'&level=8',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['','','','','Танк','Ур.','Шт.'],
		colModel :[
		    {name:'wotidt', index:'wotidt', width:40,sortable:false, align:"center",key:true,hidden:true},	
			{name:'cls', index:'cls', width:-1,sortable:false, align:"left",hidden:true},
			{name:'nation', index:'nation',sortable:false, width:30, align:"center"},
			{name:'img', index:'img', width:50,sortable:false, align:"center"},
			{name:'col2', index:'col2', width:50,sortable:false, align:"center"},
			{name:'level', index:'level', width:20,sortable:false, align:"center",hidden:true},
			{name:'col3', index:'col3', width:20,sortable:false, align:"center"},
			
												
			],
		pager: '#techCHMpager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 620,
		height: 'auto',
		rowNum: 100,
		caption: 'Чемпионка',
		rownumbers: false,
		rownumWidth: 40,
		grouping: false,
		
		toolbar: false,
		//scroll: true,
		subGrid : true,
		// subGridUrl : 'techsubgrid.php?idc='+idc,
		// subGridModel : [ 
			// {
				// name  : ['№', 'Имя', 'Боёв', '%%'],
				// width : [55, 200, 80, 80, 80],
				// align : ['left','left','right','right','right'],
				// params:['wotidt'],
			// }
		// ],
		subGridRowExpanded: function(subgrid_id, row_id) { 
			// we pass two parameters 
			// subgrid_id is a id of the div tag created whitin a table data 
			// the id of this elemenet is a combination of the "sg_" + id of the row 
			// the row_id is the id of the row 
			// If we wan to pass additinal parameters to the url we can use 
			// a method getRowData(row_id) - which returns associative array in type name-value 
			// here we can easy construct the flowing 
			var subgrid_table_id; 
			subgrid_table_id = subgrid_id+"_t"; 
			$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table>"); 
			jQuery("#"+subgrid_table_id).jqGrid({ 
				url:"techsubgrid.php?idc="+idc+"&wotidt="+row_id, 
				datatype: "json", 
				colNames: ['No','','','Имя','Боёв','%%','Активен'], 
				altRows: true,
				colModel: [ 
					{name:"id",index:"id",width:55,key:true,sortable:false}, 
					{name:"wotidt",index:"wotidt",hidden:true,editable: true}, 
					{name:"idp",index:"idp",hidden:true,editable: true}, 
					{name:"name",index:"name",width:200}, 
					{name:"battle_count",index:"battle_count",width:80,align:"right"}, 
					{name:"proc",index:"proc",width:80,align:"right"}, 
					{name:"garage",index:"garage",width:80,align:"center",sortable:false},
				], 
				rowNum:100, 
				sortname: 'name', 
				sortorder: "asc", 
				height: 'auto',
								
			});
			if((role==='recruit')){
				jQuery("#"+subgrid_table_id).hideCol("garage").trigger("reloadGrid");
			}
		}, 
		subGridRowColapsed: function(subgrid_id, row_id) { 
			// this function is called before removing the data 
			//var subgrid_table_id; 
			//subgrid_table_id = subgrid_id+"_t"; 
			//jQuery("#"+subgrid_table_id).remove(); 
		},
		afterInsertRow: function(row_id, row_data){
			if (row_data.cls == 'SPG'){
				$('#techCHM').jqGrid('setCell',row_id,'img','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'col1','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'col2','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'level','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'col3','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'nation','',{'background-color':'#ffdab9'});
				$('#techCHM').jqGrid('setCell',row_id,'subgrid','',{'background-color':'#ffdab9'});
			}	
			if (row_data.cls == 'AT-SPG'){
			$('#techCHM').jqGrid('setCell',row_id,'img','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'col1','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'col2','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'level','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'col3','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'nation','',{'background-color':'#c6efef'});
				$('#techCHM').jqGrid('setCell',row_id,'subgrid','',{'background-color':'#c6efef'});
			}	
			if (row_data.cls == 'mediumTank'){
			   $('#techCHM').jqGrid('setCell',row_id,'img','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'col1','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'col2','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'level','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'col3','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'nation','',{'background-color':'#d0f0c0'});
				$('#techCHM').jqGrid('setCell',row_id,'subgrid','',{'background-color':'#d0f0c0'});
			}
			if (row_data.cls == 'heavyTank'){
		    	$('#techCHM').jqGrid('setCell',row_id,'img','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'col1','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'col2','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'level','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'col3','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'nation','',{'background-color':'#98ff98'});
				$('#techCHM').jqGrid('setCell',row_id,'subgrid','',{'background-color':'#98ff98'});
				
			}
		}
		
	});
	
	var twm1 = $('#wmProvinces').jqGrid({
		sortable: false,
		altRows: false,
		url:'get_wm1_json.php?idc='+idc+'&capital=1',
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Тип','?','Название','Карта','Прайм-тайм','Доход','Время владения'],
		colModel :[
			{name:'type', index:'type', width:40, align:"center"},
			{name:'status', index:'status', width:25, align:"center"},
			{name:'name', index:'name', width:300, align:"left"},
			{name:'map', index:'map', width:140, align:"left"},
			{name:'prime_time', index:'prime_time', width:100, align:"center"},
			{name:'revenue', index:'revenue', width:60, align:"left"},
			{name:'occ_time', index:'occ_time', width:70, align:"center"}
												
			],
		//pager: '#wmProvPager',
		sortname: 'class',
		viewrecords: true,
		sortorder: 'desc',
		width: 700,
		height: "100%",
		rowNum: 100,
		//hiddengrid:true,
		caption: 'Владения на ГК',
		rownumbers: true,
		rownumWidth: 20,
		grouping: false,
		toolbar: false,
		scroll: false
		
	});
	var bwm1 = $('#battles1').jqGrid({
		sortable: false,
		altRows: true,
		url:'get_btl1_json.php?idc='+idc,
		datatype: 'json',
		mtype: "POST",
		postData: {'filterBy':null},
		colNames:['Тип','Провинция','Карта','Время','Соперник','Сервер'],
		colModel :[
			{name:'type', index:'type', width:150, align:"left"},
			{name:'name', index:'name', width:200, align:"left"},
			{name:'map', index:'map', width:200, align:"center"},
			{name:'time', index:'time', width:100, align:"center"},
			{name:'enemy', index:'enemy', width:370, align:"left"},
			{name:'perphery', index:'priphery', width:45, align:"center"}									
			],
		//pager: '#wmProvPager',
		sortname: 'time',
		viewrecords: true,
		sortorder: 'desc',
		width: 1050,
		height: "100%",
		rowNum: 100,
		//hiddengrid:true,
		caption: 'Запланированные битвы',
		rownumbers: true,
		rownumWidth: 20,
		grouping: false,
		toolbar: false,
		scroll: false
		
	});

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
});
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

