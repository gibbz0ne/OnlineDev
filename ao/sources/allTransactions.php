<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$date = date("Y-m-d");
	$id = $_SESSION["userId"];
	$query = $db->query("SELECT *FROM tbl_consumers JOIN tbl_consumer_address USING (cid) JOIN tbl_applications USING (cid) JOIN tbl_barangay USING(brgyId) ORDER BY appDate DESC");
	$list = Array();
	$i = "";
	if($query->rowCount() > 0){
		foreach($query as $row){
			foreach($db->query("SELECT *FROM tbl_transactions a
								LEFT OUTER JOIN tbl_status b ON a.status = b.statId
								WHERE a.appId = ".$row["appId"]."
								AND a.processedBy = $id
								ORDER BY a.tid DESC LIMIT 1") as $row2){

				foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row3)
					$status = $row2["statName"];
					
					if($row2["action"] == 0){
						$action = "PENDING";
					} else if($row2["action"] == 1){
						$action = "APPROVED";
					} else if($row2["action"] == 2){
						$action = "CANCELLED";
					}
					
					$serviceArr = array();
					$query = $db->query("select a.serviceCode from tbl_service a left outer join tbl_app_service b on a.serviceId = b.serviceId where b.appId = '".$row["appId"]."'");
					foreach($query as $rowS){
						$serviceArr[] = $rowS["serviceCode"];
					}
					
					$d = explode(" ", $row["appDate"]);
					$d1 = explode(" ", $row2["dateProcessed"]);
					// if($d[0] == $date || $d1[0] == $date){
						$list[] = array("consumerName" => $row["fname"]." ".($row["mname"] ? " ".$row["mname"]." " : " ")." ".$row["lname"].($row["ename"] ? " ".$row["ename"] : " "),
									"bName" => $row["bname"],
									"address" => $row["address"]." ".$row["purok"]." ".str_replace("ñ", "Ñ", $row["brgyName"])." ".str_replace("ñ", "Ñ", $row3["munDesc"]),
									"status" => $status,
									"so" => $row["appSOnum"],
									"car" => $row["appCAR"],
									"remarks" => $row2["remarks"],
									"dateApp" => $row["appDate"],
									"dateProcessed" => $row2["dateProcessed"],
									"acctNo" => $row["sysPro"],
									"appId" => $row["appId"],
									"action" => $action,
									"cid" => $row["cid"],
									"car" => $row["appCAR"],
									"service" => implode($serviceArr, ",")
						);
					// }
			}
		}
		echo json_encode($list);
	}
?>