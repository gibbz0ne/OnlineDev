<?php
error_reporting(E_ALL ^ E_DEPRECATED);

# FileName="connect.php"
$hostname = "10.33.187.140";
$database = "dummy_apec";
$username = "admin";
$password = "admin#123";

$connect = mysql_connect($hostname, $username, $password)
or die('Could not connect: ' . mysql_error());
  //select database
mysql_select_db($database, $connect);
mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER SET utf8");
mysql_query("SET COLLATION_CONNECTION = 'utf8_unicode_ci'");

require('../assets/fpdf/fpdf.php');

$account = $_GET['ref'];
$year = $_GET['year'];

//$account = '100000629651915'; 
//$pdf = new FPDF('P','mm',array(100,150));

$month =Array();
$sales_invoice =Array();
$sales_month =Array();
$sales_reading =Array();
$sales_kwh =Array();
$sales_amount =Array();
$sales_payment =Array();
$sales_status =Array();
$sales_adjusted =Array();
$sales_dcm =Array();
$sales_LedgerRemarks =Array();

$current_year = date("M-d-Y");
$month[1] = "January";
$month[2] = "February";
$month[3] = "March";
$month[4] = "April";
$month[5] = "May";
$month[6] = "June";
$month[7] = "July";
$month[8] = "August";
$month[9] = "September";
$month[10] = "October";
$month[11] = "November";
$month[12] = "December";




$pdf=new FPDF('L','mm','Letter');
$pdf->SetFont('Arial','B',10);
$pdf->AddPage();

$get_info = mysql_query("SELECT * FROM consumers WHERE AccountNumber=$account")or die("SQL Error 1: " . mysql_error());
while($info = mysql_fetch_array($get_info)){
	$name = $info['AccountName'];	
	$address = $info['Address'];	
	$branch = $info['Branch'];	
}

$table = "sales_".$branch."_".$year;
$ledger = "ledger_".$branch."_".$year;
for($i=1;$i<=12;$i++){
	
	
$m = $month[$i];
	
$SQLQuery = "SELECT a.BillMonth ,a.PreviousReading ,a.PreviousReadingDate,a.PresentReading,a.PresentReadingDate, a.Invoice , a.Kwhu , a.AmountDue ,a.IsDCM , b.TotalAmount, b.Status,b.Adjusted,b.Dcm,b.LedgerRemarks FROM $table a LEFT JOIN $ledger b ON a.AcctNo = b.AccountNumber AND a.BillMonth = b.BillMonth AND a.BillYear = b.BillYear WHERE a.AcctNo = '$account' AND a.BillYear = '$year' AND a.BillMonth = '$i' ORDER BY (a.BillMonth+0) ASC";


$query = mysql_query($SQLQuery)or die("SQL Error 1: " . mysql_error());
$count_query = mysql_num_rows($query);
if($count_query==0){
		$sales_payment[$i]='-';	
		$sales_status[$i]='-';
		$sales_adjusted[$i]='-';	
		$sales_dcm[$i]='-';	
		$sales_LedgerRemarks[$i]='-';
		$sales_month[$i]=$month[$i];
		$sales_invoice[$i]='-';	
		$sales_reading[$i]='-';	
		$sales_kwh[$i]='-';	
		$sales_amount[$i]='-';	
	}else{
		
		
	while($row=mysql_fetch_array($query, MYSQL_ASSOC)){
	
	
	
	
		
						$sales_payment[$i]=$row['TotalAmount'];
						if($sales_payment[$i]==''){
						$sales_payment[$i]='-';	
						}
						$sales_status[$i]=$row['Status'];
						
						if($sales_status[$i]==''){
						$sales_status[$i]='-';	
						}
						
						$sales_adjusted[$i]=$row['Adjusted'];
						if($sales_adjusted[$i]==''){
						$sales_adjusted[$i]='-';	
						}
						
						$sales_dcm[$i]=$row['Dcm'];
						if($sales_dcm[$i]==''){
						$sales_dcm[$i]='-';	
						}
						
						$sales_LedgerRemarks[$i]=$row['LedgerRemarks'];	
						if($sales_LedgerRemarks[$i]=='' || $sales_LedgerRemarks[$i]==' '){
						$sales_LedgerRemarks[$i]='-';	
						}
						
						
						$sales_month[$i] = $month[$i];
						
						if($sales_month[$i]==''){
						$sales_month[$i]='-';	
						}
						$sales_invoice[$i] =$row['Invoice'];
						
						if($sales_invoice[$i]==''){
						$sales_invoice[$i]='-';	
						}
						$sales_reading[$i] = $row['PresentReading'];
						if($sales_reading[$i]==''){
						$sales_reading[$i]='-';	
						}
						$sales_kwh[$i] = $row['Kwhu'];
						if($sales_kwh[$i]==''){
						$sales_kwh[$i]='-';	
						}
						$sales_amount[$i]= $row['AmountDue'];
						if($sales_amount[$i]==''){
						$sales_amount[$i]='-';	
						}	
		
	}
	
						
}	
	
			
		
}
$total_payment = 0;
$total_sales = 0;

for($b=1;$b<=12;$b++){
	
		$total_sales += $sales_amount[$b];
		$tomatch_status =strtoupper($sales_status[$b]);
		if (strpos($tomatch_status, 'PAID') !==false){
				
				$total_payment +=$sales_amount[$b];
		}else if(strpos($tomatch_status, 'REMAINING') !==false){
				//$balance =$sales_amount[$b]-$sales_payment[$b];
				$total_payment +=$sales_payment[$b];
		}else if(strpos($tomatch_status, 'NO PAY') !==false){
				$total_payment +=0;
		}else{
			
				$total_payment +=0;
		}
	
}
$s_total_payment =  number_format((float)$total_payment, 2, '.', ',');
$s_total_sales = number_format((float)$total_sales, 2, '.', ',');
$total_balance = $total_sales - $total_payment;
$s_total_balance = number_format((float)$total_balance, 2, '.', ',');

$pdf->Image('../assets/images/logo.jpg',25,10,30);

$pdf->SetX(105);
$pdf->Cell(39,10,'ALBAY POWER AND ENERGY CORPORATION',0);
$pdf->Ln(8);
$pdf->SetX(114);
$pdf->Cell(39,10,'W. Vinzon St., Albay Dist., Leg. City',0);
$pdf->Ln(8);
$pdf->SetX(111);
$pdf->Cell(39,10,'VAT Reg. TIN 008-661-918-000, Leg. City',0);
$pdf->SetY(40);
$pdf->SetX(124);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(39,10,$year.' Payment Form',0);
$pdf->SetFont('Arial','B',10);
$pdf->Ln(8);
$pdf->SetX(25);
$pdf->Cell(39,10,'Date',0);
$pdf->Cell(10,10,':',0);
$pdf->Cell(39,10,$current_year,0);
$pdf->Ln(8);
$pdf->SetX(25);
$pdf->Cell(39,10,'Account Number',0);
$pdf->Cell(10,10,':',0);
$pdf->Cell(39,10,$account,0);
$pdf->Ln(8);
$pdf->SetX(25);
$pdf->Cell(39,10,'Name ',0);
$pdf->Cell(10,10,':',0);
$pdf->Cell(39,8,$name,0);
$pdf->Ln(8);
$pdf->SetX(25);
$pdf->Cell(39,10,'Address',0);
$pdf->Cell(10,10,':',0);
$pdf->Cell(39,8,$address,0);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(25,84);
$pdf->Cell(23,7,'Month',1,0,'C');
$pdf->Cell(23,7,'Invoice',1,0,'C');
$pdf->Cell(23,7,'Kwh',1,0,'C');
$pdf->Cell(23,7,'Amount',1,0,'C');
$pdf->Cell(23,7,'Payment',1,0,'C');
$pdf->Cell(23,7,'Status',1,0,'C');
$pdf->Cell(23,7,'Adjusted',1,0,'C');
$pdf->Cell(23,7,'Dcm',1,0,'C');
$pdf->Cell(23,7,'Remarks',1,0,'C');
$pdf->SetFont('Arial','B',8);

for($b=1;$b<=12;$b++){


		$pdf->Ln(7);	
		$pdf->SetX(25);
		$pdf->Cell(23,7,$sales_month[$b],1,0,'C');
		$pdf->Cell(23,7,$sales_invoice[$b],1,0,'C');
		$pdf->Cell(23,7,$sales_kwh[$b],1,0,'C');
		$pdf->Cell(23,7,$sales_amount[$b],1,0,'C');
		$pdf->Cell(23,7,$sales_payment[$b],1,0,'C');
		$pdf->Cell(23,7, $sales_status[$b],1,0,'C');
		$pdf->Cell(23,7,$sales_adjusted[$b],1,0,'C');
		$pdf->Cell(23,7,$sales_dcm[$b],1,0,'C');		
		$pdf->Cell(23,7,$sales_LedgerRemarks[$b],1,0,'C');		
				
	
}

$pdf->Ln(7);
$pdf->SetX(25);	
$pdf->Cell(52,7,'TOTAL SALES',1,0,'C');
$pdf->Cell(52,7,$s_total_sales,1,0,'C');
$pdf->Cell(52,7,'TOTAL PAYMENTS',1,0,'C');
$pdf->Cell(51,7,$s_total_payment,1,0,'C');


$pdf->Ln(7);	
$pdf->SetX(25);
$pdf->Cell(104,7,'REMAINING BALANCE',1,0,'C');

$pdf->Cell(103,7,$s_total_balance,1,0,'C');


ob_end_clean();
$pdf->Output();


?>