<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$accountId = $_POST["accountId"];
	
	$delete = $db->prepare("DELETE FROM tbl_accounts WHERE accountId = ?");
	$delete->execute(array($accountId));
?>