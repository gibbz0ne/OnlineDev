<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["appId"])){
		$appId = $_POST["appId"];
		$cid = $_POST["cid"];
		
		$query = $db->query("SELECT *FROM tbl_consumers JOIN tbl_address USING (cid) JOIN tbl_barangay USING (brgyId) WHERE appId = '$appId' AND cid = '$cid'");
		
		if($query->rowCount() > 0){
			foreach($query as $row)
			foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row2)
			
			echo $row["lname"].", ".$row["fname"]." ".$row["mname"][0].".";
		}
	}
?>