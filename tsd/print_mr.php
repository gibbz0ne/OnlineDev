<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include "../class/connect.class.php";
require('../assets/fpdf/fpdf.php');

$con = new getConnection();
$db = $con->PDO();
$userId = $_SESSION["userId"];
$mrNo = $_GET["ref"];
$acct1 = $acct2 = $acct3 = $acct4 = $acct5 = $pos1 = $pos2 = $pos3 = $pos4 = $pos5 = "";
$pdf=new FPDF('P','mm','Letter');
$pdf->SetFont('Arial','B',9);
$pdf->AddPage();


//$pdf->Image('../assets/images/logo.jpg',30,15,25);
if(isset($_GET["ref"])){
	foreach($db->query("SELECT *FROM tbl_mr WHERE mrNo = '$mrNo'") as $row)
		$d = explode(" ", $row["mrDate"]);
		$date = date("M, d Y", strtotime($d[0]));
		$purpose = $row["mrPurpose"];

	$pdf->Ln();
	$pdf->SetX(70);
	$pdf->Cell(39,10,'ALBAY POWER AND ENERGY CORPORATION', 0);
	$pdf->SetFont('Arial','',9);
	$pdf->Ln(4);
	$pdf->SetX(79);
	$pdf->Cell(39,10,'W. Vinzon St., Albay Dist., Leg. City',0);
	$pdf->Ln(5);
	$pdf->SetX(55);
	$pdf->Ln(6);
	$pdf->SetX(78);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(39,10,'MATERIAL REQUISITION',0);
	$pdf->Ln();
	$pdf->SetX(88);
	$pdf->SetFont('Arial', "",9);
	$pdf->Cell(39, 0,'(For On Stock Materials)',0);
	$pdf->Ln(6);
	$pdf->SetX(20);
	$pdf->SetFont('Arial', "I", 7);
	$pdf->Cell(140, 10, $_GET["ref"], 0);
	$pdf->SetFont('Arial', "", 7);
	$pdf->Cell(10, 10, "Date: ".$date, 0);
	$pdf->SetX(10);
	$pdf->SetFont('Arial','',8);
	$pdf->Ln(8);
	$pdf->SetX(10);
	$pdf->Cell(10, 5, "Sir / Madam: ", 0);
	$pdf->Ln(4);
	$pdf->SetX(18);
	$pdf->Cell(10, 5, "Please furnish the following materials / supplies for the purpose stated below.", 0);
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B', 7);
	$pdf->Cell(11, 5, "ITEM", 1, 0, "C");
	$pdf->Cell(30, 5, "STOCK CODE #", 1, 0, "C");
	$pdf->Cell(122, 5, "DESCRIPTION", 1, 0, "C");
	$pdf->Cell(15, 5, "QTY.", 1, 0, "C");
	$pdf->Cell(20, 5, "UNIT", 1, 0, "C");
	$pdf->SetFont('Arial','',8);
	$pdf->Ln();
	$pdf->SetX(10);

	$totalRows = 36;
	$sumRows = 2;
	$query =$db->query("SELECT *FROM tbl_mr join tbl_mr_content using(mrNo) join tbl_materials using(entry_id) WHERE mrNo = '$mrNo'");
	$pdf->SetFont('Arial','',6);
	if($query->rowCount() > 0){
		$ctr = 1;
		$sumRows += $query->rowCount();
		foreach($query as $row){
			$pdf->Cell(11, 4, $ctr, 1, 0, "C");
			$pdf->Cell(30, 4, $row["materialCode"], 1, 0, "C");
			$pdf->Cell(122, 4, $row["materialDesc"], 1, 0, "C");
			$pdf->Cell(15, 4, $row["mrQuantity"], 1, 0, "C");
			$pdf->Cell(20, 4, $row["unit"], 1, 0, "C");
			$pdf->Ln();
			$ctr++;
		}
	}

	$pdf->SetFont('Arial','',6);
	$query2 =$db->query("SELECT * FROM tbl_mr_wo a 
							LEFT OUTER JOIN tbl_work_order b ON a.wo = b.wo 
							LEFT OUTER JOIN consumers c ON b.cid = c.Entry_Number 
							LEFT OUTER JOIN tbl_applications d ON c.Entry_Number = d.Entry_Number
							WHERE a.mrNo = '$mrNo'");

	if($query2->rowCount() > 0){
		$ctr = 1;
		$sumRows += $query2->rowCount();
		foreach($query2 as $row){
			
			$pdf->Cell(11, 4, $ctr, 1, 0, "R");
			$pdf->Cell(0, 4, $pdf->Cell(30, 4, $row["wo"], "B", 0, "C")."
			".$pdf->Cell(20, 4, "SO#".$row["appSOnum"], "B", 0, "C")."
			".$pdf->Cell(45, 4, $row["AccountName"], "B", 0, "C")."
			".$pdf->Cell(50, 4, $row["Address"], "B", 0, "C")."
			".$pdf->Cell(25, 4, $row["AccountNumber"], "B", 0, "C")."
			".$pdf->Cell(17, 4, $sumRows, "RB", 0, "C"), 0, 0);
			$pdf->Ln();
			$ctr++;
		}
	}
	
	$pdf->Cell(11, 4, "", 1, 0, "R");
	$pdf->Cell(187, 4, "***NOTHING FOLLOWS***", 1, 0, "C");
	$pdf->Ln();
	
	while($sumRows < $totalRows){
		$pdf->Cell(11, 4, "", 1, 0, "C");
		$pdf->Cell(30, 4, "", 1, 0, "C");
		$pdf->Cell(122, 4, "", 1, 0, "C");
		$pdf->Cell(15, 4, "", 1, 0, "C");
		$pdf->Cell(20, 4, "", 1, 0, "C");
		$pdf->Ln();
		$sumRows++;
	}
	
	$query = $db->query("SELECT *FROM tbl_signatories JOIN tbl_accounts USING (accountId) WHERE userId = '$userId' AND aGroup = 'MR'");
	
	if($query->rowCount() > 0){
		$row = $query->fetchAll(PDO::FETCH_ASSOC);
		$acct1 = $row[0]["aFname"]." ".$row[0]["aMname"][0].". ".$row[0]["aLname"];
		$acct2 = $row[1]["aFname"]." ".$row[1]["aMname"][0].". ".$row[1]["aLname"];
		$acct3 = $row[2]["aFname"]." ".$row[2]["aMname"][0].". ".$row[2]["aLname"];
		$acct4 = $row[3]["aFname"]." ".$row[3]["aMname"][0].". ".$row[3]["aLname"];
		$acct5 = $row[4]["aFname"]." ".$row[4]["aMname"][0].". ".$row[4]["aLname"];
		
		$pos1 = $row[0]["aPosition"];
		$pos2 = $row[1]["aPosition"];
		$pos3 = $row[2]["aPosition"];
		$pos4 = $row[3]["aPosition"];
		$pos5 = $row[4]["aPosition"];
	}
	
	$pdf->SetFont("Arial", "B", 7);
	$pdf->Cell(20, 10, "PURPOSE:", "L", 0, "L");
	$pdf->SetFont("Arial", "", 7);
	$pdf->Cell(178, 10, strtoupper($purpose), "R", 0, "L");
	$pdf->Ln();
	$pdf->Cell(198, 5, "I HEREBY CERTIFY that the supplies requisitioned above are necessary and will be used solely for the purpose stated.", "LBR", 0);
	$pdf->Ln();
	$pdf->Cell(33, 10, "CHARGE WORK ORDER #", "L", 0, "L");
	$pdf->Cell(165, 10, "", "R", 0, "L");
	$pdf->Ln();
	$pdf->Cell(40, 10, "Requisition by:", "L", 0, "L");
	$pdf->Cell(40, 10, "Checked by:", 0, 0, "L");
	$pdf->Cell(40, 10, "Recommening Approval:", 0, 0, "L");
	$pdf->Cell(40, 10, "Verified by:", 0, 0, "L");
	$pdf->Cell(38, 10, "Approved By:", "R", 0, "L");
	$pdf->Ln();
	$pdf->Cell(40, 5, $acct1, "L", 0, "C");
	$pdf->Cell(40, 5, $acct2, 0, 0, "C");
	$pdf->Cell(40, 5, $acct3, 0, 0, "C");
	$pdf->Cell(40, 5, $acct4, 0, 0, "C");
	$pdf->Cell(38, 5, $acct5, "R", 0, "C");
	$pdf->Ln();
	$pdf->Cell(40, 4, $pos1, "LB", 0, "C");
	$pdf->Cell(40, 4, $pos2, "B", 0, "C");
	$pdf->Cell(40, 4, $pos3, "B", 0, "C");
	$pdf->Cell(40, 4, $pos4, "B", 0, "C");
	$pdf->Cell(38, 4, $pos5, "RB", 0, "C");
}

ob_end_clean();
$pdf->Output();
?>