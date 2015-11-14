<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	$branch = $_SESSION["branch"];
	$ext = "";
	$ext = $wom = $num = "";
	if($branch == "B1"){
		$ext = "WOE".date("y")."-T";
		$num = 3;
	}
	if($branch == "B2"){
		$ext = "WOM-MAIN-".date("y");
		$num = 4;
	}
	if(isset($_POST["appId"])){
		$appId = $_POST["appId"];
		$cid = $_POST["cid"];
		
		$query = $db->query("SELECT *FROM tbl_temp_consumers a
							 LEFT OUTER JOIN tbl_applications b ON a.cid = b.cid
							 WHERE b.appId = '$appId' AND a.cid = '$cid'");
		
		if($query->rowCount() > 0){
			foreach($query as $row)
				$address = $row["AddressT"];
				$name = $row["AccountNameT"];
				$no = $row["AccountNumberT"];
		}
		
		$y = date("Y");
		$query = $db->query("SELECT *FROM tbl_work_order WHERE wo LIKE '%$ext%' ORDER BY wo DESC LIMIT 1");
		
		if($query->rowCount() > 0){
			foreach($query as $row)
				$wo = explode("-", $row["wo"]);
				$series = intval($wo[$num])+1;
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
		<td width = "40%">NEXT WORK ORDER NO:</td>
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