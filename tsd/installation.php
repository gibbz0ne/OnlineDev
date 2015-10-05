<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	session_start();
	if(!isset($_SESSION['userId'])){
		header("Location:../index.php");
	}
	else {
		if($_SESSION['usertype'] != "tsd") {
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
				$("#jqxMenu").jqxMenu({width: window.innerwidth, theme: "custom-zandro"});
				
				$("#mainSplitter").jqxSplitter({
					width:window.innerwidth, 
					height:window.innerHeight-40,
					resizable:true,
					theme: "custom-zandro",
					orientation: "horizontal",
					panels: [{ size:"50%",collapsible:false  }, 
					{ size: "50%",collapsible: false }] 
				});

				var consumerList = {
					datatype: "json",
					datafields: [
						{ name: "ctr1"},
						{ name: "acctNo"},
						{ name: "consumerName"},
						{ name: "address"},
						{ name: "meterNo"},
						{ name: "cid"}
					]
				}
				
				var mrSource = {
					datatype: "json",
					datafields: [
						{name: "ctr"},
						{name: "mrNo"},
						{name: "items"},
						{name: "wos"},
						{name: "purpose"},
						{name: "date"},
						{name: "mrNo"}
					],
					url: "sources/mrList2.php",
					async: false
				}
				
				var mrAdapter = new $.jqx.dataAdapter(mrSource);
				
				$("#mrList").jqxGrid({
					width: "100%",
					height: "100%",
					theme: "custom-zandro",
					selectionmode: "singlerow",
					source: mrAdapter,
					showtoolbar: true,
					columns: [
						  { text: "#", datafield: "ctr", align: "center", cellsalign: "center", width: 50 },
						  { text: "MR-M No", datafield: "mrNo", align: "center", cellsalign: "center", width: 250 },
						  { text: "Items", datafield: "items", align: "center", cellsalign: "center", width: 150 },
						  { text: "WO's", datafield: "wos", align: "center", cellsalign: "center",width: 150 },
						  { text: "Purpose", datafield: "purpose", align: "center", cellsalign: "center",width: 200},
						  { text: "Date", datafield: "date", align: "center", cellsalign: "center"}
					  ]
				});
				
				$("#consumerList").jqxGrid({
					width: "100%",
					height: "100%",
					theme: "custom-zandro",
					selectionmode: "singlerow",
					showtoolbar: true,
					rendertoolbar: function(toolbar){
						var container = $("<div style='margin: 5px;'></div>");
						toolbar.append(container);
						container.append('<input id="assignMeter" type="button" value="Assign Meter" />');
						
						$("#assignMeter").jqxButton({theme: "custom-zandro", width: 150, disabled: true});
					},
					ready: function(){
						$("#consumerList").jqxGrid("hidecolumn", "cid");
					},
					columns: [
						  { text: "#", datafield: "ctr1", align: "center", cellsalign: "center", width: 50 },
						  { text: "Account Number", datafield: "acctNo", align: "center", cellsalign: "center", width: 250 },
						  { text: "Consumer Name", datafield: "consumerName", align: "center", cellsalign: "center", width: 350 },
						  { text: "Address", datafield: "address", align: "center", cellsalign: "center",width: 350 },
						  { text: "Meter No", datafield: "meterNo", align: "center", cellsalign: "center",width: 200},
						  { text: "CID", datafield: "cid", align: "center", cellsalign: "center",width: 200}
					  ]
				});
				
				$("#consumerList").on("rowselect", function(event){
					$("#assignMeter").jqxButton({disabled: false});
				});
				
				$("#mrList").on("rowdoubleclick", function(events){
					var rowindex = $("#mrList").jqxGrid("getselectedrowindex");
					var data = $("#mrList").jqxGrid("getrowdata", rowindex);
					
					consumerList.url = 'sources/getConsumerWo.php?mr='+data.mrNo;
					// selected_account = data.acctNo;
					
					var dataAdapter = new $.jqx.dataAdapter(consumerList);
					$('#consumerList').jqxGrid({source:dataAdapter});
					
				});
				
				$("#meterForm").jqxWindow({
					theme: "custom-zandro", maxHeight: 510, height: 510, width:  510, cancelButton: $("#cancel"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});
				
				$("#confirmModal").jqxWindow({
					theme: "custom-zandro", height: 170, width:  400, cancelButton: $("#cancel2"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
				});

				$('#processing').jqxWindow({width: 380, height:80, resizable: false,  isModal: true,showCloseButton:false, autoOpen: false, modalOpacity: 0.50,theme: "main-theme"});
				
				$("#assignMeter").on("click", function(event){
					var row = $("#consumerList").jqxGrid("getselectedrowindex");
					var data = $("#consumerList").jqxGrid("getrowdata", row);
					
					$.ajax({
						url: "sources/getConsumerWo.php",
						type: "post",
						data: {cid: data.cid},
						success: function(data){
							$("#meterForm").jqxWindow("open");
							$("#consumerData").html(data);
						}
					});
				});
				
				$("#confirm").click(function(){
					$("#confirmModal").jqxWindow("open")
				});
				
				$("#confirm2").click(function(){
					var row = $("#consumerList").jqxGrid("getselectedrowindex");
					var data = $("#consumerList").jqxGrid("getrowdata", row);
					
					$.ajax({
						url: "functions/issueMeter.php",
						type: "post",
						data: {cid: data.cid, mReading: $("#mReading").val(), mBrand: $("#mBrand").val(), mClass: $("#mClass").val(), mSerial: $("#mSerial").val(), mERC: $("#mERC").val(), mLabSeal: $("#mLabSeal").val(), mTerminal: $("#mTerminal").val(), multiplier: $("#mMultiplier").val()},
						success: function(data){
							console.log(data);
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
				
			});
		</script>
	</head>
	<body class="default">
		<div class = "row" style="margin:0 !important;">
			<div class = "col-sm-12">
				<div id="jqxMenu">
					<ul>
						<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "#"> Home</a></li>
						<li id = "woReports"><img  src="../assets/images/icons/icol16/src/page_white_text.png" alt=""/><a href = "#"> Work Order</a></li>
						<li id = "mrReports"><img  src="../assets/images/icons/icol16/src/report.png" alt=""/><a href = "#"> Material Requisition</a></li>
						<li><img  src="../assets/images/icons/icol16/src/cog.png" alt=""/><a href = "installation.php"> Installation</a></li>
						<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
					</ul>	
				</div>
				<div id = "mainSplitter">
					<div class="splitter-panel">
						<div id = "mrList"></div>
					</div>
					<div class="splitter-panel">
						<div id = "consumerList"></div>
					</div>
				</div>
			</div>
		</div>
		<div id = "confirmModal">
			<div><img src = "../assets/images/icons/icol16/src/accept.png">CONFIRM</div>
			<div class = "text-center">
				<h4>Confirm Meter for Consumer?</h4><br><br>
				<div class = "col-sm-6"><button class = "btn btn-success btn-block" id = "confirm2">APPROVE</button></div>
				<div class = "col-sm-6"><button class = "btn btn-danger btn-block" id = "cancel2">CANCEL</button></div>
			</div>
			
		</div>
		<div id = "meterForm">
			<div><img src = "../assets/images/icons/icol16/src/accept.png"> CONFIRM</div>
			<div>
				<div id = "consumerData" class = "text-center"></div>
				<table class = "table table-condensed">
					<tr>
						<td>METER READING</td>
						<td><input type = "text" id = "mReading" class = "form-control"></td>
					</tr>
					<tr>
						<td>BRAND</td>
						<td><input type = "text" id = "mBrand" class = "form-control"></td>
					</tr>
					<tr>
						<td>CLASS/AMPERES</td>
						<td><input type = "text" id = "mClass" class = "form-control"></td>
					</tr>
					<tr>
						<td>SERIAL NO.</td>
						<td><input type = "text" id = "mSerial" class = "form-control"></td>
					</tr>
					<tr>
						<td>ERC SEAL NO.</td>
						<td><input type = "text" id = "mERC" class = "form-control"></td>
					</tr>
					<tr>
						<td>METER LAB SEAL</td>
						<td><input type = "text" id = "mLabSeal" class = "form-control"></td>
					</tr>
					<tr>
						<td>TERMINAL SEAL</td>
						<td><input type = "text" id = "mTerminal" class = "form-control"></td>
					</tr>
					<tr>
						<td>MULTIPLIER</td>
						<td><input type = "text" id = "mMultiplier" class = "form-control"></td>
					</tr>
				</table>
				<div class = "col-sm-6"><button  class = "btn btn-success btn-block" id = "confirm">CONFIRM</button></div>
				<div class = "col-sm-6"><button class = "btn btn-danger btn-block" id = "cancel">CANCEL</button></div>
			</div>
		</div>
		<div id="processing">
			<div><img src="../assets/images/icons/icol16/src/accept.png" style="margin-bottom:-5px;"><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
			<div >
			<div><img src="../assets/images/loader.gif">Please Wait
			
			</div>
			</div>
		</div>
	</body>
</html>