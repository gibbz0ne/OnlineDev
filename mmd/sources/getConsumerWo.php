<?php
    include "../../class/connect.class.php";

    $con = new getConnection();
    $db = $con->PDO();
    $ctr = 1;
    $list = array();
	
	if(isset($_GET["mr"])){
		$mr = $_GET["mr"];
		$ctr = 1;
		$query = $db->query("SELECT *FROM tbl_mr_wo JOIN tbl_applications USING (appId) JOIN tbl_consumers USING (cid) JOIN tbl_consumer_address USING(cid) JOIN tbl_barangay USING (brgyId) WHERE mrNo = '$mr'");
		
		foreach($query as $row){
			// echo $row["munId"];
			foreach($db->query("SELECT *FROM tbl_barangay JOIN tbl_municipality USING (munId) WHERE munId = '".$row["munId"]."'") as $row2)
			
			if($row["mname"] != "")
				$row["mname"][0].".";
			$mReading = $mBrand = $mClass = $mSerial = $mERC = $mLabSeal = $mTerminal = $multiplier = "";
			foreach($db->query("SELECT *FROM tbl_meter_profile WHERE cid = '".$row["cid"]."' AND appId = '".$row["appId"]."'") as $r){
				$mReading = $r["mReading"];
				$mBrand = $r["mBrand"];
				$mClass = $r["mClass"];
				$mSerial = $r["mSerial"];
				$mERC = $r["mERC"];
				$mLabSeal = $r["mLabSeal"];
				$mTerminal = $r["mTerminal"];
				$multiplier = $r["multiplier"];
			}
			
			//approve mr by mmd
			
			$list[] = array(
				"ctr1" => $ctr,
				"acctNo" => $row["sysPro"],
				"consumerName" => $row["lname"]." ".$row["fname"]." ".$row["mname"],
				"address" => $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row2["munDesc"],
				"cid" => $row["cid"],
				"mReading" => $mReading,
				"mBrand" => $mBrand,
				"mClass" => $mClass,
				"mSerial" => $mSerial,
				"mERC" => $mERC,
				"mLabSeal" => $mLabSeal,
				"mTerminal" => $mTerminal,
				"multiplier" => $multiplier
			);
			
			$ctr++;
		}
		
		echo json_encode($list);
	}
	if(isset($_POST["cid"])){
		$cid = $_POST["cid"];
		$ctr = 1;
		foreach($db->query("SELECT *FROM tbl_applications JOIN tbl_consumers USING (cid) JOIN tbl_consumer_address USING (cid) JOIN tbl_barangay USING (brgyId) WHERE cid = '$cid'") as $row){
			foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row2)

			$mReading = $mBrand = $mClass = $mSerial = $mERC = $mLabSeal = $mTerminal = $multiplier = "";
			foreach($db->query("SELECT *FROM tbl_meter_profile WHERE cid = '$cid' AND appId = '".$row["appId"]."'") as $r){
				$mReading = $r["mReading"];
				$mBrand = $r["mBrand"];
				$mClass = $r["mClass"];
				$mSerial = $r["mSerial"];
				$mERC = $r["mERC"];
				$mLabSeal = $r["mLabSeal"];
				$mTerminal = $r["mTerminal"];
				$multiplier = $r["multiplier"];
			}
			
			if($row["mname"] != "")
				$row["mname"][0].".";
			// echo "Primary Account No: ".$row["acctNo"]."<br>";
			// echo "Consumer Name: ".$row["lname"]." ".$row["fname"]." ".$row["mname"]."<br>";
			// echo "Addresss: ".$row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row2["munDesc"];
			$list = array(
				"acctNo" => $row["acctNo"],
				"consumerName" => $row["lname"]." ".$row["fname"]." ".$row["mname"],
				"address" => $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row2["munDesc"],
				"cid" => $row["cid"],
				"mReading" => $mReading,
				"mBrand" => $mBrand,
				"mClass" => $mClass,
				"mSerial" => $mSerial,
				"mERC" => $mERC,
				"mLabSeal" => $mLabSeal,
				"mTerminal" => $mTerminal,
				"multiplier" => $multiplier
			);
			
			echo json_encode($list);
		}
	}
?>