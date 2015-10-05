<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$query = $db->query("SELECT *FROM tbl_users order by id ASC");
	$list = array();
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			$list[] = array("id" => $row["id"],
							"user" => $row["Username"],
							"password" => $row["Password"],
							"name" => $row["Last_name"].", ".$row["First_name"]." ".$row["Mid_name"],
							"contact" => $row["Contact_No"],
							"position" => $row["Position"],
							"type" => $row["Type"]);
		}
		
		echo json_encode($list);
	}
?>