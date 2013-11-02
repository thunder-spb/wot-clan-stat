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
$(function() {


	var idc = current_clan_id;
	if (idc == "") idc = "102"
	
	$( "#stat" ).tabs({
        
        });		
var tall = $("#all").jqGrid({
		sortable: true,
		altRows: true,
		url:'allians_json.php',
		datatype: 'json',
		mtype: "POST",
		colNames:['ID','Тэг','Имя','Место','Сила','Скилл','Огн. мощь','Локация','Владения','Доход'],
		colModel :[			
			{name:'id', index:'id', width:20, align:"center",sortable:false},
			{name:'tag', index:'tag', width:45, align:"left"},
			{name:'name', index:'name', width:100, align:"left",sortable:false},
			{name:'position', index:'position', width:30, align:"center"},
			{name:'rate', index:'rate', width:30, align:"center"},
			{name:'skill', index:'skill', width:30, align:"center"},
			{name:'firepower', index:'firepower', width:30, align:"center"},
			{name:'cw', index:'cw', width:30, align:"center"},
			{name:'cnt', index:'cnt', width:30, align:"center"},
			{name:'revenue', index:'revenue', width:30, align:"center"},
               ],
		rowNum:100,
		scroll: false,
		sortname: 'rate',
		viewrecords: true,
		sortorder: 'desc',
		width: 900,
		height: 'auto',
		caption: 'Состав альянса ( по ivanerr.ru )',
				
	});

}); 

