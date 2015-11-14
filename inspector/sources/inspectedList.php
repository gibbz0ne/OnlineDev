<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	
	$list = array();
	$query = $db->query("SELECT *FROM tbl_temp_consumers a 
							RIGHT OUTER JOIN tbl_inspection b ON a.cid = b.cid 
							RIGHT OUTER JOIN tbl_inspection_meter c ON b.inspectionId = c.inspectionId 
							RIGHT OUTER JOIN tbl_inspection_type d ON b.inspectionId = d.inspectionId 
							LEFT OUTER JOIN tbl_protection e ON d.protectionId = e.protectionId 
							LEFT OUTER JOIN tbl_substation f ON c.subId = f.subId 
							LEFT OUTER JOIN tbl_feeder g ON c.feedId = g.feedId 
							LEFT OUTER JOIN tbl_entrance_type h ON d.eid = h.eid 
							WHERE a.branchT = '$branch'");
						 
	if($query->rowCount() > 0){
		foreach($query as $row){
			array_push($list, 
				array("acctNo" => $row["AccountNumberT"],
					  "consumerName" => $row["AccountNameT"],
					  "address" => $row["AddressT"],
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