<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
if(!isset($_SESSION['userId'])){
	header("Location:../index.php");
}
else {
	if($_SESSION['usertype'] != "ao") {
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
			echo $include->includeJSFn("ao");
		?>
		
		<script>
			$(document).ready(function(){
				var toEdit;
				var toPrint;
				var accountId;
				var appId = cid = car = so = "";
				$("#transaction_list").on("contextmenu", function () {
					return false;
				});
				
				$("#noso_list").on("contextmenu", function () {
					return false;
				});
				
				$("#tunable").on("contextmenu", function () {
					return false;
				});
				
				$("#jqxMenu").jqxMenu({ width: window.innerWidth-5, height: "30px", theme:"main-theme", autoOpen: true});
				$("#mainSplitter").jqxSplitter({
					width: window.innerWidth-5, 
					height:window.innerHeight-40,
					theme:"main-theme",
					resizable:true,
					orientation: "horizontal",
					panels: [{ size:"45%",collapsible:false  }, 
					{ size: "55%",collapsible:true }]
				});
								
				var trans_list = {
					datatype: "json",
					dataFields: [
						{ name: "consumerName" },
						{ name: "mname" },
						{ name: "address" },
						{ name: "status" },
						{ name: "so" },
						{ name: "car" },
						{ name: "remarks" },
						{ name: "dateApp"},
						{ name: "dateProcessed"},
						{ name: "acctNo"},
						{ name: "cid"},
						{ name: "appId"},
						{ name: "action"},
						{ name: "service"},
						{ name: "trans"}
					],
					url: "sources/noSOList.php"
				};
				var dataAdapter = new $.jqx.dataAdapter(trans_list);
				
				var daily_transactions = {
					datatype: "json",
					dataFields: [
						{ name: "consumerName" },
						{ name: "mname"},
						{ name: "address" },
						{ name: "status" },
						{ name: "so" },
						{ name: "car" },
						{ name: "remarks" },
						{ name: "dateApp"},
						{ name: "dateProcessed"},
						{ name: "acctNo"},
						{ name: "cid"},
						{ name: "appId"},
						{ name: "service"},
						{ name: "book"}
						
					],
					url: "sources/dailyTransactions.php"
				};
				
				var dataAdapter = new $.jqx.dataAdapter(trans_list);
				
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
				
				$("#acct1").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
				$("#acct2").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
				$("#acct3").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
				$("#acct4").jqxDropDownList({autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: acctAdapter, displayMember: "aName", valueMember: "accountId"});
								
				$("#assignAccount").click(function(){
					var acct1 = $("#acct1").jqxDropDownList("getSelectedItem");
					var acct2 = $("#acct2").jqxDropDownList("getSelectedItem");
					var acct3 = $("#acct3").jqxDropDownList("getSelectedItem");
					var acct4 = $("#acct4").jqxDropDownList("getSelectedItem");
					$.ajax({
						url: "functions/assignAccount.php",
						type: "post",
						data: {acct1: acct1.value, acct2: acct2.value, acct3: acct3.value, acct4: acct4.value},
						success: function(data){
							signatories.url = "sources/getSignatories.php";
							var acct = new $.jqx.dataAdapter(signatories);
							$("#signatory").jqxWindow("close");
							$("#signGrid").jqxGrid({source: acct});
						}
					})
				});
				
				$("#noso_list").jqxGrid({
					source: trans_list,
					width: "100%",
					height: "100%",
					theme: "main-theme",
					showtoolbar: true,
					altrows: true,
					selectionmode: "singlerow",
					pageable: true,
					rendertoolbar: function(toolbar){
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						container.append("<button id = 'soButton' >Service Order</button>");
						container.append("<button id = 'submitApp' >Submit</button>");
						toolbar.append(container);
						
						$("#soButton").jqxButton({theme: "main-theme", disabled: true, width: 130});
						$("#submitApp").jqxButton({theme: "main-theme", disabled: true, width: 130});

						$("#submitApp").on("click", function(){
							$("#submitModal").jqxWindow("open");
						});
						
						$("#soButton").click(function(){
							$("#soForm").jqxWindow("open");

							$.ajax({
								url: "sources/soForm.php",
								type: "post",
								async: true,
								data: {trans:data.trans, form:"NC"},
								success: function(out){
									$("#soFormContent").html(out);

									$('#txtControl').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
									
									$(".undertake").jqxCheckBox({checked: true, theme: "custom-abo-admin"});
									$(".service").jqxCheckBox({theme: "custom-abo-admin"});
									$("#txtDatePaid").jqxDateTimeInput({theme: "main-theme", width: "76%", formatString: 'yyyy-MM-dd'});

									$("#issue").jqxButton({
										width: '150'
									}).unbind("click").bind("click", function(event) {
										// console.log(data);
										$.ajax({
											url: "functions/issueSo.php",
											async: true,
											data: $("#frmSO").serialize()+"&trans="+data.trans,
											success: function(outIssue){
												console.log(outIssue+"dfbdfbdfbdfbasasdas");
												daily_transactions.url = 'sources/dailyTransactions.php';
												var allTransactions = new $.jqx.dataAdapter(daily_transactions);
												$('#transaction_list').jqxGrid({source:allTransactions});
												
												trans_list.url = "sources/noSOlist.php";
												var serviceOrders = new $.jqx.dataAdapter(trans_list);
												$("#noso_list").jqxGrid({source:trans_list});
												$("#soButton").jqxButton({disabled: true});
												$("#noso_list").jqxGrid("clearselection");
											}
										});
									});
									
									if($("#divReason").html().trim().length > 0) {
										$(".reason").jqxRadioButton({
											checked: false,
											theme: "main-theme",
											groupName: "rbReason"
										});
									}
								}
							});
						});
					},
					columns: [
						{text: "Account Number", pinned: true, dataField: "acctNo", cellsalign: "center", align: "center", width: 150},
						{text: "Consumer Name", pinned: true, dataField: "consumerName", align: "center", width: 250},
						{text: "Address", dataField: "address", align: "center", width: 290},
						{text: "Application Date", dataField: "dateApp", cellsalign: "center", align: "center", width: 150},
						{text: "Processed Date", dataField: "dateProcessed", cellsalign: "center", align: "center", width: 150},
						{text: "S.O.", dataField: "so", cellsalign: "center", align: "center", width: 100},
						{text: "C.A.R.", dataField: "car", cellsalign: "center", align: "center", width: 150},
						{text: "Application", dataField: "service", cellsalign: "center", align: "center", width: 150},
						{text: "Status", dataField: "status", cellsalign: "center", align: "center", width: 150},
						{text: "Remarks", dataField: "remarks", align: "center", width: 150}
					]
				});
				
				$("#confirmApp").click(function(){
					$.ajax({
						url: "functions/confirmApp.php",
						type: "post",
						data: {appId: appId},
						success: function(result){
							console.log(result);
						}						
					})
				});
				
				$("#print_car").on("close", function(event){
					$("#carModal").jqxWindow("close");
					daily_transactions.url = 'sources/dailyTransactions.php';
					var allTransactions = new $.jqx.dataAdapter(daily_transactions);
					$('#transaction_list').jqxGrid({source:allTransactions});
					
					trans_list.url = "sources/noSOlist.php";
					var serviceOrders = new $.jqx.dataAdapter(trans_list);
					$("#noso_list").jqxGrid({source:trans_list});
					var rowindex = $('#transaction_list').jqxGrid('getselectedrowindex');
					$('#transaction_list').jqxGrid('unselectrow', rowindex);
					
					$("#car").jqxButton({disabled: true});
				});
				
				$("#transaction_list").jqxGrid({
					source: daily_transactions,
					width: "100%",
					height: "100%",
					theme: "main-theme",
					showtoolbar: true,
					altrows: true,
					selectionmode: "singlerow",
					columnsresize: true,
					pageable: true,
					filterable: true,
					rendertoolbar: function(toolbar){
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						container.append('<input id="dailyT" type="button" value="Daily Transactions" />');
						container.append('<input id="allT" type="button" value="All Transactions" />');
						container.append('<input id="car" type="button" value="C.A.R." />');
						toolbar.append(container);
						
						$("#dailyT").jqxButton({theme: "main-theme", width: 130});
						$("#allT").jqxButton({theme: "main-theme", width: 130});
						$("#car").jqxButton({theme: "main-theme", disabled: true, width: 130});
						
						$("#car").click(function(){
							if(toPrint == 1){
								$("#print_car").jqxWindow("open");
								$("#print_car").jqxWindow("setContent", "<iframe src = 'print_car.php?ref="+appId+"&car="+car+"' width = '99%' height = '98%'></iframe>");
							} else $("#carModal").jqxWindow("open");
						});
						
						
						
						$('#allT').click(function() {
							daily_transactions.url = 'sources/allTransactions.php';
							
							var allTransactions = new $.jqx.dataAdapter(daily_transactions);
							$('#transaction_list').jqxGrid({source:allTransactions});
						});
						
						$('#dailyT').click(function() {
							daily_transactions.url = 'sources/dailyTransactions.php';
							
							var allTransactions = new $.jqx.dataAdapter(daily_transactions);
							$('#transaction_list').jqxGrid({source:allTransactions});
						});

						if (theme != "") {
							input.addClass("jqx-widget-content-" + theme);
							input.addClass("jqx-rc-all-" + theme);
						}
					},
					columns: [
						{text: "Account Number", dataField: "acctNo", cellsalign: "center", align: "center", pinned: true, width: 150},
						{text: "Consumer Name", dataField: "consumerName", align: "center", pinned: true, width: 250},
						{text: "Middle Name", dataField: "mname", align: "center", pinned: true, width: 130},
						{text: "Address", dataField: "address", align: "center", width: 290},
						{text: "Application Date", dataField: "dateApp", cellsalign: "center", align: "center", width: 150},
						{text: "S.O.", dataField: "so", cellsalign: "center", align: "center", width: 100},
						{text: "C.A.R.", dataField: "car", cellsalign: "center", align: "center", width: 150},
						{text: "Application", dataField: "service", cellsalign: "center", align: "center", width: 100},
						{text: "Book", dataField: "book", cellsalign: "center", align: "center", width: 150},
						{text: "Status", dataField: "status", cellsalign: "center", align: "center", width: 150},
						{text: "Processed Date", dataField: "dateProcessed", cellsalign: "center", align: "center", width: 150},
						{text: "Remarks", dataField: "remarks", align: "center", width: 150}
					]
				});
				
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
							console.log(toEdit);
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
				
				var signatories = {
					datatype: "json",
					dataFields: [
						{name: "sign"},
						{name: "name"},
						{name: "position"}
					],
					url: "sources/getSignatories.php"
				};
				
				$("#signGrid").jqxGrid({
					source: signatories,
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
				
				$('#accountModal').jqxValidator({
					rules: [
						{ input: '#aFname', message: 'First Name is required', action: 'keyup, blur', rule: 'required' },
						{ input: '#aMname', message: 'Middle Name is required', action: 'keyup, blur', rule: 'required' },
						{ input: '#aLname', message: 'Last Name is required', action: 'keyup, blur', rule: 'required' },
						{ input: '#aPosition', message: 'Position is required', action: 'keyup, blur', rule: 'required' },
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
								console.log(data);
								accounts.url = "sources/accounts.php";
								var accountAdapter = new $.jqx.dataAdapter(accounts);
								
								$("#accountGrid").jqxGrid({source: accountAdapter});
								$("#tableForm input").val("");
								$("#addAcctModal").jqxWindow("close");
							}
						})
					}
				});
				
				$("#ok").jqxButton({theme: "main-theme", width: 100})
				
				$("#confirmCar").click(function(){
					$.ajax({
						url: "functions/issueCar.php",
						type: "post",
						data: {appId: appId, car: $("#carNo").val()},
						success: function(data){
							$("#print_car").jqxWindow("open");
							$("#print_car").jqxWindow("setContent", "<iframe src = 'print_car.php?ref="+appId+"&car="+data+"' width = '99%' height = '98%'></iframe>");
						}
					})
				});
				
				$("#transaction_list").on("rowselect", function(event){
					var args = event.args;
					// row's bound index.
					var rowBoundIndex = args.rowindex;
					// row's data. The row's data object or null(when all rows are being selected or unselected with a single action). If you have a datafield called "firstName", to access the row's firstName, use var firstName = rowData.firstName;
					var rowData = args.row;
					appId = rowData.appId;
					car = rowData.car;
					$.ajax({
						url: "sources/checkForCar.php",
						type: "post",
						data: {appId: appId, cid: rowData.cid},
						success: function(data){
							console.log(data);
							if(data == 1){
								$("#car").jqxButton({disabled: false});
								toPrint = 0;
							}
							else if(data == 2){ 
								$("#car").jqxButton({disabled: false}); 
								toPrint = 1;
							}
							else $("#car").jqxButton({disabled: true});
						}
					})
				});
				
				
				$('#noso_list').on('rowselect', function (event) {
					var args = event.args;
					// row's bound index.
					var rowBoundIndex = args.rowindex;
					// row's data. The row's data object or null(when all rows are being selected or unselected with a single action). If you have a datafield called "firstName", to access the row's firstName, use var firstName = rowData.firstName;
					data = args.row;
					appId = data.appId;
					if(data.so == null){
						$("#soButton").jqxButton({disabled: false});
						$("#submitApp").jqxButton({disabled: true});
					} else{
						$("#soButton").jqxButton({disabled: true});
						$("#submitApp").jqxButton({disabled: false});
					}
				});
				
				$("#bd").on("keyup", function(event){
					var mf = parseFloat($("#mf").val());
					var bd = parseFloat($("#bd").val());
					
					$("#tAmount").val(mf+bd);
				});
				
				var munList = {
					datatype: "json",
					dataFields: [
						{ name: "munId" },
						{ name: "munDesc" }
					],
					url: "sources/getMunicipality.php",
					async: false
				};
				
				var munAdapter = new $.jqx.dataAdapter(munList);

				var cStatusList = [
					"SINGLE",
					"MARRIED",
					"WIDOWED",
					"SEPARATED"
				];
				
				$("#newConsumer").on("click", function(event){
					$("#newConsumerForm").jqxWindow("open");
					
					$.ajax({
						url: "sources/appForm.php",
						type: "post",
						async: true,
						success: function(out){
							$("#appFormContent").html(out);

							$('#primary, #phone').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
							
							$("#municipality").jqxDropDownList({ 
								autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source:munAdapter, displayMember: 'munDesc', valueMember: 'munId', theme:'main-theme'
							}).unbind("change").on("change", function(event){
								var mun = $("#municipality").jqxDropDownList("getSelectedItem");
								var sBrgy = {
									datatype: "json",
									dataFields: [
										{ name: "bid" },
										{ name: "brgyName" }
									],
									url: "sources/getBarangay.php?id="+mun.value,
									async: false
									
								}
								var d = new $.jqx.dataAdapter(sBrgy);
								$("#brgy").jqxDropDownList({ 
										selectedIndex: 0, width: "82%", height: 20, 
										source:d, displayMember: 'brgyName', valueMember: 'bid', theme:'main-theme'
								});
							});
							
							var mun = $("#municipality").jqxDropDownList("getSelectedItem");
							var brgy_list = {
								datatype: "json",
								dataFields: [
									{ name: "bid" },
									{ name: "brgyName" }
								],
								url: "sources/getBarangay.php?id="+mun.value,
								async: false
							};
							
							var brgyAdapter = new $.jqx.dataAdapter(brgy_list);
							
							$("#brgy").jqxDropDownList({ 
								selectedIndex: 0, width: "82%", height: 20, 
								source:brgyAdapter, displayMember: 'brgyName', valueMember: 'bid', theme:'main-theme'
							});
							
							$("#civilStatus").jqxDropDownList({
								autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 20, 
								source: cStatusList, theme:'main-theme'
							});
							
							$("#customerType").jqxDropDownList({
								autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 16, 
								source: ["R", "C", "H", "F", "E"], theme:'main-theme'
							});
							
							$("#isBapa").jqxDropDownList({
								autoDropDownHeight: true, selectedIndex: 0, width: "91%", height: 16, 
								source: ["NON-BAPA", "BAPA"], theme:'main-theme'
							});
							
							// $("#acceptApp").jqxButton({width: "100%", theme: "main-theme"});
							// $(".cancelApp").jqxButton({width: "100%", theme: "main-theme"});
							
							$("#addApp").jqxButton({ theme:'main-theme',height:35,width:'100%',disabled:false});
							$("#canApp").jqxButton({ theme:'main-theme',height:35,width:'100%',disabled:false});
							
							$("#addApp").unbind("click").on("click", function(event){
								$("#confirmApplication").jqxWindow("open");
							});

							$("#canApp").unbind("click").on("click", function(event){
								$("#newConsumerForm").jqxWindow("close");
							});
							
							$(".cancelApp").unbind("click").on("click", function(event){
								$("#confirmApplication").jqxWindow("close");
								$("#confirmApplication1").jqxWindow("close");
								$("#confirmApplication2").jqxWindow("close");
								$("#confirmSO").jqxWindow("close");
							});
						}
					});
				});
				
				$("#acceptApp").click(function(event){
					$.ajax({
						type: "post",
						url: "functions/addApplication.php",
						processData: false,
						contentType: false,
						data: new FormData($("#testForm")[0]),
						success: function(data){
							if(data){
								$("#confirmApplication").jqxWindow("close");
								$("#newConsumerForm").jqxWindow("close");
								
								daily_transactions.url = 'sources/dailyTransactions.php';
							
								var allTransactions = new $.jqx.dataAdapter(daily_transactions);
								$('#transaction_list').jqxGrid({source:allTransactions});

									// window.location.href = "transactions.php";
							}
						}
					});
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
				
				$("#addAccount").click(function(){
					$("#accountModal").jqxWindow("open");
				});
				
				$("#signatories").click(function(){
					$("#signatoryModal").jqxWindow("open")
				});
				
				$("#reports").click(function(){
					$("#reportModal").jqxWindow("open");
				});
				
				var daily_report = {
					datatype: "json",
					dataFields: [
						{ name: "date" },
						{ name: "so" },
						{ name: "cType" },
						{ name: "remarks" },
						{ name: "acctNo" },
						{ name: "acctName" },
						{ name: "mName" },
						{ name: "sName" },
						{ name: "cStatus" },
						{ name: "address" },
						{ name: "municipality" },
						{ name: "feeder" },
						{ name: "bookNo" }
					],
					url: "sources/dailyReports.php",
					async: false
				};
				
				var dailyAdapter = new $.jqx.dataAdapter(daily_report);
				
				$("#reportGrid").jqxGrid({
					width: "100%",
					height: "99%",
					source: dailyAdapter,
					showtoolbar: true,
					theme: "main-theme",
					rendertoolbar: function(toolbar){
						var container = $("<div style='margin: 5px;'></div>");
						container.append("<button id = 'dailyR' >Daily</button>");
						container.append("<button id = 'monthlyR' >Monthly</button>");
						toolbar.append(container);
						
						$("#dailyR").jqxButton({theme: "main-theme", width: 100});
						$("#monthlyR").jqxButton({theme: "main-theme", width: 100});
						
						$("#dailyR").on("click", function(){
							daily_report.url = "sources/dailyReports.php";
							var dataAdapter = new $.jqx.dataAdapter(daily_report);
							$("#reportGrid").jqxGrid({source: dataAdapter});
						});
						
						$("#monthlyR").on("click", function(){
							daily_report.url = "sources/monthlyReport.php";
							var dataAdapter = new $.jqx.dataAdapter(daily_report);
							$("#reportGrid").jqxGrid({source: dataAdapter});
						});
					},
					columns: [
						{text: "Date", dataField: "date", align: "center", cellsalign: "center", width: 150},
						{text: "SO#", dataField: "so", align: "center", cellsalign: "center", width: 90},
						{text: "Customer Type", dataField: "cType", align: "center", cellsalign: "center", width: 110},
						{text: "Remarks", dataField: "remarks", align: "center", cellsalign: "center", width: 150},
						{text: "Account Number", dataField: "acctNo", align: "center", cellsalign: "center", width: 140},
						{text: "Account Name", dataField: "acctName", align: "center", width: 250},
						{text: "Middle Name", dataField: "mName", align: "center", width: 150},
						{text: "Address", dataField: "address", align: "center", width: 300},
						{text: "Municipality", dataField: "municipality", align: "center", cellsalign: "center", width: 150},
						{text: "Spouse Name", dataField: "sName", align: "center", cellsalign: "center", width: 150},
						{text: "Type", dataField: "cStatus", align: "center", cellsalign: "center", width: 50},
						{text: "Feeder", dataField: "feeder", align: "center", cellsalign: "center", width: 150},
						{text: "Book No", dataField: "bookNo", align: "center", cellsalign: "center", width: 150},
					]
				});
				
				var req = {
					datatype: "json",
					dataFields: [
						{name: "rDesc"},
					],
					url: "sources/requirements.php"
				}
				
				$("#rGrid").jqxGrid({
					width: "100%",
					height: "99%",
					showtoolbar: true,
					columns: [
						{text: "Requirement Description", dataField: "name", align: "center", cellsalign: "center", width: "100%"},
					]
				});
				
				//jqxwindows
				$("#unable").jqxWindow({
					height: 150, width:  300, cancelButton: $('#ok'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#submitModal").jqxWindow({
					height: 150, width:  300, cancelButton: $('#cancelA'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#rWindow").jqxWindow({
					height: 300, width:  600, cancelButton: $('#rcancel'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#print_car").jqxWindow({
					height: 600, width:  800,resizable: false,  isModal: true, autoOpen: false, modalOpacity: 0.3,theme:'main-theme'
				});
				
				$("#print_so").jqxWindow({
					height: 600, width:  800,resizable: false,  isModal: true, autoOpen: false, modalOpacity: 0.3,theme:'main-theme'
				});
				
				$("#soForm").jqxWindow({
					height: 580, width:  730,resizable: false,  isModal: true, autoOpen: false, modalOpacity: 0.3,theme:'main-theme'
				}).on('close', function (event) {
					$("#soFormContent").html("Loading form...");
				});

				$("#confirmApplication").jqxWindow({
					height: 150, width:  300, showCloseButton: false, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#confirmApplication1").jqxWindow({
					height: 170, width:  300, showCloseButton: false, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#confirmApplication2").jqxWindow({
					height: 170, width:  300, showCloseButton: false, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#confirmSO").jqxWindow({
					height: 170, width:  350, showCloseButton: false, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#carModal").jqxWindow({
					height: 150, width:  450, cancelButton: $('#cancel'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
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
				
				$("#reportModal").jqxWindow({
					maxWidth: 1200, maxHeight: 550, height: 500, width: 1200, showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#addAcctModal").jqxWindow({
					height: 280, width:  350, cancelButton: $('#cancelAccount'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#newConsumerForm").jqxWindow({
					height: 620, width:  600, showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				}).on('close', function (event) {
					$("#appFormContent").html("Loading form...");
				});
				 
				$('#processing').jqxWindow({width: 380, height:80, resizable: false,  isModal: true,showCloseButton:false, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'});
			});
			
			function performClick(elemId) {
			   var elem = document.getElementById(elemId);
			   if(elem && document.createEvent) {
			      var evt = document.createEvent("MouseEvents");
			      evt.initEvent("click", true, false);
			      elem.dispatchEvent(evt);
			   }
			};

			function PreviewImage() {
		        var oFReader = new FileReader();
		        oFReader.readAsDataURL(document.getElementById("uploader").files[0]);

		        oFReader.onload = function (oFREvent) {
		            document.getElementById("conPic").src = oFREvent.target.result;
		        };
		    };

		    function performClick(elemId) {
			   var elem = document.getElementById(elemId);
			   if(elem && document.createEvent) {
			      var evt = document.createEvent("MouseEvents");
			      evt.initEvent("click", true, false);
			      elem.dispatchEvent(evt);
			   }
			}
		</script>
	</head>
	<body class="default">
		<div class="row push-right-m2">
			<div id="jqxMenu" >
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "index.php"> Home</a></li>
					<li><img  src="../assets/images/icons/icol16/src/zone_money.png" alt="" /><a href = "javascript:location.reload()"> Transactions</a></li>
					<li id = "newConsumer"><img  src="../assets/images/icons/icol16/src/group.png" alt=""/>New Consumer</li>
					<li id = "reports"><img  src="../assets/images/icons/icol16/src/report.png" alt=""/>Reports</li>
					<li><img src = "../assets/images/icons/icol16/src/user.png">
						Accounts
						<ul>
							<li id = "addAccount"><img src = "../assets/images/icons/icol16/src/add.png"> Add Account</li>
							<li id = "signatories"><img src = "../assets/images/icons/icol16/src/pencil.png"> Signatories</li>
						</ul>
					</li>
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
				</ul>
			</div>
			<div id = "mainSplitter">
				<div class="splitter-panel">
					<div id = "transaction_list"></div>
				</div>
				<div class="splitter-panel">
					<div id = "noso_list"></div>
				</div>
			</div>
				
			<div id="newConsumerForm" style=" font-size: 10px; font-family: Verdana;">
				<div>
					<h5 style="margin: 0;"><img src = "../assets/images/icons/icol16/src/application_add.png"> New Consumer Application Form</h5>
				</div>
				
				<div id="appFormContent" style = "background-color: #0A525A; color: #ffffff">
					Loading form...
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
						<input type = "button" class = "form-control btn btn-danger cancelApp"  value = "Cancel">
					</div>
				</div>
			</div>
			<div id="confirmApplication1">
				<div><img  src="../assets/images/icons/icol16/src/application.png" alt="" /> CONFIRMATION</div>
				<div>
					<h4 style = "padding-bottom: 25px;" class = "text-center">Send application for meter installation?</h4>
					<div class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-success" id  = "sendApp" value = "Yes">
					</div>
					<div class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-danger cancelApp"  value = "No">
					</div>
				</div>
			</div>
			
			<div id="confirmApplication2">
				<div><img  src="../assets/images/icons/icol16/src/application.png" alt="" /> CONFIRMATION</div>
				<div>
					<h4 style = "padding-bottom: 25px;" class = "text-center">Issue C.A.R</h4>
					<div class = "col-sm-6"> 
						<input type = "button" class = "form-control btn btn-success" id  = "issueCar" value = "Issue">
					</div>
					<div class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-danger cancelApp"  value = "No">
					</div>
				</div>
			</div>
			
			<div id="confirmSO">
				<div><img  src="../assets/images/icons/icol16/src/application.png" alt="" /> CONFIRMATION</div>
				<div>
					<h4 style = "padding-bottom: 25px;" class = "text-center">Issue Service Order for NEW CONNECTION?</h4>
					<div class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-success" id  = "acceptSo" value = "Accept">
					</div>
					<div class = "col-sm-6">
						<input type = "button" class = "form-control cancelApp btn btn-danger" value = "Cancel">
					</div>
				</div>
			</div>
			<div id="processing">
				<div><img src="../assets/images/icons/icol16/src/accept.png" ><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
				<div >
				<div><img src="../assets/images/loader.gif">Please Wait
				
				</div>
				</div>
			</div>
			<div id="unable">
				<div><img src="../assets/images/icons/icol16/src/error.png" ><b><span style="margin-top:-24; margin-left:3px">Unable to continue</span></b></div>
				<div >
				<div>
					<h5 class = "text-center" style = "margin-top: 20px;">The application must be approved first.</h5>
					<div class = "text-center" style = "margin-top: 35px;">
						<input type="button" id="ok" value="OK"/>
					</div>
				</div>
				</div>
			</div>
			<div id="print_car">
				<div><img width="14" height="14" src="../assets/images/icons/icol16/src/printer.png" alt="" />Print C.A.R</div>
				<div id="print_car">
					PRINTING........................
				</div>
			</div>
			<div id="print_so">
				<div><img width="14" height="14" src="../assets/images/icons/icol16/src/printer.png" alt="" />Print SERVICE ORDER</div>
				<div id="print_so">
					PRINTING........................
				</div>
			</div>
			<div id = "soForm">
				<div>
					<img width="14" height="14" src="../assets/images/icons/icol16/src/application2.png" alt="" /> SERVICE ORDER
				</div>

				<div id="soFormContent" style = "padding:10px; background-color: #0A525A; color: #ffffff">
					Loading form...
				</div>
			</div>
			<div id="carModal">
				<div><img src="../assets/images/icons/icol16/src/accept.png" ><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
				<div >
					<br>
					<input type = "text" id = "carNo" placeholder = "C.A.R." class = "form-control" maxlength="15">
					<br>
					<div class = "col-sm-6">
						<button id = "confirmCar" class = "btn btn-success btn-block">Confirm</button>
					</div>
					<div class = "col-sm-6">
						<button id = "cancel" class = "btn btn-danger btn-block">Cancel</button>
					</div>
				</div>
			</div>
			<div id="rWindow">
				<div><img src="../assets/images/icons/icol16/src/accept.png" ><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
				<div >
					<div id = "rGrid"></div>
				</div>
			</div>
			<div id="reportModal">
				<div><img src="../assets/images/icons/icol16/src/report.png" ><b><span style="margin-top:-24; margin-left:3px">Reports</span></b></div>
				<div >
					<div id = "reportGrid"></div>
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
			<div id="submitModal">
				<div><img src="../assets/images/icons/icol16/src/accept.png"><b><span style="margin-top:-24; margin-left:3px"> Submit</span></b></div>
				<div>
					<h4 class = "text-center">Approve application</h4><br>
					<div class = "col-sm-6">
						<button id = "confirmApp" class = "btn btn-success btn-block">Confirm</button>
					</div>
					<div class = "col-sm-6">
						<td><button id = "cancelA" class = "btn btn-danger btn-block">Cancel</button></td>
					</div>
				</div>
			</div>
			<div id="signatory">
				<div><img src="../assets/images/icons/icol16/src/pencil.png"><b><span style="margin-top:-24; margin-left:3px"> Signatories</span></b></div>
				<div>
					<table class = "table table-bordered">
						<tr>
							<td width = "40%"><h5>APPROVED BY (C.A.R): </h5></td>
							<td><div class = "form-control" id = "acct1"></div></td>
						</tr>
						<tr>
							<td><h5>NOTED BY (C.A.R.)</h5></td>
							<td><div class = "form-control" id = "acct2"></div></td>
						<tr>
						</tr>
							<td><h5>PROCESSED BY (ACCOUNT NUMBER):</h5></td>
							<td><div class = "form-control" id = "acct3"></div></td>
						<tr>
						<tr>
							<td><h5>APPROVED BY (ACCOUNT NUMBER):</h5></td>
							<td><div class = "form-control" id = "acct4"></div></td>
						</tr>
					</table>
					<div class = "col-sm-6">
						<button id = "assignAccount" class = "btn btn-success btn-block">Assign</button>
					</div>
					<div class = "col-sm-6">
						<td><button id = "cancelAcct" class = "btn btn-danger btn-block">Cancel</button></td>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>