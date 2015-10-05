<?php
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	if(isset($_GET["id"])){
		$id = $_GET["id"];
		$list = Array();
		$query = $db->query("SELECT *FROM tbl_barangay WHERE munId = '$id'");
		
		foreach($query as $row){
			// echo $row["brgyName"];
			$list[] = array("bid" => $row["brgyId"],
							"brgyName" => $row["brgyName"]);
		}
		
		echo json_encode($list);
	}
?>