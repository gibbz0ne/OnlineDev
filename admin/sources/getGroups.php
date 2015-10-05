<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$list = array();
	$query = $db->query("SELECT *FROM tbl_user_groups WHERE groupName != 'ADMIN' ORDER BY groupName");
	
	// if($query->rowCount() > 0){
		foreach($query as $row){
			$list[] = array(
				"groupId" => $row["groupId"],
				"groupName" => $row["groupName"]
			);
		}
		echo json_encode($list);
	// }
?>