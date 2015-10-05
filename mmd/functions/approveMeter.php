<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$userId = $_SESSION["userId"];
	
	if(isset($_POST["mr"])){
		$mr = $_POST["mr"];
		
		$mr_update = $db->prepare("UPDATE tbl_mr SET isApproved = ? WHERE mrNo = ?");
		$mr_update->execute(array(1, $mr));
		
		foreach($db->query("SELECT *FROM tbl_mr_wo WHERE mrNo = '$mr'") as $row){
			$appId = $row["appId"];
			
			foreach($db->query("SELECT *FROM tbl_transactions WHERE appId = '$appId' AND status = '7' AND action = '0'") as $row2){
				$transac_update = $db->prepare("UPDATE tbl_transactions SET action = ?, approvedBy = ?, dateApproved = ? WHERE appId = ? AND status = ?");
				$transac_update->execute(array(1, $userId, date("Y-m-d H:i:s"), $appId, 7));

				$insert = $db->prepare("INSERT INTO tbl_transactions (appId, cid, status, processedBy, dateprocessed) VALUES (?, ?, ?, ?, ?)");
				$insert->execute(array($appId, $row2["cid"], 8, $row2["processedBy"], date("Y-m-d H:i:s")));
			}	
		}
		// echo "1";
	}
	
?>