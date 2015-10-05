<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
/* $connect = mysql_connect($hostname, $username, $password)
  or die('Could not connect: ' . mysql_error());
  mysql_select_db($database, $connect);
  $bool = mysql_select_db($database, $connect);
  if ($bool === False){
  print "can't find $database";
  }
 */  
// $get_year = mysql_query("SELECT DISTINCT(Area) FROM branch_seperator")or die("SQL Error 1: " . mysql_error());


/* while($d_year = mysql_fetch_array($get_year , MYSQL_ASSOC)){
	
	//$record_year=explode("_",$d_year['Data']);
	$hold = $d_year['Area'];
	$year[] = array(		
				'area_name' => $hold,
				'area_value' =>  $hold,
				

	);		
	
	
} */

$get_year = $db->query("SELECT DISTINCT(area) FROM tbl_municipality ORDER BY area");

foreach($get_year as $row){
	$hold = $row['area'];
	$year[] = array('area_name' => $hold, 'area_value' =>  $hold);		
}

echo json_encode($year);	



?>