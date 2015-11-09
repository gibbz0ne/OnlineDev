<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["aFname"])){
		$fname = strtoupper($_POST["aFname"]);
		$mname = strtoupper($_POST["aMname"]);
		$lname = strtoupper($_POST["aLname"]);
		$position = strtoupper($_POST["aPosition"]);
		$branch = $_POST["abranch"];
		
		$query = $db->query("SELECT *FROM tbl_accounts ORDER BY accountId DESC LIMIT 1");
		foreach($query as $row)
			$id = $row["accountId"] + 1;
		if($_POST["edit"] == 1){
			$accountId = $_POST["accountId"];
			$update = $db->prepare("UPDATE tbl_accounts SET aFname = ?, aMname = ?, aLname = ?, aPosition = ?, aBranch = ? WHERE accountId = ?");
			$update->execute(array($fname, $mname, $lname, $position, $branch, $accountId));
			
		}else{
			$insert = $db->prepare("INSERT INTO tbl_accounts (accountId, aFname, aMname, aLname, aPosition, aBranch) VALUES (?, ?, ?, ?, ?, ?)");
			$insert->execute(array($id, $fname, $mname, $lname, $position, $branch));
		}
	}
?>