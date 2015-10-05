<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$users = array();
	$query = $db->query("SELECT * FROM `tbl_users` a 
						 LEFT OUTER JOIN tbl_user_groups b ON a.Type = b.groupId ORDER BY a.id");
						 
	if($query->rowCount() > 0){
		foreach($query as $row){
			array_push($users, array("id" => $row["id"],
									 "fname" => $row["First_name"],
									 "mname" => $row["Mid_name"],
									 "lname" => $row["Last_name"],
									 "username" => $row["Username"],
									 "password" => $row["Password"],
									 "branch" => $row["Branch"],
									 "groupName" => strtoupper($row["groupName"])));
		}
		echo json_encode($users);
	}
?>