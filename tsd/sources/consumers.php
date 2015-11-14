<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$date = date("Y-m-d");
	$id = $_SESSION["userId"];
	$branch = $_SESSION["branch"];
	// $query = $db->query("SELECT *FROM tbl_consumers JOIN tbl_consumer_address USING (cid) JOIN tbl_applications USING (cid) JOIN tbl_barangay USING(brgyId) JOIN tbl_app_service USING (appId) ORDER BY appDate DESC");
	$list = Array();
	$i = "";
	
	$query = $db->query("SELECT *FROM tbl_temp_consumers a 
						LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid 
						LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId 
						LEFT OUTER JOIN tbl_status d ON c.status = d.statId 
						WHERE c.status = 4 AND c.action = 0 AND a.BranchT = '$branch'");
						
						// SELECT *FROM consumers a 
						// LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid 
						// LEFT OUTER JOIN tbl_app_service c ON b.appId = c.appId 
						// LEFT OUTER JOIN tbl_transactions d ON b.appId = d.appId 
						// LEFT OUTER JOIN tbl_status f ON d.status = f.statId 
						// LEFT OUTER JOIN tbl_app_service g ON c.appId = g.appId 
						// LEFT OUTER JOIN tbl_service h ON g.serviceId = h.serviceId 
						// WHERE d.status = 4 AND d.action = 0
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			$appId = $row["appId"];
			$query2 = $db->query("SELECT *FROM tbl_app_service a
								  LEFT OUTER JOIN tbl_service b ON a.serviceId = b.serviceId
								  WHERE a.appId = '$appId'");
			$appType = "";
			foreach($query2 as $row2){
				$appType .= $row2["serviceCode"]." ";
			}
			array_push($list, array("consumerName" => str_replace("ñ", "Ñ", $row["AccountNameT"]),
								"address" => $row["AddressT"],
								// "status" => $status,
								"so" => $row["appSOnum"],
								"remarks" => $row["remarks"],
								"appType" => $appType,
								"dateApp" => $row["appDate"],
								"cid" => $row["cid"],
								"acctNo" => $row["AccountNumberT"],
								"appId" => $row["appId"],
								"tid" => $row["tid"]));
		}
		echo json_encode($list);
	}
?>