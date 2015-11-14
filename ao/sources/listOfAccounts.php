<?php
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();

	$branch = $_SESSION["branch"];
	$area = $_SESSION["area"];
	$customers = Array();
	
	if($area == 1)
		$query = $db->query("SELECT *FROM tbl_municipality WHERE branch = '$branch'");
	else
		$query = $db->query("SELECT *FROM tbl_municipality WHERE branch = '$branch' AND area = '$area'");
	
	foreach($query as $r){
		$res = $db->query("SELECT *FROM consumers WHERE Branch = '$branch' AND Municipality = '".$r["munDesc"]."'");
		foreach($res as $row) {
			$customers[] = array(
				"cid" => $row["Entry_Number"],
				"acctNo" => $row["AccountNumber"],
				"acctAleco" => $row["AlecoAccount"],
				"acctName" => $row["AccountName"],
				"address" => $row["Address"],
				"brgy" => $row["Barangay"],
				"branch" => $row["Branch"],
				"municipality" => $row["Municipality"],
				"cType" => $row["CustomerType"],
				"bapa" => ($row["bapa"] == 0 ? "FALSE" : "TRUE"),
				"status" => $row["Status"],
				"meterNo" => $row["MeterNumber"],
			  );
		}
	}
	
	echo json_encode($customers);
?>