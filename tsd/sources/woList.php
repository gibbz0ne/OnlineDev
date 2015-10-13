<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$query = $db->query("SELECT *FROM consumers a RIGHT OUTER JOIN tbl_work_order b ON a.Entry_Number = b.cid");
	$list = array();
	
	if($query->rowCount() > 0){
		$ctr = 1;
		foreach($query as $row){
			$name = $row["AccountName"];
			$address = $row["Address"];
			$wo = $row["wo"];
			$acctNo = $row["AccountNumber"];
			$woDate = $row["woDate"];
			
			$list[] = array("ctr" => $ctr,
							"wo" => $wo,
							"consumer" => $name,
							"address" => $address,
							"acctNo" => $acctNo,
							"date" => $woDate);
			
			$ctr++;
		}
		
		echo json_encode($list);
	}
?>