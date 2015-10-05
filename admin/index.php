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
				$("#jqxMenu").jqxMenu({width: window.innerwidth, theme:"main-theme"});
				
				$('#jqxTree').jqxTree({  height: '100%', width: '100%', theme: "main-theme"});
				$("#splitter").jqxSplitter({ theme: "main-theme", width: window.innerWidth-5, height: 580, panels: [{ size: 250 }] });
				$("#panel").jqxPanel({ theme: "main-theme", width: "81%", height: "100%"});
				var applications = {
					dataType: "json",
					dataFields: [
						{name: "status"},
						{name: "acctNo"},
						{name: "consumerName"},
						{name: "bName"},
						{name: "address"},
						{name: "municipality"},
						{name: "type"},
						{name: "so"},
						{name: "car"},
						{name: "addedBy"},
						{name: "dateAdded"},
					],
					url: "sources/allApps.php",
					pagenum: 0,
					pagesize: 20,
					async: false
				}
				
				var appData = new $.jqx.dataAdapter(applications);
				
				$.ajax({
					url: "sources/getStats.php",
					dataType: "json",
					success: function(data){
						$("#stat1").html(data.stat1);
						$("#stat2").html(data.stat2);
						$("#stat3").html(data.stat3);
						$("#stat4").html(data.stat4);
						$("#stat5").html(data.stat5);
						$("#stat6").html(data.stat6);
						$("#stat7").html(data.stat7);
						$("#stat8").html(data.stat8);
					}
				});
				
				$("#applications").click(function(){
					$("#grid").jqxGrid({
						width: "100%",
						height: "100%",
						source: appData,
						pageable: true,
						theme: "main-theme",
						sortable: true,
						filterable: true,
						altrows: true,
						columns: [
							{text: "Status", dataField: "status", cellsalign: "center", pinned: true, align: "center", width: 180},
							{text: "Account Number", dataField: "acctNo", cellsalign: "center", align: "center", pinned: true, width: 150},
							{text: "Consumer Name", dataField: "consumerName", align: "center", pinned: true, width: 250},
							{text: "Business Name", dataField: "bName", align: "center", pinned: true, width: 250},
							{text: "Address", dataField: "address", align: "center", width: 290},
							{text: "Municipality", dataField: "municipality", align: "center", width: "150"},
							{text: "Type", dataField: "type", align: "center", width: 180},
							{text: "Service Order", dataField: "so", align: "center", width: 150},
							{text: "C.A.R", dataField: "car", align: "center", width: 150},
							{text: "Added By", dataField: "addedBy", align: "center", width: 200},
							{text: "Date Added (Y-m-d)", dataField: "dateAdded", align: "center", width: 150}
						]
					});
					$("#grid").jqxGrid("refresh");
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
					pagenum: 0,
					pagesize: 20,
					async: false
				}
				
				var list_data = new $.jqx.dataAdapter(list);
				
				$("#inspection").click(function(){
					$("#grid").jqxGrid({
						source: list_data,
						width: "100%",
						height: "100%",
						theme: "main-theme",
						altrows: true,
						selectionmode: "singlerow",
						pageable: true,
						columnsresize: true,
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
							{text: "Inspected By", dataField: "inspectedBy", cellsalign: "center", align: "center", width: 200}
						],
						columngroups: [
							{ text: 'Main Protection and Rating', align: 'center', name: 'mpr' },
							{ text: 'Service Entrance', align: 'center', name: 'se' },
							{ text: 'Meter', align: 'center', name: 'me' },
						]
					});
				});
				
				var consumers = {
					datatype: "json",
					datafields: [
						{name: "status"},
						{name: "acctNo"},
						{name: "consumerName"},
						{name: "address"},
						{name: "municipality"},
						{name: "area"},
						{name: "type"},
						{name: "cid"},
						{name: "appId"},
						{name: "tid"}
					],
					url: "sources/accounts.php",
					pagenum: 0,
					pagesize: 20,
					async: false
				}
				
				var consumer_data = new $.jqx.dataAdapter(consumers);
				
				$("#accountNumber").click(function(){
					$("#grid").jqxGrid({
						source: consumer_data,
						height: "100%",
						width: "100%",
						theme: "main-theme",
						pageable: true,
						columns: [
							{text: "Primary Account No.", pinned: true, align: "center", cellsalign: "center", datafield: "acctNo", width: 150},
							{text: "Consumer Name", pinned: true, align: "center", datafield: "consumerName", width: 300},
							{text: "Address", align: "center", datafield: "address", width: 350},
							{text: "Municipality", align: "center", cellsalign: "center", datafield: "municipality", width: 150},
							{text: "Area", align: "center", cellsalign: "center", datafield: "area", width: 100},
							{text: "Type", align: "center", datafield: "type"}
						]
					});
				});
				
				var consumers = {
					datatype: "json",
					datafields: [
						{name: "id"},
						{name: "fname"},
						{name: "mname"},
						{name: "lname"},
						{name: "username"},
						{name: "password"},
						{name: "groupName"}
					],
					url: "sources/users.php",
					pagenum: 0,
					pagesize: 20,
					async: false
				}
					
				var usersData = new $.jqx.dataAdapter(consumers);
				
				$("#users").click(function(){
					$("#grid").jqxGrid({
						source: usersData,
						height: "100%",
						width: "100%",
						theme: "main-theme",
						pageable: true,
						columns: [
							{text: "ID", pinned: true, align: "center", cellsalign: "center", datafield: "id", width: 50},
							{text: "First Name", pinned: true, align: "center", datafield: "fname", width: 150},
							{text: "Middle Name", align: "center", datafield: "mname", width: 150},
							{text: "Last Name", align: "center", cellsalign: "center", datafield: "lname", width: 150},
							{text: "Username", align: "center", cellsalign: "center", datafield: "username", width: 150},
							{text: "Password", align: "center", datafield: "password", width: 250},
							{text: "Group Name", align: "center", datafield: "groupName", width: 200}
						]
					});
				});
				
				var userGroup = {
					datatype: "json",
					dataFields: [
						{name: "groupId"},
						{name: "groupName"}
					],
					url: "sources/getGroups.php",
					async: false
				}
				
				var userGroupData = new $.jqx.dataAdapter(userGroup);
				
				$("#userGroup").jqxDropDownList({
					source: userGroupData,
					selectedIndex: 0,
					width: "94.5%",
					height: 20,
					autoDropDownHeight: true,
					displayMember: "groupName",
					valueMember: "groupId"
				});
				
				$("#branch").jqxDropDownList({
					source: ["B1", "B2", "B3"],
					selectedIndex: 0,
					width: "94.5%",
					height: 20,
					autoDropDownHeight: true
				});				
				
				$("#addUser").on("click", function(){
					$("#addUserModal").jqxWindow("open");
				});
				
				$("#addUserModal").on("close", function(){
					$("#addUserModal input").val("");
				});
				
				$("#add").click(function(){
					var group = $("#userGroup").jqxDropDownList('getSelectedItem');
					var branch = $("#branch").jqxDropDownList('getSelectedItem');
					// alert("Item: " + item.label);
					// console.log(branch.value);
					$.ajax({
						url: "functions/addUser.php",
						type: "post",
						data: {fname: $("#fname").val(), mname: $("#mname").val(), lname: $("#lname").val(), uname: $("#uname").val(),
								password: $("#password").val(), branch: branch.value, group: group.value },
						success: function(data){
							if(data == 1){
								$("#addUserModal").jqxWindow("close");
							}
						}
					});
				})
				
				$("#addUserModal").jqxWindow({
					height: 400, width:  600, cancelButton: $('#cancel'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#logout").click(function(){
					$.ajax({
						url: "../logout.php",
						success: function(data){
							if(data == 1)
								window.location.href = "../index.php";
						}
					});
				});
			});

		</script>
	</head>
	<body class="default push-right-m2">
		<div id="jqxMenu">
			<ul>
				<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "#"> Home</a></li>
				<li id = "addUser"><img  src="../assets/images/icons/icol16/src/add.png" alt=""/><a href = "#"> Add User</a></li>
				<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
			</ul>	
		</div>
		<div id="splitter">
			<div>
				<div style="border: none;" id='jqxTree'>
					<ul>
						<li id="dashboard" item-expanded='true'>
							<img style='float: left; margin-right: 5px;' src='../assets/images/tiles/Control Panel.png' width = "20px"/>
							<span item-title="true">DASHBOARD</span>
							<ul>
								<li id="applications" item-expanded='true'>
									<img style='float: left; margin-right: 5px;' src='../assets/images/icons/icol16/src/application.png' />
									<span item-title="true">APPLICATIONS</span>
								</li>
								<li id="inspection">
									<img style='float: left; margin-right: 5px;' src='../assets/images/tiles/Folder.png' width = "20px" />
									<span item-title="true">INSPECTIONS</span> 
								</li>
								<li id="accountNumber">
									<img style='float: left; margin-right: 5px;' src='../assets/images/tiles/Folder.png' width = "20px" />
									<span item-title="true">ACCOUNT NUMBER</span> 
								</li>
								<li id="tsd">
									<img style='float: left; margin-right: 5px;' src='../assets/images/tiles/Folder.png' width = "20px" />
									<span item-title="true">TSD</span> 
								</li>
								<li id="mmd">
									<img style='float: left; margin-right: 5px;' src='../assets/images/tiles/Folder.png' width = "20px" />
									<span item-title="true">MMD</span> 
								</li>
								<li id="users">
									<img style='float: left; margin-right: 5px;' src='../assets/images/tiles/user.png' width = "20px" />
									<span item-title="true">USERS</span> 
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div id="panel">
				<div id = "grid"><br><br>
					<div class = "row">
						<div class = "col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">FOR INSPECTION</div>
								<div class = "panel-body">Pending: <span id = "stat1"></span></div>
							</div>
						</div>
						<div class = "col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">FOR ACCOUNT NUMBER</div>
								<div class = "panel-body">Pending: <span id = "stat2"></span></div>
							</div>
						</div>
					</div>
					<div class = "row">
						<div class = "col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">FOR SO</div>
								<div class = "panel-body">Pending: <span id = "stat3"></span></div>
							</div>
						</div>
						<div class = "col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">FOR WO</div>
								<div class = "panel-body">Pending: <span id = "stat4"></span></div>
							</div>
						</div>
					</div>
					<div class = "row">
						<div class = "col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">FOR MATERIAL REQUISITION</div>
								<div class = "panel-body">Pending: <span id = "stat5"></span></div>
							</div>
						</div>
						<div class = "col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">FOR MATERIAL CHARGE TICKET</div>
								<div class = "panel-body">Pending: <span id = "stat6"></span></div>
							</div>
						</div>
					</div>
					<div class = "row">
						<div class = "col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">FOR INSTALLATION</div>
								<div class = "panel-body">Pending: <span id = "stat7"></span></div>
							</div>
						</div>
						<div class = "col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">FOR BILLING</div>
								<div class = "panel-body">Pending: <span id = "stat8"></span></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="addUserModal">
			<div><img src="../assets/images/icons/icol16/src/user.png" style="margin-bottom:-5px;"><b><span style="margin-top:-24; margin-left:3px">Add New User</span></b></div>
			<div>
				<div class = "row">
					<div class = "col-sm-1"></div>
					<div class = "col-sm-10">
						<div class = "form-control" id = "userGroup" style = "margin-top: 5px;"></div>
						<input type = "text" id = "fname" class = "form-control" style = "margin-top: 5px;" placeholder = "First Name">
						<input type = "text" id = "mname" class = "form-control" style = "margin-top: 5px;" placeholder = "Middle Name">
						<input type = "text" id = "lname" class = "form-control" style = "margin-top: 5px;" placeholder = "Last Name">
						<input type = "text" id = "uname" class = "form-control" style = "margin-top: 5px;" placeholder = "Username">
						<input type = "password" id = "password" class = "form-control" style = "margin-top: 5px;" placeholder = "Password">
						<div id = "branch" style = "margin-top: 5px; margin-bottom: 5px;" class = "form-control"></div>
						<div class = "col-sm-6">
							<button class = "btn btn-success btn-block" id = "add">ADD</button>
						</div>
						<div class = "col-sm-6">
							<button class = "btn btn-danger btn-block" id = "cancel">CANCEL</button>
						</div>
					</div>
					<div class = "col-sm-1"></div>
				</div>
			</div>
		</div>
	</body>
</html>