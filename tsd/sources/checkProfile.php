<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["cid"])){
		$cid = $_POST["cid"];
		$appId = $_POST["appId"];
		
		$query = $db->query("SELECT *FROM tbl_meter_profile WHERE appId = '$appId' AND cid = '$cid'");
							 
		if($query->rowCount() > 0)
			echo "1";
	}
?>