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
  
$get_year = mysql_query("SELECT DISTINCT(year) FROM record_holder")or die("SQL Error 1: " . mysql_error());


while($d_year = mysql_fetch_array($get_year , MYSQL_ASSOC)){
	
	//$record_year=explode("_",$d_year['Data']);
	$hold = $d_year['year'];
	$year[] = array(		
				'year_name' => $hold,
				'year_value' =>  $hold,
				

	);		
	
	
}



echo json_encode($year);	



?>