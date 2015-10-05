<?php
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$conn = array();
	$query = $db->query("SELECT * FROM tbl_type Order by typeId");
	
	$conn[] = array(
		"typeId" => 0,
		"typeDesc" => "Please choose action:"
	);
	
	foreach($query as $row) {
		if($row["typeIcon"] != null) {
			$conn[] = array(
				"typeId" => $row["typeId"],
				"typeDesc" => $row["typeDesc"]
			);
		}
	}
	
	echo json_encode($conn);
?>