<?php
	include "../connect.php";
	$date = date("Y-m-d");
	$id = $_SESSION["userId"];
	if(isset($_POST["appId"])){
		$appId = $_POST["appId"];
		$cid = $_POST["cid"];
		$so = $_POST["so"];
		
		$insert = $db->prepare("INSERT INTO transactions (AppId, Cid, Status, ProcessedBy, DateSent)
							  VALUES (?, ?, ?, ?, ?)");
		$insert->execute(array($appId, $cid, 3, $id, $date));
		
		echo 1;
	}
?>