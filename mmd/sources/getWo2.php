<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_GET["mr"])){
		$mr = $_GET["mr"];
		$list = array();
		$query = $db->query("SELECT *FROM tbl_mr_wo a
							 LEFT OUTER JOIN tbl_applications b ON a.appId = b.appId
							 LEFT OUTER JOIN tbl_temp_consumers c ON b.cid = c.cid
							 WHERE a.mrNo = '$mr'");
		
		if($query->rowCount() > 0){
			foreach($query as $row){
				array_push($list, array("wo" => $row["wo"],
										"consumer" => $row["AccountNameT"],
										"address" => $row["AddressT"],
										"acctNo" => $row["AccountNumberT"]
										));
			}
			
			echo json_encode($list);
		}
	}
?>