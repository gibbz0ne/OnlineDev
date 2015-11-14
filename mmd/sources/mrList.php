<?php
    include "../../class/connect.class.php";

    $con = new getConnection();
    $db = $con->PDO();
    $ctr = 1;
    $list = array();
	
	$query = $db->query("SELECT *FROM tbl_mr WHERE isSend = '1' AND isApproved = '0' ORDER BY mrNo DESC");
	
	if($query->rowCount() > 0){
		foreach($query as $row){
			$query2 = $db->query("SELECT *FROM tbl_mr_content WHERE mrNo = '".$row["mrNo"]."'");
            $query3 = $db->query("SELECT *FROM tbl_mr_wo WHERE mrNo = '".$row["mrNo"]."'");
			
			
			if($db->query("SELECT *FROM tbl_transactions WHERE status = '6'")->rowCount() > 0){
				$list[] = array("ctr" => $ctr,
								"mrNo" => $row["mrNo"],
								"purpose" => $row["mrPurpose"],
								"date" => $row["mrDate"],
								"items" => $query2->rowCount(),
								"wos" => $query3->rowCount()
								);
				$ctr++;
				
			}
		}
		echo json_encode($list);
	}
?>