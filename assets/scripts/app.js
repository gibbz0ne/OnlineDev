(function(){
	var app = angular.module("consumer", ["jqwidgets"]);
	
	app.controller("mainController", function($scope){
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
	
		$scope.mainMenu = {
			width:window.innerWidth-15,
			height: "30px",
			theme:"custom-zandro",
			autoOpen:false
		}
		
		$scope.mainSplitter = {
			width:window.innerWidth-15, 
			height:window.innerHeight-50,
			theme:"custom-zandro",
			resizable:false,
			orientation: "vertical", 
			panels: [{ size:"65%",collapsible:false  }, 
			{ size: "35%",collapsible:true }] 
		}
		
		$scope.leftSplitter = {
			width:"100%", 
			height:"100%",
			theme:"custom-zandro",
			resizable:true,
			orientation: "horizontal",
			panels: [{ size: "40%",collapsible:false  },
			{ size: "60%",collapsible:false }]
		}
		
		$scope.consumersGrid = {
			source: acctSource,
			width: "100%",
			height:window.innerHeight-20,
			theme: "custom-zandro",
			showtoolbar: true,
			altrows: true,
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
				if (theme != "") {
					input.addClass("jqx-widget-content-" + theme);
					input.addClass("jqx-rc-all-" + theme);
				}
				$("#search").click(function(){
					$("#acct-list").jqxGrid("clearfilters");
					var searchColumnIndex = $("#dropdownlist").jqxDropDownList("selectedIndex");
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
					var filtercondition = "contains";
					var filter = filtergroup.createfilter("stringfilter", filtervalue, filtercondition);
					filtergroup.addfilter(filter_or_operator, filter);
					$("#acct-list").jqxGrid("addfilter", datafield, filtergroup);
					// apply the filters.
					$("#acct-list").jqxGrid("applyfilters");
					
				});
			}
		}
	});
})();