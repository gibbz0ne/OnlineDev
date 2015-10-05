<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	session_start();
	if(!isset($_SESSION['username'])){
		header("Location:../index.php");
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
				$("#jqxMenu").jqxMenu({width: window.innerwidth, theme:"darkblue"});
				$("#print_mr").jqxButton({theme: "darkblue", disabled: true});
				$("#mainSplitter").jqxSplitter({
					width:window.innerwidth, 
					height:window.innerHeight-40,
					resizable:true,
					theme: "darkblue",
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
				$("#print_mr").click(function(){
					var rowindex = $("#mrMasterList").jqxGrid("getselectedrowindex");
					var data = $("#mrMasterList").jqxGrid("getrowdata", rowindex);
					$("#print_window").jqxWindow('open');
					$("#print_window").jqxWindow('setContent', '<iframe src="print_mr.php?ref='+data.mrNo+'" width="99%" height="98%"></iframe>');
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
						{ name: "wo"}
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
						// synchronize with the server - send insert command
						// call commit with parameter true if the synchronization with the server is successful 
						//and with parameter false if the synchronization failed.
						// you can pass additional argument to the commit callback which represents the new ID if it is generated from a DB.
						commit(true);
					},
					deleterow: function (rowid, commit) {
						// synchronize with the server - send delete command
						// call commit with parameter true if the synchronization with the server is successful 
						//and with parameter false if the synchronization failed.
						commit(true);
					}
					// updaterow: function (rowid, rowdata, commit) {
						// var qty_val = rowdata.qty;
						// var price_val = rowdata.unit_cost;
						// var sum = qty_val* price_val;
						// $("#materialGrid").jqxGrid('setcellvalue', rowid, 'total_amount',sum);
					// }
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
					theme:'darkblue',
					selectionmode: 'singlerow',
					localization: getLocalization('ph'),
					editmode: 'click',
					rendertoolbar: function (toolbar) {
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						toolbar.append(container);
						container.append('<input id="addrowbutton" type="button" value="Add New Row" />');
						container.append('<input style="margin-left: 5px;" id="deleterowbutton" type="button" value="Delete Selected Row" />');
						   
						$("#addrowbutton").jqxButton({theme:'darkblue'});
						$("#deleterowbutton").jqxButton({theme:'darkblue'});
						
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
								// assign a new data source to the combobox.
								
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
					theme:"darkblue",
					rendertoolbar: function(toolbar){
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField1' type='text' style='height: 23px; float: left; width: 223px;' />");
						toolbar.append(container);
						container.append(span);
						container.append(input);
						
						if (theme != "") {
							input.addClass("jqx-widget-content-" + theme);
							input.addClass("jqx-rc-all-" + theme);
						}
						
					},
					columns: [
						{text: "Date", dataField: "dateApp", cellsalign: "center", align: "center", width: 150},
						{text: "Primary Account Number", dataField: "acctNo", cellsalign: "center", align: "center", width: 200},
						{text: "Consumer Name", dataField: "consumerName", align: "center", width: 250},
						{text: "Address", dataField: "address", align: "center", width: 300},
						{text: "S.O.", dataField: "so", cellsalign: "center", align: "center", width: 100},
						{text: "Application", dataField: "appType", cellsalign: "center", align: "center", width: 120},
						// {text: "Status", dataField: "status", cellsalign: "center", align: "center", width: 160},
						{text: "Remarks", dataField: "remarks", align: "center"}
					]
				});
				
				$("#woList").jqxGrid({
					source: dataWo,
					width: "100%",
					height: "100%",
					showtoolbar: true,
					altrows: true,
					//selectionmode: "singlerow",
					theme:"darkblue",
					selectionmode: 'checkbox',
					rendertoolbar: function(toolbar){
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						var span = $("<span left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField' type='text' style='height: 23px; float: left; width: 223px;' />");
						var mrButton = $("<button id = 'mr' style='margin-left: 5px;'><img src = '../assets/images/icons/icol16/src/hammer_screwdriver.png'>Material Requisition</button>");
						toolbar.append(container);
						container.append(span);
						container.append(input);
						container.append(mrButton);
						
						$("#mr").jqxButton({theme:"darkblue"});

						$("#mr").on("click", function(){
							$("#mrModal").jqxWindow("open");
						});
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

				$("#submitMr").on("click", function(){
					var rows = $("#woList").jqxGrid("getselectedrowindexes");
					
					var ctr = 1;
					var ctr2 = 1;
					for(var i = 0; i<rows.length; i++){
						var data = $("#woList").jqxGrid("getrowdata", rows[i]);
						data2.push(ctr2, data.wo, data.so, data.consumerName, data.address, data.acctNo);
						ctr2++;
					}

					rows = $('#materialGrid').jqxGrid('getrows');
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
						data: {data2, data3, purpose: $("#purpose").val() },
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
						url: "functions/issueMR.php",
						type: "post",
						data: {data2, data3, purpose: $("#purpose").val()},
						success: function(data){
							if(data){
								$("#processing").jqxWindow("open");
								setTimeout(function(){
									$("#print_window").jqxWindow('setContent', '<iframe src="print_mr.php?ref='+data+'" width="99%" height="98%"></iframe>');
								}, 1000);
							}
						}
					});
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
					ready: function () {
						$("#mrMasterList").jqxGrid('showrowdetails', 0);
						$("#mrMasterList").jqxGrid('showrowdetails', 1);
					},
					
					columns: [
						  { text: "#", datafield: "ctr", align: "center", cellsalign: "center", width: 20 },
						  { text: "MR-M No", datafield: "mrNo", align: "center", cellsalign: "center", width: 150 },
						  { text: "Items", datafield: "items", align: "center", cellsalign: "center", width: 50 },
						  { text: "WO's", datafield: "wos", align: "center", cellsalign: "center",width: 50 },
						  { text: "Purpose", datafield: "purpose", align: "center", cellsalign: "center",width: 200},
						  { text: "Date", datafield: "date", align: "center", cellsalign: "center"}
					  ]
				});
				
				$("#woMasterList").jqxGrid({
					width: "100%",
					height: "100%",
					showfilterrow: true,
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
					// row's bound index.
					var rowBoundIndex = args.rowindex;
					// row's data. The row's data object or null(when all rows are being selected or unselected with a single action). If you have a datafield called "firstName", to access the row's firstName, use var firstName = rowData.firstName;
					var rowData = args.row;
					console.log(rowData.wo);
				});
				
				$("#panel").jqxPanel({width: "100%", height: 400});
				$("#panel1").jqxPanel({width: "100%", height: 400});
				$("#consumerList").on("contextmenu", function () {
					return false;
				});
				
				$("#woList").on("contextmenu", function () {
					return false;
				});
				
				var consumer_contextMenu = $("#consumerMenu").jqxMenu({ width: 226, height: 31, autoOpenPopup: false, mode: "popup", theme: "darkblue"});
				var appId, cid;
				$("#consumerList").on("rowclick", function (event) {
					if (event.args.rightclick) {
						var selected_account = $("#consumerList").jqxGrid("selectrow", event.args.rowindex);
						$("#consumerList").jqxGrid("focus");
						var rowindex = $("#consumerList").jqxGrid("getselectedrowindex");
						var data = $("#consumerList").jqxGrid("getrowdata", rowindex);
						var scrollTop = $(window).scrollTop();
						var scrollLeft = $(window).scrollLeft();
						appId = data.appId;
						cid = data.cid;
						consumer_contextMenu.jqxMenu("open", parseInt(event.args.originalEvent.clientX) + 5 + scrollLeft, parseInt(event.args.originalEvent.clientY) + 5 + scrollTop);
						return false;
					}
				});

				$("#issueWo").on("click", function(event){
					$.ajax({
						url: "sources/getConsumer.php",
						type: "post",
						data: {appId: appId, cid: cid},
						success: function(data){
							$("#info").html(data);
							//console.log(data);
						}
					});
					$("#woModal").jqxWindow("open");
				});

				$("#approveApp").on("click", function(){
					$.ajax({
						url: "functions/issueWo.php",
						type: "post",
						data: {appId: appId, scope: $("#scope").val(), cid: cid},
						success: function(data){
							if(data == 1){
								$("#processing").jqxWindow("open");
								setTimeout(function(){
									location.reload();
								}, 1000);
							}
						}
					});								
				});

				$('#processing').jqxWindow({width: 380, height:80, resizable: false,  isModal: true,showCloseButton:false, autoOpen: false, modalOpacity: 0.50,theme:'darkblue'});
				
				$("#woModal").jqxWindow({
					theme: "darkblue", height: 230, width:  500, cancelButton: $(".cancelApp"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});
				
				$("#print_window").jqxWindow({
					theme: "darkblue", height: 800, width:  600, showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});

				$("#confirmApp").jqxWindow({
					theme: "darkblue", height: 500, maxWidth: 1000, cancelButton: $("#back1"), width: "100%", showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});

				$("#mrModal").jqxWindow({
					theme: "darkblue", height: 500, maxWidth: 1000, cancelButton: $("#cancelMr"), width: "100%", showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});

				$("#mrListModal").jqxWindow({
					theme: "darkblue", height: 500, maxHeight: 500, maxWidth: 800, width: "100%", showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});
				
				$("#woListModal").jqxWindow({
					theme: "darkblue", height: 500, maxHeight: 500, maxWidth: 800, width: "100%", showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});
				
				$("#logout").click(function(){
					$.ajax({
						url: "../logout.php",
						success: function(data){
							if(data == 1){
								window.location.href = "../index.php";
							}
						}
					});
				});
				
				$("#purpose").jqxInput({height: 25, width: 400});

				$("#mrReports").click(function(){
					$("#mrListModal").jqxWindow("open");
					$('#mrMasterList').jqxGrid('hiderowdetails', 0);
					$('#mrMasterList').jqxGrid('hiderowdetails', 1);
				});
				
				$("#woReports").click(function(){
					$("#woListModal").jqxWindow("open");
				});
				
			});
		</script>
	</head>
	<body class="default">
		<div class = "row" style="margin:0 !important;">
			<div class = "col-sm-12">
				<div id="jqxMenu">
					<ul>
						<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "#"> Home</a></li>
						<li id = "woReports"><img  src="../assets/images/icons/icol16/src/page_white_text.png" alt=""/><a href = "#"> Work Order</a></li>
						<li id = "mrReports"><img  src="../assets/images/icons/icol16/src/report.png" alt=""/><a href = "#"> Material Requisition</a></li>
						<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
					</ul>	
				</div>
				<div id = "mainSplitter">
					<div class="splitter-panel">
						<div id = "consumerList"></div>
						<div id="consumerMenu">
							<ul>
								<li id="issueWo"><img src="../assets/images/icons/icol16/src/page_white_edit.png"> ISSUE WORK ORDER</li>
								</li>
							</ul>
						</div>
					</div>
					<div class="splitter-panel">
						<div id = "woList"></div>
					</div>
				</div>
			</div>
		</div>
		<div id = "woModal">
			<div><img src = "../assets/images/icons/icol16/src/page.png"> Work Order</div>
			<div>
				<div id = "info"></div>
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
					PURPOSE
					<input type = "text" id = "purpose" placeholder = "PURPOSE" class = "form-control"><br/>
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
		<div id="mrListModal">
			<div><img src="../assets/images/icons/icol16/src/report.png"> MR LIST</div>
			<div  class = "text-center">
				<div id = "panel">
					<div id = "mrMasterList"></div>
				</div><br>
				<button disabled class = "text-center" id = "print_mr"><img src = "../assets/images/icons/icol16/src/printer.png" /> PRINT SELECTED MR</button>
			</div>
		</div>
		<div id="woListModal">
			<div><img src="../assets/images/icons/icol16/src/page_white_text.png"> WORK ORDER LIST</div>
			<div >
				<div id = "panel1">
					<div id = "woMasterList"></div>
				</div>
			</div>
		</div>
		<div id="print_window">
			<div><img width="14" height="14" src="../assets/images/icons/icol16/src/printer.png" alt="" />Print Document</div>
			<div id="print_window">
				PRINTING........................
			</div>
		</div>
	</body>
</html>