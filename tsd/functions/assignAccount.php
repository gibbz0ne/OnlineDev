<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$userId = $_SESSION["userId"];
	
	if(isset($_POST["acct1"])){
		$acct1 = $_POST["acct1"];
		$acct2 = $_POST["acct2"];
		$acct3 = $_POST["acct3"];
		echo "wo";
		$query = $db->query("SELECT *FROM tbl_signatories WHERE userId = '$userId' AND aGroup = 'WO'");
		
		if($query->rowCount() > 0){
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ? AND aGroup = ?");
			$update->execute(array($acct1, 1, $userId, "WO"));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ? AND aGroup = ?");
			$update->execute(array($acct2, 2, $userId, "WO"));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ? AND aGroup = ?");
			$update->execute(array($acct3, 3, $userId, "WO"));
		} else{
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus, aGroup) VALUES (?, ?, ?, ?)");
			$insert->execute(array($acct1, $userId, 1, "WO"));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus, aGroup) VALUES (?, ?, ?, ?)");
			$insert->execute(array($acct2, $userId, 2, "WO"));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus, aGroup) VALUES (?, ?, ?, ?)");
			$insert->execute(array($acct3, $userId, 3, "WO"));
		}
	} else if(isset($_POST["macct1"])){
		$acct1 = $_POST["macct1"];
		$acct2 = $_POST["macct2"];
		$acct3 = $_POST["macct3"];
		$acct4 = $_POST["macct4"];
		$acct5 = $_POST["macct5"];
		echo $acct1." ".$acct2." ".$acct3." ".$acct4." ".$acct5;
		$query = $db->query("SELECT *FROM tbl_signatories WHERE userId = '$userId' AND aGroup = 'MR'");
		
		if($query->rowCount() > 0){
			echo "true";
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ? AND aGroup = ?");
			$update->execute(array($acct1, 1, $userId, "MR"));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ? AND aGroup = ?");
			$update->execute(array($acct2, 2, $userId, "MR"));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ? AND aGroup = ?");
			$update->execute(array($acct3, 3, $userId, "MR"));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ? AND aGroup = ?");
			$update->execute(array($acct4, 4, $userId, "MR"));
			
			$update = $db->prepare("UPDATE tbl_signatories SET accountId = ? WHERE aStatus = ? AND userId = ? AND aGroup = ?");
			$update->execute(array($acct5, 5, $userId, "MR"));
		} else{
			echo "trues";
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus, aGroup) VALUES (?, ?, ?, ?)");
			$insert->execute(array($acct1, $userId, 1, "MR"));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus, aGroup) VALUES (?, ?, ?, ?)");
			$insert->execute(array($acct2, $userId, 2, "MR"));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus, aGroup) VALUES (?, ?, ?, ?)");
			$insert->execute(array($acct3, $userId, 3, "MR"));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus, aGroup) VALUES (?, ?, ?, ?)");
			$insert->execute(array($acct4, $userId, 4, "MR"));
			
			$insert = $db->prepare("INSERT INTO tbl_signatories (accountId, userId, aStatus, aGroup) VALUES (?, ?, ?, ?)");
			$insert->execute(array($acct5, $userId, 5, "MR"));
		}
	}
?>