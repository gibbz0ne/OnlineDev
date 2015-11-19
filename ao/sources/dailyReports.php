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
								  LEFT OUTER JOIN tbl_inspection c ON b.cid = c.cid
								  LEFT OUTER JOIN tbl_inspection_meter d ON c.inspectionId = d.inspectionId
								  LEFT OUTER JOIN tbl_feeder e ON d.feedId = e.feedId
								  LEFT OUTER JOIN tbl_substation f ON d.subId = f.subId
								  LEFT OUTER JOIN tbl_transactions g ON a.cid = g.cid 
								  WHERE g.dateProcessed LIKE '%".date("Y-m-d")."%' AND g.appId = '$appId'
								  ORDER BY g.tid DESC LIMIT 1");
								  
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