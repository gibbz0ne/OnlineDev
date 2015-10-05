<?php
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$municipality = array();
	$query = $db->query("SELECT * FROM tbl_municipality Order by munDesc");
	
	foreach($query as $row) {
		$municipality[] = array(
			"munId" => $row["munId"],
			"munDesc" => $row["munDesc"]
		);
	}
	
	echo json_encode($municipality);
?>