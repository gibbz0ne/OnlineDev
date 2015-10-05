<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$list = array();
	$query = $db->query("SELECT *FROM tbl_applications");
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			$appId = $row["appId"];
			
			$query2 = $db->query("SELECT *FROM tbl_consumers a 
								   LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid
								   LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId
								   LEFT OUTER JOIN tbl_consumer_address d ON a.cid = d.cid
								   LEFT OUTER JOIN tbl_barangay e ON d.brgyId = e.brgyId
								   LEFT OUTER JOIN tbl_municipality f ON e.munId = f.munId
 								   LEFT OUTER JOIN tbl_consumer_connection g ON a.cid = g.cid
								   LEFT OUTER JOIN tbl_connection_type h ON g.conId = h.conId
								   LEFT OUTER JOIN tbl_connection_sub i ON g.subId = i.subId
								   WHERE b.appId = '$appId' AND c.status = '4' AND c.action = '1'
								   ORDER BY c.tid ASC LIMIT 1");
								   
			foreach($query2 as $row2){
				$status = "";
				switch($row2["status"]){
					case 1:
						$status = "INSPECTION";
						break;
					case 2:
						$status = "TSD";
						break;
					case 3:
						$status = "MMD";
						break;
					case 4:
						$status = "INSTALLATION";
				}
				
				$type = $row2["conCode"]." ".$row2["subDesc"];
				
				if($row2["mname"] != "")
					$row2["mname"] = $row2["mname"][0].".";

				if($row2["status"] == 4 and $row2["action"] == 1){
					$list[] = array(
						"status" => $status,
						"acctNo" => $row2["acctNo"],
						"consumerName" => $row2["fname"]." ".$row2["lname"]." ".$row2["mname"],
						"address" => $row2["address"]." ".$row2["purok"]." ".$row2["brgyName"],
						"municipality" => $row2["munDesc"],
						"area" => $row2["branch"],
						"type" => $type
					);
				}
			}
		}
		
		echo json_encode($list);
	}
?>