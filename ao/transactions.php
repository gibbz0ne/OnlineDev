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
				var toPrint;
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
				
				$("#jqxMenu").jqxMenu({ width: window.innerWidth-5, height: "30px", theme:"main-theme", autoOpen:false});
				
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
						{ name: "bName"},
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
						{ name: "service"}
						
					],
					url: "sources/dailyTransactions.php"
				};
				
				var dataAdapter = new $.jqx.dataAdapter(trans_list);
				
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
						var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField' type='text' style='height: 23px; float: left; width: 223px;' />");
						var searchButton = $("<div style='float: left; margin-left: 5px;' id='search'><img style='position: relative; margin-top: 2px;' src='../assets/images/search_lg.png'/><span style='margin-left: 4px; position: relative; top: -3px;'></span></div>");
						var dropdownlist2 = $("<div style='float: left; margin-left: 5px;' id='dropdownlist'></div>");
						container.append(span);
						toolbar.append(container);
						container.append(span);
						container.append(input);
						container.append(dropdownlist2);
						container.append(searchButton);
						
						
						$("#search").jqxButton({theme:"main-theme",height:18,width:24});
						$("#dropdownlist").jqxDropDownList({ 
							autoDropDownHeight: true,
							selectedIndex: 0,
							theme:"main-theme", 
							width: 200, 
							height: 25, 
							source: [
								"Consumer Name", "Address"
							]
						});
												
						if (theme != "") {
							input.addClass("jqx-widget-content-" + theme);
							input.addClass("jqx-rc-all-" + theme);
						}
						$("#search").click(function(){
							$("#noso_list").jqxGrid('clearfilters');
							var searchColumnIndex = $("#dropdownlist").jqxDropDownList('selectedIndex');
							var datafield = "";
							switch (searchColumnIndex) {
								case 0:
									datafield = "consumerName";
									break;
								case 1:
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
							$("#noso_list").jqxGrid('addfilter', datafield, filtergroup);
							$("#noso_list").jqxGrid('applyfilters');
						});
						
						var oldVal = "";
						input.on('keydown', function (event) {
							var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
								
							if (key == 13 || key == 9) {
								$("#noso_list").jqxGrid('clearfilters');
								var searchColumnIndex = $("#dropdownlist").jqxDropDownList('selectedIndex');
								var datafield = "";
								switch (searchColumnIndex) {
									case 0:
										datafield = "consumerName";
										break;
									case 1:
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
								$("#noso_list").jqxGrid('addfilter', datafield, filtergroup);
								$("#noso_list").jqxGrid('applyfilters');
							}
						   
							if(key == 27){
								$("#noso_list").jqxGrid('clearfilters');
								return true;
							}
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
						{text: "Action", dataField: "action", cellsalign: "center", align: "center", width: 150},
						{text: "Remarks", dataField: "remarks", align: "center", width: 150}
					]
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
						var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField1' type='text' style='height: 23px; float: left; width: 223px;' />");
						var searchButton = $("<div style='float: left; margin-left: 5px;' id='search'><img style='position: relative; margin-top: 2px;' src='../assets/images/search_lg.png'/><span style='margin-left: 4px; position: relative; top: -3px;'></span></div>");
						container.append('<input id="dailyT" type="button" value="Daily Transactions" />');
						container.append('<input id="allT" type="button" value="All Transactions" />');
						container.append('<input id="car" type="button" value="C.A.R." />');
						toolbar.append(container);
						container.append(span);
						container.append(input);
						container.append(searchButton);
						
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
						$("#search").jqxButton({theme:"main-theme",height:18,width:24});

						if (theme != "") {
							input.addClass("jqx-widget-content-" + theme);
							input.addClass("jqx-rc-all-" + theme);
						}
						$("#search").click(function(){
							$("#transaction_list").jqxGrid('clearfilters');
							var searchColumnIndex = $("#dropdownlist").jqxDropDownList('selectedIndex');
							var datafield = "";
							switch (searchColumnIndex) {
								case 0:
									datafield = "consumerName";
									break;
								case 1:
									datafield = "address";
									break;
								
							}

							var searchText = $("#searchField1").val();
							var filtergroup = new $.jqx.filter();
							var filter_or_operator = 1;
							var filtervalue = searchText;
							var filtercondition = 'contains';
							var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
							filtergroup.addfilter(filter_or_operator, filter);
							$("#transaction_list").jqxGrid('addfilter', datafield, filtergroup);
							$("#transaction_list").jqxGrid('applyfilters');
						});
						
						var oldVal = "";
						input.on('keydown', function (event) {
							var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
								
							if (key == 13 || key == 9) {
								$("#transaction_list").jqxGrid('clearfilters');
								var searchColumnIndex = $("#dropdownlist").jqxDropDownList('selectedIndex');
								var datafield = "";
								switch (searchColumnIndex) {
									case 0:
										datafield = "consumerName";
										break;
									case 1:
										datafield = "address";
										break;
								}
								var searchText = $("#searchField1").val();
								var filtergroup = new $.jqx.filter();
								var filter_or_operator = 1;
								var filtervalue = searchText;
								var filtercondition = 'contains';
								var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
								filtergroup.addfilter(filter_or_operator, filter);
								$("#transaction_list").jqxGrid('addfilter', datafield, filtergroup);
								$("#transaction_list").jqxGrid('applyfilters');
							}
						   
							if(key == 27){
								$("#transaction_list").jqxGrid('clearfilters');
								return true;
							}
						});
					},
					columns: [
						{text: "Account Number", dataField: "acctNo", cellsalign: "center", align: "center", pinned: true, width: 150},
						{text: "Consumer Name", dataField: "consumerName", align: "center", pinned: true, width: 250},
						{text: "Business Name", dataField: "bName", align: "center", pinned: true, width: 250},
						{text: "Address", dataField: "address", align: "center", width: 290},
						{text: "Application Date", dataField: "dateApp", cellsalign: "center", align: "center", width: 150},
						{text: "S.O.", dataField: "so", cellsalign: "center", align: "center", width: 100},
						{text: "C.A.R.", dataField: "car", cellsalign: "center", align: "center", width: 150},
						{text: "Application", dataField: "service", cellsalign: "center", align: "center", width: 150},
						{text: "Status", dataField: "status", cellsalign: "center", align: "center", width: 150},
						{text: "Action", dataField: "action", cellsalign: "center", align: "center", width: 150},
						{text: "Processed Date", dataField: "dateProcessed", cellsalign: "center", align: "center", width: 150},
						{text: "Remarks", dataField: "remarks", align: "center", width: 150}
					]
				});
				
				$("#ok").jqxButton({theme: "main-theme", width: 100})
				
				$("#confirmCar").click(function(){
					$.ajax({
						url: "functions/issueCar.php",
						type: "post",
						data: {appId: appId, car: $("#carNo").val()},
						success: function(data){
							console.log(data);
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
				
				$('#noso_list').on('rowdoubleclick', function (event) {
					var rowindex = $(this).jqxGrid('getselectedrowindex');
					var data = $(this).jqxGrid('getrowdata',rowindex);
					var so = data.so;
					var appId = data.appId;
					var cid = data.cid;
					
					if(data.acctNo) {
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
								
								$("#issue").jqxButton({
									width: '150'
								}).unbind("click").bind("click", function(event) {
									$.ajax({
										url: "functions/issueSo.php",
										async: true,
										data: $("#frmSO").serialize()+"&trans="+data.trans,
										success: function(outIssue){
											// $("#soForm").jqxWindow("close");
											// $('#processing').jqxWindow('close');

											// alert(outIssue);
											// return;

											$("#soForm").jqxWindow("close");
											$('#processing').jqxWindow('open');
											setTimeout(function(){
												$('#processing').jqxWindow('close');
												location.reload();
											},3000);
										}
									});
								});
								
								if($("#divReason").html().trim().length > 0) {
									$(".reason").jqxRadioButton({
										checked: false,
										theme: "custom-abo-admin",
										groupName: "rbReason"
									});
								}
							}
						});
					}
					else {
						alert("Primary account number not yet assigned.");
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
							
							$(".connection").jqxRadioButton({
								checked: false,
								theme: "custom-abo-admin",
								groupName: "rbType"
							}).on("change", function(event) {
								var lvSub = '';
								
								if(event.args.checked) {
									var rbID = event.target.id.split("-");
									$("#subCat").html("Loading...");
									
									$.ajax({
										url: "sources/subLV.php",
										type: "post",
										dataType: "json",
										async: true,
										data: {conID:rbID[1]},
										success: function(outLV){
											for(var i = 0; i < outLV.length; i++) {
												if(i == 0 && outLV.length > 0) {
													lvSub += '<strong>Select from below list:</strong><p>';
												}
												
												lvSub += '<div style="color: white;" id="s-'+outLV[i].subId+'" name="s-'+outLV[i].subId+'" class="lvSub">&nbsp;'+outLV[i].subDesc+'</div>';
												
												if(i == (outLV.length - 1)) {
													lvSub += '</p>';
												}
											}
											$("#subCat").html(lvSub);
											
											if(lvSub != '') {
												$(".lvSub").jqxRadioButton({
													checked: false,
													theme: "custom-abo-admin",
													groupName: "rbSType"
												});
												$("#s-1").jqxRadioButton({checked: true});
											}
											
										}
									});
								}
								else {
									$("#subCat").html("");
								}
							});
							$("#c-1").jqxRadioButton({checked: true});
							
							$("#municipality").jqxDropDownList({ 
								selectedIndex: 0, width: "91%", height: 20, 
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
								autoDropDownHeight: 200, selectedIndex: 0, width: "91%", height: 20, 
								source: cStatusList, theme:'main-theme'
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
					// var uploader = document.getElementById("uploader").files[0]
					// var tmppath = URL.createObjectURL(uploader);

					$('#processing').jqxWindow('open');
					$.ajax({
						type: "post",
						url: "functions/addApplication.php",
						processData: false,
						contentType: false,
						data: new FormData($("#testForm")[0]),
						success: function(data){
							if(!data) {
								$('#processing').jqxWindow('close');
								$("#confirmApplication").jqxWindow("close");
								// setTimeout(function(){
									// alert("Account number already taken.");
								// },1000);
								alert(data);
							}
							else {
								$("#confirmApplication").jqxWindow("close");
								$("#newConsumerForm").jqxWindow("close");
								setTimeout(function(){
									$('#processing').jqxWindow('close');
									window.location.href = "transactions.php";
								},1000);
							}
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
				
				//jqxDropDownList
				
				//jqxwindows
				$("#unable").jqxWindow({
					height: 150, width:  300, cancelButton: $('#ok'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
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
					height: 150, width:  450, cancelButton: $('#cancel'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'custom-abo-ao'
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
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
				</ul>
			</div>
			<div id = "mainSplitter">
				<div class="splitter-panel">
					<div id = "transaction_list"></div>
				</div>
				<div class="splitter-panel">
					<div id = "noso_list"></div>
					<div id="options">
						<ul>
							<!--li id="issueSo"><img src="../assets/images/icons/icol16/src/page.png"> Issue Service Order</li-->
							<li id="car1"><img src="../assets/images/icons/icol16/src/page_2.png"> C.A.R.</li>
						</ul>
					</div>
					<div id="options1">
						<ul>
							<li id="so"><img src="../assets/images/icons/icol16/src/page.png"> Service Order</li>
							<li id="car2"><img src="../assets/images/icons/icol16/src/page_2.png"> C.A.R.</li>
						</ul>
					</div>
					<div id="options2">
						<ul>
							<li id="so1"><img src="../assets/images/icons/icol16/src/page.png"> Service Order</li>
							<li id="car2"><img src="../assets/images/icons/icol16/src/page_2.png"> C.A.R.</li>
							<li id="mi"><img src="../assets/images/icons/icol16/src/meter.png"> Meter Installation</li>
						</ul>
					</div>
					<div id="options3">
						<ul>
							<!--li id="so2"><img src="../assets/images/icons/icol16/src/page.png"> Service Order</li-->
							<li id="issueCar2"><img src="../assets/images/icons/icol16/src/page_2.png"> Issue C.A.R.</li>
						</ul>
					</div>
					<div id="options4">
						<ul>
							<li id="so2"><img src="../assets/images/icons/icol16/src/page.png"> Service Order</li>
							<li id="issueCar3"><img src="../assets/images/icons/icol16/src/page_2.png"> Issue C.A.R.</li>
						</ul>
					</div>
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
				<div><img src="../assets/images/icons/icol16/src/accept.png" style="margin-bottom:-5px;"><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
				<div >
				<div><img src="../assets/images/loader.gif">Please Wait
				
				</div>
				</div>
			</div>
			<div id="unable">
				<div><img src="../assets/images/icons/icol16/src/error.png" style="margin-bottom:-5px;"><b><span style="margin-top:-24; margin-left:3px">Unable to continue</span></b></div>
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
				<div><img src="../assets/images/icons/icol16/src/accept.png" style="margin-bottom:-5px;"><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
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
		</div>
	</body>
</html>