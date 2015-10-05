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
								   LEFT OUTER JOIN tbl_status j ON j.statId = c.status
								   LEFT OUTER JOIN tbl_users k ON c.processedBy = k.id
								   WHERE b.appId = '$appId' AND a.flag = '0'
								   ORDER BY c.tid DESC LIMIT 1");
								   
			foreach($query2 as $row2){
				
				$status = $row2["statName"];
				
				$type = $row2["conCode"]." ".$row2["subDesc"];
				
				if($row2["mname"] != "")
					$row2["mname"] = $row2["mname"][0].".";
				
				array_push($list, array("status" => $status,
						"acctNo" => $row2["sysPro"],
						"consumerName" => $row2["fname"]." ".$row2["lname"]." ".$row2["mname"],
						"bName" => $row2["bname"],
						"address" => $row2["address"]." ".$row2["purok"]." ".$row2["brgyName"],
						"municipality" => $row2["munDesc"],
						"type" => $type,
						"so" => $row2["appSOnum"],
						"car" => $row2["appCAR"],
						"addedBy" => $row2["First_name"]." ".$row2["Last_name"],
						"dateAdded" => $row2["dateProcessed"]));
			}
		}
		echo json_encode($list);
	}
?>