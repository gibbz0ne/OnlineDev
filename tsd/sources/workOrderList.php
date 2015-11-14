<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$date = date("Y-m-d");
	$id = $_SESSION["userId"];
	$query = $db->query("SELECT *FROM tbl_temp_consumers a 
						 RIGHT OUTER JOIN tbl_applications b ON a.cid = b.cid
                         RIGHT OUTER JOIN tbl_work_order c ON b.appId = c.appId
                         RIGHT OUTER JOIN tbl_transactions d ON b.appId = d.appId
                         RIGHT OUTER JOIN tbl_status e ON d.status = e.statId
                         WHERE d.status = 5 and d.action = 0
						 ORDER BY b.appDate DESC");
	$list = Array();
	$i = "";
	if($query->rowCount() > 0){
		foreach($query as $row){
			$status = $row["statName"];
			$appId = $row["appId"];
			$query2 = $db->query("SELECT *FROM tbl_app_service a
								  LEFT OUTER JOIN tbl_service b ON a.serviceId = b.serviceId
								  WHERE a.appId = '$appId'");
			$appType = "";
			foreach($query2 as $row2){
				$appType .= $row2["serviceCode"]." ";
			}
			$list[] = array("consumerName" => str_replace("ñ", "Ñ", $row["AccountNameT"]),
							"address" => $row["AddressT"],
							"status" => $status,
							"so" => $row["appSOnum"],
							"remarks" => $row["remarks"],
							"appType" => $appType,
							"dateApp" => $row["appDate"],
							"cid" => $row["cid"],
							"acctNo" => $row["AccountNumberT"],
							"appId" => $row["appId"],
							"wo" => $row["wo"],
							"tid" => $row["tid"]
			);
		}
		echo json_encode($list);
	}
?>