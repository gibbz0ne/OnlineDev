<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$date = date("Y-m-d");
	$id = $_SESSION["userId"];
	$query = $db->query("SELECT *FROM tbl_consumers JOIN tbl_consumer_address USING (cid) JOIN tbl_applications USING (cid) JOIN tbl_barangay USING(brgyId) JOIN tbl_app_service USING (appId) ORDER BY appDate DESC");
	//$query2 = $db->query("SELECT *FROM tbl_mr_wo WHERE appId = '".$row["appId"]."'");
	$list = Array();
	$i = "";
	if($query->rowCount() > 0){
		//if($query2->rowCount() == 0){
			foreach($query as $row){
				foreach($db->query("SELECT *FROM tbl_work_order WHERE appId = '".$row["appId"]."'") as $row4)
				//foreach($db->query("SELECT *FROM tbl_transactions WHERE appId = '".$row["appId"]."' AND status = '4' AND action = '0' ORDER BY tid Desc LIMIT 1") as $row2){//should get the last transaction
				foreach($db->query("SELECT * FROM tbl_transactions a
									LEFT OUTER JOIN tbl_status b ON a.status = b.statId
									WHERE a.appId = '".$row["appId"]."'
									AND a.status = 5
									AND a.action = 0") as $row2){

				foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row3)
					// echo $row3["munDesc"];
					$status = $row2["statName"];

					if($row2["status"] == 1 && $row2["action"] == 2){
						$status = "CANCELLED";
					}

					if($row["serviceId"] == 1){
						$service = "NC";
					}

					if($row2["action"] == 0){
						$action = "PENDING";
					} else if($row2["action"] == 1){
						$action = "APPROVED";
					} else if($row2["action"] == 2){
						$action = "CANCELLED";
					}
					if($row["mname"] != "")
						$row["mname"] = $row["mname"][0].".";

					$list[] = array("consumerName" => str_replace("ñ", "Ñ", $row["fname"])." ".str_replace("ñ", "Ñ", $row["mname"])." ".str_replace("ñ", "Ñ", $row["lname"]),
									"address" => $row["address"]." ".$row["purok"]." ".str_replace("ñ", "Ñ", $row["brgyName"])." ".str_replace("ñ", "Ñ", $row3["munDesc"]),
									"status" => $status,
									"so" => $row["appSOnum"],
									"remarks" => $row2["remarks"],
									"appType" => $service,
									"dateApp" => $row["appDate"],
									"cid" => $row["cid"],
									"acctNo" => $row["sysPro"],
									"appId" => $row["appId"],
									"action" => $action,
									"wo" => $row4["wo"],
									"tid" => $row2["tid"]
					);
				}
			}
	//	}
		echo json_encode($list);
	}
?>