<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include "../class/connect.class.php";
require('../assets/fpdf/fpdf.php');

$con = new getConnection();
$db = $con->PDO();

$ao = $_SESSION["name"];
$req_date = $consumer = $address = $contact = $branch = $type = $so = "";
$i1 = $i2 = $i3 = $i4 = $i5 = $i6 = $i7 = $sType = 0;
$appId = $_GET["ref"];
$assignedId = "";
$query = $db->query("SELECT *FROM tbl_applications WHERE appCAR IS NOT NULL ORDER BY appCAR DESC LIMIT 1");
$query2 = $db->query("SELECT *FROM tbl_consumers JOIN tbl_address USING (cid) JOIN tbl_applications USING (cid) JOIN tbl_barangay USING(brgyId) WHERE tbl_applications.appId = '$appId'");

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
		$fname = $row["fname"];
		$mname = $row["mname"][0].".";
		$lname = $row["lname"].",";
		if($row["mname"] == ""){
			$mname = "";
		} if($row["lname"] == ""){
			$lname = "";
		}
		
		if($row["serviceId"] == 1)
			$service = "NC";
		
		$d = explode(" ", $row["dateApplied"]);
		
		$date = DateTime::createFromFormat("Y-m-d", $d[0]);
		$date1 = DateTime::createFromFormat("Y-m-d", date("Y-m-d"));
		$dateToday = $date1->format("F d, Y");
		$req_date = $date->format("F d, Y");
		$consumer = $lname." ".$fname." ".$mname;
		$address = $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row2["munDesc"];
		$address = str_replace("ñ", "Ñ", $address);
		$address = iconv('UTF-8', 'windows-1252', $address);
		$type = $service;
		$consumerType = $row["consumerType"];
		$so = $row["SO"];
		if($type == "NC")
			$type = "New Connection";
		// echo $req_date;
	}
}

// $query3 = $db->query("SELECT *FROM so_a WHERE so_a = '$so' AND AppId = '$appId'");

// if($query3->rowCount() > 0){
	// foreach($query3 as $row){
		// $i1 = $row["aConnect"];
		// $i2 = $row["aInstall"];
		// $i3 = $row["aSeal"];
		// $i4 = $row["aRecord"];
		// $i5 = $row["aMount"];
		// $i6 = $row["aOthers"];
		// $sType = $row["AType"];
		// $i7 = $row["aDetails"];
	// }
// }
// echo $address;
$pdf=new FPDF('P','mm','Letter');
$pdf->SetFont('Arial','',10);
$pdf->AddPage();


// $pdf->Image('../assets/images/logo.jpg',30,15,25);

	$pdf->Ln(20);
	$pdf->SetX(130);
	$pdf->Cell(20, 10, $dateToday, 0, 0, "L");
	$pdf->Ln(18);
	$pdf->SetX(25);
	$pdf->Cell(0, 5, $consumer, 0, 0);
	$pdf->Ln();
	$pdf->SetX(25);
	$pdf->Cell(0, 5, $address." ALBAY", 0, 0);
	$pdf->Ln(11);
	if($sType == "RESIDENTIAL"){
		$pdf->SetX(15);
		$pdf->Cell(0, 5, "X", 0, 0);
	} else if($sType == "COMMERCIAL - HV"){
		$pdf->SetX(41);
		$pdf->Cell(0, 5, "X                              ___", 0, 0);
	} else if($sType == "COMMERCIAL - LV"){
		$pdf->SetX(41);
		$pdf->Cell(0, 5, "X                      ___", 0, 0);
	} else if($sType == "INSTITUTIONAL - LV"){
		$pdf->SetX(86);
		$pdf->Cell(0, 5, "X                     ___", 0, 0);
	} else if($sType == "INSTITUTIONAL - HV"){
		$pdf->SetX(86);
		$pdf->Cell(0, 5, "X                           ___1", 0, 0);
	}
	$pdf->Ln(11);
	if($i1 == 1){
		$pdf->SetX(15);
		$pdf->Cell(0, 4, "X", 0, 0);
	}
	if($i4 == 1){
		$pdf->SetX(85);
		$pdf->Cell(0, 4, "X", 0, 0);
	}
	$pdf->Ln();
	if($i2 == 1){
		$pdf->SetX(15);
		$pdf->Cell(0, 4, "X", 0, 0);
	}
	if($i5 == 1){
		$pdf->SetX(85);
		$pdf->Cell(0, 4, "X", 0, 0);
	}
	$pdf->Ln();
	if($i3 == 1){
		$pdf->SetX(15);
		$pdf->Cell(0, 4, "X", 0, 0);
	}
	if($i6 == 1){
		$pdf->SetX(85);
		$pdf->Cell(0, 4, "X        ".$i7, 0, 0);
	}
	
	


	
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