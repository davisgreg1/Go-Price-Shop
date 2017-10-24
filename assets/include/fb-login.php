<?php
session_start();
require 'db.php';
global $path;
require_once( 'Facebook/autoload.php' );
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

$app_id = '1135496416586463';
$app_secret = 'c6209fee8fca3326277a4e0b4ec57a99';

FacebookSession::setDefaultApplication($app_id, $app_secret);
if(isset($_SESSION['access_token'])){
$session = new FacebookSession($_SESSION['access_token']);
}
elseif(isset($_POST['access_token'])){
$session = new FacebookSession($_POST['access_token']);
$_SESSION['access_token'] = $_POST['access_token'];
} 

try{
  $me = (new FacebookRequest(
        $session, 'GET', '/me?fields=name,email'
        ))->execute()->getGraphObject(GraphUser::className());
/* `$me`  will hold the user-data provided by Facebook.To check the data just dump this variable like this:*/

  $custname = $me->getProperty("name");
  $words = explode(' ',$custname);
  $fname = $words[0];
  $lname = $words[1];
  $email = $me->getProperty("email");
  $query = "SELECT * FROM cust_data WHERE email = '$email' && fname = '$fname' && lname = '$lname'";
  $result = $test_db->query($query);
  $rowcount = mysqli_num_rows($result);
  if($rowcount == 1){
  $result = $result->fetch_assoc();
  $cid = $result['id'];
  $_SESSION['username'] = $result['username'];
  $_SESSION['cust_id'] = $cid;
  $_SESSION['usertype'] = $result['usertype'];
  $_SESSION['fname'] = $fname;
  date_default_timezone_set("America/New_York");
  $timestamp = date("Y-m-d H:i:s");
  $query1 = "UPDATE cust_data SET signedin = 'TRUE', timestamp = '$timestamp' WHERE id = '$cid'";
  $result1 = $test_db->query($query1);
  logActivity("1","login",$cid,"NA");
  echo "true";
} else {
  echo "false";
}

}catch(FacebookRequestException $e){
  var_dump($e);
}
?>
