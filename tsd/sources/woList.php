<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	
	$query = $db->query("SELECT *FROM tbl_temp_consumers a RIGHT OUTER JOIN tbl_work_order b ON a.cid = b.cid WHERE a.BranchT = '$branch'");
	$list = array();
	
	if($query->rowCount() > 0){
		$ctr = 1;
		foreach($query as $row){
			$name = $row["AccountNameT"];
			$address = $row["AddressT"];
			$wo = $row["wo"];
			$acctNo = $row["AccountNumberT"];
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