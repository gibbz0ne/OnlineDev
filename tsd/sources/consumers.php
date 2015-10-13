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
	
	$query = $db->query("SELECT *FROM consumers a 
						LEFT OUTER JOIN tbl_applications b ON a.Entry_Number = b.Entry_Number 
						LEFT OUTER JOIN tbl_app_service c ON b.appId = c.appId 
						LEFT OUTER JOIN tbl_transactions d ON b.appId = d.appId 
						LEFT OUTER JOIN tbl_status f ON d.status = f.statId 
						LEFT OUTER JOIN tbl_app_service g ON c.appId = g.appId 
						LEFT OUTER JOIN tbl_service h ON g.serviceId = h.serviceId 
						WHERE d.status = 4 AND d.action = 0");
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			array_push($list, array("consumerName" => str_replace("ñ", "Ñ", $row["AccountName"]),
								"address" => $row["Address"],
								// "status" => $status,
								"so" => $row["appSOnum"],
								"remarks" => $row["remarks"],
								"appType" => $row["serviceCode"],
								"dateApp" => $row["appDate"],
								"cid" => $row["Entry_Number"],
								"acctNo" => $row["AccountNumber"],
								"appId" => $row["appId"],
								"tid" => $row["tid"]));
		}
		echo json_encode($list);
	}
?>