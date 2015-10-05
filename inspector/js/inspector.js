$(document).ready(function(){
	var req1 = req2 = req3 = req4 = req5 = req6 = req7 = 0;
	var q1 = q2 = q3 = q4 = q5 = q6 = 1;
	var ownerName = ownerAddress = prevOccupant = "";
	$("#jqxMenu").jqxMenu({width: window.innerWidth-5, height: "30px", theme: "main-theme", autoOpen:false});
	var consumer_inspectionMenu = $("#inspectionMenu").jqxMenu({ width: 226, height: 86, autoOpenPopup: false, mode: "popup",theme: "main-theme"});
	
	$("#inspection_list").on("rowclick", function (event) {
		if (event.args.rightclick) {
			var selected_account = $("#inspection_list").jqxGrid("selectrow", event.args.rowindex);
			$("#inspection_list").jqxGrid("focus");
			var scrollTop = $(window).scrollTop();
			var scrollLeft = $(window).scrollLeft();
			consumer_inspectionMenu.jqxMenu("open", parseInt(event.args.originalEvent.clientX) + 5 + scrollLeft, parseInt(event.args.originalEvent.clientY) + 5 + scrollTop);
			return false;
		}
	});
	$("#inspection_list").on("contextmenu", function () {
		return false;
	});
	
	var subStations = {
		datatype: "json",
		dataFields: [
			{name: "subId"},
			{name: "subDescription"}
		],
		url: "sources/getSubStation.php",
		async: false
	};
	var feeders = {
		datatype: "json",
		dataFields: [
			{name: "feederId"},
			{name: "feeder"}
		],
		async: false
	}
	var subData = new $.jqx.dataAdapter(subStations);
	
	$("#substation").jqxDropDownList({ 
		autoDropDownHeight: 200, selectedIndex: 0, width: "92.3%", height: 20, 
		source:subData, displayMember: 'subDescription', valueMember: 'subId', theme:'custom-abo-ao'
	}).unbind("change").on("change", function(){
		var station = $("#substation").jqxDropDownList("getSelectedItem");
		feeders.url = "sources/getFeeder.php?subId="+station.value;
		feederData = new $.jqx.dataAdapter(feeders);
		$("#feeder").jqxDropDownList({autoDropDownHeight: 200, selectedIndex: 0, width: "92.3%", height: 20, source: feederData, displayMember: "feeder", valueMember: "feederId", theme: "main-theme"});
	});
	
	var station = $("#substation").jqxDropDownList("getSelectedItem");
	
	feeders.url = "sources/getFeeder.php?subId="+station.value;
	var feederData = new $.jqx.dataAdapter(feeders);
	
	$("#feeder").jqxDropDownList({
		autoDropDownHeight: true, selectedIndex: 0, width: "92.3%", height: 20, source: feederData, displayMember: "feeder", valueMember: "feederId", theme: "main-theme"
	});
	
	var entrance = {
		dataType: "json",
		dataFields: [
			{name: "eid"},
			{name: "eDescription"}
		],
		url: "sources/getEntranceType.php",
		async: false
	}
	
	var e_data = new $.jqx.dataAdapter(entrance);
	
	$("#eType").jqxDropDownList({
		autoDropDownHeight: true, selectedIndex: 0, width: "84%", height: 20, source: e_data, displayMember: "eDescription", valueMember: "eid", theme: "main-theme"
	});
	
	var inspection_list = {
		datatype: "json",
		dataFields: [
			{ name: "acctNo" },
			{ name: "consumerName" },
			{ name: "address" },
			{ name: "status" },
			{ name: "so" },
			{ name: "remarks" },
			{ name: "appType" },
			{ name: "dateApp"},
			{ name: "acctNo"},
			{ name: "cid"},
			{ name: "appId"},
			{ name: "service"},
			{ name: "tid"}
		],
		url: "sources/inspectionList.php"
	};
	var dataAdapter = new $.jqx.dataAdapter(inspection_list);
	
	var reports = {
		datatype: "json",
		dataFields: [
			{ name: "acctNo" },
			{ name: "consumerName" },
			{ name: "address" },
			{ name: "protection" },
			{ name: "rating" },
			{ name: "type" },
			{ name: "wireSize" },
			{ name: "length" },
			{ name: "servicePole" },
			{ name: "inspectedBy" },
			{ name: "remarks" }
		],
		url: "sources/reports.php"
	}
	
	var creports = {
		datatype: "json",
		dataFields: [
			{ name: "acctNo" },
			{ name: "consumerName" },
			{ name: "address" },
			{ name: "remarks" },
			{ name: "inspectedBy" }

		],
		url: "sources/creports.php"
	}
	
	
	var daily_transactions = {
		datatype: "json",
		dataFields: [
			{ name: "consumerName" },
			{ name: "address" },
			{ name: "status" },
			{ name: "so" },
			{ name: "remarks" },
			{ name: "appType" },
			{ name: "dateApp"},
			{ name: "acctNo"},
			{ name: "cid"},
			{ name: "appId"},
			{ name: "action"}
		],
		url: "sources/dailyTransactions.php"
	};
	
	$("#inspection_list").jqxGrid({
		source: inspection_list,
		width: window.innerWidth-6,
		height: window.innerHeight-52,
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
			
			$("#search").jqxButton({theme: "main-theme",height:18,width:24});
			$("#dropdownlist").jqxDropDownList({ 
				autoDropDownHeight: true,
				selectedIndex: 0,
				theme: "main-theme", 
				width: 200, 
				height: 25, 
				source: [
					"Consumer Name", "Address","Account Number"
				]
			});
									
			if (theme != "") {
				input.addClass("jqx-widget-content-" + theme);
				input.addClass("jqx-rc-all-" + theme);
			}
			$("#search").click(function(){
				$("#inspection_list").jqxGrid('clearfilters');
				var searchColumnIndex = $("#dropdownlist").jqxDropDownList('selectedIndex');
				var datafield = "";
				switch (searchColumnIndex) {
					case 0:
						datafield = "consumerName";
						break;
					case 1:
						datafield = "address";
						break;
					case 2:
						datafield = "acctNo";
						break;
					
				}

				var searchText = $("#searchField").val();
				var filtergroup = new $.jqx.filter();
				var filter_or_operator = 1;
				var filtervalue = searchText;
				var filtercondition = 'contains';
				var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
				filtergroup.addfilter(filter_or_operator, filter);
				$("#inspection_list").jqxGrid('addfilter', datafield, filtergroup);
				$("#inspection_list").jqxGrid('applyfilters');
			});
			
			var oldVal = "";
			input.on('keydown', function (event) {
				var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
					
				if (key == 13 || key == 9) {
					$("#inspection_list").jqxGrid('clearfilters');
					var searchColumnIndex = $("#dropdownlist").jqxDropDownList('selectedIndex');
					var datafield = "";
					switch (searchColumnIndex) {
						case 0:
							datafield = "consumerName";
							break;
						case 1:
							datafield = "address";
							break;
						case 2:
							datafield = "acctNo";
							break;
					}
					var searchText = $("#searchField").val();
					var filtergroup = new $.jqx.filter();
					var filter_or_operator = 1;
					var filtervalue = searchText;
					var filtercondition = 'contains';
					var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
					filtergroup.addfilter(filter_or_operator, filter);
					$("#inspection_list").jqxGrid('addfilter', datafield, filtergroup);
					$("#inspection_list").jqxGrid('applyfilters');
				}
			   
				if(key == 27){
					$("#inspection_list").jqxGrid('clearfilters');
					return true;
				}
			});
		},
		columns: [
			{text: "Account Number", dataField: "acctNo", align: "center", width: 150},
			{text: "Consumer Name", dataField: "consumerName", align: "center", width: 300},
			{text: "Address", dataField: "address",  align: "center", width: 300},
			{text: "Application", dataField: "service", cellsalign: "center", align: "center",  width: 150},
			{text: "Date", dataField: "dateApp", cellsalign: "center", align: "center", width: 200},
			{text: "SO", dataField: "so",  align: "center", width: 150},
			{text: "Status", dataField: "status", cellsalign: "center", align: "center", width: 150}
		]
	});
	
	$("#inspection_list2").jqxGrid({
		source: reports,
		width: "99.7%",
		height: "99.5%",
		theme: "main-theme",
		showtoolbar: true,
		altrows: true,
		selectionmode: "singlerow",
		// pageable: true,
		rendertoolbar: function(toolbar){
			var me = this;
			var container = $("<div style='margin: 5px;'></div>");
			var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
			var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField1' type='text' style='height: 23px; float: left; width: 223px;' />");
		   // var refresh = $("<input style="margin-left: 5px;" id="clear" type="button" value="Clear" />");
			var searchButton = $("<div style='float: left; margin-left: 5px;' id='search1'><img style='position: relative; margin-top: 2px;' src='../assets/images/search_lg.png'/><span style='margin-left: 4px; position: relative; top: -3px;'></span></div>");
			var dropdownlist2 = $("<div style='float: left; margin-left: 5px;' id='dropdownlist1'></div>");
			var branch_sep = $("<div style='float: left; margin-left: 5px;' id='branch_sep'></div>");
			toolbar.append(container);
			container.append(span);
			container.append(input);
			container.append(dropdownlist2);

			container.append(searchButton);
			container.append(branch_sep);
			
			$("#search1").jqxButton({theme: "main-theme",height:18,width:24});
			$("#dropdownlist1").jqxDropDownList({ 
				autoDropDownHeight: true,
				selectedIndex: 0,
				theme: "main-theme", 
				width: 200, 
				height: 25, 
				source: [
					"Consumer Name", "Address","Account Number"
				]
			});
									
			if (theme != "") {
				input.addClass("jqx-widget-content-" + theme);
				input.addClass("jqx-rc-all-" + theme);
			}
			$("#search1").click(function(){
				$("#inspection_list2").jqxGrid('clearfilters');
				var searchColumnIndex = $("#dropdownlist1").jqxDropDownList('selectedIndex');
				var datafield = "";
				switch (searchColumnIndex) {
					case 0:
						datafield = "consumerName";
						break;
					case 1:
						datafield = "address";
						break;
					case 2:
						datafield = "acctNo";
						break;
					
				}

				var searchText = $("#searchField1").val();
				var filtergroup = new $.jqx.filter();
				var filter_or_operator = 1;
				var filtervalue = searchText;
				var filtercondition = 'contains';
				var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
				filtergroup.addfilter(filter_or_operator, filter);
				$("#inspection_list2").jqxGrid('addfilter', datafield, filtergroup);
				$("#inspection_list2").jqxGrid('applyfilters');
			});
			
			var oldVal = "";
			input.on('keydown', function (event) {
				var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
					
				if (key == 13 || key == 9) {
					$("#inspection_list2").jqxGrid('clearfilters');
					var searchColumnIndex = $("#dropdownlist1").jqxDropDownList('selectedIndex');
					var datafield = "";
					switch (searchColumnIndex) {
						case 0:
							datafield = "consumerName";
							break;
						case 1:
							datafield = "address";
							break;
						case 2:
							datafield = "acctNo";
							break;
					}
					var searchText = $("#searchField1").val();
					var filtergroup = new $.jqx.filter();
					var filter_or_operator = 1;
					var filtervalue = searchText;
					var filtercondition = 'contains';
					var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
					filtergroup.addfilter(filter_or_operator, filter);
					$("#inspection_list2").jqxGrid('addfilter', datafield, filtergroup);
					$("#inspection_list2").jqxGrid('applyfilters');
				}
			   
				if(key == 27){
					$("#inspection_list2").jqxGrid('clearfilters');
					return true;
				}
			});
		},
		columns: [{text: "Account Number",pinned: true, dataField: "acctNo", align: "center", width: 150},
			{text: "Consumer Name", pinned: true, dataField: "consumerName", align: "center", width: 300},
			{text: "Address", dataField: "address",  align: "center", width: 300},
			{text: "Type", columngroup: "mpr", dataField: "protection", cellsalign: "center", align: "center",  width: 150},
			{text: "Rating", columngroup: "mpr", dataField: "rating", cellsalign: "center", align: "center", width: 50},
			{text: "Type", columngroup: "se", dataField: "type",  align: "center", width: 100},
			{text: "Wire Size", columngroup: "se",dataField: "wireSize", cellsalign: "center", align: "center", width: 100},
			{text: "Length", columngroup: "se",dataField: "length", cellsalign: "center", align: "center", width: 100},
			{text: "No. of Service Pole", columngroup: "se",dataField: "servicePole", cellsalign: "center", align: "center", width: 150},
			{text: "Remarks", columngroup: "se",dataField: "remarks", cellsalign: "center", align: "center", width: 150},
			{text: "Inspected By", dataField: "inspectedBy", cellsalign: "center", align: "center", width: 150}
		],
		columngroups: 
		[
		  { text: 'Main Protection and Rating', align: 'center', name: 'mpr' },
		  { text: 'Service Entrance', align: 'center', name: 'se' },
		]
	});
	
	
	$("#inspection_list3").jqxGrid({
		source: creports,
		width: "99.7%",
		height: "99.5%",
		theme: "main-theme",
		showtoolbar: true,
		altrows: true,
		//selectionmode: "singlerow",
		// pageable: true,
		rendertoolbar: function(toolbar){
			var me = this;
			var container = $("<div style='margin: 5px;'></div>");
			var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
			var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField2' type='text' style='height: 23px; float: left; width: 223px;' />");
		   // var refresh = $("<input style="margin-left: 5px;" id="clear" type="button" value="Clear" />");
			var searchButton = $("<div style='float: left; margin-left: 5px;' id='search2'><img style='position: relative; margin-top: 2px;' src='../assets/images/search_lg.png'/><span style='margin-left: 4px; position: relative; top: -3px;'></span></div>");
			var dropdownlist2 = $("<div style='float: left; margin-left: 5px;' id='dropdownlist2'></div>");
			var branch_sep = $("<div style='float: left; margin-left: 5px;' id='branch_sep'></div>");
			toolbar.append(container);
			container.append(span);
			container.append(input);
			container.append(dropdownlist2);

			container.append(searchButton);
			container.append(branch_sep);

			$("#search2").jqxButton({theme: "main-theme",height:18,width:24});
			$("#dropdownlist2").jqxDropDownList({
				autoDropDownHeight: true,
				selectedIndex: 0,
				theme: "main-theme",
				width: 200,
				height: 25,
				source: [
					"Consumer Name", "Address","Account Number"
				]
			});

			if (theme != "") {
				input.addClass("jqx-widget-content-" + theme);
				input.addClass("jqx-rc-all-" + theme);
			}
			$("#search2").click(function(){
				$("#inspection_list3").jqxGrid('clearfilters');
				var searchColumnIndex = $("#dropdownlist2").jqxDropDownList('selectedIndex');
				var datafield = "";
				switch (searchColumnIndex) {
					case 0:
						datafield = "consumerName";
						break;
					case 1:
						datafield = "address";
						break;
					case 2:
						datafield = "acctNo";
						break;

				}

				var searchText = $("#searchField2").val();
				var filtergroup = new $.jqx.filter();
				var filter_or_operator = 1;
				var filtervalue = searchText;
				var filtercondition = 'contains';
				var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
				filtergroup.addfilter(filter_or_operator, filter);
				$("#inspection_list3").jqxGrid('addfilter', datafield, filtergroup);
				$("#inspection_list3").jqxGrid('applyfilters');
			});

			var oldVal = "";
			input.on('keydown', function (event) {
				var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;

				if (key == 13 || key == 9) {
					$("#inspection_list3").jqxGrid('clearfilters');
					var searchColumnIndex = $("#dropdownlist2").jqxDropDownList('selectedIndex');
					var datafield = "";
					switch (searchColumnIndex) {
						case 0:
							datafield = "consumerName";
							break;
						case 1:
							datafield = "address";
							break;
						case 2:
							datafield = "acctNo";
							break;
					}
					var searchText = $("#searchField2").val();
					var filtergroup = new $.jqx.filter();
					var filter_or_operator = 1;
					var filtervalue = searchText;
					var filtercondition = 'contains';
					var filter = filtergroup.createfilter('stringfilter', filtervalue, filtercondition);
					filtergroup.addfilter(filter_or_operator, filter);
					$("#inspection_list3").jqxGrid('addfilter', datafield, filtergroup);
					$("#inspection_list3").jqxGrid('applyfilters');
				}

				if(key == 27){
					$("#inspection_list3").jqxGrid('clearfilters');
					return true;
				}
			});
		},
		columns: [
			{text: "Account Number", pinned: false, dataField: "acctNo", align: "center", cellsalign: "center", width: 150},
			{text: "Consumer Name", pinned: false, dataField: "consumerName", align: "center", width: 250},
			{text: "Address", dataField: "address",  align: "center", width: 250},
			{text: "Remarks", columngroup: "se",dataField: "remarks", cellsalign: "center", align: "center", width: 150},
			{text: "Inspected By", dataField: "inspectedBy", cellsalign: "center", align: "center", width: 150}
		],

	});
	
	$("#inspectedDate").jqxDateTimeInput({ height: 32, width: '99%',  formatString: 'yyyy-MM-dd'});
	
	$("#confirmInspection").jqxWindow({
		maxWidth: 900, height: 380, width:  900, cancelButton: $(".cancelApp"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme: "main-theme"
	});
	
	$("#rejectApp").jqxWindow({
		height: 225, width:  350, cancelButton: $(".cancelApp1"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme: "main-theme"
	});
	
	$("#confirm2").jqxWindow({
		height: 140, width:  350, cancelButton: $(".cancelApp2"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme: "main-theme"
	});
	
	$("#reportList").jqxWindow({
		height: 500, width: 900, maxWidth: 900, showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme: "main-theme"
	});
	
	$("#approve").click(function(){
		$("#confirmInspection").jqxWindow("open");
	});
	
	$("#cancel").click(function(){
		$("#rejectApp").jqxWindow("open");
	});
	
	$("#cancelC").click(function(){
		$("#rejectApp").jqxWindow("close");
	});
	//asds
	$('#processing').jqxWindow({width: 380, height:80, resizable: false,  isModal: true, showCloseButton:false, autoOpen: false, modalOpacity: 0.50,theme: "main-theme"});
	
	$("#approveApp").unbind("click").bind("click", function(){
		$("#confirm2").jqxWindow("open");
		$("#submit").unbind("click").bind("click", function(){
			var rowindex = $("#inspection_list").jqxGrid("getselectedrowindex");
			var data = $("#inspection_list").jqxGrid("getrowdata", rowindex);
			var selected = $("#pType").jqxDropDownList("getSelectedItem");
			var selStation = $("#substation").jqxDropDownList("getSelectedItem");
			var selFeeder = $("#feeder").jqxDropDownList("getSelectedItem");
			var selType = $("#eType").jqxDropDownList("getSelectedItem");
			$.ajax({
				url: "functions/approveInspection.php",
				type: "post",
				data: { pType: selected.value, rating: $("#rating").val(), etype: selType.value, eSize: $("#eSize").val(), wSize: $("#wSize").val(), 
						servicePole: $("#servicePole").val(), length: $("#length").val(), totalVa: $("#totalVa").val(),
						meter: $("#meter").val(), mClass: $("#mClass").val(), station: selStation.value, feeder: selFeeder.value,
						phase: $("#phase").val(), inspectedBy: $("#inspectedBy").val(), iRemarks: $("#iRemarks").val(),
						date: $("#inspectedDate").val(), appId: data.appId, cid: data.cid, tid: data.tid, 
				},
				success: function(data){
					if(data == 1){
						$("#confirmInspection").jqxWindow("close");
						$("#confirm2").jqxWindow("close");
						$('#processing').jqxWindow('open');
						setTimeout( function(){
							$('#processing').jqxWindow('close');
							location.reload();
						}, 2000);
					} else{
						alert(data);
						console.log(data);
					}
				}
			});
		});
	});
		
	
	
	$("#approveApp1").click(function(){
		var rowindex = $("#inspection_list").jqxGrid("getselectedrowindex");
		var data = $("#inspection_list").jqxGrid("getrowdata", rowindex);
		$.ajax({
			url: "functions/cancelApplication.php",
			type: "post",
			data: {inspectedBy: $("#inspectedBy1").val(), appId: data.appId, cid: data.cid, remarks: $("#remarks1").val()},
			success: function(data){
				if(data == 1){
					$("#confirmInspection").jqxWindow("close");
					$('#processing').jqxWindow('open');
					setTimeout( function(){
						$('#processing').jqxWindow('close');
						location.reload();
					}, 2000);
				}
			}
		});
	});
	
	var mainP = {
		datatype: "json",
		dataFields: [
			{name: "protectionId"},
			{name: "protectionDesc"}
		],
		url: "sources/getProtectionType.php",
		async: false
	};
	
	var mainPData = new $.jqx.dataAdapter(mainP);
	$("#pType").jqxDropDownList({
		source: mainPData, selectedIndex: 0, autoDropDownHeight: 200, height: 20, 
		displayMember: "protectionDesc", valueMember: "protectionId", width: "92.3%"
	});
	
	$("#reports").on("click", function(event){
		$("#reportList").jqxWindow("open");
	});
	
	$('#jqxTabs').jqxTabs({ width: '100%', height: '99%', position: 'top', theme: "main-theme"});

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