
<?php
	// session_start();
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$id = $_SESSION["userId"];
	$query = $db->query("SELECT * FROM consumers a 
							LEFT OUTER JOIN tbl_applications b ON a.Entry_Number = b.Entry_Number 
							LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId 
							LEFT OUTER JOIN tbl_status d ON c.status = d.statId 
							WHERE appSOnum is NULL AND c.status = 2 AND c.action = 0 AND c.processedBy = $id
							ORDER BY appDate Desc");
	
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
				$status = $row["statName"];
				
				if($row["action"] == 1 && $row["status"] == 1){
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
				
				$list[] = array("consumerName" => str_replace("ñ", "Ñ", $row["AccountName"]),
								"mname" => $row["MiddleName"],
								"address" => $row["Address"],
								"status" => $status,
								"so" => $row["appSOnum"],
								"car" => $row["appCAR"],
								"remarks" => $row["remarks"],
								"dateApp" => $row["appDate"],
								"dateProcessed" => $row["dateProcessed"],
								"acctNo" => $row["AccountNumber"],
								"appId" => $row["appId"],
								"cid" => $row["Entry_Number"],
								"service" => implode($serviceArr, ","),
								"trans" => $row["tid"]
				);
			}
		echo json_encode($list);
	}
?>