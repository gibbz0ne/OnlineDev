<?php
    include "../../class/connect.class.php";

    $con = new getConnection();
    $db = $con->PDO();

    $table = "<table width = '95%' class = ' text-center table table-condensed table-striped table-bordered'>
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Stock Code</td>
                        <td>Description</td>
                        <td>Quantity</td>
                    </tr>
                </thead><tbody>";
				
    if(isset($_POST["id"])){
        $id = $_POST["id"];
        $query = $db->query("SELECT *FROM tbl_mr_content WHERE mrNo = '$id'");

        $ctr = 1;
        if($query->rowCount() >0){
            foreach($query as $row){
                   $qty = $row["mrQuantity"];
                foreach($db->query("SELECT *FROM tbl_materials WHERE entry_id = '".$row["entry_id"]."'") as $row2){
                    $stock_code = $row2["materialCode"];
                    $description = $row2["materialDesc"];
                }
                $table .= "<tr>
                       <td>$ctr</td>
                       <td>$stock_code</td>
                       <td>$description</td>
                       <td>$qty</td>
                </tr>";
                $ctr++;
            }
        }
    }
    $table .="</tbody></thead></table>";
    echo $table;
?>