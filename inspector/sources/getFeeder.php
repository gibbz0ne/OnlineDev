<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$list = array();
	
	if(isset($_GET["subId"])){
		$station = $_GET["subId"];
		
		$query = $db->query("SELECT *FROM tbl_feeder WHERE subId = '$station'");
		
		if($query->rowCount() > 0){
			foreach($query as $row){
				$list[] = array("feederId" => $row["feedId"],
								"feeder" => $row["feederName"]);
			}
			
			echo json_encode($list);
		}
	}
?>