<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	$list = array();
	
	$query = $db->query("SELECT *FROM tbl_substation JOIN tbl_feeder USING(subId) WHERE branch = '$branch'");
	
	if($query->rowCount() > 0){
		$checker = "";
		foreach($query as $row){
			if($row["subId"] != $checker){
				array_push($list, array("subId" => $row["subId"],
						"subDescription" => $row["subDescription"]));
				$checker = $row["subId"];
			}
		}
		
		echo json_encode($list);
	}
?>