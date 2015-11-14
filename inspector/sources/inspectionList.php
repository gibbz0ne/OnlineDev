<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	
	$query = $db->query("SELECT *FROM tbl_temp_consumers a 
							LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid 
							LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId 
							LEFT OUTER JOIN tbl_status d ON c.status = d.statId 
							WHERE b.appSOnum is NULL AND c.status = 1 AND c.action = 0 AND a.branchT = '$branch' ORDER BY appDate DESC");
	
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			$status = $row["statName"];
				
			$serviceArr = array();
			$query = $db->query("select a.serviceCode from tbl_service a left outer join tbl_app_service b on a.serviceId = b.serviceId where b.appId = '".$row["appId"]."'");
			foreach($query as $rowS){
				$serviceArr[] = $rowS["serviceCode"];
			}
				
			$list[] = array("consumerName" => $row["AccountNameT"],
							"address" => $row["AddressT"],
							"status" => $status,
							"so" => $row["appSOnum"],
							"remarks" => $row["remarks"],
							"service" => implode($serviceArr, ","),
							"dateApp" => $row["appDate"],
							"dateProcessed" => $row["dateProcessed"],
							"acctNo" => $row["AccountNumberT"],
							"appId" => $row["appId"],
							"cid" => $row["cid"],
							"tid" => $row["tid"],
							"type" => $row["CustomerTypeT"]
			);
		}
		echo json_encode($list);
	}
?>