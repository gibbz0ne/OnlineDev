<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["acctNo"])){
		$acctNo = $_POST["acctNo"];
		$query = $db->query("SELECT *FROM tbl_consumers a 
							 LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid 
							 LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId 
							 WHERE a.sysPro = '$acctNo' ORDER BY tid DESC LIMIT 1");
							 
		if($query->rowCount() > 0){
			foreach($query as $row){
				$appId = $row["appId"];
				$cid = $row["cid"];
				$processedBy = $row["processedBy"];

				$query2 = $db->query("SELECT *FROM tbl_meter_profile WHERE cid = '".$row["cid"]."'");
				if($query2->rowCount() > 0){

					if($row["action"] == 0 && $row["status"] == 7){
						$update = $db->prepare("UPDATE tbl_transactions SET action = ?, dateApproved = ? WHERE status = ? AND action = ? AND appId = ? AND cid = ?");
						$update->execute(array(1, date("Y-m-d H:i:s"), 7, 0, $appId, $cid));
						
						$insert = $db->prepare("INSERT INTO tbl_transactions (appId, cid, status, processedBy, dateProcessed) VALUES (?, ?, ?, ?, ?)");
						$insert->execute(array($appId, $cid, 8, $processedBy, date("Y-m-d H:i:s")));
					}
					
				}
			}
			echo "1";
		}
	}
?>