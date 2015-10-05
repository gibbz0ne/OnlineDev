<?php
	include "../../class/connect.class.php";
	
	$con = new getConnection();
	$db = $con->PDO();
	
	$stats = array();
	$query1 = $db->query("SELECT *FROM tbl_transactions WHERE status = '1' AND action = '0'");
	$query2 = $db->query("SELECT *FROM tbl_transactions WHERE status = '2' AND action = '0'");
	$query3 = $db->query("SELECT *FROM tbl_transactions WHERE status = '3' AND action = '0'");
	$query4 = $db->query("SELECT *FROM tbl_transactions WHERE status = '4' AND action = '0'");
	$query5 = $db->query("SELECT *FROM tbl_transactions WHERE status = '5' AND action = '0'");
	$query6 = $db->query("SELECT *FROM tbl_transactions WHERE status = '6' AND action = '0'");
	$query7 = $db->query("SELECT *FROM tbl_transactions WHERE status = '7' AND action = '0'");
	$query8 = $db->query("SELECT *FROM tbl_transactions WHERE status = '8' AND action = '0'");
	
	$stats =  array("stat1" => $query1->rowCount(),
							"stat2" => $query2->rowCount(),
							"stat3" => $query3->rowCount(),
							"stat4" => $query4->rowCount(),
							"stat5" => $query5->rowCount(),
							"stat6" => $query6->rowCount(),
							"stat7" => $query7->rowCount(),
							"stat8" => $query8->rowCount());
							
	echo json_encode($stats);
?>