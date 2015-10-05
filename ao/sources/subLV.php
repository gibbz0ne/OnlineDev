<?PHP
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$conID = $_POST["conID"];
	$out = array();
	
	$res = $db->query("select * from tbl_connection_sub where conId = $conID");
	
	foreach($res as $row) {
		$out[] = array(
					"subId"=>$row["subId"],
					"subDesc"=>$row["subDesc"],
				);
	}
	
	echo json_encode($out);
?>