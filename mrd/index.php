<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
if(!isset($_SESSION['userId'])){
	header("Location:../index.php");
}
else {
	if($_SESSION['usertype'] != "mrd") {
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
				$("#jqxMenu").jqxMenu({ width: window.innerWidth-5, height: "30px", theme:"main-theme", autoOpen:false});
				$("#mainSplitter").jqxSplitter({
					width: window.innerWidth-5, 
					height: window.innerHeight-40,
					theme:"main-theme",
					resizable:true,
					orientation: "horizontal",
					panels: [{ size:"55%",collapsible:false  }, 
					{ size: "45%",collapsible: true }] 
				});
				
			});
		</script>
	</head>
	<body class="default">
		<div class="row push-right-m2">
			<div id="jqxMenu" >
				<ul>
					<li><img  src="../assets/images/icons/icol16/src/house.png" alt=""/><a href = "javascript:location.reload()">Home</a></li>
					<li><img  src="../assets/images/icons/icol16/src/zone_money.png" alt="" /><a href = "transactions.php"> Transactions</a></li>
					<li id = "newConsumer"><img  src="../assets/images/icons/icol16/src/group.png" alt=""/><a href = "javascript:void(0)">New Consumer</a></li>
					<li id = "logout"><img src = "../assets/images/icons/icol16/src/lock.png"> Logout</li>
				</ul>	
			</div>
			<div id = "mainSplitter">
				<div class="splitter-panel">
					<div id = "acct-list"></div>
				</div>
				<div class = "splitter-panel">
				</div>
			</div>
		</div>
	</body>
</html>