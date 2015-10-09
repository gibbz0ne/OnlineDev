<?php
	// session_start();
	include "../../class/connect.class.php";
	$date = date("Y-m-d");
	$conn = new getConnection();
	$db = $conn->PDO();
	// echo $date;
	$id = $_SESSION["userId"];
	$query = $db->query("SELECT * FROM consumers JOIN tbl_applications USING (Entry_Number) ORDER BY appDate DESC");
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			foreach($db->query("SELECT *FROM tbl_transactions a
								LEFT OUTER JOIN tbl_status b ON a.status = b.statId
								WHERE a.appId = ".$row["appId"]."
								AND a.dateProcessed like('".date("Y-m-d")."%')
								AND a.processedBy = $id
								ORDER BY a.tid DESC LIMIT 1") as $row2){
			
					$status = $row2["statName"];
					
					$serviceArr = array();
					$query = $db->query("select a.serviceCode from tbl_service a left outer join tbl_app_service b on a.serviceId = b.serviceId where b.appId = '".$row["appId"]."'");
					foreach($query as $rowS){
						$serviceArr[] = $rowS["serviceCode"];
					}
					
					$d = explode(" ", $row["appDate"]);
					$d1 = explode(" ", $row2["dateProcessed"]);
					if($d[0] == $date){
						$list[] = array("consumerName" => $row["AccountName"],
									"address" => $row["Address"],
									"status" => $status,
									"so" => $row["appSOnum"],
									"car" => $row["appCAR"],
									"remarks" => $row2["remarks"],
									"dateApp" => $row["appDate"],
									"dateProcessed" => $row2["dateProcessed"],
									"acctNo" => $row["AccountNumber"],
									"appId" => $row["appId"],
									"cid" => $row["Entry_Number"],
									"car" => $row["appCAR"],
									"service" => implode($serviceArr, ",")
						);
					}
			}
		}
		echo json_encode($list);
	}
?>