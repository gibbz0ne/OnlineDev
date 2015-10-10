<?php
	include "../../class/connect.class.php";
	
	$id = $_SESSION["userId"];
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	include "../../class/accountNum.class.php";
	$processNext = new getNextNum();

	// $imgData = addslashes(file_get_contents($_FILES['uploader']['tmp_name']));
	// $imageProperties = getimageSize($_FILES['uploader']['tmp_name']);

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
		$customerType = $_POST["customerType"];
		$isBapa = ($_POST["isBapa"] == "BAPA" ? 1 : 0);
		$appId = date("Ymd")."001";
		$cid = 1;
		$brgy = $_POST["brgy"];
		$mname = ($mname == "" ? "" : " ".$mname[0].".");
		$hno = ($hno == "" ? "" : $hno);
		$purok = ($purok == "" ? "" : $purok);
		
		$query = $db->query("SELECT *FROM tbl_municipality WHERE munId = '$municipality'");
		foreach($query as $row)
			$municipality = $row["munDesc"];
			
		$query = $db->query("SELECT *FROM tbl_barangay WHERE brgyId = '$brgy'");
		foreach($query as $row)
			$brgy = $row["brgyName"];
		
		$consumer = $db->query("SELECT * FROM consumers ORDER BY Entry_Number DESC LIMIT 1");
		foreach($consumer as $row){
			$cid = $row["Entry_Number"]+1;
		}
		
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
			
			$insertC = $db->prepare("INSERT INTO consumers (Entry_Number, AccountName, Address, Barangay, Branch, Municipality, CustomerType, bapa)
									 VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$insertC->execute(array($cid, $lname." ".$fname.$mname, $hno." ".$purok." ".$brgy." ".$municipality, $brgy, $branch, $municipality, $customerType, $isBapa));
			
			$app = $db->prepare("INSERT INTO tbl_applications (appId, Entry_Number, appDate)
								 VALUES (?, ?, ?)");
			$app->execute(array($appId, $cid, date("Y-m-d H:i:s")));
				
			$transactions = $db->prepare("INSERT INTO tbl_transactions(appId, Entry_Number, status, processedBy, dateProcessed)
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
		
			$db->commit();
			echo true;
		}catch(PDOException $e){
			$db->rollBack();
			echo $e;
		}
		
	}
?>