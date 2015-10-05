<?php
	include "../../class/connect.class.php";
	
	$id = $_SESSION["userId"];
	$con = new getConnection();
	$db = $con->PDO();
	
	include "../../class/accountNum.class.php";
	$processNext = new getNextNum();

	$imgData = addslashes(file_get_contents($_FILES['uploader']['tmp_name']));
	$imageProperties = getimageSize($_FILES['uploader']['tmp_name']);

	if(isset($_POST["fname"])){
		$acctNum = null;
		$primary = (isset($_POST["primary"]) ? $_POST["primary"] : "");
		$email = $_POST["email"];
		$phone = $_POST["phone"];
		$count = ($_POST["count"] != "" ? $_POST["count"] : 1);
		$bname = (isset($_POST["bname"]) ? str_replace("ñ", "Ñ", strtoupper($_POST["bname"])) : NULL);
		$fname = str_replace("ñ", "Ñ", strtoupper($_POST["fname"]));
		$mname = str_replace("ñ", "Ñ", strtoupper($_POST["mname"]));
		$lname = str_replace("ñ", "Ñ", strtoupper($_POST["lname"]));
		$ename = strtoupper($_POST["ename"]);
		$civilStatus =strtoupper( $_POST["civilStatus"]);
		$spouseName = str_replace("ñ", "Ñ", strtoupper($_POST["spouseName"]));
		$hno = strtoupper($_POST["hno"]);
		$purok = strtoupper($_POST["purok"]);
		$brgy = strtoupper($_POST["brgy"]);
		$municipality = $_POST["municipality"];
		$appId = date("Ymd")."001";
		$cid = 1;
		$brgy = $_POST["brgy"];

		$consumer = $db->query("SELECT * FROM tbl_consumers ORDER BY cid DESC LIMIT 1");
		foreach($consumer as $row){
			$cid = $row["cid"]+1;
		}
		
		$consumer = $db->query("SELECT * FROM tbl_consumers ORDER BY cid DESC LIMIT 1");
		
		if($consumer->rowCount() > 0){
			foreach($consumer as $row){
				$cid = $row["cid"]+1;
				$acctNum = ($row["acctNo"] ? $processNext->processCurrNum($row["acctNo"]) : $processNext->processCurrNum("500000000000001"));
			}
		} else{
			$acctNum = $processNext->processCurrNum("500000000000001");//as
		}
		try{
			$db->beginTransaction();
			$insertC = $db->prepare("INSERT INTO tbl_consumers (cid, acctNo, fname, mname, lname, bname, ename, civilStatus, acctCount) 
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$insertC->execute(array($cid, $acctNum, $fname, $mname, $lname, $bname, $ename, $civilStatus, $count));
			
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
				
			$consumerAddress = $db->prepare("INSERT INTO tbl_consumer_address (cid, address, purok, munId, brgyId) 
											 VALUES(?, ?, ?, ?, ?)");
			$consumerAddress->execute(array($cid, $hno, $purok, $municipality, $brgy));

			$consumerContact = $db->prepare("INSERT INTO tbl_consumer_contact (cid, contactType, contactValue) 
											 VALUES(?, ?, ?), (?, ?, ?)");
			$consumerContact->execute(array($cid, 1, ($phone != "" ? $phone : null), $cid, 2, ($email != "" ? $email : null)));

			$consumerPhoto = $db->prepare("INSERT INTO tbl_consumer_photos (cid, imageType, imageData) 
											 VALUES($cid, '{$imageProperties['mime']}', '{$imgData}')");
			$consumerPhoto->execute();

			if($spouseName != ""){
				$spouse = $db->prepare("INSERT INTO tbl_consumer_relation (cid, relationName, relationStatus, relationType)
										VALUES (?, ?, ?, ?)");
				$spouse->execute(array($cid, $spouseName, "MARRIED", "SPOUSE"));
			}
			
			$transactions = $db->prepare("INSERT INTO tbl_transactions(appId, cid, status, processedBy, dateProcessed)
										VALUES (?, ?, ?, ?, ?)");
			$transactions->execute(array($appId, $cid, 1, $id, date("Y-m-d")." ".date("H:i:s")));
			
			//insert app_type
			$insert = $db->prepare("INSERT INTO tbl_app_type (appId, typeId) 
											 VALUES(?, ?)");
			$insert->execute(array($appId, 1));
			
			//insert app_service
			$insert = $db->prepare("INSERT INTO tbl_app_service (appId, serviceId) 
											 VALUES(?, ?)");
			$insert->execute(array($appId, 1));
			
			$resC = $db->query("SELECT * FROM tbl_connection_type");
			foreach($resC as $rowC) {
				if($_POST["c-".$rowC["conId"]] == "true") {
					$subId = "";
					
					$resS = $db->query("SELECT * FROM tbl_connection_sub where conId = ".$rowC["conId"]." ");
					foreach($resS as $rowS) {
						if($_POST["s-".$rowS["subId"]] == "true") {
							$subId = $rowS["subId"];
						}
					}
					
					$insert = $db->prepare("INSERT INTO tbl_consumer_connection VALUES(?, ?, ?)");
					$insert->execute(array($cid, $rowC["conId"], ($subId ? $subId : null)));
				}
			}
			$db->commit();
		}catch(PDOException $e){
			$db->rollBack();
			echo $e;
		}
		
		echo true;
	}
?>