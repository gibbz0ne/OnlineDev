<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
if(!isset($_SESSION['userId'])){
	header("Location:../index.php");
}
else {
	if($_SESSION['usertype'] != "mmd") {
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
			var mr = "";
			var initGrid = function(){
				$("#materialsGrid").jqxGrid({
					width: "100%",
					height: "100%",
					theme: "main-theme",
					selectionmode: "singlecell",
					editable: true,
					showtoolbar: true,
					rendertoolbar: function (toolbar) {
						var me = this;
						var container = $("<div style='margin: 5px;'></div>");
						toolbar.append(container);
						container.append('<input id="approve" type="button" value="Approve" />');
						
						$("#approve").jqxButton({theme: "main-theme", width: 150, disabled: true});
						$("#approve").click(function(){
							$("#approveMr").jqxWindow("open");
						});
					},
					ready: function(){
						$("#materialsGrid").jqxGrid("hidecolumn", "entryId");
					},
					columns: [
						  { text: "#", datafield: "ctr", pinned: true, editable: false, align: "center", cellsalign: "center", width: 50 },
						  { text: "Material Code", editable: false, pinned: true,datafield: "mCode", align: "center", cellsalign: "center", width: 250 },
						  { text: "Description", editable: false, pinned: true, datafield: "description", align: "center", cellsalign: "center", width: 450 },
						  { text: "Unit", editable: false, pinned: true, datafield: "unit", align: "center", cellsalign: "center",width: 150 },
						  { text: "Quantity", editable: false, pinned: true, datafield: "qty", align: "center", cellsalign: "center",width: 200},
						  { text: "Issue Quantity", editable: true, datafield: "iQty", align: "center", cellsalign: "center"},
						  { text: "Entry", editable: true, datafield: "entryId", align: "center", cellsalign: "center"}
					  ]
				});
			};
			var initGrid2 = function(){
				$("#woList").jqxGrid({
					width: "100%",
					height: "100%",
					theme: "main-theme",
					filterable: true,
					columns: [
						  { text: "WO#", pinned: true, datafield: "wo", align: "center", cellsalign: "center", width: 200 },
						  { text: "Consumer",  pinned: true, datafield: "consumer", align: "center", cellsalign: "center", width: 250 },
						  { text: "Address", datafield: "address", align: "center", cellsalign: "center", width: 350 },
						  { text: "Primary No", datafield: "acctNo", align: "center", cellsalign: "center",  width: 200},
						  { text: "Date", datafield: "date", align: "center", cellsalign: "center", width: 200}
					  ]
				});
			};
			$("#jqxMenu").jqxMenu({width: window.innerWidth-3, theme: "main-theme"});
			var woSource ={
				datatype: "json",
				datafields: [
					{name: "ctr"},
					{name: "wo"},
					{name: "consumer"},
					{name: "address"},
					{name: "acctNo"},
					{name: "date"},
				],
				url: "sources/woList.php",
				async: false
				
			}

			var woAdapter = new $.jqx.dataAdapter(woSource);
			
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
				url: "sources/mrList.php",
				async: false
			}
			
			var materials = {
				datatype: "json",
				datafields: [
					{ name: "ctr"},
					{ name: "mCode"},
					{ name: "description"},
					{ name: "unit"},
					{ name: "qty"},
					{ name: "iQty"},
					{ name: "entryId"}
				],
				cache: false,
				updaterow: function (rowid, rowdata, commit) {}
			}
			
			$("#mainSplitter").jqxSplitter({
				width: window.innerWidth-4, 
				height:window.innerHeight-80,
				resizable:true,
				theme: "main-theme",
				orientation: "horizontal",
				panels: [{ size:"50%",collapsible:false  }, 
				{ size: "50%",collapsible: false }] 
			});
			
			var mrAdapter = new $.jqx.dataAdapter(mrSource);
			$("#mrList").on("contextmenu", function(){ return false;});
			
			$("#mrList").on("rowdoubleclick", function(event){
				var rowindex = $('#mrList').jqxGrid('getselectedrowindex');
				//alert(data);
				var data = $('#mrList').jqxGrid('getrowdata', rowindex);
				console.log(data);
				materials.url = 'sources/getItems.php?mr='+data.mrNo;
				var dataAdapter = new $.jqx.dataAdapter(materials);
				$("#materialsGrid").jqxGrid({source: materials});
				
				woSource.url = "sources/getWo2.php?mr="+data.mrNo;
				var dataAdapter = new $.jqx.dataAdapter(woSource);
				$("#woList").jqxGrid({source: dataAdapter});
				
				$("#approve").jqxButton({disabled: false});
				mr = data.mrNo;
			});
			
			var initWidgets = function (tab) {
				switch (tab) {
					case 0:
						initGrid();
						break;
					case 1:
						initGrid2();
						break;
				}
			}
			
			$('#jqxTabs').jqxTabs({ width: "100%", height: "100%", theme: "main-theme", initTabContent: initWidgets });
				
			$("#mrList").jqxGrid({
				width: "100%",
				height: "100%",
				theme: "main-theme",
				source: mrAdapter,
				columns: [
					  { text: "#", datafield: "ctr", align: "center", cellsalign: "center", width: 50 },
					  { text: "MR-M No", datafield: "mrNo", align: "center", cellsalign: "center", width: 250 },
					  { text: "Items", datafield: "items", align: "center", cellsalign: "center", width: 150 },
					  { text: "WO's", datafield: "wos", align: "center", cellsalign: "center",width: 150 },
					  { text: "Purpose", datafield: "purpose", align: "center", cellsalign: "center",width: 200},
					  { text: "Date", datafield: "date", align: "center", cellsalign: "center"}
				  ]
			});
			
			
			
			$("#confirm").click(function(){
				var rows = $("#materialsGrid").jqxGrid("getrows");
				var items = [];
				for(var i=0; i<rows.length; i++){
					var row = rows[i];
					items.push(row.iQty, row.mCode, row.entryId);
				}
				$.ajax({
					url: "functions/approveMr.php",
					type: "post",
					data: {mr: mr, items: items},
					success: function(result){
						if(result == 1){
							mrSource.url = "sources/mrList.php";
							var mrAdapter = new $.jqx.dataAdapter(mrSource);
							$("#mrList").jqxGrid({source: mrAdapter});
							
							$("#approve").jqxButton({disabled: true});
							$("#materialsGrid").jqxGrid("clear");
							$("#approveMr").jqxWindow("close");
						}
					}
				})
			});
			
			$("#approveMr").jqxWindow({
				theme: "main-theme", height: 150, width:  400, cancelButton: $("#cancel"), showCloseButton: true, draggable: false, resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.50
			});

			$('#processing	').jqxWindow({width: 380, height:80, resizable: false,  isModal: true,showCloseButton:false, autoOpen: false, modalOpacity: 0.01,theme:'custom-abo-ao'});
			
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
	<div class = "row push-right-m2" style="margin:0 !important;">
		<div id="jqxMenu">
			<ul>
				<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "index.php"> Home</a></li>
				<li><img src = "../assets/images/icons/icol16/src/cog.png"><a href = "mr.php"> Meters</a></li>
				<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
			</ul>
		</div>
		<div id = "mainSplitter">
			<div class="splitter-panel">
				<div id = "mrList"></div>
			</div>
			<div class="splitter-panel">
				<div id='jqxTabs'>
					<ul>
						<li style="margin-left: 30px;">
							Materials
						</li>
						<li>
							Work Order
						</li>
					</ul>
					<div style="overflow: hidden;">
						<div style="border:none;" id="materialsGrid">
						</div>
					</div>
					<div style="overflow: hidden;">
						<div style="border:none;" id="woList"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div id = "approveMr">
			<div><img src = "../assets/images/icons/icol16/src/accept.png">CONFIRM</div>
			<div class = "text-center">
				<h4>Confirm Material Requisition</h4>
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