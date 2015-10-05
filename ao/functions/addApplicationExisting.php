<?php
	include "../../class/connect.class.php";
	
	$id = $_SESSION["userId"];
	$con = new getConnection();
	$db = $con->PDO();

	$id = $_SESSION["userId"];
	$type = $_POST["type"];
	$cid = $_POST["cid"];

	$query = $db->query("SELECT *FROM tbl_applications ORDER BY appId DESC LIMIT 1");

	if($query->rowCount() > 0){
		foreach($query as $row){
			$d = explode(" ", $row["appDate"]);
			if($d[0] == date("Y-m-d")){
				$checker = $row["appId"][8].$row["appId"][9].$row["appId"][10];
				$incr = intval($checker)+1;
			
				if($incr >= 100 || $incr >= 99){
					$appId = date("Ymd").$incr;
				} else if($incr >= 10){
					if( $incr == 9){
						$appId = date("Ymd")."00".$incr;
					} else{
						$appId = date("Ymd")."0".$incr;
					}
				} else{
					$appId = date("Ymd")."00".$incr;
				}
			} 
		}
	} 
	$app = $db->prepare("INSERT INTO tbl_applications (appId, cid, appDate)
						 VALUES (?, ?, ?)");
	$app->execute(array($appId, $cid, date("Y-m-d H:i:s")));

	//insert app_type
	$insert = $db->prepare("INSERT INTO tbl_app_type (appId, typeId) 
							VALUES(?, ?)");
	$insert->execute(array($appId, $type));
	
	//insert app_service
	// $insert = $db->prepare("INSERT INTO tbl_app_service (appId, serviceId) 
	// 						VALUES(?, ?)");
	// $insert->execute(array($appId, 1));

	$transactions = $db->prepare("INSERT INTO tbl_transactions(appId, cid, status, processedBy, dateProcessed)
								VALUES (?, ?, ?, ?, ?)");
	$transactions->execute(array($appId, $cid, 1, $id, date("Y-m-d")." ".date("H:i:s")));

	echo 1;
?>