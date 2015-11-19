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
								  LEFT OUTER JOIN tbl_consumer_relation b ON a.cid = b.cid
								  LEFT OUTER JOIN tbl_transactions c ON a.cid = c.cid 
								  LEFT OUTER JOIN tbl_inspection d ON c.appId = d.appId
								  LEFT OUTER JOIN tbl_inspection_meter e ON d.inspectionId = e.inspectionId
								  LEFT OUTER JOIN tbl_feeder f ON e.feedId = f.feedId
								  LEFT OUTER JOIN tbl_substation g ON f.subId = g.subId
								  WHERE c.dateProcessed LIKE '%".date("Y-m")."%' AND c.appId = '$appId'
								  ORDER BY c.tid DESC LIMIT 1");
								  
			if($query2->rowCount() > 0){
				foreach($query2 as $row2){
					array_push($list, array("date" => $row2["dateProcessed"],
									  "so" => $row["appSOnum"],
									  "cType" => $row2["CustomerTypeT"],
									  "remarks" => $row2["remarks"],
									  "acctNo" => $row2["AccountNumberT"],
									  "acctName" => $row2["AccountNameT"],
									  "mName" => $row2["MiddleName"],
									  "sName" => $row2["relationName"],
									  "cStatus" => "",
									  "address" => $row2["AddressT"],
									  "municipality" => $row2["MunicipalityT"],
									  "feeder" => $row2["feederName"],
									  "bookNo" => $row2["bookT"]));
				}
			}
		}
		echo json_encode($list);
	}
							 
?>