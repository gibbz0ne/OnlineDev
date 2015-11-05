<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["fname"])){
		$fname = strtoupper($_POST["fname"]);
		$mname = strtoupper($_POST["mname"]);
		$lname = strtoupper($_POST["lname"]);
		$uname = $_POST["uname"];
		$password = md5($_POST["password"]);
		$branch = $_POST["branch"];
		$group = $_POST["group"];
		
		try{
			$db->beginTransaction();
			
			$insert = $db->prepare("INSERT INTO tbl_users (First_name, Mid_name, Last_name, Username, Password, Branch, Status, Type) 
									VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$insert->execute(array($fname, $mname, $lname, $uname, $password, $branch, 1, $group));
			
			$db->commit();
			echo "1";
		} catch(PDOException $e){
			echo $e;
			$db->rollBack();
		}
	}
?>