<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$list = array();
	$query = $db->query("SELECT *FROM tbl_entrance_type");
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			array_push($list, 
				array(
					"eid" => $row["eid"],
					"eDescription" =>$row["eDescription"]
				)
			);
		}
		echo json_encode($list);
	}
?>