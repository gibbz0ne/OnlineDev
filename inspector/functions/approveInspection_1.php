<?php
	include "../../class/connect.class.php";
	$id = $_SESSION["userId"];
	$con = new getConnection();
	$db = $con->PDO();
	
	$tid = $_POST["tid"];

	$date = date("Y-m-d");
	$time = date("H:i:s");
	if(isset($_POST["inspectedBy"])){
		$inspector = strtoupper($_POST["inspectedBy"]);
		$iRemarks = strtoupper($_POST["iRemarks"]);
		if($_POST["pType"] == "FUSE"){
			$pType = 1;
		}else{
			$pType = 2;
		}
		$rating = strtoupper($_POST["rating"]);
		$etype = strtoupper($_POST["etype"]);
		$wSize = strtoupper($_POST["wSize"]);
		$servicePole = strtoupper($_POST["servicePole"]);
		$length = strtoupper($_POST["length"]);
		$totalVa = $_POST["totalVa"];
		$appId = $_POST["appId"];
		$cid = $_POST["cid"];
		$date = $_POST["date"];
		$status = 2;

		$res = $db->query("select sysPro from tbl_consumers where cid = $cid");
		$rowT = $res->fetchAll(PDO::FETCH_ASSOC);

		if($rowT[0]["sysPro"]) {
			$status = 3;
		}
		
		$i = 1;
		$query = $db->query("SELECT *FROM tbl_inspection ORDER BY inspectionId DESC limit 1");
		
		if($query->rowCount() > 0){
			foreach($query as $row){
				$i = $row["inspectionId"]+1;
			}
		}

		$insert = $db->prepare("INSERT INTO tbl_inspection (inspectionId, appId, cid, protection, pRating, sType, wireSize, length, servicePole, inspectedBy, iRemarks, dateInspected)
								VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$insert->execute(array($i, $appId, $cid, $pType, $rating, $etype, $wSize, $length, $servicePole, $inspector, $iRemarks, $date));

		$processed = 0;
		$q = $db->query("SELECT *FROM tbl_transactions where tid = $tid");
		foreach ($q as $r) {
			$processed = $r["processedBy"];
		}

		$update = $db->prepare("UPDATE tbl_transactions SET action = ?, approvedBy = ?, dateApproved = ?, remarks = ? WHERE tid = ?");
		$update->execute(array(1, $id, date("Y-m-d H:i:s"), $iRemarks, $tid));

		$insert = $db->prepare("INSERT INTO tbl_transactions (appId, cid, status, processedBy, dateProcessed)
							VALUES(?, ?, ?, ?, ?)");
		$insert->execute(array($appId, $cid, $status, $processed, date("Y-m-d H:i:s")));
		
		echo "1";
	}
?>