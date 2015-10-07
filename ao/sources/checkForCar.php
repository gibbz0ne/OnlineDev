<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$checker = 0;
	
	if(isset($_POST["appId"])){
		$appId = $_POST["appId"];
		$cid = $_POST["cid"];
		
		$query = $db->query("SELECT *FROM tbl_applications WHERE appId = '$appId' AND cid = '$cid' AND appCAR IS NULL");
		
		if($query->rowCount() > 0)
			$checker = 1;

		$query = $db->query("SELECT *FROM tbl_transactions WHERE appId = '$appId' AND cid = '$cid' ORDER BY tid DESC LIMIT 1");
		
		$row = $query->fetch(PDO::FETCH_ASSOC);
		if($row["status"] == 2 && $row["action"] == 0 || $row["status"] == 3)
			$checker = 1;
		else
			$checker = 0;
		
		$query = $db->query("SELECT *FROM tbl_applications WHERE appId = '$appId' AND cid = '$cid' AND appCAR IS NOT NULL");
		
		if($query->rowCount() > 0)
			$checker = 2;
		
		echo $checker;
	}
?>