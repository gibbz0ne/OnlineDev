<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["mrNo"])){
		$mrNo = $_POST["mrNo"];
		$appId = $_POST["appId"];
		$query = $db->query("SELECT *FROM tbl_mr JOIN tbl_mr_wo USING (mrNo) WHERE mrNo = '$mrNo' AND appId = '$appId' AND isApproved = '1'");
		if($query->rowCount() > 0){
			foreach($query as $row){
				$query2 = $db->query("SELECT *FROM tbl_transactions WHERE appId = '$appId' ORDER BY tid DESC LIMIT 1");
				if($query2->rowCount() > 0){
					foreach($query2 as $row2){
						$query3 = $db->query("SELECT *FROM tbl_app_service a
											  LEFT OUTER JOIN tbl_service b ON a.serviceId = b.serviceId
											  WHERE a.appId = '$appId'");
						$type = "";		  
						if($query3->rowCount() > 0){
							foreach($query3 as $row3){
								$type .= $row3["serviceCode"];
							}
						}
						if($row2["status"] == 7 && $row2["action"] == 0){
							if($type == "NC")
								echo "0";
							else if($type == "RC")
								echo "1";
							else if($type == "CM")
								echo "0";
							else if($type == "RCCM")
								echo "0";
						} else
							echo "1";
					}
				}
			}
		}
	}
?>