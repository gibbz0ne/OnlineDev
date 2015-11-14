<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["cid"])){
		$cid = $_POST["cid"];
		$appId = $_POST["appId"];
		$checker = "";
		$query = $db->query("SELECT *FROM tbl_meter_profile WHERE appId = '$appId' AND cid = '$cid'");
		$query2 = $db->query("SELECT *FROM tbl_transactions WHERE appId = '$appId' AND cid = '$cid' ORDER BY tid DESC LIMIT 1");
		if($query->rowCount() > 0)
			$checker = 1;
		
		if($query2->rowCount() > 0){
			foreach($query2 as $row2){
				if($row2["status"] == 7 && $row2["action"] == 0)
					$checker = 1;
				else
					$checker = 0;
			}
		}
		
		echo $checker;
	}
?>