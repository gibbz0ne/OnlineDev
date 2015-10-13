<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$date = date("Y-m-d");
	$id = $_SESSION["userId"];
	$query = $db->query("SELECT *FROM consumers a
						 RIGHT OUTER JOIN tbl_applications b ON a.Entry_Number = b.Entry_Number
						 RIGHT OUTER JOIN tbl_app_service c ON c.appId = b.appId
                         RIGHT OUTER JOIN tbl_work_order d ON b.appId = d.appId
                         RIGHT OUTER JOIN tbl_transactions e ON b.appId = e.appId
                         RIGHT OUTER JOIN tbl_status f ON e.status = f.statId
                         RIGHT OUTER JOIN tbl_service g ON c.serviceId = g.serviceId
                         WHERE e.status = 5 and e.action = 0
						 ORDER BY b.appDate DESC");
	$list = Array();
	$i = "";
	if($query->rowCount() > 0){
		foreach($query as $row){
				$status = $row["statName"];

				$list[] = array("consumerName" => str_replace("ñ", "Ñ", $row["AccountName"]),
								"address" => $row["Address"],
								"status" => $status,
								"so" => $row["appSOnum"],
								"remarks" => $row["remarks"],
								"appType" => $row["serviceCode"],
								"dateApp" => $row["appDate"],
								"cid" => $row["Entry_Number"],
								"acctNo" => $row["AccountNumber"],
								"appId" => $row["appId"],
								"wo" => $row["wo"],
								"tid" => $row["tid"]
				);
		}
		echo json_encode($list);
	}
?>