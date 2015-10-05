<?php
	session_start();
	
	if(isset($_SESSION["userId"])){
		$type = strtolower($_SESSION["usertype"]);
		header("Location: $type");

	}
	else{
		header("Location: login.php");
	}
?>	