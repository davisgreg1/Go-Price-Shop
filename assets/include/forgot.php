<?php
session_start();
global $path;
$x = 0;
require 'db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'mail/Exception.php';
require 'mail/PHPMailer.php';
require 'mail/SMTP.php';
$status = isset($_GET['code']) ? $_GET['code'] : '';
if($status == ""){
$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';
}
if($status == "") {
	logError("4","forgot","1","Status variable is not set.");
}

  if($status == "user") {
	  $_SESSION['status'] = "user";
	if (empty($_POST['email'])) {
        $errMessage = "Email address is required";
		$x++;
		$_SESSION['errmessage'] = $errMessage;
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
    } else {
        $email = test_input($_POST['email']);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errMessage = "This is not a valid email address, please try again";
			$x++;
			$_SESSION['errmessage'] = $errMessage;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit();
    }
	}
	if (empty($_POST['dob1'])) {
        $errMessage = "Date of birth is required";
		$x++;
		$_SESSION['errmessage'] = $errMessage;
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	} else {
		$datepicker = test_input($_POST['dob1']);
		$query = "SELECT * FROM cust_data WHERE email = '$email' AND birthdate = '$datepicker'";
        $result = $test_db->query($query);
        $result = $result->fetch_assoc();
        if($result == ''){
            $errMessage = "This information does not match our records, please try again.";
			$x++;
			$_SESSION['errmessage'] = $errMessage;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit();
        }
		$username = $result['username'];
		$cust_id = $result['id'];
		$fname = $result['fname'];
		$lname = $result['lname'];
		$fullname = $fname . " " . $lname;
	}
	if($x == 0){
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.drmwebdesign.com;mail.drmwebdesign.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'noreply@drmwebdesign.com';                 // SMTP username
    $mail->Password = 'drm0940!';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('noreply@drmwebdesign.com', 'Go Price Shop');
    $mail->addAddress($email, $fullname);     // Add a recipient

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Go Price Shop - Username Reminder';
    $mail->Body    = '<h3>Here is your requested username: </h3><p>If you are having trouble remembering your username, once you log back in, you can change your username to something easier to remember.</p>
	<p>Username: '.$username.'</p>
	<p><a href="'.$path.'login.php">Click here</a> to return to login page.</p>';
    $mail->AltBody = 'Here is your requested username: \n
	If you are having trouble remembering your username, once you log back in, you can change your username to something easier to remember.\n
	Username: '.$username.'\n
	Copy and paste this URL to return to login page: https://www.gopriceshop.com/login.php';
    $mail->send();
	logActivity("3","forgot",$cust_id,"NA");
	header("Location:../accountpending.php?code=user");
	exit();
} catch (Exception $e) {
	$errmess =  $mail->ErrorInfo;
	logError("2","forgot",$cust_id, $errmess);
}
	} 
  }
  if($status == "pass") {
	  $_SESSION['status'] = "pass";
	if (empty($_POST['email'])) {
        $errMessage = "Email address is required";
		$x++;
		$_SESSION['errmessage'] = $errMessage;
		$_SESSION['showPass'] = TRUE;
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
    } else {
        $email = test_input($_POST['email']);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errMessage = "This is not a valid email address, please try again";
			$x++;
			$_SESSION['errmessage'] = $errMessage;
			$_SESSION['showPass'] = TRUE;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit();
    }
	}
	if (empty($_POST['dob'])) {
        $errMessage = "Date of birth is required";
		$x++;
		$_SESSION['errmessage'] = $errMessage;
		$_SESSION['showPass'] = TRUE;
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	} else {
		$datepicker = test_input($_POST['dob']);
		$query = "SELECT * FROM cust_data WHERE email = '$email' AND birthdate = '$datepicker'";
        $result = $test_db->query($query);
        $result = $result->fetch_assoc();
        if($result == ''){
			$x++;
            $errMessage = "This information does not match our records, please try again.";
			$_SESSION['errmessage'] = $errMessage;
			$_SESSION['showPass'] = TRUE;
			header('Location: ' . $_SERVER['HTTP_REFERER']);
			exit();
        }
		$cust_id = $result['id'];
		$fname = $result['fname'];
		$lname = $result['lname'];
		$fullname = $fname . " " . $lname;
		$newpass = mt_rand(10000000, 99999999);
	}
	if($x == 0){
	$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'mail.drmwebdesign.com;mail.drmwebdesign.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'noreply@drmwebdesign.com';                 // SMTP username
    $mail->Password = 'drm0940!';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('noreply@drmwebdesign.com', 'Go Price Shop');
    $mail->addAddress($email, $fullname);     // Add a recipient

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Go Price Shop - Password Reset';
    $mail->Body    = '<h3>Your password has been reset.</h3><p>Your password has been reset for your protection.<br>
	Below you will find your temporary password to login with to create a new password.</p>
	<p>Temporary Password: '.$newpass.'</p>
	<p><a href="'.$path.'accountpending.php?code=pass">Click here</a> to put in your new password.</p>';
    $mail->AltBody = 'Your password has been reset.  Your password has been reset for your protection.\n
	Below you will find your temporary password to login with to create a new password.\n
	Temporary Password: '.$newpass.'\n
	Copy and paste this URL into your browser: https://www.gopriceshop.com/accountpending.php?code=pass to put in your new password.';
    $mail->send();
    $encrypted = encryptIt( $newpass );
	$query = "UPDATE cust_data SET passwd = '$encrypted', passreset = 'TRUE' WHERE id = '$cust_id'";
	$result = $test_db->query($query);
	logActivity("4","forgot",$cust_id,"NA");
	header("Location:../accountpending.php?code=pass");
	exit();
} catch (Exception $e) {
	$errmess =  $mail->ErrorInfo;
	logError("2","forgot",$cust_id, $errmess);
}
	} 
  }