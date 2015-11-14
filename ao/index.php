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
	?>
	
	<script>
		$(document).ready(function(){
			var req1 = req2 = req3 = req4 = req5 = req6 = req7 = 0;
			var q1 = q2 = q3 = q4 = q5 = q6 = 1;
			var ownerName = ownerAddress = prevOccupant = "";
			$("#jqxMenu").jqxMenu({ width: window.innerWidth-5, height: "30px", theme:"main-theme", autoOpen:false});
			$("#mainSplitter").jqxSplitter({
				width: window.innerWidth-5, 
				height: window.innerHeight-40,
				theme:"main-theme",
				resizable:true,
				orientation: "horizontal",
				panels: [{ size:"55%",collapsible:false  }, 
				{ size: "45%", collapsible: true}] 
			});
			
			$.ajax({
				url:'sources/conList.php',
				success:function(out){
					$("#ConsumerMenu").html(out);
					
					$("#ConsumerMenu").jqxMenu({
						width: 300,
						//height: 193,
						autoOpenPopup: false,
						mode: "popup",
						theme: "main-theme"
					});
				}
			});

			var acctSource = {
				datatype: "json",
				dataFields: [
					{ name: "cid"},
					{ name: "acctNo" },
					{ name: "acctAleco" },
					{ name: "acctName" },
					{ name: "address" },
					{ name: "brgy" },
					{ name: "branch"},
					{ name: "municipality"},
					{ name: "cType"},
					{ name: "bapa"},
					{ name: "status"},
					{ name: "meterNo"}
				],
				// pagesize: 40,
				url: "sources/listOfAccounts.php"
			}
			var dataAdapter = new $.jqx.dataAdapter(acctSource);
			
			$("#acct-list").on("contextmenu", function () {
				return false;
			});

			// $("#acct-list").on("rowclick", function (event) {
				// if (event.args.rightclick) {
					// var selected_account = $("#acct-list").jqxGrid("selectrow", event.args.rowindex);
					// $("#acct-list").jqxGrid("focus");
					// var scrollTop = $(window).scrollTop();
					// var scrollLeft = $(window).scrollLeft();
					
					// $("#ConsumerMenu").jqxMenu("open", parseInt(event.args.originalEvent.clientX) + 5 + scrollLeft, parseInt(event.args.originalEvent.clientY) + 5 + scrollTop);
				// }
			// });
	
			$("#acct-list").jqxGrid({
				source: acctSource,
				width: "100%",
				height:"100%",
				theme: "main-theme",
				showtoolbar: true,
				altrows: true,
				// pageable: true,
				filterable: true, 
				columnsresize: true,
				selectionmode: "singlerow",
				columns: [
					{text: "Account Number", dataField: "acctNo", width: 200},
					{text: "Aleco Account", dataField: "acctAleco", width: 160},
					{text: "Account Name", dataField: "acctName", width: 250},
					{text: "Address", dataField: "address", width: 250},
					{text: "Barangay", dataField: "brgy", width: 200},
					{text: "Municipality", dataField: "municipality", width: 150},
					{text: "Branch", dataField: "branch", width: 100},
					{text: "Customer Type", dataField: "cType", width: 150},
					{text: "BAPA", dataField: "bapa", width: 100},
					{text: "Status", dataField: "status", width: 100},
					{text: "Meter Number", dataField: "meterNo", width: 200},
				],
				rendertoolbar: function(toolbar){
					var me = this;
					var container = $("<div style='margin: 5px;'></div>");
					var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
					var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField' type='text' placeholder='' style='height: 23px; float: left; width: 223px;' />");
					var dropdownlist2 = $("<div style='float: left; margin-left: 5px;' id='dropdownlist'></div>");
					var dropdownlist_conn = $("<div style='float: right; margin-right: 20px;' id='dropdownlist_conn'></div>");
					toolbar.append(container);
					container.append(span);
					container.append(input);
					container.append(dropdownlist2);
					container.append(dropdownlist_conn);
					
					$("#dropdownlist").jqxDropDownList({ 
						autoDropDownHeight: true,
						selectedIndex: 0,
						theme:"main-theme", 
						width: 150, 
						height: 25, 
						source: [
							"Account Number","Aleco Account", "Account Name","Address"
						]
					});
					
					var conn_source ={
						datatype: "json",
						datafields: [
						{ name: 'typeId'},
						{ name: 'typeDesc'},
						],
						url: 'sources/conn_source.php'
					};
					
					var conAdapter = new $.jqx.dataAdapter(conn_source);
					
					$("#dropdownlist_conn").jqxDropDownList({ 
						autoDropDownHeight: true,
						selectedIndex: 0,
						theme:"main-theme", 
						width: 300, 
						height: 25, 
						source: conAdapter,
						displayMember: "typeDesc",
						valueMember: "typeId"
					}).bind('select', function (event) {
						var args = event.args;
						var item = $(this).jqxDropDownList('getItem', args.index);

						if(item.value != 0) {
							if($('#acct-list').jqxGrid('getselectedrowindex') != -1) {
								$("#txtAction").html(item.label);
								$("#confirmAction").jqxWindow("open");
							}
							else {
								$(this).jqxDropDownList({ selectedIndex: 0 });
								alert("Please select account first to perform action.");
							}
						}
					});
					
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
					input.on('keyup', function (event) {
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
			
			$("#acct-list").on("rowselect", function (event) {
				$("#dropdownlist_conn").jqxDropDownList({selectedIndex: 0});
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
				
				c_sales_source.url = 'sources/get_sales.php?Account='+data.acctNo+'&&Year='+$("#dropdownlist3").jqxComboBox("getSelectedValue")+'&&Branch='+data.branch;
				selected_account = data.acctNo;
				
				var new_c_source_adapter = new $.jqx.dataAdapter(c_sales_source);
				$('#ledger-grid').jqxGrid({source:new_c_source_adapter});
				
				$('#print_grid').jqxButton({theme:'main-theme',disabled:false});
				
			});
			// ||-------ledger grid--------||
			$("#ledger-grid").jqxGrid({
				// source: c_sales_source,
				width :"100%",
				theme:"main-theme",
				height:"100%",
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
					var dropdownlist3 = $("<div style='float: left; margin-left: 5px;' id='dropdownlist3'></div>");
					
					container.append(span);
					container.append(dropdownlist3);
					container.append('<input id="print_grid" type="button" value="Print Data" />');
					container.append('<input id="show_hide" type="button" value="Show/Hide Columns" />');
					
					
					toolbar.append(container);
									
					$('#print_grid').jqxButton({theme:'main-theme',disabled:true});
					$('#show_hide').jqxButton({theme:'main-theme',disabled:false});
					
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
						autoDropDownHeight: true, width: 100, height: 22, 
						source:yearAdapter,displayMember: 'year_name',valueMember: 'year_value',theme:'main-theme'
					});
					
					var years = $("#dropdownlist3").jqxComboBox("getItems");
					$("#dropdownlist3").jqxComboBox({ selectedIndex: years.length - 1 });
					
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
				height: 190, width:  200,resizable: false,  isModal: false, autoOpen: false, modalOpacity: 0.01,theme:'main-theme'
			});
			$("#print_window").jqxWindow({
				height: 600, width:  800,resizable: false,  isModal: true, autoOpen: false, modalOpacity: 0.3,theme:'main-theme'
			});
			
			$("#sales_prev_reading_date").jqxCheckBox({  checked: true,theme:'main-theme'});
			$("#sales_prev_reading").jqxCheckBox({  checked: true,theme:'main-theme'});
			$("#sales_reading_date").jqxCheckBox({  checked: true,theme:'main-theme'});
			$("#sales_reading").jqxCheckBox({  checked: true ,theme:'main-theme'});
			$("#sales_adjusted").jqxCheckBox({  checked: false,theme:'main-theme' });
			$("#sales_dcm").jqxCheckBox({  checked: true,theme:'main-theme' });
			$("#sales_remarks").jqxCheckBox({  checked: true,theme:'main-theme' });
			
			$("#sales_prev_reading_date,#sales_prev_reading,#sales_reading_date, #sales_reading, #sales_adjusted, #sales_dcm, #sales_remarks").on('unchecked', function (event) {
				var datafield = event.target.id;
				$("#ledger-grid").jqxGrid('setcolumnproperty', datafield,'hidden',true);
			});
			
			$("#sales_prev_reading_date,#sales_prev_reading,#sales_reading_date, #sales_reading, #sales_adjusted, #sales_dcm, #sales_remarks").on('checked', function (event) {
				var datafield = event.target.id;
				$("#ledger-grid").jqxGrid('setcolumnproperty', datafield,'hidden',false);
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
			
			var cType = [
				"R",
				"C",
				"H",
				"F",
				"E"
			];
						
			$("#confirmApplication").jqxWindow({
				height: 150, width:  300, showCloseButton: false, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
			});

			$("#confirmAction").jqxWindow({
				height: 150, width:  300, showCloseButton: false, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
			});
			
			$("#newConsumerForm").jqxWindow({
				height: 620, width:  600, showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
			}).on('close', function (event) {
				$("#appFormContent").html("Loading form...");
			});
			 
			$('#processing').jqxWindow({width: 380, height:80, resizable: false,  isModal: true,showCloseButton:false, autoOpen: false, modalOpacity: 0.01,theme:'main-theme'});
			
			$("#newConsumer").on("click", function(event){
				$("#newConsumerForm").jqxWindow("open");
				
				$.ajax({
					url: "sources/appForm.php",
					type: "post",
					async: true,
					success: function(out){
						$("#appFormContent").html(out);

						$('#primary, #phone').on('keydown', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

						// $(".connection").jqxRadioButton({
							// checked: false,
							// theme: "custom-abo-admin",
							// groupName: "rbType"
						// }).on("change", function(event) {
							// var lvSub = '';
							
							// if(event.args.checked) {
								// var rbID = event.target.id.split("-");
								// $("#subCat").html("Loading...");
								
								// $.ajax({
									// url: "sources/subLV.php",
									// type: "post",
									// dataType: "json",
									// async: true,
									// data: {conID:rbID[1]},
									// success: function(outLV){
										// for(var i = 0; i < outLV.length; i++) {
											// if(i == 0 && outLV.length > 0) {
												// lvSub += '<strong>Select from below list:</strong><p>';
											// }
											
											// lvSub += '<div style="color: white;" id="s-'+outLV[i].subId+'" name="s-'+outLV[i].subId+'" class="lvSub">&nbsp;'+outLV[i].subDesc+'</div>';
											
											// if(i == (outLV.length - 1)) {
												// lvSub += '</p>';
											// }
										// }
										// $("#subCat").html(lvSub);
										
										// if(lvSub != '') {
											// $(".lvSub").jqxRadioButton({
												// checked: false,
												// theme: "custom-abo-admin",
												// groupName: "rbSType"
											// });
											// $("#s-1").jqxRadioButton({checked: true});
										// }
										
									// }
								// });
							// }
							// else {
								// $("#subCat").html("");
							// }
							
						// });
						// $("#c-1").jqxRadioButton({checked: true});
						
						$("#municipality").jqxDropDownList({ 
							autoDropDownHeight: true,
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
									source:d, displayMember: 'brgyName', valueMember: 'brgyName', theme:'main-theme'
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
						
						$("#customerType").jqxDropDownList({
							autoDropDownHeight: 200, selectedIndex: 0, width: "91%", height: 16, 
							source: cType, theme:'main-theme'
						});
						
						$("#isBapa").jqxDropDownList({
							autoDropDownHeight: 200, selectedIndex: 0, width: "91%", height: 16, 
							source: ["NON-BAPA", "BAPA"], theme:'main-theme'
						});

						$("#civilStatus").jqxDropDownList({
							autoDropDownHeight: 200, selectedIndex: 0, width: "91%", height: 20, 
							source: cStatusList, theme:'main-theme'
						});
						
						$("#addApp").jqxButton({ theme:'main-theme',height:35,width:'100%',disabled:false});
						$("#canApp").jqxButton({ theme:'main-theme',height:35,width:'100%',disabled:false});
						
						$("#addApp").unbind("click").bind("click", function(event){
							$("#confirmApplication").jqxWindow("open");
						});

						$("#canApp").unbind("click").bind("click", function(event){
							$("#newConsumerForm").jqxWindow("close");
						});
					
						$("#cancelApp").unbind("click").bind("click", function(event){
							$("#confirmApplication").jqxWindow("close");
						});
					}
				});
			});

			$("#cancelAction").click(function(event){
				$("#dropdownlist_conn").jqxDropDownList({ selectedIndex: 0 });
				$("#confirmAction").jqxWindow("close");
			});

			$("#acceptAction").click(function(event){
				var rowindex = $('#acct-list').jqxGrid('getselectedrowindex');
				var data = $('#acct-list').jqxGrid('getrowdata', rowindex);

				var actionIndex = $("#dropdownlist_conn").jqxDropDownList('selectedIndex');
				var item = $("#dropdownlist_conn").jqxDropDownList('getItem', actionIndex);
				var type = item.value;

				$.ajax({
					url: "functions/addApplicationExisting.php",
					type: "post",
					data: {type: item.value, cid: data.cid},
					success: function(data){
						if(data == 1){
							$("#processing").jqxWindow("open");
							setTimeout(function(){
								$("#processing").jqxWindow("close");
								$("#confirmAction").jqxWindow("close");
							}, 1000);
						}
						console.log(data);
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
						// alert(data)
						if(data){
							$("#confirmApplication").jqxWindow("close");
							$("#newConsumerForm").jqxWindow("close");
								window.location.href = "transactions.php";
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
			
			$("#soForm").jqxWindow({
				height: 580, width:  730,resizable: false,  isModal: true, draggable:false, autoOpen: false, modalOpacity: 0.3,theme:'main-theme'
			}).on('close', function (event) {
				$("#dropdownlist_conn").jqxDropDownList({ selectedIndex: 0 });
				$("#soFormContent").html("Loading form...");
			});
		});

		function PreviewImage() {
			var uploader = document.getElementById("uploader").files[0]
	        var oFReader = new FileReader();
	        oFReader.readAsDataURL(uploader);

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
		
		function conSelected(con) {
			var rowindex = $('#acct-list').jqxGrid('getselectedrowindex');
			var data = $('#acct-list').jqxGrid('getrowdata', rowindex);
			var type = $("#dropdownlist_conn").jqxDropDownList("getSelectedItem");

			if(data.pending == 1) {
				alert("Selected account has pending S.O.");
				$("#dropdownlist_conn").jqxDropDownList({ selectedIndex: 0 });
			}
			else {
				$("#soForm").jqxWindow("open");
				
				$.ajax({
					url: "sources/soForm.php",
					type: "post",
					async: true,
					data: {con:con, form:"", acct:data.acctNo},
					success: function(out){
						
						$("#soFormContent").html(out);
						
						$(".undertake").jqxCheckBox({checked: true, theme: "custom-abo-admin"});
						$(".service").jqxCheckBox({theme: "custom-abo-admin"});
						
						$("#issue").jqxButton({
							width: '150'
						}).unbind("click").bind("click", function(event) {
							$.ajax({
								url: "functions/issueSo.php",
								async: true,
								data: $("#frmSO").serialize()+"&acct="+data.acctNo+"&type="+type.value,
								success: function(outIssue){
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
		};
	</script>
</head>
<body class="default">
	<div class="row push-right-m2">
			<div id="jqxMenu" >
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "javascript:location.reload()">Home</a></li>
					<li><img  src="../assets/images/icons/icol16/src/zone_money.png" alt="" /><a href = "transactions.php"> Transactions</a></li>
					<li id = "newConsumer"><img  src="../assets/images/icons/icol16/src/group.png" alt=""/><a href = "javascript:void(0)">New Consumer</a></li>
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
				</ul>	
			</div>
			<div id = "mainPage">
			<div id = "mainSplitter">
				<div class="splitter-panel">
					<div id = "acct-list"></div>
					<div id="ConsumerMenu">
						Loading list...
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
				<div>
					<h5 style="margin: 0;"><img src = "../assets/images/icons/icol16/src/application_add.png"> New Consumer Application Form</h5>
				</div>
				
				<div id="appFormContent" style = "background-color: #0A525A; color: #ffffff">
					Loading form...
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
			<div id="confirmAction">
				<div><img  src="../assets/images/icons/icol16/src/application.png" alt="" /> CONFIRMATION</div>
				<div>
					<h5 style = "padding-bottom: 25px;" class = "text-center">Submit <span style="font-weight:bold;" id="txtAction"></span> application for inspection?</h4>
					<div class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-success btn-sm" id  = "acceptAction" value = "Accept">
					</div>
					<div class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-danger btn-sm" id  = "cancelAction" value = "Cancel">
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
			<div id = "soForm">
				<div>
					<img width="14" height="14" src="../assets/images/icons/icol16/src/application2.png" alt="" /> SERVICE ORDER
				</div>

				<div id="soFormContent" style = "padding:10px; background-color: #0A525A; color: #ffffff">
					Loading form...
				</div>
			</div>
	</div>
</body>
</html>