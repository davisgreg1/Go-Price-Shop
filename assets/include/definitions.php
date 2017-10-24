<?php
global $path;
global $username;
$path = "https://www.gopriceshop.com/";
//Server Information
$dbhost = "localhost";
$dbname = "dromproj_pricecompare";
$dbuser = "dromproj";
$dbpass = "jesus321";

if(isset($_SESSION['username'])){
  $username = $_SESSION['username'];
} else {
  $username = "";
}
//Point System
$addstorepoints = 50;
if(isset($_SESSION['cust_id'])){
  $uid = $_SESSION['cust_id'];
  $db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
  $query1 = $db->query("SELECT * FROM pointsystem WHERE cust_id = '$uid'");
  $result1 = $query1->fetch_assoc();
  $curlevel = $result1['cur_level'];
  $pointbal = $result1['points'];
}
//Error messages
$error1 = "Not able to execute SQL update"; //Possible mis-type, host down, or database corruption
$error2 = "Email was not able to send"; //Some issue with server most likely
$error3 = "Sent to a broken link"; //wrong referral, needs corrected ASAP
$error4 = "Status session not set";
$error5 = "User went to non-existing page";
//Status messages
$status1 = "Logged into website";
$status2 = "Logged out of website";
$status3 = "Requested username to be resent";
$status4 = "Requested password to be reset";
$status5 = "Changed store ID # ";
$status6 = "Deleted store ID # ";
$status7 = $addstorepoints . " points have been added to your account. Approved store ID # ";
$status8 = "Disapproved store ID # ";
$status9 = "Approved product ID # ";
$status10 = "Deleted product ID # ";
$status11 = "Changed city ID # ";
$status12 = "Login session expired";
$status13 = "Tried to access restricted area";
$status14 = "Deleted ingredient ID # ";
$status15 = "Selected new store as favorite. Store ID #: ";
$status16 = "Removed store as favorite. Store ID #: ";
?>
