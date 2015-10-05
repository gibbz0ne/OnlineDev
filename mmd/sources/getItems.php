<?php
    include "../../class/connect.class.php";

    $con = new getConnection();
    $db = $con->PDO();

   	$list = array();
    if(isset($_GET["mr"])){
        $id = $_GET["mr"];
		
        $query = $db->query("SELECT *FROM tbl_mr_content WHERE mrNo = '$id'");

        $ctr = 1;
        if($query->rowCount() >0){
            foreach($query as $row){
                   $qty = $row["mrQuantity"];
                foreach($db->query("SELECT *FROM tbl_materials WHERE entry_id = '".$row["entry_id"]."'") as $row2){
                    $stock_code = $row2["materialCode"];
                    $description = $row2["materialDesc"];
					$unit = $row2["unit"];
                }
				$list[] = array("ctr" => $ctr,
								"mCode" => $stock_code,
								"description" => $description,
								"unit" => $unit,
								"qty" => $row["mrQuantity"],
								"iQty" => $row["mrQuantity"],
								"entryId" => $row["entry_id"]);
                $ctr++;
            }
        }
		echo json_encode($list);
    }
?>