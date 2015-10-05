<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	
	$query = $db->query("SELECT *FROM tbl_consumers a
						 LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid
						 LEFT OUTER JOIN tbl_consumer_address c ON a.cid = c.cid
						 LEFT OUTER JOIN tbl_barangay d ON c.brgyId = d.brgyId
						 LEFT OUTER JOIN tbl_municipality e ON d.munId = e.munId
						 LEFT OUTER JOIN tbl_consumer_connection f ON a.cid = f.cid
						 LEFT OUTER JOIN tbl_connection_type g ON f.conId = g.conId
						 LEFT OUTER JOIN tbl_connection_sub h ON f.subId = h.subId
						 WHERE b.appSOnum is NULL AND e.branch = '$branch' ORDER BY appDate DESC");
	
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			foreach($db->query("SELECT * FROM tbl_transactions a
								LEFT OUTER JOIN tbl_status b ON a.status = b.statId
								WHERE a.appId = '".$row["appId"]."'
								AND a.status = 1
								AND a.action = 0") as $row2){
				foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row3)
				
				$status = $row2["statName"];

				if($row2["action"] == 0){
					$action = "PENDING";
				} else if($row2["action"] == 1){
					$action = "APPROVED";
				}
				
				$serviceArr = array();
				$query = $db->query("select a.serviceCode from tbl_service a left outer join tbl_app_service b on a.serviceId = b.serviceId where b.appId = '".$row["appId"]."'");
				foreach($query as $rowS){
					$serviceArr[] = $rowS["serviceCode"];
				}
				
				$list[] = array("consumerName" => $row["fname"]." ".$row["mname"]." ".$row["lname"],
								"address" => $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row["munDesc"],
								"status" => $status,
								"so" => $row["appSOnum"],
								"remarks" => $row2["remarks"],
								"service" => implode($serviceArr, ","),
								"dateApp" => $row["appDate"],
								"dateProcessed" => $row2["dateProcessed"],
								"acctNo" => $row["sysPro"],
								"appId" => $row["appId"],
								"cid" => $row["cid"],
								"action" => $action,
								"tid" => $row2["tid"],
								"type" => $row["conCode"]." ".$row["subDesc"]
				);
			}
		}
		echo json_encode($list);
	}
?>