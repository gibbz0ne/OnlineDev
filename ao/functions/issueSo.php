<?php
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();
	
	$id = $_SESSION["userId"];

	// echo $_GET["trans"];

	// exit;
	
	if(isset($_GET["trans"])){
		$trans = $_GET["trans"];
	}
	else {
		$type = $_GET["type"];
		
		$res = $db->query("SELECT Entry_Number FROM consumers");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		$cid = $row[0]["Entry_number"];
	
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
		
		try{
			$db->beginTransaction();
			
			$applications = $db->prepare("INSERT INTO tbl_applications (appId, Entry_Number, appDate)
								 VALUES (?, ?, ?)");
			$applications->execute(array($appId, $cid, date("Y-m-d H:i:s")));
			
			$transactions = $db->prepare("INSERT INTO tbl_transactions(appId, Entry_Number, status, processedBy, dateProcessed)
										VALUES (?, ?, ?, ?, ?)");
			$transactions->execute(array($appId, $cid, 1, $id, date("Y-m-d H:i:s")));
			
			$res = $db->query("SELECT * FROM tbl_transactions WHERE appId = $appId and Entry_Number = $cid");
			$row = $res->fetchAll(PDO::FETCH_ASSOC);
			$trans = $row[0]["tid"];
			
			//insert app_type========================================
			$consumerAddress = $db->prepare("INSERT INTO tbl_app_type (appId, typeId) 
											 VALUES(?, ?)");
			$consumerAddress->execute(array($appId, $type));
			
			//=======================================================
			$res = $db->query("SELECT * FROM tbl_service WHERE typeId = $type");
			$rowService = $res->fetchAll(PDO::FETCH_ASSOC);
			
			if(count($rowService) == 1) {
				$insert = $db->prepare("INSERT INTO tbl_app_service VALUES(?, ?)");
				$insert->execute(array($appId, $rowService[0]["serviceId"]));
			}
			else if(count($rowService) > 1) {
				$res = $db->query("SELECT * FROM tbl_service WHERE typeId = $type");
				foreach($res as $rowService) {
					if($_GET["s-".$rowService["serviceId"]] == "true") {
						$insert = $db->prepare("INSERT INTO tbl_app_service VALUES(?, ?)");
						$insert->execute(array($appId, $rowService["serviceId"]));
					}
				}
			}
			
			foreach($res as $row) {
				$insert = $db->prepare("INSERT INTO tbl_so_fee VALUES(?, ?, ?)");
				$insert->execute(array($soID, $row["tfId"], $_GET["txtFee-".$row["tfId"]]));
			}
			$db->commit();
		} catch(PDOException $e){
			$db->rollBack();
			echo $e;
		}
	}
	
	try{
		$db->beginTransaction();
		$res = $db->query("SELECT * FROM tbl_transactions a 
							LEFT OUTER JOIN	tbl_applications b ON a.appId = b.appId 
							LEFT OUTER JOIN tbl_app_type c ON b.appId = c.appId 
							LEFT OUTER JOIN	tbl_type d ON c.typeId = d.typeId  
							LEFT OUTER JOIN consumers e ON a.Entry_Number = e.Entry_Number
							WHERE a.tid = $trans");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		$status = 3;
		if($row[0]["AccountNumber"])
			$status = 4;
			
		$type = $row[0]["typeId"];
		$app = $row[0]["appId"];
		$cid = $row[0]["Entry_Number"];
		$soPre = $row[0]["typeCode"];
		$soCtr = ($_GET["txtControl"] != "" ? $_GET["txtControl"] : 0);
		

		if($soCtr == 0) {
			$res = $db->query("SELECT sonum FROM tbl_so WHERE substr(sonum, 1, 1) = '$soPre'
								ORDER BY sonum DESC limit 1");
			
			if($rowSonum = $res->fetchAll(PDO::FETCH_ASSOC)) {
				$soCtr = (int)substr($rowSonum[0]["sonum"], 1);
			}

			$soCtr ++;
		}
		
		$sonumber = $soPre.str_pad($soCtr,5,"0",STR_PAD_LEFT);
		
		$insert = $db->prepare("INSERT INTO tbl_so (sonum, soRemarks, datePaid, cId, appId) VALUES(?, ?, ?, ?, ?)");
		$insert->execute(array($sonumber, ($_GET["taRemarks"] ? $_GET["taRemarks"] : null), $_GET["txtDatePaid"], $cid, $app));
		
		$update = $db->prepare("UPDATE tbl_applications SET appSOnum = ? WHERE Entry_Number = ? AND appId = ?");
		$update->execute(array($sonumber, $cid, $app));
		
		$res = $db->query("SELECT * FROM tbl_so WHERE sonum = '$sonumber'");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		$soID = $row[0]["soId"];
		
		$out = "";
		
		//=======================================================
		$res = $db->query("SELECT * FROM tbl_service WHERE typeId = $type");
		$rowService = $res->fetchAll(PDO::FETCH_ASSOC);
		
		// if(count($rowService) == 1) {
			// $res = $db->query("SELECT * FROM tbl_app_service ");
			// $insert = $db->prepare("INSERT INTO tbl_app_service VALUES(?, ?)");
			// $insert->execute(array($app, $rowService[0]["serviceId"]));
		// }
		// else 
		if(count($rowService) > 1) {
			$res = $db->query("SELECT * FROM tbl_service WHERE typeId = $type");
			foreach($res as $rowService) {
				if($_GET["s-".$rowService["serviceId"]] == "true") {
					$insert = $db->prepare("INSERT INTO tbl_app_service VALUES(?, ?)");
					$insert->execute(array($app, $rowService["serviceId"]));
				} else{
					$delete = $db->prepare("DELETE FROM tbl_app_service WHERE appId = ? AND serviceId = ?");
					$delete->execute(array($app, $rowService["serviceId"]));
				}
			}
		}
		
		foreach($res as $row) {
			$insert = $db->prepare("INSERT INTO tbl_so_fee VALUES(?, ?, ?)");
			$insert->execute(array($soID, $row["tfId"], $_GET["txtFee-".$row["tfId"]]));
		}
		
		//=======================================================
		$res = $db->query("SELECT * FROM tbl_type_fee WHERE typeId = $type");
		foreach($res as $row) {
			$insert = $db->prepare("INSERT INTO tbl_so_fee VALUES(?, ?, ?)");
			$insert->execute(array($soID, $row["tfId"], $_GET["txtFee-".$row["tfId"]]));
		}
		
		//=======================================================
		$res = $db->query("SELECT * FROM tbl_type_reason WHERE typeId = $type");
		foreach($res as $row) {
			if($_GET["r-".$row["trId"]] == "true") {
				$insert = $db->prepare("INSERT INTO tbl_so_reason VALUES(?, ?)");
				$insert->execute(array($soID, $row["trId"]));
			}
		}
		
		//=======================================================
		$res = $db->query("SELECT * FROM tbl_type_undertake WHERE typeId = $type");
		foreach($res as $row) {
			if($_GET["u-".$row["suId"]] == "true") {
				$insert = $db->prepare("INSERT INTO tbl_so_undertake VALUES(?, ?)");
				$insert->execute(array($soID, $row["suId"]));
			}
		}

		$processed = 0;
		$q = $db->query("SELECT *FROM tbl_transactions where tid = $trans");
		foreach ($q as $r) {
			$processed = $r["processedBy"];
		}

		$update = $db->prepare("UPDATE tbl_transactions SET action = ?, approvedBy = ?, dateApproved = ? WHERE tid = ?");
		$update->execute(array(1, $id, date("Y-m-d H:i:s"), $trans));

		$insert = $db->prepare("INSERT INTO tbl_transactions (appId, Entry_Number, status, processedBy, dateProcessed)
							VALUES(?, ?, ?, ?, ?)");
		$insert->execute(array($app, $cid, $status, $processed, date("Y-m-d H:i:s")));

		// $insert = $db->prepare("update tbl_consumers set hasPendingSO = true WHERE Entry_Number = $cid");
		// $insert->execute();
		$db->commit();
		echo "1";
	} catch(PDOException $e){
		$db->rollBack();
		echo $e;
	}
?>