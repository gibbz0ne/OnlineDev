<?php
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	
	$query = $db->query("SELECT *FROM tbl_protection");
	$list = array();
	if($query->rowCount() > 0){
		foreach($query as $row){
			$list[] = array("protectionId" => $row["protectionId"],
							"protectionDesc" => $row["protectionDesc"]);
		}
		
		echo json_encode($list);
	}
?>