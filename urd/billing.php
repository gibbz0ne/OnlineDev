<?php
	error_reporting(E_ALL ^ E_DEPRECATED);
	session_start();
	if(!isset($_SESSION['userId'])){
		header("Location:../index.php");
	}
	else {
		if($_SESSION['usertype'] != "urd") {
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
				$("#jqxMenu").jqxMenu({width: window.innerwidth, theme:"main-theme"});

				$("#mainSplitter").jqxSplitter({
					width:window.innerWidth-8,
					height:window.innerHeight-40,
					resizable:true,
					orientation: "horizontal",
					panels: [{ size:"50%",collapsible:false  },
						{ size: "50%",collapsible: false }]
				});

				$('#processing').jqxWindow({width: 380, height:80, resizable: false,  isModal: true,showCloseButton:false, autoOpen: false, modalOpacity: 0.01,theme:'custom-abo-ao'});
				
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
						{name: "appType"}
					],
					url: "sources/consumers2.php",
					async: false
				}
				
				setInterval(function(){
					var dataAdapter = new $.jqx.dataAdapter(consumers);
					$('#consumerList').jqxGrid({source:dataAdapter});
				},360000);
				
				var consumer_data = new $.jqx.dataAdapter(consumers);

				$("#consumerList").on("contextmenu", function(){
					return false;
				});
				
				$("#consumerList").jqxGrid({
					source: consumer_data,
					height: "100%",
					width: "100%",
					theme: "main-theme",
					pageable: true,
					showtoolbar: true,
					rendertoolbar: function(toolbar){
						var container = $("<div style='margin: 4px;'></div>");
						toolbar.append(container);
						container.append('<input id="approve" type="button" value="Approve" />');
						
						$("#approve").jqxButton({theme: "main-theme", disabled: true});
						
						$("#approve").on("click", function(){
							$("#approveModal").jqxWindow("open");
						});
					},
					columns: [
						{text: "Primary Account No.", pinned: true, align: "center", cellsalign: "center", datafield: "acctNo", width: 150},
						{text: "Consumer Name", pinned: true, align: "center", datafield: "consumerName", width: 300},
						{text: "Address", align: "center", datafield: "address", width: 350},
						{text: "Municipality", align: "center", cellsalign: "center", datafield: "municipality", width: 150},
						{text: "Area", align: "center", cellsalign: "center", datafield: "area", width: 100},
						{text: "Type", align: "center", cellsalign: "center", datafield: "type", width: 100},
						{text: "Application ", align: "center", cellsalign: "center", datafield: "appType"},
					]
				});
				var acctNo;
				var	appType;
				$("#consumerList").on("rowselect", function(event){
					acctNo = event.args.row.acctNo;
					appType = event.args.row.appType;
					$("#approve").jqxButton({disabled: false});
				});
				
				$("#approveModal").jqxWindow({
					height: 150, width: 400, cancelButton: $('#cancel'), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50,theme:'main-theme'
				});
				
				$("#confirmApp").click(function(){
					$.ajax({
						url: "functions/approveApp.php",
						type: "post",
						data: {type: appType.replace(/ /g, ""), acctNo: acctNo},
						success: function(data){
							consumers.url = "sources/consumers2.php";
							var consumerData = new $.jqx.dataAdapter(consumers);
							$("#consumerList").jqxGrid({source: consumerData});
							$("#approveModal").jqxWindow("close");
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
		<div class = "row push-right-m2">
			<div id="jqxMenu">
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "index.php"> Home</a></li>
					<li><img  src="../assets/images/icons/icol16/src/script.png" alt=""/><a href = "biling.php"> For Billing</a></li>
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
				</ul>
			</div>
			<div id = "mainSplitter">
				<div class="splitter-panel">
					<div id = "consumerList"></div>
				</div>
				<div class="splitter-panel">
					<div id = "woList"></div>
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
		<div id="approveModal">
			<div><img src="../assets/images/icons/icol16/src/accept.png" ><b><span style="margin-top:-24; margin-left:3px">Processing</span></b></div>
			<div >
				<div>
					<h4 class = "text-center">Approve Consumer Application?</h4>
				</div><br>
				<div class = "col-sm-6">
					<button id = "confirmApp" class = "btn btn-success btn-block">Confirm</button>
				</div>
				<div class = "col-sm-6">
					<button id = "cancel" class = "btn btn-danger btn-block">Cancel</button>
				</div>
			</div>
		</div>
	</body>
</html>