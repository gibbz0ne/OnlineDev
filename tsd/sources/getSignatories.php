<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$list = array();
	$userId = $_SESSION["userId"];
	$sign = $type = "";
	
	if(isset($_GET["wo"]))
		$type = "wo";
	else if(isset($_GET["mr"]))
		$type = "mr";
	else if(isset($_GET["stype"]))
		$type = $_GET["stype"];
	$query = $db->query("SELECT *FROM tbl_signatories a
						 LEFT OUTER JOIN tbl_accounts b ON a.accountId = b.accountId
						 WHERE a.userId = '$userId' AND a.aGroup LIKE '%$type%' ORDER BY a.aStatus");
						 
	if($query->rowCount() > 0){
		foreach($query as $row){
			if($type == "wo"){
				switch($row["aStatus"]){
					case 1:
						$sign = "Issued By: ";
						break;
					case 2:
						$sign = "Issued To: (CAR)";
						break;
					case 3:
						$sign = "Approved By: (Account No)";
						break;
				}
			} else if($type == "mr"){
				switch($row["aStatus"]){
					case 1:
						$sign = "Requisition by: ";
						break;
					case 2:
						$sign = "Checked by:";
						break;
					case 3:
						$sign = "Recommending Approval:";
						break;
					case 4:
						$sign = "Verified by:";
						break;
					case 5:
						$sign = "Approved by";
						break;
				}
			}
			
			$mname = ($row["aMname"] == "" ? "" : $row["aMname"][0].".");
			
			array_push($list, array("sign" => $sign,
									"name" => $row["aFname"]." ".$mname." ".$row["aLname"],
									"position" => $row["aPosition"]));
		}
		
		echo json_encode($list);
	}
?>