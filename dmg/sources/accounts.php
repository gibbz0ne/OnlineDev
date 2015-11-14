<?php
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();

	$branch = $_SESSION["branch"];
	$area = $_SESSION["area"];
	$customers = Array();
	
	$res = $db->query("SELECT *FROM tbl_temp_consumers a
						LEFT OUTER JOIN tbl_transactions b ON a.cid = b.cid WHERE a.AccountNumberT IS NOT NULL AND b.status = 3 AND b.action = 1");
	foreach($res as $row) {
		$customers[] = array(
			"acctNo" => $row["AccountNumberT"],
			// "acctAleco" => $row["AlecoAccountT"],
			"acctName" => $row["AccountNameT"],
			"address" => $row["AddressT"],
			"brgy" => $row["BarangayT"],
			"branch" => $row["BranchT"],
			"municipality" => $row["MunicipalityT"],
			"cType" => $row["CustomerTypeT"],
			"bapa" => ($row["bapaT"] == 0 ? "FALSE" : "TRUE")
			// "status" => $row["Status"],
			// "meterNo" => $row["MeterNumber"],
		  );
	}
	
	echo json_encode($customers);
?>