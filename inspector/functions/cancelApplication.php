<?php
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$id = $_SESSION["userId"];
	$date = date("Y-m-d");
	$time = date("H:i:s");
	if($_POST["inspectedBy"]){
		$inspector = strtoupper($_POST["inspectedBy"]);
		$remarks = strtoupper($_POST["remarks"]);
		$appId = $_POST["appId"];
		$cid = $_POST["cid"];
		
		$update = $db->prepare("UPDATE tbl_transactions SET action = ?, approvedBy = ?, dateApproved = ?, remarks = ? WHERE appId = ? AND cid = ?");
		$update->execute(array(2, $id, date("Y-m-d")." ".date("H:i:s"), $remarks, $appId, $cid));
		
		$i = 1;
		
		$query = $db->query("SELECT *FROM tbl_inspection ORDER BY inspectionId DESC limit 1");
		
		if($query->rowCount() > 0){
			foreach($query as $row){
				$i = $row["inspectionId"]+1;
			}
		}
		
		$insert = $db->prepare("INSERT INTO tbl_inspection (inspectionId, appId, cid, inspectedBy, iRemarks)
								VALUES (?, ?, ?, ?, ?)");
		$insert->execute(array($i, $appId, $cid, $inspector, $remarks));
		
		echo "1";
	}
?>