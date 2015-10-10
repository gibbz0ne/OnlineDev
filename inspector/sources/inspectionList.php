<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	
	$query = $db->query("SELECT *FROM consumers a 
							LEFT OUTER JOIN tbl_applications b ON a.Entry_Number = b.Entry_Number 
							LEFT OUTER JOIN tbl_consumer_connection c ON a.Entry_Number = c.cid 
							LEFT OUTER JOIN tbl_connection_type d ON c.conId = d.conId 
							LEFT OUTER JOIN tbl_connection_sub e ON c.subId = e.subId 
							LEFT OUTER JOIN tbl_transaCtions f ON b.appId = f.appId 
							LEFT OUTER JOIN tbl_status g ON f.status = g.statId 
							WHERE b.appSOnum is NULL AND f.status = 1 AND f.action = 0 AND a.branch = '$branch' ORDER BY appDate DESC");
	
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			$status = $row["statName"];
				
			$serviceArr = array();
			$query = $db->query("select a.serviceCode from tbl_service a left outer join tbl_app_service b on a.serviceId = b.serviceId where b.appId = '".$row["appId"]."'");
			foreach($query as $rowS){
				$serviceArr[] = $rowS["serviceCode"];
			}
				
			$list[] = array("consumerName" => $row["AccountName"],
							"address" => $row["Address"],
							"status" => $status,
							"so" => $row["appSOnum"],
							"remarks" => $row["remarks"],
							"service" => implode($serviceArr, ","),
							"dateApp" => $row["appDate"],
							"dateProcessed" => $row["dateProcessed"],
							"acctNo" => $row["AccountNumber"],
							"appId" => $row["appId"],
							"cid" => $row["Entry_Number"],
							"tid" => $row["tid"],
							"type" => $row["conCode"]." ".$row["subDesc"]
			);
		}
		echo json_encode($list);
	}
?>