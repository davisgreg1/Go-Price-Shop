<?php
session_start();
require 'db.php';
global $path;
$user = $_SESSION['username'];
date_default_timezone_set("America/New_York");
$timestamp = date("Y-m-d H:i:s");
	$verify_user = "UPDATE cust_data SET signedin = 'FALSE', timestamp = '$timestamp' WHERE username = '$user'";
	$result = $test_db->query($verify_user);
			if($result){
				$getcustid = "SELECT * FROM cust_data WHERE username = '$user'";
				$resultid = $test_db->query($getcustid);
				$resultid = $resultid->fetch_assoc();
				$cust_id = $resultid['id'];
				logActivity("2","logout",$cust_id,"NA");
				$_SESSION = array();
				session_destroy();
				header('Location: '.$path.'login.php');
				exit();
        } else {
					logError("1","logout","1","1");
	}
?>
