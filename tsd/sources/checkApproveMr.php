<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	if(isset($_POST["mrNo"])){
		$mrNo = $_POST["mrNo"];
		// echo $mrNo;
		if($db->query("SELECT *FROM tbl_mr WHERE mrNo = '$mrNo' AND isApproved = '1'")->rowCount() > 0){
			$query = $db->query("SELECT *FROM tbl_mr a
						   LEFT OUTER JOIN tbl_mr_wo b ON a.mrNo = b.mrNo
						   LEFT OUTER JOIN tbl_transactions c ON b.appId = c.appId
						   WHERE a.mrNo = '$mrNo'
						   ORDER BY tid DESC LIMIT 1");
			if($query->rowCount() > 0){
				foreach($query as $row){
					if($row["status"] == 7 && $row["action"] == 0)
						echo "1";
					else
						echo "0";
				}
			}
			else
				echo "0";
		}
		else
			echo "0";
	}
?>