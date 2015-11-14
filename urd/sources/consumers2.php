<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$list = array();
	$query = $db->query("SELECT *FROM tbl_applications");
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			$appId = $row["appId"];
			
			$query2 = $db->query("SELECT *FROM tbl_temp_consumers a 
								   LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid
								   LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId
								   WHERE b.appId = '$appId' AND c.status = '8' AND c.action = '0'
								   ORDER BY c.tid ASC LIMIT 1");
								   
			foreach($query2 as $row2){
				$appId = $row2["appId"];
				$query3 = $db->query("SELECT *FROM tbl_app_service a 
										LEFT OUTER JOIN tbl_service b ON a.serviceId = b.serviceId
										LEFT OUTER JOIN tbl_app_type c on a.appId = c.appId
										WHERE a.appId = '$appId'");
				$cType = "";					
				if($query3->rowCount() > 0){
					foreach($query3 as $row3){
						$cType .= $row3["serviceCode"]." ";
					}
				}
				if($row2["status"] == 8 and $row2["action"] == 0){
					$list[] = array(
						"status" => "",
						"acctNo" => $row2["AccountNumberT"],
						"consumerName" => $row2["AccountNameT"],
						"address" => $row2["AddressT"],
						"municipality" => $row2["MunicipalityT"],
						"area" => $row2["BranchT"],
						"type" => $row2["CustomerTypeT"],
						"appType" => $cType
					);
				}
			}
		}
		
		echo json_encode($list);
	}
?>