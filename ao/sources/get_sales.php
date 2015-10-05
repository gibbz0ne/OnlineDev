<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include('connect.php');
$connect = mysql_connect($hostname, $username, $password)
  or die('Could not connect: ' . mysql_error());
  //select database
  mysql_select_db($database, $connect);
  //Select The database
  $bool = mysql_select_db($database, $connect);
  if ($bool === False){
  print "can't find $database";
  }
  
//$account = '100000179091719'; 
$account = $_GET['Account'];
$year = $_GET['Year'];

$branch = $_GET['Branch'];

$branch = strtolower($branch);
$sales = "sales_".$branch."_".$year;
$ledger = "ledger_".$branch."_".$year;
$list = "";

$month =Array();
$current_year = date("Y");
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


$SQLQuery = "SELECT a.BillMonth ,a.PreviousReading ,a.PreviousReadingDate,a.PresentReading,a.PresentReadingDate, a.Invoice , a.Kwhu , a.AmountDue ,a.IsDCM , b.TotalAmount, b.Status,b.Adjusted,b.Dcm,b.LedgerRemarks FROM $sales a LEFT JOIN $ledger b ON a.AcctNo = b.AccountNumber AND a.BillMonth = b.BillMonth AND a.BillYear = b.BillYear WHERE a.AcctNo = '$account' AND a.BillYear = '$year' ORDER BY (a.BillMonth+0) ASC";
// echo $account;
$query = mysql_query($SQLQuery)or die(mysql_error());


while($row=mysql_fetch_array($query, MYSQL_ASSOC)){
	
	
	$sales_month = $month[$row['BillMonth']];
	$sales_invoice = $row['Invoice'];
	$sales_prev_reading_date= $row['PreviousReadingDate'];
	$sales_prev_reading= $row['PreviousReading'];
	$sales_reading_date= $row['PresentReadingDate'];
	$sales_reading= $row['PresentReading'];
	$sales_kwh= $row['Kwhu'];
	$sales_amount= $row['AmountDue'];
	$sales_payment= $row['TotalAmount'];
	$sales_status= $row['Status'];
	$sales_adjusted= $row['Adjusted'];
	$sales_dcm= $row['Dcm'];
	$sales_remarks= $row['LedgerRemarks'];
	$dcm_checker= $row['IsDCM'];
	
	$customers[] = array(		
				'sales_month' => $sales_month,
				'sales_invoice' =>  $sales_invoice,
				'sales_prev_reading_date' =>  $sales_prev_reading_date,
				'sales_prev_reading' =>  $sales_prev_reading,	
				'sales_reading_date' =>  $sales_reading_date,
				'sales_reading' =>  $sales_reading,
				'sales_kwh' =>  $sales_kwh,
				'sales_amount' =>$sales_amount,
				'sales_payment'=>$sales_payment,
				'sales_status' =>$sales_status,
				'sales_adjusted' =>$sales_adjusted,
				'sales_dcm' =>$sales_dcm,
				'sales_remarks' =>$sales_remarks,
				'dcm_checker' =>$dcm_checker
				);		
	$list = $customers;
}	

echo json_encode($list);	



?>