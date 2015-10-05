<?PHP
	include "../../class/connect.class.php";
	$conn = new getConnection();
	$db = $conn->PDO();
	
	$res = $db->query("select * from tbl_type order by typeId");
	
	$out = '<ul>';
	
	foreach($res as $row) {
		if($row["typeIcon"] != null) {
			$out .= '<li id="con-'.$row["typeId"].'" onclick="conSelected(this.id)"><img src="../assets/images/icons/icol16/src/'.$row["typeIcon"].'"> '.$row["typeDesc"].'</li>';
		}
	}
	
	$out .= '</ul>';
	
	echo $out;
?>