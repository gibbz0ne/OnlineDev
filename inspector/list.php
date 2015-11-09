<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
if(!isset($_SESSION['userId'])){
	header("Location:../index.php");
}
else {
	if($_SESSION['usertype'] != "inspector") {
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
			// echo $include->includeJSFn("inspector");
		?>
		<script>
			$(document).ready(function(){
				$("#jqxMenu").jqxMenu({width: window.innerWidth-5, height: "30px", theme: "main-theme", autoOpen:false});
				// var consumer_inspectionMenu = $("#inspectionMenu").jqxMenu({ width: 226, height: 86, autoOpenPopup: false, mode: "popup",theme: "main-theme"});
				
				// $("#inspection_list").on("rowclick", function (event) {
					// if (event.args.rightclick) {
						// var selected_account = $("#inspection_list").jqxGrid("selectrow", event.args.rowindex);
						// $("#inspection_list").jqxGrid("focus");
						// var scrollTop = $(window).scrollTop();
						// var scrollLeft = $(window).scrollLeft();
						// consumer_inspectionMenu.jqxMenu("open", parseInt(event.args.originalEvent.clientX) + 5 + scrollLeft, parseInt(event.args.originalEvent.clientY) + 5 + scrollTop);
						// return false;
					// }
				// });
				$("#inspection_list").on("contextmenu", function () {
					return false;
				});
				
				var list = {
					datatype: "json",
					dataFields: [
						{name: "acctNo"},
						{name: "consumerName"},
						{name: "address"},
						{name: "protection"},
						{name: "rating"},
						{name: "type"},
						{name: "eSize"},
						{name: "wireSize"},
						{name: "length"},
						{name: "servicePole"},
						{name: "remarks"},
						{name: "meterForm"},
						{name: "meterClass"},
						{name: "totalva"},
						{name: "substation"},
						{name: "feeder"},
						{name: "phase"},
						{name: "inspectedBy"}
					],
					url: "sources/inspectedList.php",
					async: false
				}
				
				var list_data = new $.jqx.dataAdapter(list);
				
				$("#inspection_list").jqxGrid({
					source: list_data,
					width: "99.7%",
					height: "99.5%",
					theme: "main-theme",
					showtoolbar: true,
					altrows: true,
					selectionmode: "singlerow",
					pageable: true,
					showtoolbar: true,
					columnsresize: true,
					rendertoolbar: function(toolbar){
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						var span = $("<span style='float: left; margin-top: 5px; margin-right: 4px;'>Search : </span>");
						var input = $("<input class='jqx-input jqx-widget-content jqx-rc-all' id='searchField' type='text' style='height: 23px; float: left; width: 223px;' />");
						var dropdownlist = $("<div style='float: left; margin-left: 5px;' id='dropdownlist'></div>");
						toolbar.append(container);
						container.append(span);
						container.append(input);
						container.append(dropdownlist);

						$("#dropdownlist").jqxDropDownList({
							autoDropDownHeight: true,
							selectedIndex: 0,
							width: 200,
							height: 23,
							theme: "main-theme",
							source: [
								"Consumer Name",
								"Address"
							]
						});
						
						var searchColumnIndex = $("#dropdownlist").jqxDropDownList("selectedIndex");
						var columnField;
						
						switch(searchColumnIndex){
							case 0:
								columnField = "consumerName";
								break;
							case 1:
								columnField = "address";
								break;
						}
						
						// var searchText = $("#searchField").val();
						// var filtergroup = new $.jqx.filter();
						// var filter_or_operator = 1;
					},
					columns: [
						{text: "Account Number",pinned: true, dataField: "acctNo", align: "center", width: 150},
						{text: "Consumer Name", pinned: true, dataField: "consumerName", align: "center", width: 300},
						{text: "Address", dataField: "address",  align: "center", width: 300},
						{text: "Type", columngroup: "mpr", dataField: "protection", cellsalign: "center", align: "center",  width: 150},
						{text: "Rating", columngroup: "mpr", dataField: "rating", cellsalign: "center", align: "center", width: 50},
						{text: "Type", columngroup: "se", dataField: "type",  align: "center", width: 100},
						{text: "Size", columngroup: "se", dataField: "eSize",  align: "center", width: 100},
						{text: "Wire Size", columngroup: "se",dataField: "wireSize", cellsalign: "center", align: "center", width: 100},
						{text: "Length", columngroup: "se",dataField: "length", cellsalign: "center", align: "center", width: 100},
						{text: "No. of Service Pole", columngroup: "se",dataField: "servicePole", cellsalign: "center", align: "center", width: 150},
						{text: "Remarks", columngroup: "se",dataField: "remarks", cellsalign: "center", align: "center", width: 150},
						{text: "Meter Form", columngroup: "me", dataField: "meterForm", cellsalign: "center", align: "center", width: 150},
						{text: "Class", columngroup: "me", dataField: "meterClass", cellsalign: "center", align: "center", width: 150},
						{text: "Total VA", columngroup: "me", dataField: "totalva", cellsalign: "center", align: "center", width: 150},
						{text: "Substation", columngroup: "me", dataField: "substation", cellsalign: "center", align: "center", width: 150},
						{text: "Feeder", columngroup: "me", dataField: "feeder", cellsalign: "center", align: "center", width: 150},
						{text: "Phase", columngroup: "me", dataField: "phase", cellsalign: "center", align: "center", width: 150},
						{text: "Inspected By", dataField: "inspectedBy", cellsalign: "center", align: "center", width: 150}
					],
					columngroups: 
					[
					  { text: 'Main Protection and Rating', align: 'center', name: 'mpr' },
					  { text: 'Service Entrance', align: 'center', name: 'se' },
					  { text: 'Meter', align: 'center', name: 'me' },
					]
				});
				
				$("#logout").click(function(){
					$.ajax({
						url: "../logout.php",
						success: function(data){
							if(data == 1){
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
		<div class="row push-right-m2">
			<div id="jqxMenu" >
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "index.php"> Home</a></li>
					<li><img  src="../assets/images/icons/icol16/src/report.png" alt=""/><a href = "list.php"> Inspected</a></li>
					<!--li id = "reports"><img  src="../assets/images/icons/icol16/src/report.png" alt=""/> Reports</li-->
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock_unlock.png"> Logout</li>
				</ul>
			</div>
			<div>
				<div id = "inspection_list"></div>
			</div>
		</div>
	</body>
</html>