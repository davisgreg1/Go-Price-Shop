<?php
session_start();
require 'db.php';
global $path;
$errorid = $_GET['id'];
if($errorid == "all"){
	$query = "DELETE FROM errorlog";
}else{
	$query = "DELETE FROM errorlog WHERE id = '$errorid'";
}
	$result = $test_db->query($query);
	if($result){
		$_SESSION['deleted'] = "yes";
		header("Location:".$path."errorlist.php");
		exit();
	} else {
		logError("1","delete-error","1","1");
	}
?>
