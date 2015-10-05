<?php
error_reporting(E_ALL ^ E_DEPRECATED);
session_start();
$selected_branch = $_SESSION['branch']; 
echo $selected_branch;

	
?>