<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["mrNo"])){
		$mrNo = $_POST["mrNo"];
		$query = $db->query("SELECT *FROM tbl_mr JOIN tbl_mr_wo USING (mrNo) WHERE mrNo = '$mrNo' AND isApproved = '1'");
		if($query->rowCount() > 0){
			foreach($query as $row){
				$appId = $row["appId"];
				$query2 = $db->query("SELECT *FROM tbl_transactions WHERE status = '7' AND action = '0' AND appId = '$appId'");
				
				if($query2->rowCount() > 0)
					echo "0";
				else
					echo "1";
			}
		}
	}
?>