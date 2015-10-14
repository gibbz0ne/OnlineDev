<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include "../class/connect.class.php";
require('../assets/fpdf/fpdf.php');

$con = new getConnection();
$db = $con->PDO();

$mrNo = $_GET["ref"];
$pdf=new FPDF('P','mm','Letter');
$pdf->SetFont('Arial','B',10);
$pdf->AddPage();


//$pdf->Image('../assets/images/logo.jpg',30,15,25);
if(isset($_GET["ref"])){
	foreach($db->query("SELECT *FROM tbl_mr WHERE mrNo = '$mrNo'") as $row)
		$d = explode(" ", $row["mrDate"]);
		$date = date("M, d Y", strtotime($d[0]));
		$purpose = $row["mrPurpose"];

	$pdf->Ln(10);
	$pdf->SetX(70);
	$pdf->Cell(39,10,'ALBAY POWER AND ENERGY CORPORATION', 0);
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(5);
	$pdf->SetX(79);
	$pdf->Cell(39,10,'W. Vinzon St., Albay Dist., Leg. City',0);
	$pdf->Ln(5);
	$pdf->SetX(55);
	$pdf->Cell(39,10,'Tel. Nos. 481-1584; 481-5555; 487-4625; 485-2252;  Fax No. 820-2668',0);
	$pdf->Ln(12);
	$pdf->SetX(78);
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(39,10,'MATERIAL REQUISITION',0);
	$pdf->Ln();
	$pdf->SetX(88);
	$pdf->SetFont('Arial', "",9);
	$pdf->Cell(39, 0,'(For On Stock Materials)',0);
	$pdf->Ln(6);
	$pdf->SetX(20);
	$pdf->SetFont('Arial', "I", 9);
	$pdf->Cell(140, 10, $_GET["ref"], 0);
	$pdf->SetFont('Arial', "", 10);
	$pdf->Cell(10, 10, "Date: ".$date, 0);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','',10);
	$pdf->Ln(8);
	$pdf->SetX(10);
	$pdf->Cell(10, 5, "Sir / Madam: ", 0);
	$pdf->Ln(4);
	$pdf->SetX(18);
	$pdf->Cell(10, 5, "Please furnish the following materials / supplies for the purpose stated below.", 0);
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(11, 7, "ITEM", 1, 0, "C");
	$pdf->Cell(30, 7, "STOCK CODE #", 1, 0, "C");
	$pdf->Cell(122, 7, "DESCRIPTION", 1, 0, "C");
	$pdf->Cell(15, 7, "QTY.", 1, 0, "C");
	$pdf->Cell(20, 7, "UNIT", 1, 0, "C");
	$pdf->SetFont('Arial','',8);
	$pdf->Ln(7);
	$pdf->SetX(10);

	$totalRows = 10;
	$query =$db->query("SELECT *FROM tbl_mr join tbl_mr_content using(mrNo) join tbl_materials using(entry_id) WHERE mrNo = '$mrNo'");
	$pdf->SetFont('Arial','',8);
	if($query->rowCount() > 0){
		$ctr = 1;
		if($totalRows>$query->rowCount()){
			foreach($query as $row){
				$pdf->Cell(11, 5, $ctr, 1, 0, "C");
				$pdf->Cell(30, 5, $row["materialCode"], 1, 0, "C");
				$pdf->Cell(122, 5, $row["materialDesc"], 1, 0, "C");
				$pdf->Cell(15, 5, $row["mrQuantity"], 1, 0, "C");
				$pdf->Cell(20, 5, $row["unit"], 1, 0, "C");
				$pdf->Ln(5);

				$ctr++;
			}

			for($i=1; $i<$totalRows-$query->rowCount(); $i++){
				$str = "";
				if($i == 1)
					$str = "*** NOTHING FOLLOWS ***";

				$pdf->Cell(11, 5, "", 1, 0, "C");
				$pdf->Cell(30, 5, "", 1, 0, "C");
				$pdf->Cell(122, 5, $str, 1, 0, "C");
				$pdf->Cell(15, 5, "", 1, 0, "C");
				$pdf->Cell(20, 5, "", 1, 0, "C");
				$pdf->Ln(5);
			}
		} else{
			foreach($query as $row){
				$pdf->Cell(11, 5, $ctr, 1, 0, "C");
				$pdf->Cell(30, 5, $row["materialCode"], 1, 0, "C");
				$pdf->Cell(122, 5, $row["materialDesc"], 1, 0, "C");
				$pdf->Cell(15, 5, $row["mrQuantity"], 1, 0, "C");
				$pdf->Cell(20, 5, $row["unit"], 1, 0, "C");
				$pdf->Ln(5);

				$ctr++;
			}

			$pdf->Cell(11, 5, "", 1, 0, "C");
			$pdf->Cell(30, 5, "", 1, 0, "C");
			$pdf->Cell(122, 5, "*** NOTHING FOLLOWS ***", 1, 0, "C");
			$pdf->Cell(15, 5, "", 1, 0, "C");
			$pdf->Cell(20, 5, "", 1, 0, "C");
			$pdf->Ln(5);
		}
	}

	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(7, 7, "#", 1, 0, "C");
	$pdf->Cell(32, 7, "WO #", 1, 0, "C");
	$pdf->Cell(15, 7, "SO #", 1, 0, "C");
	$pdf->Cell(55, 7, "CONSUMER", 1, 0, "C");
	$pdf->Cell(60, 7, "ADDRESS", 1, 0, "C");
	$pdf->Cell(29, 7, "ACCOUNT #", 1, 0, "C");
	$pdf->Ln(7);
	$pdf->SetFont('Arial','',8);
	$query2 =$db->query("SELECT * FROM tbl_mr_wo a 
							LEFT OUTER JOIN tbl_work_order b ON a.wo = b.wo 
							LEFT OUTER JOIN consumers c ON b.cid = c.Entry_Number 
							LEFT OUTER JOIN tbl_applications d ON c.Entry_Number = d.Entry_Number
							WHERE a.mrNo = '$mrNo'");

	if($query2->rowCount() > 0){
		$ctr = 1;
		if($totalRows>$query2->rowCount()){
			foreach($query2 as $row){
				
				$pdf->Cell(7, 5, $ctr, 1, 0, "C");
				$pdf->Cell(32, 5, $row["wo"], 1, 0, "C");
				$pdf->Cell(15, 5, $row["appSOnum"], 1, 0, "C");
				$pdf->Cell(55, 5, $row["AccountName"], 1, 0, "C");
				$pdf->Cell(60, 5, $row["Address"], 1, 0, "C");
				$pdf->Cell(29, 5, $row["AccountNumber"], 1, 0, "C");
				$pdf->Ln(5);
				$ctr++;
			}

			for($i=1; $i<$totalRows-$query->rowCount(); $i++){
				$str = "";
				if($i == 1){
					$str = "*** NOTHING FOLLOWS ***";
					$pdf->Cell(198, 5, $str, 1, 0, "C");
					$pdf->Ln(5);
				} else{
					$pdf->Cell(7, 5, "", 1, 0, "C");
					$pdf->Cell(32, 5, "", 1, 0, "C");
					$pdf->Cell(15, 5, "", 1, 0, "C");
					$pdf->Cell(55, 5, "", 1, 0, "C");
					$pdf->Cell(60, 5, "", 1, 0, "C");
					$pdf->Cell(29, 5, "", 1, 0, "C");
					$pdf->Ln(5);
				}
			}
		}  else{
			foreach($query2 as $row){
				foreach($db->query("SELECT *FROM tbl_municipality WHERE munId = '".$row["munId"]."'") as $row2)
				foreach($db->query("SELECT *FROM tbl_applications WHERE cid = '".$row["cid"]."'") as $row3)
				$mname = $row["mname"];
				if($row["mname"] != "")
					$mname = $row["mname"][0].".";

				$pdf->Cell(7, 5, $ctr, 1, 0, "C");
				$pdf->Cell(32, 5, $row["wo"], 1, 0, "C");
				$pdf->Cell(15, 5, $row3["appSOnum"], 1, 0, "C");
				$pdf->Cell(55, 5, $row["fname"]." ".$mname." ".$row["lname"], 1, 0, "C");
				$pdf->Cell(60, 5, $row["address"]." ".$row["purok"]." ".$row["brgyName"]." ".$row2["munDesc"], 1, 0, "C");
				$pdf->Cell(29, 5, $row["acctNo"], 1, 0, "C");
				$pdf->Ln(5);
				$ctr++;
			}

			$pdf->Cell(198, 5, "*** NOTHING FOLLOWS ***", 1, 0, "C");
			$pdf->Ln(5);
		}
	}
	$pdf->Ln(6);
	$pdf->SetFont("Arial", "B", 12);
	$pdf->Cell(30, 10, "PURPOSE: ", 0, "C");
	$pdf->SetFont("Arial", "U", 11);
	$pdf->Cell(55, 10, strtoupper($purpose), 0, "C");
	
	$pdf->Ln(9);
	$pdf->SetFont("Arial", "", 11);
	$pdf->SetX(18);
	$pdf->MultiCell(180, 5, "I HEREBY CERTIFY that the supplies requisitioned above are necessary and will be used solely for the purpose stated.", 0);
}

ob_end_clean();
$pdf->Output();
?>