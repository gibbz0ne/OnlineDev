<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	$ext = "";
	$ext = $wom = "";
	if($branch == "B1")
		$ext = "WOE".date("y")."-T";
	if($branch == "B2")
		$ext = "WOM".date("y");
	
	if(isset($_POST["appId"])){
		$appId = $_POST["appId"];
		$cid = $_POST["cid"];
		
		$query = $db->query("SELECT *FROM tbl_applications JOIN tbl_consumers USING (cid) JOIN tbl_consumer_address USING (cid) JOIN tbl_barangay USING (brgyId) WHERE tbl_applications.appId = '$appId' AND cid = '$cid'");
		
		if($query->rowCount() > 0){
			foreach($query as $row)
			foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row2)
			
			if($row["mname"] != "")
				$row["mname"] = $row["mname"][0].".";
			$address = $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row2["munDesc"];
			$name = $row["lname"].", ".$row["fname"]." ".$row["mname"];
			$no = $row["sysPro"];
		}
		
		$y = date("Y");
		$query = $db->query("SELECT *FROM tbl_work_order WHERE wo LIKE '%$y%' ORDER BY wo DESC LIMIT 1");
		
		if($query->rowCount() > 0){
			foreach($query as $row)
				$wo = explode("-", $row["wo"]);
				$series = intval($wo[3])+1;
				if(strlen($series) == 1){
					$wom = "APEC-".$ext."-000".$series;
				} else if(strlen($series) == 2){
					$wom = "APEC-".$ext."-00".$series;
				} else if(strlen($series) == 3){
					$wom = "APEC-".$ext."-0".$series;
				} else{
					$wom = "APEC-".$ext."-".$series;
				}
		} else{
			$wom = "APEC-".$ext."-0001";
		}
	}
?>
<table width = "100%">
	<tr>
		<td>NEXT WORK ORDER NO:</td>
		<td><?php echo $wom;?></td>
	</tr>
	<tr>
		<td>LOCATION:</td>
		<td><?php echo $address;?></td>
	</tr>
	<tr>
		<td>ACCOUNT NAME:</td>
		<td><?php echo $name;?></td>
	</tr>
	<tr>
		<td>ACCOUNT NUMBER:</td>
		<td><?php echo $no;?></td>
	</tr>
</table>