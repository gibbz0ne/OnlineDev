<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$query = $db->query("SELECT *FROM tbl_work_order JOIN tbl_temp_consumers USING(cid) ORDER BY wo DESC");
	
	if($query->rowCount() > 0){
		$ctr = 1;
		$list = array();
		foreach($query as $row){

			$name = $row["AccountNameT"];
			$address = $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row2["munDesc"];
			$wo = $row["wo"];
			$acctNo = $row["sysPro"];
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