<?PHP
	include "../../class/connect.class.php";
	$con = new getConnection();
	$db = $con->PDO();

	$cid = $_GET["cid"];

	$q = $db->query("SELECT * FROM tbl_consumer_photos where cid = $cid");
	$r = $q->fetch(PDO::FETCH_ASSOC);

	header("Content-type: " . $r["imageType"]);
	echo $r["imageData"];
?>