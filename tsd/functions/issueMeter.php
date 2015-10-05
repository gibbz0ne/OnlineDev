<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["cid"])){
		$cid = $_POST["cid"];
		$mReading = $_POST["mReading"];
		$mBrand = strtoupper($_POST["mBrand"]);
		$mClass = $_POST["mClass"];
		$mSerial = $_POST["mSerial"];
		$mERC = $_POST["mERC"];
		$mLabSeal = $_POST["mLabSeal"];
		$mTerminal = $_POST["mTerminal"];
		$multiplier = $_POST["multiplier"];
		
		foreach($db->query("SELECT *FROM tbl_applications WHERE cid = '$cid'") as $row)
			$appId = $row["appId"];
		
		if($db->query("SELECT *FROM tbl_meter_profile WHERE cid = '$cid' AND appId = '$appId'")->rowCount() > 0){
			$profile_update = $db->prepare("UPDATE tbl_meter_profile SET mReading = ?, mBrand = ?, mClass = ?, mSerial = ?, mERC = ?, mLabSeal = ?, mTerminal = ?, multiplier = ? 
											WHERE cid = ? and appId = ?");
			$profile_update->execute(array($mReading, $mBrand, $mClass, $mSerial, $mERC, $mLabSeal, $mTerminal, $multiplier, $cid, $appId));
		}else{
			$insert = $db->prepare("INSERT INTO tbl_meter (entry_id) VALUES (?)");
			$insert->execute(array(""));
			
			$mid = $db->lastInsertId();
			
			$profile = $db->prepare("INSERT INTO tbl_meter_profile (mid, appId, cid, mReading, mBrand, mClass, mSerial, mERC, mLabSeal, mTerminal, multiplier)
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$profile->execute(array($mid, $appId, $cid, $mReading, $mBrand, $mClass, $mSerial, $mERC, $mLabSeal, $mTerminal, $multiplier));
		}
		echo "1";
	}
?>