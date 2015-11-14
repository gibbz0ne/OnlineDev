<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	session_start();
	if(!isset($_SESSION['userId'])){
		header("Location:../index.php");
	}
	else {
		if($_SESSION['usertype'] != "tsd") {
			header("Location:../".$_SESSION['usertype']);
		}
	}

	include "../class/includes.class.php";
	$include = new includes();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="description" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
		<link rel="shortcut icon" type="image/x-icon" href="../assets/images/icons/icon.png" />
		<title id="Description">CHANGE METER/NEW CONNECTION</title>
		
		<?PHP
			echo $include->includeCSS();
			echo $include->includeJS();
		?>
		
		<script>
			$(document).ready(function(){
				$("#jqxMenu").jqxMenu({width: window.innerWidth-5, theme: "main-theme"});
				$("#print_mr").jqxButton({theme: "main-theme", disabled: true});
				$("#print_wo").jqxButton({theme: "main-theme", disabled: true});
				
				$("#mainSplitter").jqxSplitter({
					width: window.innerWidth-6, 
					height:window.innerHeight-40,
					resizable:true,
					theme: "main-theme",
					orientation: "horizontal",
					panels: [{ size:"50%",collapsible:false  }, 
					{ size: "50%",collapsible: false }] 
				});
				
				$("#mrMasterList").on("rowselect", function(event){
					$("#print_mr").jqxButton({disabled: false});
				});
				
				$("#mrMasterList").on("rowunselect", function(event){
					$("#print_mr").jqxButton({disabled: false});
				});
				
				$("#woMasterList").on("rowselect", function(event){
					$("#print_wo").jqxButton({disabled: false});
				});
				
				$("#woMasterList").on("rowunselect", function(event){
					$("#print_wo").jqxButton({disabled: false});
				});
				
				$("#print_mr").click(function(){
					var rowindex = $("#mrMasterList").jqxGrid("getselectedrowindex");
					var data = $("#mrMasterList").jqxGrid("getrowdata", rowindex);
					$("#print_window").jqxWindow('open');
					$("#print_window").jqxWindow('setContent', '<iframe src="print_mr.php?ref='+data.mrNo+'" width="99%" height="98%"></iframe>');
				});
				
				$("#print_wo").click(function(){
					var rowindex = $("#woMasterList").jqxGrid("getselectedrowindex");
					var data = $("#woMasterList").jqxGrid("getrowdata", rowindex);
					$("#print_window").jqxWindow('open');
					$("#print_window").jqxWindow('setContent', '<iframe src="print_wo.php?ref='+data.wo+'" width="99%" height="98%"></iframe>');
				});
				
				var consumers = {
					datatype: "json",
					dataFields: [
						{ name: "dateApp"},
						{ name: "acctNo" },
						{ name: "acctAleco" },
						{ name: "consumerName" },
						{ name: "cid"},
						{ name: "so"},
						{ name: "address" },
						{ name: "brgy" },
						{ name: "branch"},
						{ name: "appType"},
						{ name: "appId"},
						{ name: "tid"}
					],
					url: "sources/consumers.php"
				}
				
				var workOrder = {
					datatype: "json",
					dataFields: [
						{ name: "dateApp"},
						{ name: "acctNo" },
						{ name: "acctAleco" },
						{ name: "consumerName" },
						{ name: "cid"},
						{ name: "so"},
						{ name: "address" },
						{ name: "brgy" },
						{ name: "branch"},
						{ name: "appType"},
						{ name: "appId"},
						{ name: "wo"},
						{ name: "tid"}
					],
					url: "sources/workOrderList.php",
					async: false
				}
				
				var dataWo = new $.jqx.dataAdapter(workOrder);
				
				var columnsrenderer = function (value) {
					return '<div style="text-align: center;">' + value + '</div>';
				}

				var cellendedit = function (rowid,rowdata,datafield,columntype,value) {
					if(rowdata == 'stock_code'){
						var code = value;
						item = {itemCode:code},
						$.ajax({
							dataType: 'json',
							url: 'sources/get_item_description.php',
							data: item,
							type: 'post',
							success: function (data) {
								$("#materialGrid").jqxGrid('setcellvalue', rowid, 'item_description', data.description);
								$("#materialGrid").jqxGrid('setcellvalue', rowid, 'stock_code', data.materialCode);
								$("#materialGrid").jqxGrid('setcellvalue', rowid, 'unit', data.unit);
							}
						});
					}
				}
				var data2 = [];
				var data3 = [];
				var data = {};
				var item_no =[""];
				var discription = [""];
				var qty =[""];
				var unit =[""];
				var generaterow = function (i) {
					var row = {};
					var productindex = 1;
					var price = 1;
					var quantity = 1;
					row["item_no"] = item_no[0];
					row["discription"] = discription[0];
					row["qty"] = qty[0];
					row["unit"] = "";
					return row;
				}

				for (var i = 0; i < 10; i++) {
					var row = generaterow(i);
					data[i] = row;
				}
				
				var new_mct_empty_source = {
					localdata: data,
					datatype: "local",
					datafields: [
						{ name: 'mct_item_no', type: 'string' },
						{ name: 'stock_code', type: 'string' },
						{ name: 'item_description', type: 'string' },
						{ name: 'unit', type: 'string' },
						{ name: 'qty', type: 'number' },
					],
					addrow: function (rowid, rowdata, position, commit) {
						commit(true);
					},
					deleterow: function (rowid, commit) {
						commit(true);
					}
				};

				var new_mct_adapter = new $.jqx.dataAdapter(new_mct_empty_source);

				var item_list_source ={
					datatype: "json",
					datafields: [
					{ name: 'item_code'},
					{ name: 'item_description'},
					{ name: 'entry_id'}
					],
					url: "sources/item_list.php",
					async: false
				};		
				var ItemListAdapter = new $.jqx.dataAdapter(item_list_source);

				var mrSource = {
					datatype: "json",
					datafields: [
						 {name: "ctr"},
						 {name: "mrNo"},
						 {name: "items"},
						 {name: "wos"},
						 {name: "purpose"},
						 {name: "date"},
					],
					url: "sources/mrList.php",
					async: false
				}
				
				var mrAdapter = new $.jqx.dataAdapter(mrSource);
				
				var woSource ={
					datatype: "json",
					datafields: [
						{name: "ctr"},
						{name: "wo"},
						{name: "consumer"},
						{name: "address"},
						{name: "acctNo"},
						{name: "date"},
					],
					url: "sources/woList.php",
					async: false
					
				}

				var woAdapter = new $.jqx.dataAdapter(woSource);
				
				$("#materialGrid").jqxGrid({
					width : '100%',
					height: '300',
					source: new_mct_adapter,
					showtoolbar: true,
					editable:true,
					theme: "main-theme",
					selectionmode: 'singlerow',
					localization: getLocalization('ph'),
					editmode: 'click',
					rendertoolbar: function (toolbar) {
						var container = $("<div style='margin: 5px;'></div>");
						toolbar.append(container);
						container.append('<input id="addrowbutton" type="button" value="Add New Row" />');
						container.append('<input style="margin-left: 5px;" id="deleterowbutton" type="button" value="Delete Selected Row" />');
						   
						$("#addrowbutton").jqxButton({theme: "main-theme"});
						$("#deleterowbutton").jqxButton({theme: "main-theme"});
						
						$("#deleterowbutton").on('click',function(){
							var selectedrowindex = $("#materialGrid").jqxGrid('getselectedrowindex');
							var rowscount = $("#materialGrid").jqxGrid('getdatainformation').rowscount;
							if (selectedrowindex >= 0 && selectedrowindex < rowscount) {
								var id = $("#materialGrid").jqxGrid('getrowid', selectedrowindex);
								var commit = $("#materialGrid").jqxGrid('deleterow', id);
							}
						});
						$("#addrowbutton").on('click', function () {
							var datarow = generaterow();
							var commit = $("#materialGrid").jqxGrid('addrow', null,datarow);
						});    					
					},	
					columns: [
						{ text: 'Stock Code', datafield: 'stock_code', width: 150, columntype: 'combobox',cellendedit: cellendedit, renderer:columnsrenderer,width: '30%',cellsalign: 'center' ,
							createeditor: function (row, column, editor) {
							   editor.jqxComboBox({ source: ItemListAdapter, promptText: "Please Choose:" ,displayMember: 'item_description',
							   valueMember: 'entry_id'});
							}
						},	 
						{ text: 'Description', datafield: 'item_description', width: '50%',cellsalign: 'center',renderer:columnsrenderer,editable:false },
						{ text: 'Unit', datafield: 'unit', width: '10%',cellsalign: 'center',renderer:columnsrenderer },
						{ text: 'Qty', datafield: 'qty', width: '10%',cellsalign: 'center',cellendedit: cellendedit,renderer:columnsrenderer},
					]
				});
					
				$("#consumerList").jqxGrid({
					source: consumers,
					width: "100%",
					height: "100%",
					showtoolbar: true,
					altrows: true,
					selectionmode: "singlerow",
					theme: "main-theme",
					rendertoolbar: function(toolbar){
						var container = $("<div style='margin: 4px;'></div>");
						var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField1' type='text' style='height: 23px; float: left; width: 223px;' />");
						toolbar.append(container);
						// container.append(span);
						// container.append(input);
						container.append('<input id="wo" style = "margin-left: 10px;" type="button" value="Work Order" />');
						
						$("#wo").jqxButton({theme: "main-theme", disabled: true});
						
						$("#wo").on("click", function(event){
							$.ajax({
								url: "sources/getConsumer.php",
								type: "post",
								data: {appId: appId, cid: cid},
								success: function(data){
									$("#info").html(data);
								}
							});
							$("#woModal").jqxWindow("open");
						});
					},
					columns: [
						{text: "Date", dataField: "dateApp", cellsalign: "center", align: "center", width: 150},
						{text: "Primary Account Number", dataField: "acctNo", cellsalign: "center", align: "center", width: 200},
						{text: "Consumer Name", dataField: "consumerName", align: "center", width: 250},
						{text: "Address", dataField: "address", align: "center", width: 300},
						{text: "S.O.", dataField: "so", cellsalign: "center", align: "center", width: 100},
						{text: "Application", dataField: "appType", cellsalign: "center", align: "center", width: 120},
						{text: "Remarks", dataField: "remarks", align: "center"}
					]
				});
				
				$("#woList").jqxGrid({
					source: dataWo,
					width: "100%",
					height: "100%",
					showtoolbar: true,
					altrows: true,
					theme: "main-theme",
					selectionmode: 'checkbox',
					rendertoolbar: function(toolbar){
						var container = $("<div style='margin: 5px;'></div>");
						var span = $("<span left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField' type='text' style='height: 23px; float: left; width: 223px;' />");
						var mrButton = $("<button id = 'mr' style='margin-left: 5px;'><img src = '../assets/images/icons/icol16/src/hammer_screwdriver.png'>Material Requisition</button>");
						toolbar.append(container);
						// container.append(span);
						// container.append(input);
						container.append(mrButton);
						
						$("#mr").jqxButton({theme: "custom-button"});				
					},
					columns: [
						{text: "Work Order", dataField: "wo", pinned: true, cellsalign: "center", align: "center", width: 200},
						{text: "Primary Account Number", pinned: true, dataField: "acctNo", cellsalign: "center", align: "center", width: 200},
						{text: "Consumer Name", pinned: true, dataField: "consumerName", align: "center", width: 250},
						{text: "Address", dataField: "address", align: "center", width: 290},
                        {text: "Date", dataField: "dateApp", cellsalign: "center", align: "center", width: 150},
						{text: "S.O.", dataField: "so", cellsalign: "center", align: "center", width: 100},
						{text: "Application", dataField: "appType", cellsalign: "center", align: "center", width: 120},
						{text: "Remarks", dataField: "remarks", align: "center", width: 160}
					]
				});
				
				$("#mr").on("click", function(){
					var rows = $("#woList").jqxGrid("getselectedrowindexes");
					if(rows.length > 0)
						$("#mrModal").jqxWindow("open");
					else
						$("#vSelect").jqxWindow("open");
				});

				
				$("#submitMr").on("click", function(){
					var rows = $("#woList").jqxGrid("getselectedrowindexes");
					
					var ctr = 1;
					var ctr2 = 1;
					for(var i = 0; i<rows.length; i++){
						var data = $("#woList").jqxGrid("getrowdata", rows[i]);
						data2.push(ctr2, data.wo, data.so, data.consumerName, data.address, data.acctNo, data.tid);
						ctr2++;
					}
					var rows = $('#materialGrid').jqxGrid('getrows');
					var result = "";
					
					for(var i = 0; i < rows.length; i++){
						var row = rows[i];
						var item_description = row.item_description;
						if(row.stock_code){
						data3.push(ctr, row.stock_code, item_description, row.unit, row.qty);
						ctr++;
						}
					}

					$.ajax({
						url: "sources/MRSubmission.php",
						type: "post",
						data: {data2, data3, purpose: $("#purpose").val(), mrNum: $("#mrNum").val() },
						success: function(result){
							$("#result").html(result);
							$("#confirmApp").jqxWindow("open");
						}
					});
				});
				
				$("#confirmApp").on("close", function(event){
					data2 = [];
					data3 = [];
				});

				$("#confirmMr").click(function(){
					$.ajax({
						url: "functions/issueMr.php",
						type: "post",
						data: {data2, data3, purpose: $("#purpose").val(), mrNum: $("#mrNum").val()},
						success: function(data){
							if(data){
								woSource.url = "sources/woList.php";
								var woAdapter = new $.jqx.dataAdapter(woSource);
								$("#woMasterList").jqxGrid({source: woAdapter});
								
								mrSource.url = "sources/mrList.php";
								var mrAdapter = new $.jqx.dataAdapter(mrSource);
								$("#mrMasterList").jqxGrid({source: mrAdapter});
								
								$("#mrModal").jqxWindow("close");
								$("#confirmApp").jqxWindow("close");
								$("#print_window").jqxWindow("open");
								
								$("#print_window").jqxWindow('setContent', '<iframe src="print_mr.php?ref='+data+'" width="99%" height="98%"></iframe>');
							}
						}
					});
				});
				
				$("#print_window").on("close", function(){
					$("#mrModal").jqxWindow("close");
					$("#confirmApp").jqxWindow("close");
					$("#mrListModal").jqxWindow("close");
					$("#woListModal").jqxWindow("close");
					
					consumers.url = "sources/consumers.php";
					var consumersData = new $.jqx.dataAdapter(consumers);
					$("#consumerList").jqxGrid({source: consumersData});
					
					workOrder.url = "sources/workOrderList.php";
					var workOrders = new $.jqx.dataAdapter(workOrder);
					$("#woList").jqxGrid({source: workOrders});
				});
				
				var initrowdetails = function (index, parentElement, gridElement, datarecord) {
					var tabsdiv = null;
					var information = null;
					var notes = null;
					tabsdiv = $($(parentElement).children()[0]);
					if (tabsdiv != null) {
						information = tabsdiv.find('.information');
						notes = tabsdiv.find('.notes');
						var title = tabsdiv.find('.title');
						title.text("Material Lists");
						var container = $('<div style="margin: 5px;"></div>')
						container.appendTo($(information));
						
						$.ajax({
							url: "sources/getItems.php",
							type: "post",
							data: {id: datarecord.mrNo},
							success: function(data){
								var leftcolumn = $('<div id = "ml-'+datarecord.mrNo+'"></div>');
								var panel = $('<div class = "pnl"></div>')
								container.append(panel);
								panel.append(leftcolumn);
								$(".pnl").jqxPanel({width: "95%", height: 140});
								$("#ml-"+datarecord.mrNo).html(data);
							}
						});
						
						$.ajax({
							url: "sources/getWo.php",
							type: "post",
							data: {id: datarecord.mrNo},
							success: function(data){
								var notescontainer = $('<div id = "wl-'+datarecord.mrNo+'" style="white-space: normal; margin: 5px;"><span>hello there</span></div>');
								var panel = $('<div id = "pnl-'+datarecord.mrNo+'"></div>')
								
								$(notes).append(panel);
								panel.append(notescontainer);
								$("#pnl-"+datarecord.mrNo).jqxPanel({width: "95%", height: 140});
								$("#wl-"+datarecord.mrNo).html(data);
							}
						})

						$(tabsdiv).jqxTabs({ width: 750, height: 210});
					}
				}
				$("#mrMasterList").jqxGrid({
					width: "100%",
					height: "100%",
					source: mrAdapter,
					rowdetails: true,
					rowdetailstemplate: { rowdetails: "<div style='margin: 10px;'><ul style='margin-left: 30px;'><li class='title'></li><li>Work Order Lists</li></ul><div class='information'></div><div class='notes'></div></div>", rowdetailsheight: 200 },
					initrowdetails: initrowdetails,
					columns: [
						  { text: "#", datafield: "ctr", align: "center", cellsalign: "center", width: 20 },
						  { text: "MR-M No", datafield: "mrNo", align: "center", cellsalign: "center", width: 150 },
						  { text: "Items", datafield: "items", align: "center", cellsalign: "center", width: 50 },
						  { text: "WO's", datafield: "wos", align: "center", cellsalign: "center",width: 50 },
						  { text: "Purpose", datafield: "purpose", align: "center", cellsalign: "center",width: 200},
						  { text: "Date", datafield: "date", align: "center", cellsalign: "center"}
					  ]
				});
				
				$("#woMasterList").jqxGrid({ //work order printing
					width: "100%",
					height: "100%",
					theme: "main-theme",
					// showfilterrow: true,
					columnsresize: true,
					filterable: true,
					source: woAdapter,
					columns: [
						  { text: "WO#", pinned: true, datafield: "wo", align: "center", cellsalign: "center", width: 170 },
						  { text: "Consumer",  pinned: true, datafield: "consumer", align: "center", cellsalign: "center", width: 220 },
						  { text: "Address", datafield: "address", align: "center", cellsalign: "center", width: 300 },
						  { text: "Primary No", datafield: "acctNo", align: "center", cellsalign: "center",  width: 150},
						  { text: "Date", datafield: "date", align: "center", cellsalign: "center", width: 160}
					  ]
				});
				
				$("#woMasterList").on("rowselect", function(event){
					 var args = event.args;
					var rowBoundIndex = args.rowindex;
					var rowData = args.row;
				});
				
				$("#panel").jqxPanel({width: "100%", height: 400});
				$("#panel1").jqxPanel({width: "100%", height: 400});
				$("#consumerList").on("contextmenu", function () {
					return false;
				});
				
				$("#woList").on("contextmenu", function () {
					return false;
				});
				
				var appId, cid, tid;
				$("#consumerList").on("rowselect", function (event) {
					$("#wo").jqxButton({disabled: false});
					var args = event.args;
					var rowBoundIndex = args.rowindex;
					var rowData = args.row;
					appId = rowData.appId;
					cid = rowData.cid;
					tid = rowData.tid;
				});

				$("#approveApp").on("click", function(){
					$.ajax({
						url: "functions/issueWo.php",
						type: "post",
						data: {appId: appId, scope: $("#scope").val(), cid: cid, tid: tid, workNo: $("#workNo").val()},
						success: function(data){
							if(data == 1){
								workOrder.url = "sources/workOrderList.php";
								var workOrders = new $.jqx.dataAdapter(workOrder);
								$("#woList").jqxGrid({source: workOrders});
								
								woSource.url = "sources/woList.php";
								var woAdapter = new $.jqx.dataAdapter(woSource);
								$("#woMasterList").jqxGrid({source: woAdapter});
								
								consumers.url = "sources/consumers.php";
								var consumersData = new $.jqx.dataAdapter(consumers);
								$("#consumerList").jqxGrid({source: consumersData});
								$("#woModal").jqxWindow("close");
							}
						}
					});								
				});

				$('#processing').jqxWindow({width: 380, height:80, resizable: false,  isModal: true,showCloseButton:false, autoOpen: false, modalOpacity: 0.50,theme: "main-theme"});
				
				$("#woModal").jqxWindow({
					theme: "main-theme", height: 310, width:  500, cancelButton: $(".cancelApp"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});
				
				$("#vSelect").jqxWindow({
					theme: "main-theme", height: 150, width:  400, cancelButton: $(".cancel"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});
				
				$("#print_window").jqxWindow({
					theme: "main-theme", height: 800, width:  600, showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});

				$("#confirmApp").jqxWindow({
					theme: "main-theme", height: 500, maxWidth: 1000, cancelButton: $("#back1"), width: "100%", showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});

				$("#mrModal").jqxWindow({
					theme: "main-theme", height: 500, maxWidth: 1000, cancelButton: $("#cancelMr"), width: "100%", showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});

				$("#mrListModal").jqxWindow({
					theme: "main-theme", height: 500, maxHeight: 500, maxWidth: 800, width: "100%", showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});
				
				$("#woListModal").jqxWindow({
					theme: "main-theme", height: 500, maxHeight: 500, maxWidth: 800, width: "100%", showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});
				
				$("#accountModal").jqxWindow({
					maxWidth: 1000, maxHeight: 550, height: 550, width: 1000, cancelButton: $('#cancelAccount'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#signatoryModal").jqxWindow({
					maxWidth: 1000, maxHeight: 550, height: 250, width: 1000, cancelButton: $('#cancelAccount'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#signatory").jqxWindow({
					maxWidth: 1000, maxHeight: 550, height: 350, width: 600, cancelButton: $('#cancelAcct'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#purpose").jqxInput({height: 25, width: 400});

				$("#mrReports").click(function(){
					$("#mrListModal").jqxWindow("open");
				});
				
				$("#woReports").click(function(){
					$("#woListModal").jqxWindow("open");
				});
				
				$("#addAccount").click(function(){
					$("#accountModal").jqxWindow("open");
				});
				
				$("#addAcctModal").jqxWindow({
					height: 280, width:  350, cancelButton: $('#cancelAccount'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$('#accountModal').jqxValidator({
					rules: [
						{ input: '#aFname', message: 'First Name is required', action: 'keyup, blur', rule: 'required' },
						{ input: '#aMname', message: 'Middle Name is required', action: 'keyup, blur', rule: 'required' },
						{ input: '#aLname', message: 'Last Name is required', action: 'keyup, blur', rule: 'required' },
						{ input: '#aPosition', message: 'Position is required', action: 'keyup, blur', rule: 'required' },
					]
				});
				
				
				var accounts = {
					datatype: "json",
					dataFields: [
						{name: "aName"},
						{name: "aPosition"},
						{name: "accountId"}
					],
					url: "sources/accounts.php"
				};
				
				var acctAdapter = new $.jqx.dataAdapter(accounts);
				
				$("#accountGrid").jqxGrid({
					source: accounts,
					width: "100%",
					height: "100%",
					theme: "main-theme",
					showtoolbar: true,
					altrows: true,
					selectionmode: "singlerow",
					columnsresize: true,
					pageable: true,
					rendertoolbar: function(toolbar){
						var container = $("<div style='margin: 5px;'></div>");
						container.append('<button id="addAcct" type="button"> Add </button>');
						container.append('<button id="editAcct" type="button"> Edit </button>');
						container.append('<button id="deleteAcct" type="button"> Delete </button>');
						toolbar.append(container);
						
						
						$("#addAcct").jqxButton({width: 80, theme: "main-theme"});
						$("#editAcct").jqxButton({width: 80, theme: "main-theme", disabled: true});
						$("#deleteAcct").jqxButton({width: 80, theme: "main-theme"});
						
						
						$("#accountGrid").on("rowselect", function(event){
							accountId = event.args.row.accountId;
							$("#editAcct").jqxButton({disabled: false});
						});
						
						$("#addAcct").click(function(){
							$("#addAcctModal").jqxWindow("open");
							toEdit = 0;
						});
						
						$("#deleteAcct").click(function(){
							$.ajax({
								url: "functions/deleteAccount.php",
								type: "post",
								data: {accountId: accountId},
								success: function(data){
									accounts.url = "sources/accounts.php";
									var accountAdapter = new $.jqx.dataAdapter(accounts);
									
									$("#accountGrid").jqxGrid({source: accountAdapter});
								}
							})
						});
						
						$("#editAcct").click(function(){
							toEdit = 1;
							$.ajax({
								url: "sources/getAccount.php",
								type: "post",
								dataType: "json",
								data: {accountId: accountId},
								success: function(data){
									$("#addAcctModal").jqxWindow("open");
									$("#addAcctModal input")[0].value = data.fname;
									$("#addAcctModal input")[1].value = data.mname;
									$("#addAcctModal input")[2].value = data.lname;
									$("#addAcctModal input")[3].value = data.position;
								}
							});
						});
					},
					ready: function(){
						$("#accountGrid").jqxGrid("hidecolumn", "accountId");
					},
					columns: [
						{text: "Name", dataField: "aName", align: "center", cellsalign: "center", width: "50%"},
						{text: "Position", dataField: "aPosition", align: "center", cellsalign: "center", width: "50%"},
						{text: "Account ID", dataField: "accountId", align: "center", cellsalign: "center", width: "50%"}
					]
				});
				
				$("#accountGrid").on("contextmenu", function(){
					return false;
				})
				
				var signatories = {
					datatype: "json",
					dataFields: [
						{name: "sign"},
						{name: "name"},
						{name: "position"}
					]
				};
				
				$("#signGrid").jqxGrid({
					width: "100%",
					height: "100%",
					theme: "main-theme",
					showtoolbar: true,
					altrows: true,
					selectionmode: "singlerow",
					columnsresize: true,
					rendertoolbar: function(toolbar){
						var container = $("<div style='margin: 5px;'></div>");
						container.append('<button id="updateSignatories" type="button"> Update </button>');
						toolbar.append(container);
						
						$("#updateSignatories").jqxButton({width: 80, theme: "main-theme"});
						
						$("#updateSignatories").click(function(){
							$("#signatory").jqxWindow("open")
						});
					},
					columns: [
						{text: "", dataField: "sign", align: "center", cellsalign: "center", width: "30%"},
						{text: "Name", dataField: "name", align: "center", cellsalign: "center", width: "35%"},
						{text: "Position", dataField: "position", align: "center", cellsalign: "center", width: "35%"}
					]
				});

				$("#confirmAccount").click(function(){
					if($('#accountModal').jqxValidator('validate')){
						$.ajax({
							url: "functions/addAccount.php",
							type: "post",
							data: {edit: toEdit, aFname: $("#aFname").val(), aMname: $("#aMname").val(), 
									aLname: $("#aLname").val(),	aPosition: $("#aPosition").val(),
									abranch: $("#abranch").val(), accountId: accountId},
							success: function(data){
								accounts.url = "sources/accounts.php";
								var accountAdapter = new $.jqx.dataAdapter(accounts);
								
								$("#accountGrid").jqxGrid({source: accountAdapter});
								$("#tableForm input").val("");
								$("#addAcctModal").jqxWindow("close");
							}
						})
					}
				});
				
				var accounts = {
					datatype: "json",
					dataFields: [
						{name: "aName"},
						{name: "aPosition"},
						{name: "accountId"}
					],
					url: "sources/accounts.php"
				};
				
				var acctAdapter = new $.jqx.dataAdapter(accounts)
				var sType;
				$("#woSignatories").click(function(){
					signatories.url = "sources/getSignatories.php?wo";
					var acct = new $.jqx.dataAdapter(signatories);
					$("#signGrid").jqxGrid({source: acct});
					$("#signatory").jqxWindow({height: 250});
					sType = false;
					$.ajax({
						url: "formWO.php",
						success: function(out){
							$("#formType").html(out);
							$("#acct1").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
							$("#acct2").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
							$("#acct3").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
						}
					})
					
					$("#signatoryModal").jqxWindow("open");
				});
				
				$("#mrSignatories").click(function(){
					signatories.url = "sources/getSignatories.php?mr";
					var acct = new $.jqx.dataAdapter(signatories);
					$("#signGrid").jqxGrid({source: acct});
					$("#signatory").jqxWindow({height: 365});
					sType = true;
					$.ajax({
						url: "formMR.php",
						success: function(out){
							$("#formType").html(out);
							$("#macct1").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
							$("#macct2").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
							$("#macct3").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
							$("#macct4").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
							$("#macct5").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
						}
					})
					
					$("#signatoryModal").jqxWindow("open");
				});
				
				$("#assignAccount").click(function(){
					var aGroup;
					if(sType === false){
						var	acct1 = $("#acct1").jqxDropDownList("getSelectedItem");
						var acct2 = $("#acct2").jqxDropDownList("getSelectedItem");
						var	acct3 = $("#acct3").jqxDropDownList("getSelectedItem");
						var data = {
							acct1: acct1.value,
							acct2: acct2.value,
							acct3: acct3.value
						}
						aGroup = "wo";
					} else{
						var	acct1 = $("#macct1").jqxDropDownList("getSelectedItem");
						var	acct2 = $("#macct2").jqxDropDownList("getSelectedItem");
						var	acct3 = $("#macct3").jqxDropDownList("getSelectedItem");
						var	acct4 = $("#macct4").jqxDropDownList("getSelectedItem");
						var	acct5 = $("#macct5").jqxDropDownList("getSelectedItem");
						var data = {
							macct1: acct1.value,
							macct2: acct2.value,
							macct3: acct3.value,
							macct4: acct4.value,
							macct5: acct5.value
						}
						aGroup = "mr";
					}
					
					$.ajax({
						url: "functions/assignAccount.php",
						type: "post",
						data: data,
						success: function(data){
							signatories.url = "sources/getSignatories.php?stype="+aGroup;
							var acct = new $.jqx.dataAdapter(signatories);
							$("#signatory").jqxWindow("close");
							$("#signGrid").jqxGrid({source: acct});
						}
					});
				});
				
				$("#logout").click(function(){
			        $.ajax({
			            url: "../logout.php",
			            success: function(data){
			                if(data == 1){
			                    $("#processing").jqxWindow("open");
			                    setTimeout(function(){
			                        window.location.href = "../index.php";
			                    }, 1000);
			                }
			            }
			        });
			    });
			});
		</script>
	</head>
	<body class="default">
		<div class = "row push-right-m2">
			<div id="jqxMenu">
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "#"> Home</a></li>
					<li id = "woReports"><img  src="../assets/images/icons/icol16/src/page_white_text.png" alt=""/><a href = "#"> Work Order</a></li>
					<li id = "mrReports"><img  src="../assets/images/icons/icol16/src/report.png" alt=""/><a href = "#"> Material Requisition</a></li>
					<li><img  src="../assets/images/icons/icol16/src/cog.png" alt=""/><a href = "mr.php"> Meters</a></li>
					<li><img src = "../assets/images/icons/icol16/src/user.png">
						Accounts
						<ul>
							<li id = "addAccount"><img src = "../assets/images/icons/icol16/src/add.png"> Add Account</li>
							<li id = "signatories"><img src = "../assets/images/icons/icol16/src/pencil.png"> Signatories
								<ul>
									<li id = "woSignatories">WO</li>
									<li id = "mrSignatories">MR</li>
								</ul>
							</li>
						</ul>
					</li>
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
				</ul>	
			</div>
			<div id = "mainSplitter">
				<div class="splitter-panel">
					<div id = "consumerList"></div>
				</div>
				<div class="splitter-panel">
					<div id = "woList"></div>
				</div>
			</div>
		</div>
		<div id = "woModal">
			<div><img src = "../assets/images/icons/icol16/src/page.png"> Work Order</div>
			<div>
				<div id = "info"></div>
				<h5>Work Order #</h5>
				<input type = "text" style = "margin-top: 7px;" id = "workNo" class = "form-control" placeholder = "Work Order No.">
				<h5>Scope of Work</h5>
				<input type = "text" style = "margin-top: 7px;" id = "scope" class = "form-control" placeholder = "Scope of Work">
				<div style = "margin-top: 10px;" class = "col-sm-6">
					<input type = "button" class = "form-control btn btn-success" value = "Approve" id = "approveApp">
				</div>
				<div style = "margin-top: 10px;" class = "col-sm-6">
					<input type = "button" class = "form-control btn btn-danger cancelApp" value = "Cancel">
				</div>
			</div>
		</div>
		<div id = "mrModal">
			<div><img src = "../assets/images/icons/icol16/src/hammer_screwdriver.png"> Material Requisition</div>
			<div>
				<div class = "col-sm-5">
					PURPOSE:
					<input type = "text" id = "purpose" placeholder = "PURPOSE" class = "form-control">
					MR NUMBER:
					<input type = "text" id = "mrNum" placeholder = "MR Number" class = "form-control"><br/>
				</div>
				<div id = "materialGrid"></div><br/>
				<div class = "row">
					<div class = "col-sm-2"></div>
					<div class = "col-sm-4">
						<button id = "submitMr" class = "btn btn-success btn-block">SUBMIT</button>
					</div>
					<div class = "col-sm-4">
						<button id = "cancelMr"class = "btn btn-danger btn-block">CANCEL</button>
					</div>
					<div class = "col-sm-2"></div>
				</div>
			</div>
		</div>
		<div id = "confirmApp">
			<div><img src = "../assets/images/icons/icol16/src/hammer_screwdriver.png"> Confirm MR</div>
			<div class = "text-center">
				<div id = "result"></div>
				<div class = "col-sm-2"></div>
				<div class = "col-sm-4">
					<button id = "confirmMr" class = "form-control btn btn-success btn-block">Confirm and Print</button>
				</div>
				<div class = "col-sm-4">
					<button id = "back1" class = "form-control btn btn-danger btn-block">Back</button>
				</div>
				<div class = "col-sm-2"></div>
			</div>	
		</div>
		<div id="processing">
			<div><img src="../assets/images/icons/icol16/src/accept.png"> PROCESSING</div>
			<div >
			<div><img src="../assets/images/loader.gif">Please Wait</div>
			</div>
		</div>
		
		<div id="vSelect">
			<div><img src="../assets/images/icons/icol16/src/cancel.png"> ERROR</div>
			<div >
				
				<div style = "margin-top: 10px;" class = "col-sm-12 text-center">
					<h4>Please Select Work Order/s</h4>
					<div class = "row">
						<div class = "col-sm-4"></div>
						<div class = "col-sm-4">
							<button type = "button" class = "btn btn-block btn-danger cancel">OK</button>
						</div>
						<div class = "col-sm-4"></div>
					</div>
				</div>
			</div>
		</div>
		
		<div id="mrListModal">
			<div><img src="../assets/images/icons/icol16/src/report.png"> MR LIST</div>
			<div  class = "text-center">
				<div id = "panel">
					<div id = "mrMasterList"></div>
				</div><br>
				<button class = "text-center" id = "print_mr"><img src = "../assets/images/icons/icol16/src/printer.png" /> PRINT SELECTED MR</button>
			</div>
		</div>
		<div id="woListModal">
			<div><img src="../assets/images/icons/icol16/src/page_white_text.png"> WORK ORDER LIST</div>
			<div class = "text-center">
				<div id = "panel1">
					<div id = "woMasterList"></div>
				</div>
				<br>
				<button class = "text-center" id = "print_wo"><img src = "../assets/images/icons/icol16/src/printer.png" alt = "" /> PRINT SELECTED WO</button>
			</div>
		</div>
		<div id="print_window">
			<div><img width="14" height="14" src="../assets/images/icons/icol16/src/printer.png" alt="" /> Print Document</div>
			<div id="print_window">
				PRINTING........................
			</div>
		</div>
		<div id="accountModal">
			<div><img src="../assets/images/icons/icol16/src/user.png" ><b><span style="margin-top:-24; margin-left:3px"> Accounts</span></b></div>
			<div >
				<div id = "accountGrid"></div>
			</div>
		</div>
		<div id="addAcctModal">
			<div><img src="../assets/images/icons/icol16/src/add.png"><b><span style="margin-top:-24; margin-left:3px"> Add Account</span></b></div>
			<div>
				<table id = "tableForm">
					<tr>
						<td width = "30%"><h5>First Name:<h5></td>
						<td><input type = "text" id = "aFname" class = "form-control"></td>
					</tr>
					<tr>
						<td><h5>Middle Name:<h5></td>
						<td><input type = "text" id = "aMname" class = "form-control"></td>
					</tr>
					<tr>
						<td><h5>Last Name:<h5></td>
						<td><input type = "text" id = "aLname" class = "form-control"></td>
					</tr>
					<tr>
						<td><h5>Position:<h5></td>
						<td><input type = "text" id = "aPosition" class = "form-control"></td>
					</tr>
					<tr>
						<td><h5>Branch:<h5></td>
						<td>
							<select id = "abranch" class = "form-control">
								<option value = "B1">B1</option>
								<option value = "B2">B2</option>
								<option value = "B3">B3</option>
							</select>
						</td>
					</tr>
				</table><br>
				<div class = "col-sm-6">
					<button id = "confirmAccount" class = "btn btn-success btn-block">Confirm</button>
				</div>
				<div class = "col-sm-6">
					<td><button id = "cancelAccount" class = "btn btn-danger btn-block">Cancel</button></td>
				</div>
			</div>
		</div>
		<div id="signatoryModal">
			<div><img src="../assets/images/icons/icol16/src/pencil.png"><b><span style="margin-top:-24; margin-left:3px"> Signatories</span></b></div>
			<div>
				<div id = "signGrid"></div>
			</div>
		</div>
		<div id="signatory">
				<div><img src="../assets/images/icons/icol16/src/pencil.png"><b><span style="margin-top:-24; margin-left:3px"> Signatories</span></b></div>
				<div>
					<div id = "formType"></div>
					
					<div class = "col-sm-6">
						<button id = "assignAccount" class = "btn btn-success btn-block">Assign</button>
					</div>
					<div class = "col-sm-6">
						<td><button id = "cancelAcct" class = "btn btn-danger btn-block">Cancel</button></td>
					</div>
				</div>
			</div>
	</body>
</html>