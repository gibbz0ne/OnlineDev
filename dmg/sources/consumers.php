<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$list = array();
	
	$query2 = $db->query("SELECT *FROM tbl_temp_consumers a 
							LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid 
							LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId 
							WHERE c.status = 3 AND c.action = 0 
							ORDER BY c.tid DESC");
						   
	foreach($query2 as $row){
		$status = $row["status"];
		
		$list[] = array(
			"status" => $status,
			"acctNo" => $row["AccountNumberT"],
			"consumerName" => $row["AccountNameT"],
			"mname" => $row["MiddleName"],
			"address" => $row["AddressT"],
			"municipality" => $row["MunicipalityT"],
			"area" => $row["BranchT"],
			"type" => $row["CustomerTypeT"],
			"cid" => $row["cid"],
			"appId" => $row["appId"],
			"tid" => $row["tid"]
		);
	}
		
	echo json_encode($list);
?>