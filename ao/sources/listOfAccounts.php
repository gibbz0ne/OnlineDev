<?php
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();

	$customers = Array();
	$res = $db->query("select * from tbl_consumers a
				left outer join tbl_consumer_address b on a.cid = b.cid
				left outer join tbl_barangay c on b.brgyId = c.brgyId
				left outer join tbl_municipality d on c.munId = d.munId
				where a.forBilling = true");
				
	foreach($res as $row) {
		$customers[] = array(
			'acctNo' => $row['sysPro'],
			'acctAleco' => $row['alecoNo'],
			'acctName' => $row["fname"].($row["mname"] ? " ".$row["mname"]." " : " ").$row["lname"].($row["ename"] ? " ".$row["ename"]." " : " "),
			'address' => $row['address'],
			'brgy' => $row['brgyName'],
			'branch' => $row['branch'],
			'pending' => $row['hasPendingSO'],
			'cid' => $row['cid']
		  );
	}
  
	echo json_encode($customers);
?>