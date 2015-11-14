<?php
    include "../../class/connect.class.php";

    $con = new getConnection();
    $db = $con->PDO();
    $ctr = 1;
    $list = array();
	$branch = $_SESSION["branch"];
    $query = $db->query("SELECT *FROM tbl_mr ORDER BY mrNo DESC");

    if($query->rowCount()>0){
        foreach($query as $row){
            $query2 = $db->query("SELECT *FROM tbl_mr_content WHERE mrNo = '".$row["mrNo"]."'");
            $query3 = $db->query("SELECT *FROM tbl_mr_wo WHERE mrNo = '".$row["mrNo"]."'");
			$query4 = $db->query("SELECT *FROM tbl_applications a
								  LEFT OUTER JOIN tbl_temp_consumers b ON a.cid = b.cid
								  LEFT OUTER JOIN tbl_mr_wo c ON a.appId = c.appId
								  WHERE b.BranchT = '$branch' AND c.mrNo = '".$row["mrNo"]."'");
			
			if($query4->rowCount() > 0){
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