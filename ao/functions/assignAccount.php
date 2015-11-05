<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$userId = $_SESSION["userId"];
	
	if(isset($_POST["acct1"])){
		$acct1 = $_POST["acct1"];
		$acct2 = $_POST["acct2"];
		$acct3 = $_POST["acct3"];
		$acct4 = $_POST["acct4"];
		
		$query = $db->query("SELECT *FROM tbl_signatories WHERE userId = '$userId'");
		
		if($query->rowCount() > 0){
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ?");
			$update->execute(array($acct1, 1, $userId));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ?");
			$update->execute(array($acct2, 2, $userId));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ?");
			$update->execute(array($acct3, 3, $userId));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ?");
			$update->execute(array($acct4, 4, $userId));
		} else{
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus) VALUES (?, ?, ?)");
			$insert->execute(array($acct1, $userId, 1));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus) VALUES (?, ?, ?)");
			$insert->execute(array($acct2, $userId, 2));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus) VALUES (?, ?, ?)");
			$insert->execute(array($acct3, $userId, 3));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus) VALUES (?, ?, ?)");
			$insert->execute(array($acct4, $userId, 4));
		}
	}
?>