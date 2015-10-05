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
				var req1 = req2 = req3 = req4 = req5 = req6 = req7 = 0;
				var q1 = q2 = q3 = q4 = q5 = q6 = 1;
				var ownerName = ownerAddress = prevOccupant = "";
				$("#jqxMenu").jqxMenu({ width:window.innerWidth-5, height: "30px", theme:"custom-zandro", autoOpen:false});
				$("#addApp").jqxButton({ theme:'energyblue',height:35,width:150,disabled:false});
				$("#mainSplitter").jqxSplitter({
					width:window.innerWidth-6, 
					height:window.innerHeight-40,
					theme:"custom-zandro",
					resizable:true,
					orientation: "horizontal",
					panels: [{ size:"55%",collapsible:false  }, 
					{ size: "45%",collapsible: false }] 
				});
				
				var acctSource = {
					datatype: "json",
					dataFields: [
						{ name: "acctNo" },
						{ name: "acctAleco" },
						{ name: "acctName" },
						{ name: "address" },
						{ name: "brgy" },
						{ name: "branch"}
					],
					url: "sources/listOfAccounts.php"
				}
				var dataAdapter = new $.jqx.dataAdapter(acctSource);
				
				$("#acct-list").on("contextmenu", function () {
					return false;
				});
				
				var consumer_contextMenu = $("#ConsumerMenu").jqxMenu({ width: 245, height: 193, autoOpenPopup: false, mode: "popup",theme:"custom-zandro"});
				$("#acct-list").on("rowclick", function (event) {
					if (event.args.rightclick) {
						var selected_account = $("#acct-list").jqxGrid("selectrow", event.args.rowindex);
						$("#acct-list").jqxGrid("focus");
						var scrollTop = $(window).scrollTop();
						var scrollLeft = $(window).scrollLeft();
						consumer_contextMenu.jqxMenu("open", parseInt(event.args.originalEvent.clientX) + 5 + scrollLeft, parseInt(event.args.originalEvent.clientY) + 5 + scrollTop);
						return false;
					}
				});
				$("#acct-list").jqxGrid({
					source: acctSource,
					width: "100%",
					height:"100%",
					theme: "custom-zandro",
					showtoolbar: true,
					altrows: true,
					// pageable: true,
					columns: [
						{text: "Account Number", dataField: "acctNo", width: 200},
						{text: "Aleco Account", dataField: "acctAleco", width: 150},
						{text: "Account Name", dataField: "acctName", width: 300},
						{text: "Address", dataField: "address", width: 300},
						{text: "Barangay", dataField: "brgy", width: 300},
						{text: "Branch", dataField: "branch", width:300}
					],
					rendertoolbar: function(toolbar){
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField' type='text' style='height: 23px; float: left; width: 223px;' />");
					   // var refresh = $("<input style="margin-left: 5px;" id="clear" type="button" value="Clear" />");
						var searchButton = $("<div style='float: left; margin-left: 5px;' id='search'><img style='position: relative; margin-top: 2px;' src='../assets/images/search_lg.png'/><span style='margin-left: 4px; position: relative; top: -3px;'></span></div>");
						var dropdownlist2 = $("<div style='float: left; margin-left: 5px;' id='dropdownlist'></div>");
						// var branch_sep = $("<div style='float: left; margin-left: 5px;' id='branch_sep'></div>");
						toolbar.append(container);
						container.append(span);
						container.append(input);
						container.append(dropdownlist2);

						container.append(searchButton);
						// container.append(branch_sep);
						
						$("#search").jqxButton({theme:"custom-zandro",height:18,width:24});
						$("#dropdownlist").jqxDropDownList({ 
							autoDropDownHeight: true,
							selectedIndex: 0,
							theme:"custom-zandro", 
							width: 200, 
							height: 25, 
							source: [
								"Account Number","Aleco Account", "Account Name","Address"
							]
						});
						var sep_source ={
							datatype: "json",
							datafields: [
							{ name: 'area_name'},
							{ name: 'area_value'},
							],
							url: 'sources/branch_source.php',
							async: false
						};

						var sepAdapter = new $.jqx.dataAdapter(sep_source);

						$.ajax({
							url:'sources/check_branch.php',
							type:'post',
							datatype:'json',
							success:function(data){
							var selected = data;
							//$("#branch_sep").val(selected);
							}
						});
						
						// $("#branch_sep").jqxComboBox({ 
							// autoDropDownHeight: true, 
							// width: 100, 
							// height: 22, 
							// source: sepAdapter,displayMember: 'area_name',valueMember: 'area_value',theme:'custom-zandro',promptText:"BRANCH"
						// });
						
						if (theme != "") {
							input.addClass("jqx-widget-content-" + theme);
							input.addClass("jqx-rc-all-" + theme);
						}
						$("#search").click(function(){
							$("#acct-list").jqxGrid('clearfilters');
							var searchColumnIndex = $("#dropdownlist").jqxDropDownList('selectedIndex');
							var datafield = "";
							switch (searchColumnIndex) {
								case 0:
									datafield = "acctNo";
									break;
								case 1:
									datafield = "acctAleco";
									break;
								case 2:
									datafield = "acctName";
									break;
								case 3:
									datafield = "address";
									break;
							}

							var searchText = $("#searchField").val();
							var filtergroup = new $.jqx.filter();
							var filter_or_operator = 1;
							var filtervalue = searchText;
							var filtercondition = 'contains';
							var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
							filtergroup.addfilter(filter_or_operator, filter);
							$("#acct-list").jqxGrid('addfilter', datafield, filtergroup);
							$("#acct-list").jqxGrid('applyfilters');
						});
						
						var oldVal = "";
						input.on('keydown', function (event) {
							var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
								
							if (key == 13 || key == 9) {
								$("#acct-list").jqxGrid('clearfilters');
								var searchColumnIndex = $("#dropdownlist").jqxDropDownList('selectedIndex');
								var datafield = "";
								switch (searchColumnIndex) {
									case 0:
										datafield = "acctNo";
										break;
									case 1:
										datafield = "acctAleco";
										break;
									case 2:
										datafield = "acctName";
										break;
									case 3:
										datafield = "address";
										break;
								}

								var searchText = $("#searchField").val();
								var filtergroup = new $.jqx.filter();
								var filter_or_operator = 1;
								var filtervalue = searchText;
								var filtercondition = 'contains';
								var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
								filtergroup.addfilter(filter_or_operator, filter);
								$("#acct-list").jqxGrid('addfilter', datafield, filtergroup);
								$("#acct-list").jqxGrid('applyfilters');
							}
						   
							if(key == 27){
								$("#acct-list").jqxGrid('clearfilters');
								return true;
							}
						});
					}
				});
				
				var c_sales_source ={
					datatype: "json",
					datafields: [
						{ name: 'sales_month'},
						{ name: 'sales_invoice'},
						{ name: 'sales_prev_reading_date'},
						{ name: 'sales_prev_reading'},
						{ name: 'sales_reading_date'},
						{ name: 'sales_reading'},
						{ name: 'sales_kwh'},
						{ name: 'sales_amount'},
						{ name: 'sales_payment'},
						{ name: 'sales_status'},
						{ name: 'sales_adjusted'},
						{ name: 'sales_dcm'},
						{ name: 'sales_remarks'},
						{ name: 'dcm_checker'}
						
					],
					url: 'get_sales.php',
					cache: false,
					updaterow: function (rowid, rowdata, commit) {}
				};

				$('#acct-list').on('rowdoubleclick', function (event) {
					var rowindex = $('#acct-list').jqxGrid('getselectedrowindex');
					var data = $('#acct-list').jqxGrid('getrowdata',rowindex);
					
					c_sales_source.url = 'sources/get_sales.php?Account='+data.acctNo+'&&Year=2014&&Branch='+data.branch;
					selected_account = data.acctNo;
					
					var new_c_source_adapter = new $.jqx.dataAdapter(c_sales_source);
					$('#ledger-grid').jqxGrid({source:new_c_source_adapter});
					
					$('#print_grid').jqxButton({theme:'custom-button',disabled:false});	
					
				});
				// ||-------ledger grid--------||
				$("#ledger-grid").jqxGrid({
					// source: c_sales_source,
					width :'100%',
					theme:'custom-zandro',
					height:'100%',
					columnsresize: true,
					editable:false,
					selectionmode: 'singlerow',
					showtoolbar: true,
					showstatusbar: false,
					altrows: true,
					rendertoolbar: function (toolbar){
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Select Year: </span>");
						container.append(span);
						var dropdownlist3 = $("<div style='float: left; margin-left: 5px;' id='dropdownlist3'></div>");
						container.append(dropdownlist3);
						container.append('<input id="print_grid" type="button" value="Print Data" />');
						container.append('<input id="show_hide" type="button" value="Show/Hide Columns" />');
						toolbar.append(container);
										
						$('#print_grid').jqxButton({theme:'custom-button',disabled:true});
						$('#show_hide').jqxButton({theme:'custom-button',disabled:false});
						
						var year_source = {
							datatype: "json",
							datafields: [
								{ name: 'year_name'},
								{ name: 'year_value'},
							],
							url: 'sources/year_source.php',
							async: false
						};

						var yearAdapter = new $.jqx.dataAdapter(year_source);

						$("#dropdownlist3").jqxComboBox({ 
							autoDropDownHeight: true, selectedIndex: 0, width: 100, height: 22, 
							source:yearAdapter,displayMember: 'year_name',valueMember: 'year_value',theme:'custom-zandro'
						});
						
						$('#dropdownlist3').on('select', function (event){
							var args = event.args;
							if (args) {
								
								var index = args.index;
								var item = args.item;
								// get item's label and value.
								var value = item.value;
								var rowindex = $('#acct-list').jqxGrid('getselectedrowindex');
								var data = $('#acct-list').jqxGrid('getrowdata', rowindex);
								c_sales_source.url = 'sources/get_sales.php?Account='+data.acctNo+'&&Year='+value+'&&Branch='+data.branch;
								selected_account = data.acctNo;
								var new_c_source_adapter = new $.jqx.dataAdapter(c_sales_source);
								$('#ledger-grid').jqxGrid({source:new_c_source_adapter});	
							}
								
						});
						
						$('#show_hide').click(function(){
							$('#show_hide_column_window').jqxWindow('open');
						});
						
						$('#print_grid').click(function(){
							var s_year = $('#dropdownlist3').val();
							$('#print_window').jqxWindow('open');
							$("#print_window").jqxWindow('setContent', '<iframe src="print_data.php?ref='+selected_account+'&&year='+s_year+'" width="99%" height="98%"></iframe>');
						});
					},
					columns: [
						{ text: 'Month', datafield:'sales_month',pinned:true,editable:false,width:120,/* cellclassname: cellclass */},
						{ text: 'Invoice', datafield:'sales_invoice',pinned:true,editable:false,width:120,/*cellclassname: cellclass*/},
						{ text: 'Prev Rdg Date', datafield:'sales_prev_reading_date',editable:true,hidden:false,width:120,/*cellclassname: cellclass*/},
						{ text: 'Prev Reading', datafield:'sales_prev_reading',editable:true,hidden:false,width:120,/*cellclassname: cellclass*/},
						{ text: 'Pres Rdg Date', datafield:'sales_reading_date',editable:true,hidden:false,width:120,/*cellclassname: cellclass*/},
						{ text: 'Pres Reading', datafield:'sales_reading',editable:true,width:120,/*cellclassname: cellclass*/},
						{ text: 'Kwh', datafield: 'sales_kwh',width:120,/*cellclassname: cellclass*/},
						{ text: 'Sales Amount', datafield: 'sales_amount',width:120,/*cellclassname: cellclass*/},
						{ text: 'Payment', datafield:'sales_payment',editable:false,width:120,/*cellclassname: cellclass*/},
						{ text: 'Status', datafield:'sales_status',editable:false,hidden:false,width:120,/*cellclassname: cellclass*/},
						{ text: 'Adjusted', datafield:'sales_adjusted',editable:false,hidden:false,width:120,/*cellclassname: cellclass*/},
						{ text: 'Dcm Number', datafield:'sales_dcm',editable:false,hidden:true,width:120,/*cellclassname: cellclass*/},
						{ text: 'Remarks', datafield:'sales_remarks',editable:false, hidden:false,width:120,/*cellclassname: cellclass*/},
						{ text: 'DCM CHECKER', datafield:'dcm_checker',editable:false, hidden:true,width:120,/*cellclassname: cellclass*/}
					]
				});
				$("#show_hide_column_window").jqxWindow({
					height: 200, width:  170,resizable: false,  isModal: false, autoOpen: false, modalOpacity: 0.01,theme:'custom-zandro'
				});
				$("#print_window").jqxWindow({
					height: 600, width:  800,resizable: false,  isModal: true, autoOpen: false, modalOpacity: 0.3,theme:'custom-zandro'
				});
				
				$("#sales_prev_reading_date").jqxCheckBox({  checked: true,theme:'custom-zandro'});
				$("#sales_prev_reading").jqxCheckBox({  checked: true,theme:'custom-zandro'});
				$("#sales_reading_date").jqxCheckBox({  checked: true,theme:'custom-zandro'});
				$("#sales_reading").jqxCheckBox({  checked: true ,theme:'custom-zandro'});
				$("#sales_adjusted").jqxCheckBox({  checked: false,theme:'custom-zandro' });
				$("#sales_dcm").jqxCheckBox({  checked: true,theme:'custom-zandro' });
				$("#sales_remarks").jqxCheckBox({  checked: true,theme:'custom-zandro' });
				
				$("#sales_prev_reading_date,#sales_prev_reading,#sales_reading_date, #sales_reading, #sales_adjusted, #sales_dcm, #sales_remarks").on('unchecked', function (event) {
					var datafield = event.target.id;
					$("#ledger-grid").jqxGrid('setcolumnproperty', datafield,'hidden',true);
				});
				
				$("#sales_prev_reading_date,#sales_prev_reading,#sales_reading_date, #sales_reading, #sales_adjusted, #sales_dcm, #sales_remarks").on('checked', function (event) {
					var datafield = event.target.id;
					$("#ledger-grid").jqxGrid('setcolumnproperty', datafield,'hidden',false);
				});
				
				var list = [
					{text: "ALBAY", value: 19},
					{text: "BACACAY", value: 1},
					{text: "CAMALIG", value: 2},
					{text: "DARAGA", value: 3},
					{text: "GUINOBATAN", value: 4},
					{text: "JOVELLAR", value: 5},
					{text: "LEGAZPI CITY", value: 6},
					{text: "LIBON", value: 7},
					{text: "LIGAO CITY", value: 8},
					{text: "MALILIPOT", value: 9},
					{text: "MALINAO", value: 10},
					{text: "MANITO", value: 11},
					{text: "OAS", value: 12},
					{text: "PIODURAN", value: 13},
					{text: "POLANGUI", value: 14},
					{text: "RAPU-RAPU", value: 15},
					{text: "STO. DOMINGO", value: 16},
					{text: "TABACO CITY", value: 17},
					{text: "TIWI", value: 18}
				];

				var listAdapter = new $.jqx.dataAdapter(list);

				var cStatusList = [
					"SINGLE",
					"MARRIED",
					"WIDOWED",
					"SEPARATED"
				];
				
				var cTypeList = [
					"R",
					"C",
					"H",
					"E",
					"F",
					"BAPA"
				];
				
				var brgy_list = {
					datatype: "json",
					dataFields: [
						{ name: "bid" },
						{ name: "brgyName" }
					],
					url: "sources/defaultBrgy.php",
					async: false
				};
				
				var brgyAdapter = new $.jqx.dataAdapter(brgy_list);
				
				$("#municipality").jqxDropDownList({ 
					selectedIndex: 0, width: "87%", height: 20, 
					source:list, displayMember: 'text', valueMember: 'text', theme:'custom-zandro'
				});
				
				$("#brgy").jqxDropDownList({ 
					selectedIndex: 0, width: "87%", height: 20, 
					source:brgyAdapter, displayMember: 'brgyName', valueMember: 'bid', theme:'custom-zandro'
				});
					
				$("#municipality").on("change", function(event){
					var mun = $("#municipality").jqxDropDownList("getSelectedItem");
					var sBrgy = {
						datatype: "json",
						dataFields: [
							{ name: "bid" },
							{ name: "brgyName" }
						],
						url: "sources/getBrgy.php?id="+mun.value,
						async: false
						
					}
					var d = new $.jqx.dataAdapter(sBrgy);
					$("#brgy").jqxDropDownList({ 
							selectedIndex: 0, width: "87%", height: 20, 
							source:d, displayMember: 'brgyName', valueMember: 'bid', theme:'custom-zandro'
					});
				});
				
				$("#civilStatus").jqxDropDownList({
					autoDropDownHeight: 200, selectedIndex: 0, width: "87%", height: 20,
					source: cStatusList, theme:'custom-zandro'
				});
				
				$("#customerType").jqxDropDownList({
					autoDropDownHeight: 200, selectedIndex: 0, width: "87%", height: 20,
					source: cTypeList, theme:'custom-zandro'
				});
				
				$("#confirmApplication").jqxWindow({
					height: 150, width:  300, showCloseButton: false, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'custom-zandro'
				});
				
				$("#newConsumerForm").jqxWindow({
					maxHeight: 400, maxWidth: 650, height: 650, width:  650, showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'custom-zandro'
				});
				 
				$('#processing	').jqxWindow({width: 380, height:80, resizable: false,  isModal: true,showCloseButton:false, autoOpen: false, modalOpacity: 0.01,theme:'custom-zandro'});
				
				$("#acceptApp").jqxButton({width: "100%", theme: "custom-zandro"});
				$("#cancelApp").jqxButton({width: "100%", theme: "custom-zandro"});
				
				$("#addApp").click(function(event){
					$("#confirmApplication").jqxWindow("open");
				});
				
				$("#cancelApp").click(function(event){
					$("#confirmApplication").jqxWindow("close");
				});
				
				$("#newConsumer").on("click", function(event){
					$("#newConsumerForm").jqxWindow("open");
				});
				
				$("#acceptApp").click(function(event){
					var selectedStatus = $("#civilStatus").jqxDropDownList("getSelectedItem");
					var selectedType = $("#customerType").jqxDropDownList("getSelectedItem");
					var selectedMunicipality = $("#municipality").jqxDropDownList("getSelectedItem");
					var selectedBrgy = $("#brgy").jqxDropDownList("getSelectedItem");
					$.ajax({
						url: "functions/addApplication.php",
						type: "post",
						data: {consumerType: selectedType.label, ename: $("#ename").val(), fname: $("#fname").val(), mname: $("#mname").val(), lname: $("#lname").val(), civilStatus: selectedStatus.label,
								spouseName: $("#spouseName").val(), hno: $("#hno").val(), purok: $("#purok").val(), brgy: selectedBrgy.value, municipality: selectedMunicipality.value},
						success: function(data){
							console.log(data);
							if(data == 1){
								$('#processing').jqxWindow('open');
								$("#confirmApplication").jqxWindow("close");
								$("#newConsumerForm").jqxWindow("close");
								setTimeout(function(){
									$('#processing').jqxWindow('close');
									$.ajax({
										success: function(){
											window.location.href = "transactions.php";
										}
									})
								},3000);
							}	
						}
					});
				});
				$("#logout").click(function(){
					$.ajax({
						url: "logout.php",
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
		<div id="jqxMenu" >
			<ul>
				<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "#">Home</a></li>
				<li><img  src="../assets/images/icons/icol16/src/zone_money.png" alt="" /><a href = "transactions.php">Daily Transactions</a></li>
				<li id = "newConsumer"><img  src="../assets/images/icons/icol16/src/group.png" alt=""/><a href = "#newConsumer">New Consumer</a></li>
				<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
			</ul>	
		</div>
		<div id = "mainPage">
		<div id = "mainSplitter">
			<div class="splitter-panel">
				<div id = "acct-list"></div>
				<div id="ConsumerMenu">
					<ul>
						<li id="sReconnection"><img src="../assets/images/icons/icol16/src/connect.png"> RECONNECTION / CHANGE METER
						</li>
						<li id="sTemporaryLight"><img src="../assets/images/icons/icol16/src/lightbulb.png"> TEMPORARY LIGHT/ SPECIAL SERVICE
						</li>
						<li id="sTransferMeter"><img src="../assets/images/icons/icol16/src/transmit.png"> RELOCATION/ TRANSFER METER
						</li>
						<li id="sTestMeter"><img src="../assets/images/icons/icol16/src/meter1.png"> CHECK/ TEST METER
						</li>
						<li id="sDisconnection"><img src="../assets/images/icons/icol16/src/disconnect.png"> DISCONNECTION
						</li>
						<li id="sChangeBilling"><img src="../assets/images/icons/icol16/src/user.png"> UPGRADE/ DOWNGRADE
						</li>
						<li id="sChangeBilling"><img src="../assets/images/icons/icol16/src/user.png"> CHANGE BILLING NAME/ ADDRESS
						</li>
					</ul>
			   </div>
			</div>
			<div class="splitter-panel">
				<div id = "ledger-grid"></div>
				<div id="show_hide_column_window">
					<div><img  src="../assets/images/icons/icol16/src/report.png" alt="" /> Columns</div>
					<div>
						<div style="margin-top: 5px;" id="sales_prev_reading_date">Previous Reading Date</div>
						<div style="margin-top: 5px;" id="sales_prev_reading">Previous Reading</div>
						<div style="margin-top: 5px;" id="sales_reading_date">Reading Date</div>
						<div style="margin-top: 5px;" id="sales_reading">Reading</div>
						<div style="margin-top: 5px;" id="sales_adjusted">Adjusted Amount</div>
						<div style="margin-top: 5px;" id="sales_dcm">Dcm Number</div>
						<div style="margin-top: 5px;" id="sales_remarks">Remarks</div>
					</div>
				</div>
			</div>
		</div>
		<div id="newConsumerForm" style=" font-size: 10px; font-family: Verdana;">
			<div><h5><img src = "../assets/images/icons/icol16/src/application_add.png"> New Consumer Application Form</h5></div>
			<div style = "background-color: #0A525A; color: #ffffff">
				<form id="testForm" action="">
					<table width = "100%">
						<tr>
							<td width = "33%" style = "padding-top: 10px;"><div id = "customerType" class = "form-control"></div></td>
						</tr>
						<tr>
							<td style = "padding-top: 10px;"><input type = "text" id = "fname" class = "form-control" placeholder = "First Name"></td>
							<td style = "padding-top: 10px;"><input type = "text" id = "mname" class = "form-control" placeholder = "Middle Name"></td>
							<td style = "padding-top: 10px;"><input type = "text" id = "lname" class = "form-control" placeholder = "Last Name"></td>
						</tr>
						<tr>
							<td style = "padding-top: 10px;"><input type = "text" id = "ename" class = "form-control" placeholder = "Extension Name"></td>
							<td style = "padding-top: 10px;"><div id = "civilStatus" class = "form-control"></div></td>
							<td style = "padding-top: 10px;"><input type = "text" id = "spouseName" placeholder = "Name of Spouse" class = "form-control"></td>
						</tr>
						<tr>
							<td colspan = "3" class = "text-center"><h5>Location/Address to be provided with Electric Service</h5></td>
						</tr>
						<tr>
							<td style = "padding-top: 10px;"><input type = "text" id = "hno" class = "form-control" placeholder = "House No."></td>
							<td style = "padding-top: 10px;"><input type = "text" id = "purok" class = "form-control" placeholder = "Purok"></td>
							<td style = "padding-top: 10px;"><div id = "municipality" class = "form-control"></div></td>
						</tr>
						<tr>
							<td style = "padding-top: 10px;"><div id = "brgy" class = "form-control"></div></td>
						</tr>
						<tr>
							<td></td>
							<td style = "padding-top: 10px;" class = "text-center">
								<input type = "button" id = "addApp" value = "Add Application">
							</td>
							<td></td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		</div>
		<div id="print_window">
			<div><img width="14" height="14" src="../assets/images/icons/icol16/src/printer.png" alt="" />Print Document</div>
			<div id="print_window">
				PRINTING........................
			</div>
		</div>
		<div id="confirmApplication">
			<div><img  src="../assets/images/icons/icol16/src/application.png" alt="" /> CONFIRMATION</div>
			<div>
				<h4 style = "padding-bottom: 25px;" class = "text-center">Submit consumer application?</h4>
				<div class = "col-sm-6">
					<input type = "button" class = "form-control btn btn-success" id  = "acceptApp" value = "Accept">
				</div>
				<div class = "col-sm-6">
					<input type = "button" class = "form-control btn btn-danger" id  = "cancelApp" value = "Cancel">
				</div>
			</div>
		</div>
		<div id="processing">
			<div><img src="../assets/images/icons/icol16/src/accept.png" style="margin-bottom:-5px;"><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
			<div >
			<div><img src="../assets/images/loader.gif">Please Wait
			
			</div>
			</div>
		</div>
	</body>
</html>