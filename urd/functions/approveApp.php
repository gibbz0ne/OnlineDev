<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();

	function reconnect($account, $db){
		$recon = $db->prepare("UPDATE consumers SET Status = ? WHERE AccountNumber = ?");
		$recon->execute(array("A", $account));
	}
	
	function updateTransaction($account, $tid, $db){
		$update = $db->prepare("UPDATE tbl_transactions SET action = ? WHERE tid = ? AND status = ?");
		$update->execute(array(1, $tid, 8));
		
		$update = $db->prepare("UPDATE tbl_temp_consumers SET flag = ? WHERE AccountNumber = ?");
		$update->execute(array(1, $account));
	}
	
	if(isset($_POST["acctNo"])){
		$type = $_POST["type"];
		$acctNo = $_POST["acctNo"];
		$query = $db->query("SELECT *FROM tbl_temp_consumers a 
							LEFT OUTER JOIN tbl_transactions b ON a.cid = b.cid 
							LEFT OUTER JOIN tbl_meter_profile c ON a.cid = c.cid
							WHERE a.AccountNumberT = '$acctNo' ORDER BY tid DESC LIMIT 1");
		
		$row = $query->fetchAll(PDO::FETCH_ASSOC);
		if($type == "NC"){
			$insert = $db->prepare("INSERT INTO consumers (Accountaccountber, AccountName, Address, Barangay, Branch, Municipality, CustomerType, bapa, Status, Multiplier)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$insert->execute(array($row[0]["AccountNumberT"], $row[0]["AccountNameT"], $row[0]["AddressT"], 
								   $row[0]["BarangayT"], $row[0]["BranchT"], $row[0]["MunicipalityT"], 
								   $row[0]["CustomerTypeT"], ($row[0]["bapaT"] == 0 ? "NO" : "YES"), "A", $row[0]["multiplier"]));
			updateTransaction($acctNo, $row[0]["tid"]);
			
		} else if($type == "RC"){
			reconnect($acctNo, $db);
			updateTransaction($acctNo, $row[0]["tid"], $db);
		} 
		else if($type == "RCCM"){
			reconnect($acctNo, $db);
			updateTransaction($acctNo, $row[0]["tid"], $db);
			// $insert = $db->prepare("INSERT INTO tbl_meter_profile (appId, cid, AccountNumber, mReading, mBrand, mClass, mSerial, mERC, mLabSeal, mTerminal, multiplier)
									// VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			// $insert->execute(array($row[0]["appId"], $row[0]["cid"], $row[0]["AccountNumberT"], $row[0][""], $row[0][""], $row[0][""], $row[0][""], $row[0][""], $row[0][""], $row[0][""], $row[0][""], ))
		}
	}
?>