<?php
include "../../class/connect.class.php";
$con = new getConnection();
$db = $con->PDO();
$b = $_SESSION["branch"];

$car1 = "$b-".date("Ym")."-001";
$appId = $_POST["appId"];
$query = $db->query("SELECT *FROM tbl_applications WHERE appCAR IS NOT NULL AND appCAR like '%$b%' ORDER BY appCAR DESC limit 1");

if(isset($_POST["car"]) && $_POST["car"] != ""){
	$appId = $_POST["appId"];
	$car = $_POST["car"];
	
	$update = $db->prepare("UPDATE tbl_applications SET appCAR = ? WHERE appId = ?");
	$update->execute(array($car, $appId));
	
	echo "1";
} else{
	if($query->rowCount() > 0){
		foreach($query as $row){
			$car = $row["appCAR"];
			$d = explode("-", $car);
			if(date("mY") == $d[1]){
				$d[2]++;
				if($d[2] < 10){
					$car1 = $d[0]."-".$d[1]."-"."00".$d[2];
				}
				elseif($d[2] >= 10 && $d[2] <= 99){
					$car1 = $d[0]."-".$d[1]."-"."0".$d[2];
				}
				elseif($d[2] > 99){
					$car1 = $d[0]."-".$d[1]."-"."".$d[2];
				}
			}
		}
		$update = $db->prepare("UPDATE tbl_applications SET appCAR = ? WHERE appId = ?");
		$update->execute(array($car1, $appId));
		echo "1";
	}
	else{
		$update = $db->prepare("UPDATE tbl_applications SET appCAR = ? WHERE appId = ?");
		$update->execute(array($car1, $appId));
		echo "1";
	}
}

?>