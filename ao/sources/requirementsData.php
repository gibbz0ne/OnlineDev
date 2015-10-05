<?php
	include "../class/connect.class.php";
	$id = $_POST["id"];
	$query = $db->query("SELECT * from bert_requirement_list 
							JOIN bert_requirements using(reqId) 
							JOIN bert_services using (serviceId) WHERE serviceId = '$id' ORDER BY serviceId");
							
	foreach($query as $row){
		$list[] = array("ServiceId" => $row["serviceId"],
						"Description" => $row["req_description"]);
	}
	
	echo json_encode($list);
?>