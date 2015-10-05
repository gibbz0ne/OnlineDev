<?php
	// session_start();
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$query = $db->query("SELECT *FROM tbl_consumers JOIN tbl_consumer_address USING (cid) JOIN tbl_applications USING (cid) JOIN tbl_barangay USING(brgyId) ORDER BY appDate DESC");
	
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			foreach($db->query("SELECT *FROM tbl_transactions WHERE appId = '".$row["appId"]."' AND status = 1 AND action = 1") as $row2){
			foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row4)
				$status = $action = 0;
				if($row2["action"] == 0){
					$action = "PENDING";
				} else if($row2["action"] == 1){
					$action = "APPROVED";
				}
				
				if($row2["status"] == 1){
					$status = "FOR INSPECTION";
				}
				foreach($db->query("SELECT *FROM tbl_inspection WHERE appId = '".$row["appId"]."'") as $row3){
					$pType = "";
					if($row3["protection"] == 1){
						$pType = "FUSE";
					} 
					if($row3["protection"] == 2){
						$pType = "CIRCUIT BREAKER";
					}
					
					$list[] = array("consumerName" => $row["fname"]." ".$row["mname"]." ".$row["lname"],
									"address" => $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row4["munDesc"],
									"status" => $status,
									"acctNo" => $row["acctNo"],
									"appId" => $row["appId"],
									"cid" => $row["cid"],
									"action" => $action,
									"protection" => $pType,
									"rating" => $row3["pRating"],
									"type" => $row3["sType"],
									"wireSize" => $row3["wireSize"],
									"length" => $row3["length"],
									"se" => $row3["servicePole"],
									"remarks" => $row3["iRemarks"],
									"inspectedBy" => $row3["inspectedBy"]
							);
				}
			}
		}
		echo json_encode($list);
	}
?>