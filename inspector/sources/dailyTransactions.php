<?php
	// session_start();
	include "../connect.php";
	$date = date("Y-m-d");
	// echo $date;
	$query = $db->query("SELECT *FROM consumers_cn JOIN consumer_address USING (Cid) JOIN applications USING (Cid) WHERE DateApplied = '$date'");
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			foreach($db->query("SELECT *FROM transactions WHERE AppId = '".$row["AppId"]."' ORDER BY tid Desc LIMIT 1") as $row2){//should get the last transaction
				$status = $action = 0;
				if($row2["Status"] == 1){
					$status = "FOR INSPECTION";
				} else if($row2["Status"] == 2){
					$status = "IT";
				}
				
				if($row2["Action"] == 0){
					$action = "PENDING";
				} else if($row2["Action"] == 1){
					$action = "APPROVED";
				}
				
				$list[] = array("consumerName" => $row["FirstName"]." ".$row["MiddleName"]." ".$row["LastName"],
								"address" => $row["HouseNo"]." ".$row["Purok"]." ".$row["Barangay"]." ".$row["Municipality"],
								"status" => $status,
								"so" => $row["SO"],
								"remarks" => $row2["Remarks"],
								"appType" => $row["AppService"],
								"dateApp" => $row["DateApplied"],
								"acctNo" => $row["AccountNumber"],
								"appId" => $row["AppId"],
								"action" => $action,
								"cid" => $row["Cid"]
				);
			}
		}
		echo json_encode($list);
	}
?>