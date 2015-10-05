<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$list = array();
	
	$query = $db->query("SELECT *FROM tbl_applications");
	
	foreach($query as $row){
		$query2 = $db->query("SELECT *FROM tbl_consumers a 
							   LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid
							   LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId
							   LEFT OUTER JOIN tbl_consumer_address d ON a.cid = d.cid
							   LEFT OUTER JOIN tbl_barangay e ON d.brgyId = e.brgyId
							   LEFT OUTER JOIN tbl_municipality f ON e.munId = f.munId
								LEFT OUTER JOIN tbl_consumer_connection g ON a.cid = g.cid
							   LEFT OUTER JOIN tbl_connection_type h ON g.conId = h.conId
							   LEFT OUTER JOIN tbl_connection_sub i ON g.subId = i.subId
							   WHERE a.sysPro IS NOT NULL AND b.appId = '".$row["appId"]."'
							   ORDER BY c.tid DESC LIMIT 1");
							   
		foreach($query2 as $row2){
			$status = $row2["status"];
			
			$type = $row2["conCode"]." ".$row2["subDesc"];
			
			if($row2["mname"] != "")
				$row2["mname"] = $row2["mname"][0].".";

			$list[] = array(
				"status" => $status,
				"acctNo" => $row2["sysPro"],
				"consumerName" => $row2["fname"]." ".$row2["lname"]." ".$row2["mname"],
				"address" => $row2["address"]." ".$row2["purok"]." ".$row2["brgyName"],
				"municipality" => $row2["munDesc"],
				"area" => $row2["branch"],
				"type" => $type,
				"car" => $row2["appCAR"],
				"cid" => $row2["cid"],
				"appId" => $row2["appId"],
				"tid" => $row2["tid"]
			);
		}
	}
		
	echo json_encode($list);
?>