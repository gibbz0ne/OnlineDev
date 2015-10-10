<?php
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();

	$branch = $_SESSION["branch"];
	$area = $_SESSION["area"];
	$customers = Array();
	
	$res = $db->query("SELECT *FROM consumers WHERE AccountNumber IS NOT NULL");
	foreach($res as $row) {
		$customers[] = array(
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
	
	echo json_encode($customers);
?>