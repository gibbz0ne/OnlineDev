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
								   LEFT OUTER JOIN tbl_status d ON d.statId = c.status
								   WHERE b.appId = '$appId' AND a.flag = '0'
								   ORDER BY c.tid DESC LIMIT 1");
								   
			foreach($query2 as $row2){
				$status = $row2["statName"];
				
				if($row2["status"] != 8){
					$list[] = array(
						"status" => $status,
						"acctNo" => $row2["AccountNumberT"],
						"consumerName" => $row2["AccountNameT"],
						"address" => $row2["AddressT"],
						"municipality" => $row2["MunicipalityT"],
						"area" => $row2["BranchT"],
						"type" => $row2["CustomerTypeT"]
					);
				}
			}
		}
		
		echo json_encode($list);
	}
?>