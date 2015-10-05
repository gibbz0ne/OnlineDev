<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$table = "<table width = '95%' class = ' text-center table table-condensed table-bordered'>
                <thead>
                    <tr>
                        <td>#</td>
                        <td>Work Order</td>
                        <td>Primary No</td>
                        <td>Consumer</td>
                    </tr>
                </thead><tbody>";
	
	if(isset($_POST["id"])){
		$id = $_POST["id"];
		$query = $db->query("SELECT *FROM tbl_mr_wo WHERE mrNo = '$id'");
		
		$ctr = 1;
		if($query->rowCount() > 0){
			foreach($query as $row){
				$wo = $row["wo"];
				foreach($db->query("SELECT *FROM tbl_applications JOIN tbl_consumers USING (cid) WHERE appId = '".$row["appId"]."'") as $row2){
					
					if($row2["mname"] != "")
						$row2["mname"] = $row2["mname"][0]." ";
					
					$name = $row2["fname"]." ".$row2["lname"]." ".$row2["mname"];
					$acctNo = $row2["acctNo"];
				}
				$table .= "<tr>
							<td>$ctr</td>
							<td>$wo</td>
							<td>$acctNo</td>
							<td>$name</td>
							</tr>";
				$ctr++;
			}
			$table .= "</thead></tbody></table>";
			echo $table;
		}
	}
?>