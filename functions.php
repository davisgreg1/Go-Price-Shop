<?php
function logActivity($statuscode,$pagename,$cust_id,$store_id){
global $test_db;
global $path;
date_default_timezone_set("America/New_York");
$timestamp = date("Y-m-d h:i:sa");
$ipproxy = "";
if($store_id == "NA"){
	$store_id = "";
}

if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$ipproxy = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
$ipadd = $_SERVER['REMOTE_ADDR'];
	$input_activity = "INSERT INTO activitylog (statuscode, ipadd, ipproxy, pagename, timestamp, cust_id, store_id) VALUES ('$statuscode','$ipadd','$ipproxy','$pagename','$timestamp','$cust_id','$store_id')";
	$result = $test_db->query($input_activity);
			if($result){
				$x=1;
        } else {
			echo "There is an issue with the server.  The issue has been directed directly to our host to be resolved. Error Code: 1-001";
	}
}

function showErrors($errmessage,$variable,$value,$name){
	echo "<div class='form-group has-error has-feedback'>";
	echo "<label class='control-label' for='".$variable."'>" . $errmessage . "</label>";
	echo "<input type='text' name='".$variable."' id='".$variable."' value='". $value."' placeholder='".$name."' class='form-control' autocomplete='off'/>";
	echo "<span class='glyphicon glyphicon-remove form-control-feedback'></span>";
	echo "</div>";
	}

function addPoints($uid,$numpoints){
	global $test_db;
	global $path;
	$update_points = "UPDATE pointsystem SET points = points + '$numpoints' WHERE cust_id = '$uid'";
	$result = $test_db->query($update_points);
}

function logError($statuscode,$pagename,$cust_id,$errmessage){
global $test_db;
global $path;
$ipproxy = "";
date_default_timezone_set("America/New_York");
$timestamp = date("Y-m-d h:i:sa");
if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
	$ipproxy = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
$ipadd = $_SERVER['REMOTE_ADDR'];
	$input_activity = "INSERT INTO errorlog (errorcode, filename, timestamp, cust_id, ipadd, ipproxy, errormessage) VALUES ('$statuscode','$pagename','$timestamp','$cust_id','$ipadd','$ipproxy','$errmessage')";
	$result = $test_db->query($input_activity);
			if($result){
				$x=1;
        } else {
			echo "There is an issue with the server.  The issue has been directed directly to our host to be resolved. Error Code: 1-001";
	}
}

function verifyUser(){
	global $test_db;	
	global $path;
	global $username;
	$checkuser = "SELECT * FROM cust_data WHERE username = '$username'";
	$result = $test_db->query($checkuser);
	$user_rows = mysqli_num_rows($result);
	$result = $result->fetch_assoc();
	$lastlogin = $result['timestamp'];
	if ($user_rows != 1){
				logActivity(13,"default",$result['id'],"NA");
				$_SESSION['status'] = "notauthorized";
				header("Location:".$path."login.php?location=" . urlencode($_SERVER['REQUEST_URI']));
				exit();
        } else {
			date_default_timezone_set("America/New_York");
			$timestamp = date("Y-m-d h:i:sa");
			$to_time = strtotime($timestamp);
			$from_time = strtotime($lastlogin);
			$numofmin = round(abs($to_time - $from_time) / 60,2);
			if($numofmin >= 30){
				logActivity(12,"default",$result['id'],"NA");
				header("Location:".$path."php/logout.php");
				exit();
			}
			$update_stamp = "UPDATE cust_data SET timestamp='$timestamp', signedin='TRUE' WHERE username = '$username'";
			$result = $test_db->query($update_stamp);
		}
}
function verifyAdmin(){	
	global $path;
	global $test_db;
	global $username;
	$checkuser = "SELECT * FROM cust_data WHERE username = '$username'";
	$result = $test_db->query($checkuser);
	$result = $result->fetch_assoc();
	$lastlogin = $result['timestamp'];
	if ($result['usertype'] != "admin"){
				logActivity(13,"default",$result['id'],"NA");
				$_SESSION['status'] = "notauthorized";
				header("Location:".$path."login.php?location=" . urlencode($_SERVER['REQUEST_URI']));
				exit();
        } else {
			date_default_timezone_set("America/New_York");
			$timestamp = date("Y-m-d h:i:sa");
			$to_time = strtotime($timestamp);
			$from_time = strtotime($lastlogin);
			$numofmin = round(abs($to_time - $from_time) / 60,2);
			if($numofmin >= 30){
				logActivity(12,"default",$result['id'],"NA");
				header("Location:".$path."php/logout.php");
				exit();
			}
			$update_stamp = "UPDATE cust_data SET timestamp='$timestamp', signedin='TRUE' WHERE username = '$username'";
			$result = $test_db->query($update_stamp);
		}
}
function getAvatarInfo($username){	
	global $path;
	global $test_db;
	$query = "SELECT * FROM cust_data WHERE username = '$username'";
	$result = $test_db->query($query);
    $result = $result->fetch_assoc();
	$avatarname = $result['id'];
	$avatartype = $result['avatartype'];
	return array($avatarname,$avatartype);
}
function encryptIt( $q ) {
	$cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
	$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
	return( $qEncoded );
	}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>