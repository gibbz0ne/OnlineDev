<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$list = array();
	
	$query2 = $db->query("SELECT *FROM consumers a 
							LEFT OUTER JOIN tbl_applications b ON a.Entry_Number = b.Entry_Number 
							LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId 
							LEFT OUTER JOIN tbl_consumer_connection d ON a.Entry_Number = d.cid 
							LEFT OUTER JOIN tbl_connection_type e ON d.conId = e.conId 
							LEFT OUTER JOIN tbl_connection_sub f ON d.subId = f.subId 
							WHERE c.status = 2 AND c.action = 0 
							ORDER BY c.tid DESC");
						   
	foreach($query2 as $row){
		$status = $row["status"];
		
		$list[] = array(
			"status" => $status,
			"acctNo" => $row["AccountNumber"],
			"consumerName" => $row["AccountName"],
			"address" => $row["Address"],
			"municipality" => $row["Municipality"],
			"area" => $row["Branch"],
			"type" => $row["CustomerType"],
			"cid" => $row["Entry_Number"],
			"appId" => $row["appId"],
			"tid" => $row["tid"]
		);
	}
		
	echo json_encode($list);
?>