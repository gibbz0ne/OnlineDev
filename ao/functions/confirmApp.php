<?php
	include "../../class/connect.class.php";
	
	$userId = $_SESSION["userId"];
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["appId"])){
		$appId = $_POST["appId"];
		
		try{
			$db->beginTransaction();
			
			$update = $db->prepare("UPDATE tbl_transactions SET action = ?, approvedBy = ?, dateApproved = ? WHERE appId = ? AND status = ?");
			$update->execute(array(1, $userId, date("Y-m-d H:i:s"), $appId, 8));
			
			$db->commit();
		} catch(PDOException $e){
			$db->rollBack();
			echo $e;
		}
	}
?>