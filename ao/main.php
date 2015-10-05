<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();

include "../class/includes.class.php";
$include = new includes();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="description" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
		<link rel="shortcut icon" type="image/x-icon" href="../assets/images/icons/icon.png" />
		<title id="Description">APEC LEDGER VIEWER</title>
		
		<?PHP
			echo $include->includeCSS();
			echo $include->includeJS();
		?>
		
	</head>
	<body class="default">
		<div id="jqxMenu" >
			<ul>
				<li><img  src="../assets/images/icons/icol16/src/group.png" alt=""/><a href = "#newApp" id = "newApp">New Consumer</a></li>
				<li><img  src="../assets/images/icons/icol16/src/zone_money.png" alt="" />Daily Transactions</li>
			</ul>	
		</div>
		<div id="mainSplitter">
			<div class="splitter-panel">
				<div id="leftSplitter">
					<div class="splitter-panel">
						<div id = "acct-list"></div>
						<div id="ConsumerMenu">
							<ul>
								<li id="sNewConnection"><img src="../assets/images/icons/icol16/src/page_2.png"> NEW CONNECTION
								</li>
								<li id="sTransferMeter"><img src="../assets/images/icons/icol16/src/transmit.png"> TRANSFER METER
								</li>
								<li id="sDisconnection"><img src="../assets/images/icons/icol16/src/disconnect.png"> DISCONNECTION
								</li>
								<li id="sReconnection"><img src="../assets/images/icons/icol16/src/connect.png"> RECONNECTION / CHANGE METER
								</li>
								<li id="sChangeBillingName"><img src="../assets/images/icons/icol16/src/user.png"> CHANGE BILLING NAME
								</li>
								<li id="sChangeBillingStatus"><img src="../assets/images/icons/icol16/src/pencil.png"> CHANGE BILLING STATUS
								</li>
								<li id="sTemporaryLight"><img src="../assets/images/icons/icol16/src/lightbulb.png"> TEMPORARY LIGHT
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
			</div>
			<div class="splitter-panel">
				<div id='panel' style=" font-size: 10px; font-family: Verdana;">
					
					<form id="testForm" action="">
						<table width = "100%">
							<tr>
								<td width = "33%" style = "padding-top: 10px;"><div id = "customerType" class = "form-control"></div></td>
							</tr>
							<tr>
								<td style = "padding-top: 10px;"><input type = "text" id = "fname" class = "form-control" placeholder = "First Name"></td>
								<td style = "padding-top: 10px;"><input type = "text" id = "mname" class = "form-control" placeholder = "Middle Name"></td>
								<td style = "padding-top: 10px;"><input type = "text" id = "acctNo" class = "form-control" placeholder = "Last Name"></td>
							</tr>
							<tr>
								<td style = "padding-top: 10px;"><input type = "text" id = "contact" placeholder = "Contact" class = "form-control"></td>
								<td style = "padding-top: 10px;"><input type = "text" id = "age" class = "form-control" placeholder = "Age"></td>
								<td style = "padding-top: 10px;"><div id = "civilStatus" class = "form-control"></div></td>
							</tr>
							<tr>
								<td colspan = "3" style = "padding-top: 10px;"><input type = "text" id = "contact" placeholder = "Contact" class = "form-control"></td>
							</tr>
							<tr>
								<td colspan = "3" style = "padding-top: 10px;"><input type = "text" id = "spouseName" placeholder = "Name of Spouse" class = "form-control"></td>
							</tr>
							<tr>
								<td colspan = "3" class = "text-center"><h5>Present Address</h5></td>
							</tr>
							<tr>
								<td style = "padding-top: 10px;"><input type = "text" id = "hno" class = "form-control" placeholder = "House No."></td>
								<td style = "padding-top: 10px;"><input type = "text" id = "purok" class = "form-control" placeholder = "Purok"></td>
								<td style = "padding-top: 10px;"><input type = "text" id = "street" class = "form-control" placeholder = "Street"></td>
							</tr>
							<tr>
								<td style = "padding-top: 10px;"><input type = "text" id = "brgy" class = "form-control" placeholder = "Barangay"></td>
								<td style = "padding-top: 10px;"><div id = "municipality" class = "form-control"></div></td>
							</tr>
							<tr>
								<td colspan = "3" class = "text-center"><h5>Location/Address to be provided with Electric Service</h5></td>
							</tr>
							<tr>
								<td style = "padding-top: 10px;"><input type = "text" id = "hno1" class = "form-control" placeholder = "House No."></td>
								<td style = "padding-top: 10px;"><input type = "text" id = "purok1" class = "form-control" placeholder = "Purok"></td>
								<td style = "padding-top: 10px;"><input type = "text" id = "street1" class = "form-control" placeholder = "Street"></td>
							</tr>
							<tr>
								<td style = "padding-top: 10px;"><input type = "text" id = "brgy1" class = "form-control" placeholder = "Barangay"></td>
								<td style = "padding-top: 10px;"><div id = "municipality1" class = "form-control"></div></td>
							</tr>
							<tr>
								<td colspan = "3" class = "text-center"><h5>Members of the Family</h5></td>
							</tr>
							<tr>
								<td colspan = "3" class = "text-center"><div id = "familyGrid"></div></td>
							</tr>
							<tr>
								<td colspan = "3" class = "text-center"><h5>Requirements</h5></td>
							</tr>
							<tr>
								<td colspan = "3">
									<div id = "req1" style = "color: #FFFFFF;"><h5>Brgy Certificate</h5></div>
									<div id = "req2" style = "color: #FFFFFF;"><h5>Electrical Plan/Sketch Plan</h5></div>
									<div id = "req3" style = "color: #FFFFFF;"><h5>Wiring Permit</h5></div>
									<div id = "req4" style = "color: #FFFFFF;"><h5>1x1 Picture(if married, one for each spouse)</h5></div>
									<div id = "req5" style = "color: #FFFFFF;"><h5>Valid ID(Voter's ID, SSS, PRC etc.) - Photocopy only</h5></div>
									<div id = "req6" style = "color: #FFFFFF;"><h5>Sketch of Location</h5></div>
									<div id = "req7" style = "color: #FFFFFF;"><h5>Photocopy of TCT/Deed-of-Sale/Tax Declaration</h5></div>
								</td>
							</tr>
						</table>
						<table>
							<tr>
								<td colspan = "3" class = "text-center"><h5>Please answer the following questions</h5></td>
							</tr>
							<tr>
								<td colspan = "1" class = "text-center"><div id="button1"></div></td>
								<td colspan = "1" class = "text-center"><h5>(a)</h5></td>
								<td class = "text-left"><h5>Is the Above address your permanent residence?</h5></td>
							</tr>
							<tr>
								<td class = "text-center" valign = "top"><div id="button2"></div></td>
								<td class = "text-center" valign = "top"><h5>(b)</h5></td>
								<td class = "text-left"><h5>Do you own the house/establishments and/or premises in the above location 
									where electric service is to be provided? (if not, indicate name and address of owner)</h5>
									<input type = "text" id = "ownerName" disabled class = "form-control" placeholder = "Name of Owner">
									<input type = "text" id = "ownerAddress" disabled class = "form-control" placeholder = "Address of Owner">
								</td>
							</tr>
							<tr>
								<td class = "text-center"><div id="button3"></div></td>
								<td class = "text-center" valign = "top"><h5>(c)</h5></td>
								<td class = "text-left"><h5>Is the Electric Installation in your house/establishments complete and ready for connection?</h5></td>
							</tr>
							<tr>
								<td class = "text-center" valign = "top"><div id="button4"></div></td>
								<td class = "text-center" valign = "top"><h5>(d)</h5></td>
								<td class = "text-left">
									<h5>Are you the first occupant of the house/establishments?(if not, indicate name of the previous occupant)</h5>
									<input type = "text" disabled id = "prevOccupant" class = "form-control" placeholder = "Name of previous Occupant">
								</td>
							</tr>
							<tr>
								<td class = "text-center"><div id="button5"></div></td>
								<td class = "text-center" valign = "top"><h5>(e)</h5></td>
								<td class = "text-left"><h5>Should there be any unpaid account by the previous occupant, are you willing to should such an obligation?</h5></td>
							</tr>
							<tr>
								<td class = "text-center" valign = "top"><div id="button6"></div></td>
								<td class = "text-center" valign = "top"><h5>(f)</h5></td>
								<td class = "text-left"><h5>Are you going to install electric devised in your house/establishments? (if yes what are those devices?)</h5>
									<div id = "deviceGrid"></div>
								</td>
							</tr>
							
						</table>
						<table width = "100%">
							<tr>
								<td><h5 class = "text-center">Applicant's source of Income:</h5></td>
							</tr>
							<tr>
								<td>
									<div id = "source1" style = "color: #ffffff"><h5>Employment</h5></div>
									<input type = "text" disabled id = "sourceName1" placeholder = "Name of Employer" class = "form-control" >
									<div style = "padding-top: 5px;"><input type = "text" id = "sourceAddress1" placeholder = "Address of office/firm" class = "form-control" disabled></div>
								</td>
							</tr>
							<tr>
								<td>
									<div id = "source2" style = "color: #ffffff"><h5>Business</h5></div>
									<div><input type = "text" id = "sourceName2" placeholder = "Name of Employer" class = "form-control" disabled></div>
									<div style = "padding-top: 5px;"><input type = "text" id = "sourceAddress2" placeholder = "Address of office/firm" class = "form-control" disabled></div>
								</td>
							</tr>
							<tr>
								<td>
									<div id = "source3" style = "color: #ffffff"><h5>Others</h5></div>
									<div><input type = "text" id = "sourceName3" placeholder = "Others" class = "form-control" disabled></div>
								</td>
							</tr>
							<tr>
								<td colspan = "3" style = "padding-top: 10px;" class = "text-center">
									<input type = "button" id = "addApp" value = "Add Application">
								</td>
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
	</body>
</html>