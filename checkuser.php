<?php
error_reporting(E_ALL ^ E_DEPRECATED);
include "class/connect.class.php";

$con = new getConnection();
$db = $con->PDO();

// if(!isset($_SESSION['username'])){
// if(isset($_POST['check'])){
$success = 0;
$username = $_POST['username'];
$p = $_POST['password'];
$password = md5($p);

$query = $db->query("SELECT * FROM tbl_users a
                    LEFT OUTER JOIN tbl_user_groups b ON a.Type = b.groupId
                    WHERE a.Username = '$username' AND a.Password = '$password' AND a.Status = true");
if($query->rowCount() > 0){
    $ipaddress = getenv('REMOTE_ADDR');
    $row = $query->fetch(PDO::FETCH_ASSOC);

    $fname = strtoupper($row['First_name']);
    $mname = strtoupper($row['Mid_name'][0].".");
    $lname = strtoupper($row['Last_name']);
    $department =$row['Department'];
    $type=$row['groupName'];
    $name = $fname." ".$mname." ".$lname;
    $active_user ="$lname, $fname";
    $branch = $row['Branch'];
	$mun = $row["Municipality"];
    $RequestorID = $row['id'];
    if($type!='Encoder'){
        $_SESSION['username']=$username;
        $_SESSION['usertype']=strtolower($type);
        $_SESSION['name']=$name;
        $_SESSION['reviewer']='';
        $_SESSION['supervisor']='';
        $_SESSION['active_user']=$active_user;
        $_SESSION['branch']=$branch;
        $_SESSION['userId']=$RequestorID;
		$_SESSION["mun"] = $mun;
        $success=1;
    } else{
        $_SESSION['branch']=$branch;
        $success=0;
    }
    echo $success;
}
// }
// }
?>