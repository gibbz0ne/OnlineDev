<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	// if(isset($_POST["item"])){
		$list = array();
		foreach($db->query("SELECT *FROM tbl_materials") as $row){
			$list[] = array("item_code" => $row["materialCode"],
						  "item_description" => $row["materialDesc"],
						  "entry_id" => $row["entry_id"]);
		}
		echo json_encode($list);
	// }
?>