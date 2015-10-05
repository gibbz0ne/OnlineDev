<?php
	error_reporting(E_ALL ^ E_DEPRECATED);

	# FileName="connect.php"
	$hostname = "10.33.187.140";
	$database = "dummy_apec";
	$username = "admin";
	$password = "admin#123";
	// $hostname = "localhost";
	// $database = "system";
	// $username = "root";
	// $password = "";

	$connect = mysql_connect($hostname, $username, $password)
	or die('Could not connect: ' . mysql_error());
	  // select database
	mysql_select_db($database, $connect);
	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET CHARACTER SET utf8");
	mysql_query("SET COLLATION_CONNECTION = 'utf8_unicode_ci'");
	
	// $db = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", "$username", "$password");
	// $db = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", "$username");
	// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// $db->query("SET NAMES 'utf8'");
	// $db->query("SET CHARACTER SET utf8");
	// $db->query("SET COLLATION_CONNECTION = 'utf8_unicode_ci'");
?>