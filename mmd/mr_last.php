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
			var mr = "";
			$("#jqxMenu").jqxMenu({width: "100%", theme: "custom-zandro"});

			$("#mainSplitter").jqxSplitter({
				width: "100%", 
				height:window.innerHeight-40,
				resizable:true,
				theme: "custom-zandro",
				orientation: "horizontal",
				panels: [{ size:"50%",collapsible:false  }, 
				{ size: "50%",collapsible: false }] 
			});
			
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
			
			$("#logout").click(function(){
				$.ajax({
					url: "../logout.php",
					success: function(data){
						if(data == 1){
							// $("#processing").jqxWindow("open");
							// setTimeout(function(){
							window.location.href = "../index.php";
							// }, 1000);
						}
					}
				});
			});
		});
		</script>
	</head>
	<body class="default">
	<div class = "row">
		<div class = "col-sm-12">
			<div id="jqxMenu">
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "index.php"> Home</a></li>
					<li><img src = "../assets/images/icons/icol16/src/hammer_screwdriver.png"><a href = "mr.php"> Material Requisition</a></li>
					<li><img  src="../assets/images/icons/icol16/src/cog.png" alt=""/><a href = "installation.php"> Installation</a></li>
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
				</ul>
			</div>
			<div id = "mainSplitter">
				<div class="splitter-panel">
					<div id = "mrList"></div>
				</div>
				<div class="splitter-panel">
					<div id = "materialsGrid"></div>
				</div>
			</div>
		</div>
	</body>
</html>