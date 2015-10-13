<?php
    include "../../class/connect.class.php";
    $con = new getConnection();
    $db = $con->PDO();
	$y = date("y");
	
	if(isset($_POST["mrNum"]) && $_POST["mrNum"] != ""){
		if(strlen($_POST["mrNum"]) == 1)
			$mrNo = "MR-M-000".$_POST["mrNum"];
		else if(strlen($_POST["mrNum"]) == 2)
			$mrNo = "MR-M-00".$_POST["mrNum"];
		else if(strlen($_POST["mrNum"]) == 3)
			$mrNo = "MR-M-0".$_POST["mrNum"];
		else
			$mrNo = "MR-M-".$_POST["mrNum"];
	} else{
		$query = $db->query("SELECT *FROM tbl_mr WHERE mrNo LIKE '%$y%' ORDER BY mrNo DESC LIMIT 1");

		if($query->rowCount() > 0){
			foreach($query as $row){
				$mrNo = explode("-", $row["mrNo"]);
				$series = intval($mrNo[3]+1);

				if(strlen($series) == 1){
					$mrNo = "MR-M-".$y."-000".$series;
				} else if(strlen($series) == 2){
					$mrNo = "MR-M-".$y."-00".$series;
				} else if(strlen($series) == 3){
					$mrNo = "MR-M-".$y."-0".$series;
				} else{
					$mrNo = "MR-M-".$y."-".$series;
				}
			}
		} else{
			$mrNo = "MR-M-".$y."-0001";
		}
	}
	
	$woList = '<table class = "table table-condensed table-striped table-bordered">
		<thead>
			<tr>
				<td><strong>#</strong></td>
				<td><strong>WORK ORDER</strong></td>
				<td><strong>SO</strong></td>
				<td><strong>CONSUMER NAME</strong></td>
				<td><strong>ADDRESS</strong></td>
				<td><strong>PRIMARY ACCOUNT NUMBER</strong></td>
			</tr>
		</thead>
		<tbody>
			<tr>';
	$materialList = "<table class = 'table table-condensed table-striped table-bordered' border = '1'>
		<thead>				
			<tr>
				<td><strong>ITEM</strong></td>
				<td><strong>STOCK CODE</strong></td>
				<td><strong>DESCRIPTION</strong></td>
				<td><strong>UNIT</strong></td>
				<td><strong>QUANTITY</strong></td>
			</tr>
		</thead>
		<tbody>
			<tr>";
    $con = new getConnection();
    $db = $con->PDO();

    if(isset($_POST["data3"])){
        $data = $_POST["data2"];
        $materials = $_POST["data3"];
        $purpose = $_POST["purpose"];
		$ctr = $ctr2 = $ctr3 = 1;

		//$dataCount = count($data);
        
        for($i = 0; $i < count($data); $i++){
			if($ctr%6 == 0 && $i != 0) { $woList .= "<td>".$data[$i]."</td></tr><tr>"; }
            else if($ctr%7 == 0 && $i != 0) { $woList .= ""; $ctr = 0; }
            else { $woList .= "<td>".$data[$i]."</td>"; }
			$ctr++;
        }

        //$woList .= "<td>".$dataCount."</td></tr>";
        $woList .= "</table>";
		
        for($i = 0; $i<count($materials); $i++){
			if($ctr2%5 == 0 && $i != 0)
				$materialList .= "<td>".$materials[$i]."</td></tr><tr>";
			else
				$materialList .= "<td>".$materials[$i]."</td>";
			$ctr2++;
        }
        $materialList .= "</tbody></table>";

		echo "<div class = 'col-sm-1'></div>";
		echo "<div class = 'col-sm-2 text-left'>
				MR-NO: <br>
				DATE: <br>
				PURPOSE: <br>
			</div>";
		echo "<div class = 'col-sm-4 text-left'>
				$mrNo <br>
				".date("Y-m-d")." <br>
				".strtoupper($purpose)."
				</div>";
        echo "<br><br><br><br>".$materialList;
        echo $woList;
        
    }
?>