<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$branch = $_SESSION["branch"];
	$list = array();
	$query = $db->query("SELECT *FROM tbl_accounts WHERE aBranch = '$branch'");
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			$mname = ($row["aMname"] == "" ? "" : $row["aMname"][0].".");
			array_push($list, array("aName" => $row["aFname"]." ".$mname." ".$row["aLname"],
									"aPosition" => $row["aPosition"],
									"accountId" => $row["accountId"]));
		}
		
		echo json_encode($list);
	}
?>