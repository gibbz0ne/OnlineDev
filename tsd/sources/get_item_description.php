<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["itemCode"])){
		$code = $_POST["itemCode"];
		
		$query = $db->query("SELECT *FROM tbl_materials WHERE entry_id = '$code'");
		
		if($query->rowCount() > 0){
			foreach($query as $row){
				echo json_encode(array("description" => $row["materialDesc"],
									   "materialCode" => $row["materialCode"],
									   "unit" => $row["unit"]));
			}
		}
		else
			echo json_encode(array("description" => "",
									"materialCode" => "",
									"unit" => ""));
	}
?>