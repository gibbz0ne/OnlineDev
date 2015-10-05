<?php
	include "../connect.php";
	
	if(isset($_POST["cid"])){
		$cid = $_POST["cid"];
		$so = $_POST["so"];
		$appId = $_POST["appId"];
		
		$query = $db->query("SELECT *FROM applications WHERE Cid = '$cid' AND AppId = '$appId' AND SO = '$so'");
		
		if($query->rowCount() > 0){
			echo "1";
		} else{
			echo "2";
		}
	}
?>