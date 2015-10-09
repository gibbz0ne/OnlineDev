<?php
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	$municipality = array();
	$branch = $_SESSION["branch"];
	$area = $_SESSION["area"];
	
	if($area != 1)
		$query = $db->query("SELECT * FROM tbl_municipality WHERE branch = '$branch' AND area = '$area'Order by munDesc");
	else
		$query = $db->query("SELECT * FROM tbl_municipality WHERE branch = '$branch' AND area IS NULL Order by munDesc");
	foreach($query as $row) {
		$municipality[] = array(
			"munId" => $row["munId"],
			"munDesc" => $row["munDesc"]
		);
	}
	
	echo json_encode($municipality);
?>