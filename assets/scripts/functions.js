$(document).ready(function (){
	
	$("#jqxMenu").jqxMenu({ width:window.innerWidth-15, height: "30px", theme:"custom-zandro",autoOpen:false});
	$("#panel").jqxPanel({ width:'100%',height:'100%',theme:'custom-panel'});
	$("#addApp").jqxButton({ theme:'energyblue',height:35,width:150,disabled:false});
	var consumer_contextMenu = $("#ConsumerMenu").jqxMenu({ width: 226, height: 210, autoOpenPopup: false, mode: "popup",theme:"custom-zandro"});
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

	
	$("#acct-list").on("contextmenu", function () {
		return false;
	});

	$("#mainSplitter").jqxSplitter({
		width:window.innerWidth-15, 
		height:window.innerHeight-50,
		theme:"custom-zandro",
		resizable:true,
		orientation: "vertical",
		panels: [{ size:"65%",collapsible:false  }, 
		{ size: "35%",collapsible:true }] 
	});
	
	
	$("#leftSplitter").jqxSplitter({
		width:"100%", 
		height:"100%",
		theme:"custom-zandro",
		resizable:true,
		orientation: "horizontal", 
		panels: [{ size: "40%",collapsible:false  }, 
		{ size: "60%",collapsible:false }] 
	});
	
	$("#newApp").on("click", function(event){
		$("#mainSplitter").jqxSplitter("expand");
		console.log("asdafaxcz");
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
			var branch_sep = $("<div style='float: left; margin-left: 5px;' id='branch_sep'></div>");
			toolbar.append(container);
			container.append(span);
			container.append(input);
			container.append(dropdownlist2);

			container.append(searchButton);
			container.append(branch_sep);
			
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
			
			$("#branch_sep").jqxComboBox({ 
				autoDropDownHeight: true, 
				width: 100, 
				height: 22, 
				source: sepAdapter,displayMember: 'area_name',valueMember: 'area_value',theme:'custom-zandro',promptText:"BRANCH"
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
				// apply the filters.
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
					// apply the filters.
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
			{ text: 'Prev Rdg Date', datafield:'sales_prev_reading_date',editable:true,hidden:true,width:120,/*cellclassname: cellclass*/},
			{ text: 'Prev Reading', datafield:'sales_prev_reading',editable:true,hidden:true,width:120,/*cellclassname: cellclass*/},
			{ text: 'Pres Rdg Date', datafield:'sales_reading_date',editable:true,hidden:false,width:120,/*cellclassname: cellclass*/},
			{ text: 'Pres Reading', datafield:'sales_reading',editable:true,width:120,/*cellclassname: cellclass*/},
			{ text: 'Kwh', datafield: 'sales_kwh',width:120,/*cellclassname: cellclass*/},
			{ text: 'Sales Amount', datafield: 'sales_amount',width:120,/*cellclassname: cellclass*/},
			{ text: 'Payment', datafield:'sales_payment',editable:false,width:120,/*cellclassname: cellclass*/},
			{ text: 'Status', datafield:'sales_status',editable:false,hidden:false,width:120,/*cellclassname: cellclass*/},
			{ text: 'Adjusted', datafield:'sales_adjusted',editable:false,hidden:true,width:120,/*cellclassname: cellclass*/},
			{ text: 'Dcm Number', datafield:'sales_dcm',editable:false,hidden:true,width:120,/*cellclassname: cellclass*/},
			{ text: 'Remarks', datafield:'sales_remarks',editable:false, hidden:true,width:120,/*cellclassname: cellclass*/},
			{ text: 'DCM CHECKER', datafield:'dcm_checker',editable:false, hidden:true,width:120,/*cellclassname: cellclass*/}
		]
	});
	
	$("#show_hide_column_window").jqxWindow({
		height: 200, width:  170,resizable: false,  isModal: false, autoOpen: false, modalOpacity: 0.01,theme:'custom-zandro'
	});
	$("#print_window").jqxWindow({
		height: 600, width:  800,resizable: false,  isModal: true, autoOpen: false, modalOpacity: 0.3,theme:'custom-zandro'
	});
	
	$("#sales_prev_reading_date").jqxCheckBox({  checked: false,theme:'custom-zandro'});
	$("#sales_prev_reading").jqxCheckBox({  checked: false,theme:'custom-zandro'});
	$("#sales_reading_date").jqxCheckBox({  checked: true,theme:'custom-zandro'});
	$("#sales_reading").jqxCheckBox({  checked: true ,theme:'custom-zandro'});
	$("#sales_adjusted").jqxCheckBox({  checked: false,theme:'custom-zandro' });
	$("#sales_dcm").jqxCheckBox({  checked: false,theme:'custom-zandro' });
	$("#sales_remarks").jqxCheckBox({  checked: false,theme:'custom-zandro' });
	
	$("#sales_prev_reading_date,#sales_prev_reading,#sales_reading_date, #sales_reading, #sales_adjusted, #sales_dcm, #sales_remarks").on('unchecked', function (event) {
		var datafield = event.target.id;
		$("#ledger-grid").jqxGrid('setcolumnproperty', datafield,'hidden',true);
		// alert(event.args.checked);
	});
	
	$("#sales_prev_reading_date,#sales_prev_reading,#sales_reading_date, #sales_reading, #sales_adjusted, #sales_dcm, #sales_remarks").on('checked', function (event) {
		var datafield = event.target.id;
		$("#ledger-grid").jqxGrid('setcolumnproperty', datafield,'hidden',false);
		// alert(event.args.checked);
	});
	
	var list = {
		datatype: "json",
		datafields: [
			{ name: 'municipality'}
		],
		url: 'sources/getMunicipality.php',
		async: false
	};

	var listAdapter = new $.jqx.dataAdapter(list);

	var cStatusList = [
		"SINGLE",
		"MARRIED",
		"WIDOWED",
		"SEPARATED"
	];
	
	var cTypeList = [
		"RESIDENTIAL",
		"COMMERCIAL",
		"HIGH VOLTAGE",
		"LOW VOLTAGE",
		"BAPA",
		"INDUSTRIAL"
	];
	
	$("#municipality").jqxComboBox({ 
		selectedIndex: 1, width: "89%", height: 22, 
		source:listAdapter, displayMember: 'municipality', valueMember: 'municipality', theme:'custom-zandro'
	});
	
	$("#municipality1").jqxComboBox({ 
		selectedIndex: 1, width: "89%", height: 22, 
		source:listAdapter, displayMember: 'municipality', valueMember: 'municipality', theme:'custom-zandro'
	});
	
	$("#civilStatus").jqxComboBox({
		autoDropDownHeight: 200, selectedIndex: 0, width: "89%", height: 22, 
		source: cStatusList, theme:'custom-zandro'
	});
	
	$("#customerType").jqxComboBox({
		autoDropDownHeight: 200, selectedIndex: 0, width: "89%", height: 22, 
		source: cTypeList, theme:'custom-zandro'
	});
	
	$("#familyGrid").jqxGrid({
		width: "99%",
		autoheight: true,
		filterable: true,
		// source: dataAdapter,
		// scrollbar: false,
		showeverpresentrow: true,
		everpresentrowposition: "top",
		everpresentrowactions: "add update delete reset",
		editable: true,
		selectionmode: 'multiplecellsadvanced',
		columns: [
			{ text: 'Name', columntype: 'textbox', filtertype: 'input', datafield: 'name', width: "40%"},
			{ text: 'Age', columntype: 'dropdown', datafield: 'age', width: "20%"},
			{ text: 'Civil Status', filtertype: 'checkedlist', datafield: 'civilStatus', width: "40%"}
		]
	});
	
	$("#deviceGrid").jqxGrid({
		width: "98%",
		autoheight: true,
		filterable: true,
		// source: dataAdapter,
		// scrollbar: false,
		showeverpresentrow: true,
		everpresentrowposition: "top",
		everpresentrowactions: "add update delete reset",
		editable: true,
		selectionmode: 'multiplecellsadvanced',
		columns: [
			{ text: 'Device Name', columntype: 'textbox', filtertype: 'input', datafield: 'deviceName'}
		]
	});
	
	$("#req1").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#req2").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#req3").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#req4").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#req5").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#req6").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#req7").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#source1").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#source2").jqxCheckBox({checked: false, theme:'custom-zandro'});
	$("#source3").jqxCheckBox({checked: false, theme:'custom-zandro'});
	
	// $("#incomeSource1").on('checked', function(event){
		// alert("asdsad");
	// });
	
	$('#button1').jqxSwitchButton({ height: 27, width: 81,  checked: true, onLabel: "yes", offLabel: "no" });
	$('#button2').jqxSwitchButton({ height: 27, width: 81,  checked: true, onLabel: "yes", offLabel: "no" });
	$('#button3').jqxSwitchButton({ height: 27, width: 81,  checked: true, onLabel: "yes", offLabel: "no" });
	$('#button4').jqxSwitchButton({ height: 27, width: 81,  checked: true, onLabel: "yes", offLabel: "no" });
	$('#button5').jqxSwitchButton({ height: 27, width: 81,  checked: true, onLabel: "yes", offLabel: "no" });
	$('#button6').jqxSwitchButton({ height: 27, width: 81,  checked: true, onLabel: "yes", offLabel: "no" });
		
	
	$("#button2").on("checked", function(event){
		$("#ownerName").prop("disabled", false);
		$("#ownerAddress").prop("disabled", false);
	});
	
	$("#button2").on("unchecked", function(event){
		$("#ownerName").val("");
		$("#ownerAddress").val("");
		$("#ownerName").prop("disabled", true);
		$("#ownerAddress").prop("disabled", true);
	});
	
	$("#button4").on("checked", function(event){
		$("#prevOccupant").prop("disabled", false);
	});
	$("#button4").on("unchecked", function(event){
		$("#prevOccupant").val("");
		$("#prevOccupant").prop("disabled", true);
	});
	
	$("#button6").on("checked", function(event){
		$("#deviceGrid").jqxGrid({disabled: true, showeverpresentrow: false});
		$("#deviceGrid").jqxGrid("clear");
	});
	$("#button6").on("unchecked", function(event){
		$("#deviceGrid").jqxGrid({disabled: false, showeverpresentrow: true});
	});
	
	$("#source1").on("checked", function(event){
		$("#sourceName1").prop("disabled", false);
		$("#sourceAddress1").prop("disabled", false);
	});
	
	$("#source1").on("unchecked", function(event){
		$("#sourceName1").val("");
		$("#sourceAddress1").val("");
		$("#sourceName1").prop("disabled", true);
		$("#sourceAddress1").prop("disabled", true);
	});
	
	$("#source2").on("checked", function(event){
		$("#sourceName2").prop("disabled", false);
		$("#sourceAddress2").prop("disabled", false);
	});
	
	$("#source2").on("unchecked", function(event){
		$("#sourceName2").val("");
		$("#sourceAddress2").val("");
		$("#sourceName2").prop("disabled", true);
		$("#sourceAddress2").prop("disabled", true);
	});
	
	$("#source3").on("checked", function(event){
		$("#sourceName3").prop("disabled", false);
	});
	
	$("#source3").on("unchecked", function(event){
		$("#sourceName3").val("");
		$("#sourceName3").prop("disabled", true);
	});
	
	$("#confirmApplication").jqxWindow({
		height: 150, width:  300, showCloseButton: false, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'custom-zandro'
	});
	
	$("#acceptApp").jqxButton({width: "100%", theme: "custom-zandro"});
	$("#cancelApp").jqxButton({width: "100%", theme: "custom-zandro"});
	
	$("#addApp").click(function(event){
		$("#confirmApplication").jqxWindow("open");
	});
	
	$("#cancelApp").click(function(event){
		$("#confirmApplication").jqxWindow("close");
	});
});