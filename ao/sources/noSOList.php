
<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$id = $_SESSION["userId"];
	$query = $db->query("SELECT * FROM tbl_consumers JOIN tbl_consumer_address USING (cid) JOIN tbl_applications USING (cid) JOIN tbl_barangay USING(brgyId) WHERE appSOnum is NULL ORDER BY appDate Desc");
	
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			foreach($db->query("SELECT * FROM tbl_transactions a
								LEFT OUTER JOIN tbl_status b ON a.status = b.statId
								WHERE a.appId = '".$row["appId"]."'
								AND a.status = 3
								AND a.action = 0
								AND a.processedBy = $id") as $row2){

				foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row3)

				$status = $row2["statName"];
				
				if($row2["action"] == 0){
					$action = "PENDING";
				} else if($row2["action"] == 1){
					$action = "APPROVED";
				} else if($row2["action"] == 2){
					$action = "CANCELLED";
				}
				
				if($row2["action"] == 1 && $row2["status"] == 1){
					$status = "INSPECTED";
				}
				
				$serviceArr = array();
				$query = $db->query("select a.serviceCode from tbl_service a left outer join tbl_app_service b on a.serviceId = b.serviceId where b.appId = '".$row["appId"]."'");
				foreach($query as $rowS){
					$serviceArr[] = $rowS["serviceCode"];
				}
				
				$type = "";
				$res = $db->query("select typeId from tbl_app_type where appId = '".$row["appId"]."'");
				$rowT = $res->fetchAll(PDO::FETCH_ASSOC);
				
				if(count($row) > 0) {
					$type = $rowT[0]["typeId"];
				}
				
				$list[] = array("consumerName" => str_replace("ñ", "Ñ", $row["fname"])." ".str_replace("ñ", "Ñ", $row["mname"])." ".str_replace("ñ", "Ñ", $row["lname"]),
								"address" => $row["address"]." ".$row["purok"]." ".str_replace("ñ", "Ñ", $row["brgyName"])." ".$row3["munDesc"],
								"status" => $status,
								"so" => $row["appSOnum"],
								"car" => $row["appCAR"],
								"remarks" => $row2["remarks"],
								"dateApp" => $row["appDate"],
								"dateProcessed" => $row2["dateProcessed"],
								"acctNo" => $row["sysPro"],
								"appId" => $row["appId"],
								"cid" => $row["cid"],
								"action" => $action,
								"service" => implode($serviceArr, ","),
								"trans" => $row2["tid"]
				);
			}
		}
		echo json_encode($list);
	}
?>