<?php
	// session_start();
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$query = $db->query("SELECT * FROM 
							tbl_consumers a left outer JOIN 
							tbl_consumer_address b on a.cid = b.cid left outer JOIN
							tbl_applications c on a.cid = c.cid left outer JOIN
							tbl_barangay d on b.brgyId = d.brgyId left outer JOIN
							tbl_municipality e on b.munId = e.munId left outer JOIN
							tbl_transactions f on c.appId = f.appId left outer JOIN
							tbl_inspection g on c.appId = g.appId
						where f.status = 1 and f.action = 2 
						order by appDate desc");
	
	$list = Array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			
			$list[] = array("acctNo" => $row["acctNo"],
							"consumerName" => $row["fname"]." ".$row["mname"]." ".$row["lname"],
							"address" => $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row["munDesc"],
							"remarks" => $row["iRemarks"],
							"inspectedBy" => $row["inspectedBy"]
					);
		}
	}
	
	echo json_encode($list);
?>