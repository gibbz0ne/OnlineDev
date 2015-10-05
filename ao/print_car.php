<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include "../class/connect.class.php";
require('../assets/fpdf/fpdf.php');

$con = new getConnection();
$db = $con->PDO();

$ao = $_SESSION["name"];
$mun = $_SESSION["mun"];
$req_date = $consumer = $address = $contact = $branch = $type = $so = $acctNo = "";
$appId = $_GET["ref"];
echo $appId;
$assignedId = "";
$query = $db->query("SELECT *FROM tbl_applications WHERE appCAR IS NOT NULL ORDER BY appCAR DESC LIMIT 1");
$query2 = $db->query("SELECT *FROM tbl_consumers JOIN tbl_consumer_address USING (cid) JOIN tbl_applications USING (cid) JOIN tbl_barangay USING(brgyId) WHERE tbl_applications.appId = '$appId'");
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


if($query2->rowCount() > 0){
	foreach($query2 as $row){
		foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row2)
		$query3 = $db->query("SELECT *FROM tbl_consumer_contact WHERE cid = '".$row["cid"]."' AND contactType = '1'");
		$r = $query3->fetch(PDO::FETCH_ASSOC);
		$contact = $r["contactValue"];
		$fname = $row["fname"];
		$mname = $row["mname"][0].".";
		$lname = $row["lname"].",";
		if($row["mname"] == ""){
			$mname = "";
		} if($row["lname"] == ""){
			$lname = "";
		}
		$acctNo = $row["sysPro"];
		$d = explode(" ", $row["appDate"]);
		$date = DateTime::createFromFormat("Y-m-d", $d[0]);

		$req_date = $date->format("F d, Y");
		$consumer = $lname." ".$fname." ".$mname;
		$address = $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row2["munDesc"];
		$address = str_replace("ñ", "Ñ", $address);
		$address = iconv('UTF-8', 'windows-1252', $address);
		$type = $row["serviceId"];
		$consumerType = $row["consumerType"];
		$so = $row["SO"];
		if($type == 1)
			$type = "New Connection";
		// echo $req_date;
	}
}
// echo $address;
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
	$pdf->Cell(65, 6, "TYPE OF REQUEST: ", 0, 0, "");
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
	$pdf->Cell(30, 6, "", 0, 0, "R");
	$pdf->SetX(10);
	$pdf->Ln();
	$pdf->Cell(40, 6, "Ampere/s: ", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "C");
	$pdf->Cell(35, 6, "Terminal Seal No.: ", 0, 0, "R");
	$pdf->Cell(23, 6, "", 0, 0, "C");
	$pdf->Cell(33, 6, "Feeder Line:", 0, 0, "R");
	$pdf->Cell(30, 6, "", 0, 0, "R");
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
	$pdf->Cell(65, 5, "ANALYN G. PALACIOS", 0, 0, "L");
	$pdf->Cell(35, 5, "MA. AILEEN BALAGON", 0, 0, "L");
	$pdf->Ln();
	$pdf->SetX(20);
	$pdf->Cell(65, 5, "Account Officer", 0, 0, "L");
	$pdf->Cell(65, 5, "Branch Supervisor", 0, 0, "L");
	$pdf->Cell(65, 5, "URD, Billing & Settlement Head", 0, 0, "L");
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
	$pdf->Cell(65, 6, "To be filled-up by IT", 0, 0, "L");
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
	$pdf->Cell(100, 6, "DEOFELYN M. GOMEZ", 0, 0, "L");
	$pdf->Cell(65, 6, iconv('UTF-8', 'windows-1252', "ANDREW CHAMBERLAIN A. ZUÑIGA VII"), 0, 0, "L");
	$pdf->Ln(5);
	$pdf->SetX(18);
	$pdf->Cell(100, 6, "DATA ANALYST/URD-DMG", 0, 0, "L");
	$pdf->Cell(65, 6, "URD-DMG HEAD", 0, 0, "L");
	$pdf->Ln(12);
	$pdf->SetFont('Arial','I',9);
	$pdf->Cell(100, 5, "Accomplish in 3 copies for the following:", 0, 0);
	$pdf->Ln();
	$pdf->Cell(100, 5, "Original Copy - Branch; 2nd copy - URD; 3rd copy - Finance, A/R", 0, 0);

	
// $pdf->SetY(40);
// $pdf->SetX(124);
// $pdf->SetFont('Arial','B',14);
// $pdf->Cell(39,10,$year.' Payment Form',0);
// $pdf->SetFont('Arial','B',10);
// $pdf->Ln(8);
// $pdf->SetX(25);
// $pdf->Cell(39,10,'Date',0);
// $pdf->Cell(10,10,':',0);
// $pdf->Cell(39,10,$current_year,0);
// $pdf->Ln(8);
// $pdf->SetX(25);
// $pdf->Cell(39,10,'Account Number',0);
// $pdf->Cell(10,10,':',0);
// $pdf->Cell(39,10,$account,0);
// $pdf->Ln(8);
// $pdf->SetX(25);
// $pdf->Cell(39,10,'Name ',0);
// $pdf->Cell(10,10,':',0);
// $pdf->Cell(39,8,$name,0);
// $pdf->Ln(8);
// $pdf->SetX(25);
// $pdf->Cell(39,10,'Address',0);
// $pdf->Cell(10,10,':',0);
// $pdf->Cell(39,8,$address,0);
// $pdf->SetFont('Arial','B',8);
// $pdf->SetXY(25,84);
// $pdf->Cell(23,7,'Month',1,0,'C');
// $pdf->Cell(23,7,'Invoice',1,0,'C');
// $pdf->Cell(23,7,'Kwh',1,0,'C');
// $pdf->Cell(23,7,'Amount',1,0,'C');
// $pdf->Cell(23,7,'Payment',1,0,'C');
// $pdf->Cell(23,7,'Status',1,0,'C');
// $pdf->Cell(23,7,'Adjusted',1,0,'C');
// $pdf->Cell(23,7,'Dcm',1,0,'C');
// $pdf->Cell(23,7,'Remarks',1,0,'C');
// $pdf->SetFont('Arial','B',8);

// for($b=1;$b<=12;$b++){


		// $pdf->Ln(7);	
		// $pdf->SetX(25);
		// $pdf->Cell(23,7,$sales_month[$b],1,0,'C');
		// $pdf->Cell(23,7,$sales_invoice[$b],1,0,'C');
		// $pdf->Cell(23,7,$sales_kwh[$b],1,0,'C');
		// $pdf->Cell(23,7,$sales_amount[$b],1,0,'C');
		// $pdf->Cell(23,7,$sales_payment[$b],1,0,'C');
		// $pdf->Cell(23,7, $sales_status[$b],1,0,'C');
		// $pdf->Cell(23,7,$sales_adjusted[$b],1,0,'C');
		// $pdf->Cell(23,7,$sales_dcm[$b],1,0,'C');		
		// $pdf->Cell(23,7,$sales_LedgerRemarks[$b],1,0,'C');		
				
	
// }

// $pdf->Ln(7);
// $pdf->SetX(25);	
// $pdf->Cell(52,7,'TOTAL SALES',1,0,'C');
// $pdf->Cell(52,7,$s_total_sales,1,0,'C');
// $pdf->Cell(52,7,'TOTAL PAYMENTS',1,0,'C');
// $pdf->Cell(51,7,$s_total_payment,1,0,'C');


// $pdf->Ln(7);	
// $pdf->SetX(25);
// $pdf->Cell(104,7,'REMAINING BALANCE',1,0,'C');

// $pdf->Cell(103,7,$s_total_balance,1,0,'C');


ob_end_clean();
$pdf->Output();
?>