<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$list = array();
	$userId = $_SESSION["userId"];
	$sign = "";
	
	$query = $db->query("SELECT *FROM tbl_signatories a
						 LEFT OUTER JOIN tbl_accounts b ON a.accountId = b.accountId
						 WHERE userId = '$userId' ORDER BY a.aStatus");
						 
	if($query->rowCount() > 0){
		foreach($query as $row){
			switch($row["aStatus"]){
				case 1:
					$sign = "Approved By: (CAR)";
					break;
				case 2:
					$sign = "Noted By: (CAR)";
					break;
				case 3:
					$sign = "Proccesed By: (Account No)";
					break;
				case 4:
					$sign = "Approved By: (Account No)";
					break;
			}
			
			$mname = ($row["aMname"] == "" ? "" : $row["aMname"][0].".");
			
			array_push($list, array("sign" => $sign,
									"name" => $row["aFname"]." ".$mname." ".$row["aLname"],
									"position" => $row["aPosition"]));
		}
		
		echo json_encode($list);
	}
?>