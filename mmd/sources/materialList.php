<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$query = $db->query("SELECT *FROM tbl_materials");
	$list = array();
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			$list[] = array("materialCode" => $row["materialCode"],
							"materialDesc" => $row["materialDesc"],
							"currentQty" => "",
							"totalIn" => "",
							"totalOut" => "");
		}
		
		echo json_encode($list);
	}
?>