<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	
	$list = array();
	$query = $db->query("SELECT *FROM tbl_consumers a
						 LEFT OUTER JOIN tbl_consumer_address b ON a.cid = b.cid
						 LEFT OUTER JOIN tbl_barangay c ON b.brgyId = c.brgyId
						 LEFT OUTER JOIN tbl_municipality d ON c.munId = d.munId
						 RIGHT OUTER JOIN tbl_inspection e ON a.cid = e.cid
						 RIGHT OUTER JOIN tbl_inspection_meter f ON e.inspectionId = f.inspectionId
						 RIGHT OUTER JOIN tbl_inspection_type g ON e.inspectionId = g.inspectionId
						 LEFT OUTER JOIN tbl_protection h ON g.protectionId = h.protectionId
						 LEFT OUTER JOIN tbl_substation i ON f.subId = i.subId
						 LEFT OUTER JOIN tbl_feeder j ON f.feedId = j.feedId
						 LEFT OUTER JOIN tbl_entrance_type k ON g.eid = k.eid
						 WHERE d.branch = '$branch'");
						 
	if($query->rowCount() > 0){
		foreach($query as $row){
			array_push($list, 
				array("acctNo" => $row["sysPro"],
					  "consumerName" => $row["lname"]." ".$row["fname"]." ".$row["mname"],
					  "address" => $row["purok"]." ".$row["address"]." ".$row["brgyName"]." ".$row["munDesc"],
					  "protection" => $row["protectionDesc"],
					  "rating" => $row["pRating"],
					  "type" => $row["eDescription"],
					  "eSize" => $row["eSize"],
					  "wireSize" => $row["wireSize"],
					  "length" => $row["length"],
					  "servicePole" => $row["servicePole"],
					  "remarks" => $row["iRemarks"],
					  "meterForm" => $row["meterForm"],
					  "meterClass" => $row["meterClass"],
					  "totalva" => $row["totalVa"],
					  "substation" => $row["subDescription"],
					  "feeder" => $row["feederName"],
					  "phase" => $row["phase"],
					  "inspectedBy" => $row["inspectedBy"],
				)
			);
		}
		echo json_encode($list);
	}
?>