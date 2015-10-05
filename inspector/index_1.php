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
			echo $include->includeJSFn("inspector");
		?>
		
	</head>
	<body class="default">
		<div class="row push-right-m2">
			<div id="jqxMenu" >
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "index.php"> Home</a></li>
					<li id = "reports"><img  src="../assets/images/icons/icol16/src/report.png" alt=""/> Reports</li>
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock_unlock.png"> Logout</li>
				</ul>
			</div>
			<div>
				<div id = "inspection_list"></div>
				<div id="inspectionMenu">
					<ul>
						<li id="approve"><img src="../assets/images/icons/icol16/src/accept.png"> APPROVE
						</li>
						<li id = "change"><img src = "../assets/images/icons/icol16/src/arrow_refresh.png"> CHANGE APPLICATION</li>
						<li id="cancel"><img src="../assets/images/icons/icol16/src/cancel.png"> CANCEL APPLICATION
						</li>
					</ul>
				</div>
			</div>
			<div id = "confirmInspection">
				<div><img src = "../assets/images/icons/icol16/src/application.png"> Confirm Inspection</div>
				<div style = "background-color: #0A525A; color: #ffffff">
					<table align = "center"width = "90%">
						<tr>
							<td class = "text-center" colspan = "2"><h5>MAIN PROTECTION AND RATING</h5></td>
						</tr>
						<tr>
							<td class = "text-center"><div id = "pType" class = "form-control"></div></td>
							<td class = "text-center"><input type = "text" id = "rating" placeholder = "Rating" class = "form-control"></td>
						</tr>
						<tr>
							<td class = "text-center" colspan = "2"><h5>SERVICE ENTRANCE</h5></td>
						</tr>
						<tr>
							<td class = "text-center"><input type = "text" id = "eType" placeholder = "Type" class = "form-control"></div></td>
							<td class = "text-center"><input type = "text" id = "wSize" placeholder = "Wire Size" class = "form-control"></td>
						</tr>
						<tr>
							<td class = "text-center"><input type = "text" id = "servicePole" placeholder = "No. of Service Pole" class = "form-control"></div></td>
							<td class = "text-center"><input type = "text" id = "length" placeholder = "Length" class = "form-control"></td>
						</tr>
						<tr>
							<td class = "text-center" colspan = "2"><h5>CHECK LOAD</h5></td>
						</tr>
						<tr>
							<td><input type = "text" id = "totalVa" class = "form-control" placeholder = "TOTAL VA"></td>
							<td><input type = "text" id = "computedLoad" class = "form-control" placeholder = "COMPUTED LOAD"></td>
						</tr>
						<tr>
							<td><input type = "text" id = "meter" class = "form-control" placeholder = "METER"></td>
							<td></td>
						</tr>
						<tr>
							<td class = "text-center" colspan = "2"><h5>INSPECTED BY:</h5></td>
						</tr>
						<tr>
							<td><input type = "text" id = "inspectedBy" class = "form-control" placeholder = "Inspected by"></td>
							<td><div id = "inspectedDate" ></div></td>
						</tr>
						<tr>
							<td class = "text-center" colspan = "2"><h5>REMARKS:</h5></td>
						</tr>
						<tr>
							<td colspan = "2"><input type = "text" id = "iRemarks" class = "form-control" placeholder = "Remarks"></td>
						</tr>
					</table>
					<div style = "margin-top: 10px;" class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-success" value = "Approve" id = "approveApp">
					</div>
					<div style = "margin-top: 10px;" class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-danger cancelApp" value = "Cancel">
					</div>
				</div>
			</div>
			<div id = "rejectApp">
				<div><img src = "../assets/images/icons/icol16/src/application_delete.png"> Cancel Application</div>
				<div>
					<h5>Inspected By:</h5>
					<input type = "text" style = "margin-top: 7px;" id = "inspectedBy1" class = "form-control" placeholder = "Inspected by">
					<h5>Remarks:</h5>
					<input type = "text" id = "remarks1" style = "margin-top: 10px;" class = "form-control" placeholder = "Remarks">
					<div style = "margin-top: 10px;" class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-success" value = "Confirm" id = "approveApp1">
					</div>
					<div style = "margin-top: 10px;" class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-danger cancelApp1" value = "Cancel" id="cancelC">
					</div>
				</div>
			</div>
			<div id = "confirm2">
				<div><img src = "../assets/images/icons/icol16/src/accept.png"> Confirm</div>
				<div>
					<h4 class = "text-center">Submit Inspection Report</h4>
					<div style = "margin-top: 10px;" class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-success" value = "Submit" id = "submit">
					</div>
					<div style = "margin-top: 10px;" class = "col-sm-6">
						<input type = "button" class = "form-control btn btn-danger cancelApp2" value = "Cancel">
					</div>
				</div>
			</div>
			
			 <div id = "reportList">
				<div><img src = "../assets/images/icons/icol16/src/report.png"> Inspection List</div>
				<div>
					<div id='jqxTabs'>
						<ul>
							<li>Approved</li>
							<li>Cancelled</li>
						</ul>
						<div>
						   <div id = "inspection_list2"></div>
						</div>	
						<div>
							<div id = "inspection_list3"></div>
						</div>
					</div>
				</div>
			</div>
			<div id="processing">
				<div><img src="../assets/images/icons/icol16/src/accept.png" style="margin-bottom:-5px;"><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
				<div>
					<div><img src="../assets/images/loader.gif">Please Wait
					
					</div>
				</div>
			</div>
		</div>
	</body>
</html>