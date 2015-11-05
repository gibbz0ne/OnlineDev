<?php
include "../class/connect.class.php";
require('../assets/fpdf/fpdf.php');

$con = new getConnection();
$db = $con->PDO();
$branch =  $_SESSION["branch"];
$userId = $_SESSION["userId"];
$ao = $_SESSION["name"];
$mun = $_SESSION["mun"];
$area = $_SESSION["area"];
$req_date = $consumer = $address = $contact = $type = $so = $acctNo = "";
$acct1 = $acct2 = $acct3 = $acct4 = "";
$pos1 = $pos2 = $pos3 = $pos4 = "";
$appId = $_GET["ref"];
$serviceArr = $assignedId = "";
$query = $db->query("SELECT *FROM tbl_applications WHERE appCAR IS NOT NULL ORDER BY appCAR DESC LIMIT 1");
$query2 = $db->query("SELECT *FROM consumers a 
					LEFT OUTER JOIN tbl_applications b ON a.Entry_Number = b.Entry_Number
					LEFT OUTER JOIN tbl_so c ON b.appId = c.appId
					LEFT OUTER JOIN tbl_inspection d ON b.appId = d.appId
					LEFT OUTER JOIN tbl_inspection_meter e ON d.inspectionId = e.inspectionId
					LEFT OUTER JOIN tbl_substation f ON e.subId = f.subId
					LEFT OUTER JOIN tbl_feeder g ON e.feedId = g.feedId
					WHERE b.appId = '$appId'");


if($query->rowCount() > 0){
	foreach($query as $row){
		$my = explode("-", $row["appCAR"]);
		if(date("mY") == $my[1]){
			$no = intval($my[2])+1;
			if($no >= 100 || $no >= 99){
				$assignedId = date("mY").$no;
			} else if($no >= 10){
				if( $no == 9){
					$assignedId = date("mY")."00".$no;
				} else{
					$assignedId = date("mY")."0".$no;
				}
			} else{
				$assignedId = date("mY")."00".$no;
			}
		}
		else{
			$assignedId = date("mY")."-001";
		}
	}
}
else{
	$assignedId =  date("mY")."-001";
}

$query4 = $db->query("SELECT *FROM tbl_signatories a
					  LEFT OUTER JOIN tbl_accounts b ON a.accountId = b.accountId
					  WHERE a.userId = '$userId' ORDER BY a.aStatus");
					  
$row = $query4->fetchAll(PDO::FETCH_ASSOC);
$acct1 = $row[0]["aFname"]." ".$row[0]["aMname"][0].". ".$row[0]["aLname"];
$acct2 = $row[1]["aFname"]." ".$row[1]["aMname"][0].". ".$row[1]["aLname"];
$acct3 = $row[2]["aFname"]." ".$row[2]["aMname"][0].". ".$row[2]["aLname"];
$acct4 = $row[3]["aFname"]." ".$row[3]["aMname"][0].". ".$row[3]["aLname"];

$pos1 = $row[0]["aPosition"];
$pos2 = $row[1]["aPosition"];
$pos3 = $row[2]["aPosition"];
$pos4 = $row[3]["aPosition"];

$query5 = $db->query("SELECT *FROM tbl_service a 
			  LEFT OUTER JOIN tbl_app_service b ON a.serviceId = b.serviceId 
			  WHERE b.appId = '$appId'");
foreach($query5 as $rowS)
	$type .= $rowS["serviceDesc"]." ";
	
if($query2->rowCount() > 0){
	foreach($query2 as $row){
		
		$query3 = $db->query("SELECT *FROM tbl_consumer_contact WHERE cid = '".$row["Entry_Number"]."' AND contactType = '1'");
		$r = $query3->fetch(PDO::FETCH_ASSOC);
		$contact = $r["contactValue"];
		
		$acctNo = $row["AccountNumber"];
		$d = explode(" ", $row["appDate"]);
		$date = DateTime::createFromFormat("Y-m-d", $d[0]);

		$req_date = $date->format("F d, Y");
		$consumer = iconv('UTF-8', 'windows-1252', $row["AccountName"]);
		$address = iconv('UTF-8', 'windows-1252', $row["Address"]);
		$address = str_replace("ñ", "Ñ", $address);
		$address = iconv('UTF-8', 'windows-1252', $address);
		// $type = $row["serviceId"];
		$consumerType = $row["CustomerType"];
		$so = $row["soNum"];
		$substation = $row["subDescription"];
		$feeder = $row["feederName"];
	}
}

$pdf=new FPDF('P','mm','Letter');
$pdf->SetFont('Arial','B',10);
$pdf->AddPage();


$pdf->Image('../assets/images/logo.jpg',30,15,25);

	$pdf->Ln(10);
	$pdf->SetX(70);
	$pdf->Cell(39,10,'ALBAY POWER AND ENERGY CORPORATION', 0);
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	$pdf->SetX(79);
	$pdf->Cell(39,10,'W. Vinzon St., Albay Dist., Leg. City',0);
	$pdf->Ln(8);
	$pdf->SetX(160);
	$pdf->Cell(39, 10, 'Request No.   '.$_GET["car"], 0);
	$pdf->Ln(10);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(197, 8, "CONSUMER'S ACCOUNT REQUEST", 1, 0, "C");
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(8);
	$pdf->SetX(10);
	$pdf->Cell(197, 145, "", 1, 0);
	$pdf->SetX(10);
	$pdf->Ln(2);
	$pdf->Cell(65, 6, "REQUEST DATE:", 0, 0, "R");
	$pdf->Cell(65, 6, $req_date, 0, 0, "");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(65, 6, "CONSUMER'S ACCOUNT NAME:", 0, 0, "R");
	$pdf->Cell(65, 6, $consumer, 0, 0, "");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(65, 7, "COMPLETE ADDRESS:", 0, 0, "R");
	$pdf->Cell(65, 7, $address, 0, 0, "");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(65, 6, "CONTACT NO:", 0, 0, "R");
	$pdf->Cell(65, 6, $contact, 0, 0, "");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(65, 6, "BRANCH:", 0, 0, "R");
	$pdf->Cell(65, 6, $mun, 0, 0, "");
	$pdf->SetX(10);
	$pdf->Ln(10);
	$pdf->Cell(65, 6, "TYPE OF REQUEST: ", 0, 0, "R");
	$pdf->Cell(65, 6, $type, 0, 0, "");
	$pdf->SetX(10);
	$pdf->Ln(10);
	$pdf->Cell(40, 6, "Reason/s: ", 0, 0, "");
	$pdf->Cell(65, 6, "________________________________________________________", 0, 0, "");
	$pdf->Ln();
	$pdf->SetX(50);	
	$pdf->Cell(65, 6, "________________________________________________________", 0, 0, "");
	$pdf->Ln();
	$pdf->SetX(50);
	$pdf->Cell(65, 6, "________________________________________________________", 0, 0, "");
	$pdf->SetX(10);
	$pdf->Ln(10);
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(40, 7, "Customer Type: ", 0, 0, "R");
	$pdf->Cell(30, 7, $consumerType, 0, 0, "C");
	$pdf->Cell(35, 7, "Initial Reading: ", 0, 0, "R");
	$pdf->Cell(23, 7, "", 0, 0, "C");
	$pdf->Cell(33, 7, "S.O. No.:", 0, 0, "R");
	$pdf->Cell(30, 7, $so, 0, 0, "C");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(40, 6, "Multiplier: ", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "C");
	$pdf->Cell(35, 6, "Old Reading: ", 0, 0, "R");
	$pdf->Cell(23, 6, "", 0, 0, "C");
	$pdf->Cell(33, 6, "Date Meter Installed:", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "R");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(40, 6, "Meter Brand: ", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "C");
	$pdf->Cell(35, 6, "Serial no.: ", 0, 0, "R");
	$pdf->Cell(23, 6, "", 0, 0, "C");
	$pdf->Cell(33, 6, "Pole No.", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "R");
	$pdf->SetX();
	$pdf->Ln();
	$pdf->Cell(40, 6, "Location of Meter: ", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "C");
	$pdf->Cell(35, 6, "Meter No.: ", 0, 0, "R");
	$pdf->Cell(23, 6, "", 0, 0, "C");
	$pdf->Cell(33, 6, "Transformer No.:", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "R");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(40, 6, "Meter Coding: ", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "C");
	$pdf->Cell(35, 6, "ERC Seal No.: ", 0, 0, "R");
	$pdf->Cell(23, 6, "", 0, 0, "C");
	$pdf->Cell(33, 6, "Substation", 0, 0, "R");
	$pdf->Cell(30, 6, $substation, 0, 0, "C");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(40, 6, "Ampere/s: ", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "C");
	$pdf->Cell(35, 6, "Terminal Seal No.: ", 0, 0, "R");
	$pdf->Cell(23, 6, "", 0, 0, "C");
	$pdf->Cell(33, 6, "Feeder Line:", 0, 0, "R");
	$pdf->Cell(30, 6, $feeder, 0, 0, "C");
	$pdf->Ln();
	$pdf->SetX(80);
	$pdf->Cell(35, 6, "APEC Seal No.: ", 0, 0, "R");
	$pdf->Ln(10);
	$pdf->SetX(20);
	$pdf->Cell(65, 5, "Requested By: ", 0, 0, "L");
	$pdf->Cell(65, 5, "Approved By: ", 0, 0, "L");
	$pdf->Cell(35, 5, "Noted By: ", 0, 0, "L");
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(65, 5, "____________________", 0, 0, "L");
	$pdf->Cell(65, 5, "____________________", 0, 0, "L");
	$pdf->Cell(35, 5, "____________________", 0, 0, "L");
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(65, 5, $ao, 0, 0, "L");
	$pdf->Cell(65, 5, iconv('UTF-8', 'windows-1252', $acct1), 0, 0, "L");
	$pdf->Cell(35, 5, iconv('UTF-8', 'windows-1252', $acct2), 0, 0, "L");
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(65, 5, "Branch ".$branch.$area." Account Officer", 0, 0, "L");
	$pdf->Cell(65, 5, $pos1, 0, 0, "L");
	$pdf->Cell(65, 5, $pos2, 0, 0, "L");
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(65, 6, "Branch", 0, 0, "L");
	$pdf->Cell(65, 6, "Branch", 0, 0, "L");
	$pdf->Ln(9);
	$pdf->SetX(10);
	$pdf->Cell(197, 45, "", 1, 0);
	$pdf->Ln(1);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(65, 6, "To be filled-up by DMG", 0, 0, "L");
	$pdf->SetFont('Arial','',9);
	$pdf->Ln(7);
	$pdf->SetX(10);
	$pdf->Cell(65, 6, "Consumer's Assigned Account Number:", 0, 0, "R");
	$pdf->SetFont("Arial", "", 10);
	$pdf->Cell(65, 6, $acctNo, 0, 0);
	$pdf->SetFont('Arial','',9);
	$pdf->Ln(10);
	$pdf->SetX(18);
	$pdf->Cell(100, 6, "Processed By:", 0, 0, "L");
	$pdf->Cell(65, 6, "Approved By:", 0, 0, "L");
	$pdf->Ln(5);
	$pdf->SetX(18);
	$pdf->Cell(100, 6, "__________________________", 0, 0, "L");
	$pdf->Cell(65, 6, "__________________________", 0, 0, "L");
	$pdf->Ln(5);
	$pdf->SetX(18);
	$pdf->Cell(100, 6, iconv('UTF-8', 'windows-1252', $acct3), 0, 0, "L");
	$pdf->Cell(65, 6, iconv('UTF-8', 'windows-1252', $acct4), 0, 0, "L");
	$pdf->Ln(5);
	$pdf->SetX(18);
	$pdf->Cell(100, 6, $pos3, 0, 0, "L");
	$pdf->Cell(65, 6, $pos4, 0, 0, "L");
	$pdf->Ln(12);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(100, 5, "Accomplish in 3 copies for the following:", 0, 0);
	$pdf->Ln();
	$pdf->Cell(100, 5, "Original Copy - Branch; 2nd copy - URD; 3rd copy - Finance, A/R", 0, 0);

ob_end_clean();
$pdf->Output();
?>