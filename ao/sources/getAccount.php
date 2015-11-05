<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$accountId = $_POST["accountId"];
	$list = array();
	
	$query = $db->query("SELECT *FROM tbl_accounts WHERE accountId = $accountId");
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			$list = array("fname" => $row["aFname"],
									"mname" => $row["aMname"],
									"lname" => $row["aLname"],
									"position" => $row["aPosition"]);
		}
		
		echo json_encode($list);
	}
?>