<?php
session_start();
$errMessage = array();
$registerError = array();
if(isset($_SESSION['errmessage'])){
	$errMessage[] = $_SESSION['errmessage'];
}
require 'php/db.php';
global $path;
$username = $verusername = $password = $verpassword = "";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'php/mail/Exception.php';
require 'php/mail/PHPMailer.php';
require 'php/mail/SMTP.php';
$fname = $lname = $address = $street_number = $city = $street_name = $state = $zipcode = $county = $country = $address2 = $phonenum = $email = $dob = $verusername = $verpassword = $gender = "";
if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
	header("Location:dashboard.php");
} ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard login">
    <meta name="author" content="DRM Web Design">
    <meta name="keyword" content="Coming Soon">
    <title>Go Price Shop - Login</title>
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="assets/js/jquery.maskedinput.js"></script>
 <script>
		jQuery(function($){
		$("#dob").mask("99-99-9999",{placeholder:"mm-dd-yyyy"});
		$("#dob1").mask("99-99-9999",{placeholder:"mm-dd-yyyy"});
		$("#phonenum").mask("999-999-9999");
		$("#dob2").mask("99-99-9999",{placeholder:"mm-dd-yyyy"});
		});
 </script>
  </head>
  <body>
  <?php if(isset($_SESSION['showregister'])){ ?>
  <script type="text/javascript">
    $(window).on('load',function(){
        $('#registerModal').modal('show');
    });
</script>
  <?php } ?>
	  <div id="login-page">
	  	<div class="container">
  <?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if($_GET['code'] == "login"){
    //Checking post values for errors and duplicate information
	$redirect = NULL;
	if($_POST['location'] != '') {
		$redirect = $_POST['location'];
		$redirect = substr($redirect, 1);
	}

    if (empty($_POST['username'])) {
        $errMessage[] = "Username is required";
    } else {
        $username = test_input($_POST['username']);
        $query = "SELECT * FROM cust_data WHERE username = '$username'";
        $result = $test_db->query($query);
        $result = $result->fetch_assoc();
        if($result == ''){
            $errMessage[0] = "This login information does not match our records, please try again.";
        }
    	if (empty($_POST['password'])) {
        $errMessage[] = "Password is required";
    } else {
        $password = test_input($_POST['password']);
		$encrypted = encryptIt( $password );
        $query = "SELECT * FROM cust_data WHERE username = '$username' AND passwd = '$encrypted'";
        $result = $test_db->query($query);
        $result = $result->fetch_assoc();
        if(!$result){
            $errMessage[0] = "This login information does not match our records, please try again.";
        }
  }
}

    if(count($errMessage) == 0){
	$checkauth = "SELECT * FROM cust_data WHERE username = '$username'";
	$resultauth = $test_db->query($checkauth);
	$resultauth = $resultauth->fetch_assoc();
	if($resultauth['emailval']!="TRUE"){
			$_SESSION['emailhash'] = $resultauth['emailhash'];
			$_SESSION['user'] = test_input($_POST['username']);
			header("Location:accountpending.php");
		}
		elseif($resultauth['passreset']=="TRUE"){
			$_SESSION['status'] = "pass";
			header("Location:accountpending.php");
		} else {
		$custid = $resultauth['id'];
		$username = test_input($_POST['username']);
		$_SESSION['username'] = $username;
		$_SESSION['cust_id'] = $custid;
		$_SESSION['usertype'] = $result['usertype'];
		$_SESSION['fname'] = $result['fname'];
		date_default_timezone_set("America/New_York");
		$timestamp = date("Y-m-d h:i:sa");
		$query = "UPDATE cust_data SET signedin = 'TRUE', timestamp = '$timestamp' WHERE id = '$custid'";
		$result = $test_db->query($query);
		logActivity("1","login",$custid,"NA");
		if($redirect) {
			header("Location:".$path.$redirect);
		} else {
			header("Location:".$path."dashboard.php");
		}
		exit();
		}
	}
  }
  if($_GET['code'] == "register"){
	  	if (empty($_POST['fname'])) {
        $registerError[0] = "First name is missing";
		$_SESSION['showregister'] = TRUE;
    } else {
        $fname = test_input($_POST['fname']);
		$fname = ucwords($fname);
        if (!preg_match("/^[a-zA-Z ,-.]*$/", $fname)) {
            $registerError[0] = "Only letters, a period, a dash, a comma, and spaces are allowed in your first name";
			$_SESSION['showregister'] = TRUE;
        }
    }
		if (empty($_POST['lname'])) {
			$registerError[1] = "Last name is required";
			$_SESSION['showregister'] = TRUE;
    } else {
        $lname = test_input($_POST['lname']);
		$lname = ucwords($lname);
        if (!preg_match("/^[a-zA-Z ,-.]*$/", $lname)) {
            $registerError[1] = "Only letters, a period, a dash, a comma, and spaces are allowed in your last name";
			$_SESSION['showregister'] = TRUE;
        }
    }
		if (empty($_POST['street_number'])) {
			$registerError[2] = "Please select an address from approved list. If your address is not listed, please <a href='#'>click here</a>";
			$_SESSION['showregister'] = TRUE;
    } else {
        $street_number = test_input($_POST['street_number']);
		$street_name = test_input($_POST['street_name']);
		$address2 = test_input($_POST['address2']);
		$address = $street_number . " " . $street_name;
		$zipcode = test_input($_POST['zipcode']);
		$query3 = "SELECT * FROM city_list WHERE zipcode = '$zipcode'";
        $result3 = $test_db->query($query3);
        $result3 = $result3->fetch_assoc();
		$cid = $result3['id'];
    }
		if (empty($_POST['phonenum'])) {
			$registerError[4] = "Phone number is required";
			$_SESSION['showregister'] = TRUE;
    } else {
        $phonenum = test_input($_POST['phonenum']);
		// set API Access Key
		$access_key = 'fd2e28c446a7cc030b761e92a7499499';

		// set phone number
		$phone_number = $phonenum;

		// Initialize CURL:
		$ch = curl_init('http://apilayer.net/api/validate?access_key='.$access_key.'&number='.$phone_number.'&country_code=US');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Store the data:
		$json = curl_exec($ch);
		curl_close($ch);

		// Decode JSON response:
		$validationResult = json_decode($json, true);

		// Access and use your preferred validation result objects
		$validationResult['valid'];
		#$validationResult['country_code'];
		#$validationResult['carrier'];
		if ($validationResult['valid'] != TRUE) {
            $registerError[4] = "Please enter a valid phone number, please try again";
			$_SESSION['showregister'] = TRUE;
		}
	}

	if (empty($_POST['email'])) {
	    $registerError[5] = "Email is required";
		$_SESSION['showregister'] = TRUE;
    } else {
    $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $registerError[5] = "This is not a valid email address, please try again";
			$_SESSION['showregister'] = TRUE;
    }
        $dupemail = "SELECT * FROM cust_data WHERE email = '$email'";
        $result = $test_db->query($dupemail);
        $email_rows = mysqli_num_rows($result);
        if ($email_rows > 0){
            $registerError[5] = "This email already exist. Maybe you already have an account with us?";
			$_SESSION['showregister'] = TRUE;
        }
}

	if (empty($_POST['dob2'])) {
		$registerError[6] = "Please select your Date of Birth";
		$_SESSION['showregister'] = TRUE;
	} else {
		$dob = test_input($_POST['dob2']);
	}

    if (empty($_POST['ethnicity'])) {
        $registerError[7] = "Please select a choice under Ethnicity";
		$_SESSION['showregister'] = TRUE;
    } else {
        $ethnicity = test_input($_POST['ethnicity']);
    }

    if (empty($_POST['gender'])) {
        $registerError[8] = "Please select a gender";
		$_SESSION['showregister'] = TRUE;
    } else {
        $gender = test_input($_POST['gender']);
    }

    if (empty($_POST['usern'])) {
        $registerError[9] = "Please type in a username";
		$_SESSION['showregister'] = TRUE;
    } else {
        $username = test_input($_POST['usern']);
        if (strlen($username) < 8){
            $registerError[9] = "Your username needs to be at least 8 characters long.";
			$_SESSION['showregister'] = TRUE;
        }
        $query = "SELECT * FROM cust_data WHERE username = '$username'";
        $result = $test_db->query($query);
        $num_rows = mysqli_num_rows($result);
        if ($num_rows > 0){
            $registerError[9] = "This username already exist. Have you tried logging in above?";
			$_SESSION['showregister'] = TRUE;
        }
	}

    if (empty($_POST['ver-username'])) {
        $registerError[10] = "Please type in your username again";
		$_SESSION['showregister'] = TRUE;
    } else {
        $verusername = test_input($_POST['ver-username']);
    }

    if (empty($_POST['passw'])) {
        $registerError[11] = "Please type in a password";
		$_SESSION['showregister'] = TRUE;
    } else {
        $password = test_input($_POST['passw']);
        if (strlen($password) < 8){
            $registerError[11] = "Your password needs to be at least 8 characters long.";
			$_SESSION['showregister'] = TRUE;
        }
    }

    if (empty($_POST['ver-password'])) {
        $registerError[12] = "Please type in your password again";
		$_SESSION['showregister'] = TRUE;
    } else {
        $verpassword = test_input($_POST['ver-password']);
	}

    if ($username != $verusername){
        $registerError[9] = "Your usernames do not match. Please try again.";
		$_SESSION['showregister'] = TRUE;
    }

    if ($password != $verpassword){
        $registerError[11] = "Your passwords do not match. Please try again.";
		$_SESSION['showregister'] = TRUE;
    }

    if(count($registerError) == 0){
	    $emailhash = md5( rand(0,1000) ); //For email verification purposes
		$fullname = $fname . " " . $lname;
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
    $mail->Subject = 'Go Price Shop - Account Confirmation';
    $mail->Body    = '<h1>Thanks for signing up!</h1><p>Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.</p>
	<p>Username: '.$username.'</p>
	<p>Password: '.$password.'</p>
	<p>Verification Key: '.$emailhash.'</p>
	<p>If you forget your username and/or password, you will be able to retrieve this on our website by answering a few questions.  An email will be sent here with temporary login information <br>
	that will allow you to update the username and/or password to your account.  This is why we do the email verification to make sure we have your email input properly in the system.</p>
	<p>Please <a href="'.$path.'php/verify.php?code=hash&hash='.$emailhash.'">click here</a> to link to activate your account.</p>';
    $mail->AltBody = 'Thanks for signing up!\n\n
	Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.\n
	Username: '.$username.'\n
	Password: '.$password.'\n
	Verification Key: '.$emailhash.'\n
	If you forget your username and/or password, you will be able to retrieve this on our website by answering a few questions.  An email will be sent here with temporary login information \n
	that will allow you to update the username and/or password to your account.  This is why we do the email verification to make sure we have your email input properly in the system.\n
	Please copy and paste this link into your browser https://www.drmwebdesign.com/php/verify.php?code=hash&hash='.$emailhash.' to activate your account.';
    $mail->send();
	} catch (Exception $e) {
	$errmess =  $mail->ErrorInfo;
	logError("2","user-register","NA", $errmess);
	}
		date_default_timezone_set("America/New_York");
		$timestamp = date("Y-m-d h:i:sa");
		$encrypted = encryptIt( $password );

        $query = "INSERT INTO cust_data (fname, lname, address, address2, cid, emailhash, phonenum, email, username, passwd, gender, ethnicity, birthdate, registerdate, timestamp) VALUES('$fname', '$lname', '$address', '$address2', '$cid', '$emailhash', '$phonenum', '$email', '$username', '$encrypted', '$gender', '$ethnicity', '$dob', '$timestamp', '$timestamp')";
        $result = $test_db->query($query);
        if ($result) {
		$curlevel = "beginner";
		$points = "10";
		$getid = "SELECT * FROM cust_data WHERE username = '$username'";
		$resultid = $test_db->query($getid);
		$resultid = $resultid->fetch_assoc();
		$cust_id = $resultid['id'];
		$query = "INSERT INTO pointsystem (cust_id, points, cur_level) VALUES('$cust_id', '$points', '$curlevel')";
        $result = $test_db->query($query);
		$_SESSION['emailhash'] = $emailhash;
		$_SESSION['user'] = $username;
		header("Location:accountpending.php");
        } else {
		logError("2","user-register","NA", $errmess);
		}
    }
  }
}
if(isset($_SESSION['status'])){
if($_SESSION['status'] == "notvalidated"){
	$errMessage[0] = "Looks like you may already be validated.  Try logging in with the username and password that you created originally.";
	unset($_SESSION['status']);
	}

if($_SESSION['status'] == "notauthorized"){
	$errMessage[0] = "Looks like your session expired.  Try logging in again.";
	session_destroy();
	}

if($_SESSION['status'] == "passreset"){ ?>
            <div class="alert alert-success alert-dismissable">
						  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <p><strong>Success!</strong> Your password has been successfully updated.  Please login with your username and new password.</p>
                </div>
     <?php session_destroy(); }

if($_SESSION['status'] == "validated"){ ?>
             <div class="alert alert-success fade in alert-dismissable">
				<a aria-label="close" class="close" data-dismiss="alert" href="#">&times;</a>
                <p><strong>Alright!</strong> Your account is now active.</p>
                </div>
    <?php session_destroy(); }
}
if(count($errMessage) > 0){ ?>
		<div class="alert alert-danger alert-dismissable">
						  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						  <p><strong>Error!</strong> <?php
                                                foreach($errMessage as $e){
                                                    echo " * " . $e . "<br />";
                                                } ?></p>
											</div>
    <?php unset($_SESSION['errmessage']); } ?>

		      <form class="form-login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?code=login");?>">
		        <h2 class="form-login-heading">sign in now</h2>
		        <div class="login-wrap">
		            <input type="text" class="form-control" name="username" id="username" placeholder="User ID" autofocus>
		            <br>
		            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
					<input type="hidden" name="location" value="<?php if(isset($_GET['location'])) { echo htmlspecialchars($_GET['location']); } if(isset($redirect)) { echo htmlspecialchars($redirect); } ?>" />
		            <label class="checkbox">
					<span class="pull-left">
		                    <a data-toggle="modal" href="login.html#userModal"> Forgot Username?</a>
		                </span>
		                <span class="pull-right">
		                    <a data-toggle="modal" href="login.html#passModal"> Forgot Password?</a>
		                </span>
		            </label>
		            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
					</form>
		            <hr>

		            <div class="login-social-link centered">
		            <p>or you can sign in via your social network</p>
		                <button class="btn btn-facebook" type="submit"><i class="fa fa-facebook"></i> Facebook</button>
		                <button class="btn btn-twitter" type="submit"><i class="fa fa-twitter"></i> Twitter</button>
		            </div>
		            <div class="registration">
		                Don't have an account yet?<br/>
		                <a data-toggle="modal" href="login.html#registerModal">
		                    Create an account
		                </a>
		            </div>
		        </div>

				<!-- Register Modal -->
				  <form class="form-login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]."?code=register");?>">
				  <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="registerModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title"> Account Registration </h4>
		                      </div>
		                      <div class="modal-body">
							  <span class="pull-left">Please fill out all fields listed below.</span>
							  <span class="pull-right"><a href="javascript:;" class="btn btn-info" id="register-info">Why do we need this info? - Click here</a></span><br><br>
	<?php if(!empty($registerError[0])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="inputError0"><?php echo $registerError[0]; ?></label>
	<input type="text" name="fname" id="inputError0" value="<?php echo $fname;?>" placeholder="First Name" class="form-control"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="text" name="fname" id="fname" value="<?php echo $fname;?>" placeholder="First Name" class="form-control"/></div>
	<?php }
	if(!empty($registerError[1])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="inputError1"><?php echo $registerError[1]; ?></label>
	<input type="text" name="lname" id="inputError1" value="<?php echo $lname;?>" placeholder="Last Name" class="form-control"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="text" name="lname" id="lname" value="<?php echo $lname;?>" placeholder="Last Name" class="form-control"/></div>
	<?php }
	 if(!empty($registerError[2])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="autocomplete"><?php echo $registerError[2]; ?></label>
	<input id="autocomplete" class="form-control" placeholder="Enter your address" onFocus="geolocate()" type="text" value="<?php if($address!='')echo $street_number . " " . $street_name . ", " . $city . ", " . $state . ", " . $country; ?>">
									<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
									<input type="hidden" id="street_number" name="street_number" value="<?php echo $street_number;?>" >
                                    <input type="hidden" id="route" name="street_name" value="<?php echo $street_name;?>" >
                                    <input type="hidden" id="locality" name="city" value="<?php echo $city;?>" >
                                    <input type="hidden" id="administrative_area_level_1" name="state" value="<?php echo $state;?>" >
                                    <input type="hidden" id="postal_code" name="zipcode" value="<?php echo $zipcode;?>">
                                    <input type="hidden" id="country" name="country" value="<?php echo $country;?>" >
                                    <input type="hidden" id="administrative_area_level_2" name="county" value="<?php echo $county;?>" >
	<?php } else { ?>
<div class="form-group"><input id="autocomplete" class="form-control" placeholder="Enter your address" onFocus="geolocate()" type="text" value="<?php if($address!='')echo $street_number . " " . $street_name . ", " . $city . ", " . $state . ", " . $country; ?>">
									<input type="hidden" id="street_number" name="street_number" value="<?php echo $street_number;?>" >
                                    <input type="hidden" id="route" name="street_name" value="<?php echo $street_name;?>" >
                                    <input type="hidden" id="locality" name="city" value="<?php echo $city;?>" >
                                    <input type="hidden" id="administrative_area_level_1" name="state" value="<?php echo $state;?>" >
                                    <input type="hidden" id="postal_code" name="zipcode" value="<?php echo $zipcode;?>">
                                    <input type="hidden" id="country" name="country" value="<?php echo $country;?>" >
                                    <input type="hidden" id="administrative_area_level_2" name="county" value="<?php echo $county;?>" ></div>
	<?php } ?>
<div class="form-group"><input type="text" name="address2" value="<?php echo $address2;?>" placeholder="Apt #, Suite #, etc" class="form-control"/></div>
	<?php if(!empty($registerError[4])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="phonenum"><?php echo $registerError[4]; ?></label>
	<input type="text" name="phonenum" id="phonenum" value="<?php echo $phonenum;?>" placeholder="Phone Number" class="form-control"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="text" name="phonenum" id="phonenum" value="<?php echo $phonenum;?>" placeholder="Phone Number" class="form-control"/></div>
	<?php }
	if(!empty($registerError[5])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="email"><?php echo $registerError[5]; ?></label>
	<input type="text" name="email" id="email" value="<?php echo $email;?>" placeholder="Email" class="form-control"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="text" name="email" id="email" value="<?php echo $email;?>" placeholder="Email" class="form-control"/></div>
	<?php }
	if(!empty($registerError[6])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="dob2"><?php echo $registerError[6]; ?></label>
	<input type="text" name="dob2" id="dob2" value="<?php echo $dob;?>" placeholder="Date of Birth" class="form-control"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="text" name="dob2" id="dob2" value="<?php echo $dob;?>" placeholder="Date of Birth" class="form-control"/></div>
	<?php }
	if(!empty($registerError[7])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="ethnicity"><?php echo $registerError[7]; ?></label>
	<fieldset>
  <select class="form-control dropdown" id="ethnicity" name="ethnicity">
      <option value="" disabled="disabled" <?php if (!isset($_POST['ethnicity'])){ echo ' selected="selected"'; } ?>>Ethnicity -- select one --</option>
      <option value="White English" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White English') echo ' selected="selected"'; } ?>>English</option>
      <option value="White Welsh" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Welsh') echo ' selected="selected"'; } ?>>Welsh</option>
      <option value="White Scottish" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Scottish') echo ' selected="selected"'; } ?>>Scottish</option>
      <option value="White Northern Irish" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Northern Irish') echo ' selected="selected"'; } ?>>Northern Irish</option>
      <option value="White Irish" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Irish') echo ' selected="selected"'; } ?>>Irish</option>
      <option value="White Gypsy or Irish Traveller" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Gypsy or Irish Traveller') echo ' selected="selected"'; } ?>>Gypsy or Irish Traveller</option>
      <option value="White Other" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Other') echo ' selected="selected"'; } ?>>Any other White background</option>
      <option value="Mixed Multiple Ethnics" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Mixed Multiple Ethnics') echo ' selected="selected"'; } ?>>Mixed Multiple Ethnics</option>
      <option value="Asian Indian" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Indian') echo ' selected="selected"'; } ?>>Indian</option>
      <option value="Asian Pakistani" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Pakistani') echo ' selected="selected"'; } ?>>Pakistani</option>
      <option value="Asian Bangladeshi" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Bangladeshi') echo ' selected="selected"'; } ?>>Bangladeshi</option>
      <option value="Asian Chinese" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Chinese') echo ' selected="selected"'; } ?>>Chinese</option>
      <option value="Asian Other" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Other') echo ' selected="selected"'; } ?>>Any other Asian background</option>
      <option value="Black African" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Black African') echo ' selected="selected"'; } ?>>African</option>
      <option value="Black African American" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Black African American') echo ' selected="selected"'; } ?>>African American</option>
      <option value="Black Caribbean" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Black Caribbean') echo ' selected="selected"'; } ?>>Caribbean</option>
      <option value="Black Other" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Black Other') echo ' selected="selected"'; } ?>>Any other Black background</option>
      <option value="Arab" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Arab') echo ' selected="selected"'; } ?>>Arab</option>
      <option value="Hispanic" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Hispanic') echo ' selected="selected"'; } ?>>Hispanic</option>
      <option value="Latino" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Latino') echo ' selected="selected"'; } ?>>Latino</option>
      <option value="Native American" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Native American') echo ' selected="selected"'; } ?>>Native American</option>
      <option value="Pacific Islander" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Pacific Islander') echo ' selected="selected"'; } ?>>Pacific Islander</option>
      <option value="Other" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Other') echo ' selected="selected"'; } ?>>Any other ethnic group</option>
  </select>
</fieldset>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><fieldset>
  <select class="form-control dropdown" id="ethnicity" name="ethnicity">
      <option value="" disabled="disabled" <?php if (!isset($_POST['ethnicity'])){ echo ' selected="selected"'; } ?>>Ethnicity -- select one --</option>
      <option value="White English" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White English') echo ' selected="selected"'; } ?>>English</option>
      <option value="White Welsh" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Welsh') echo ' selected="selected"'; } ?>>Welsh</option>
      <option value="White Scottish" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Scottish') echo ' selected="selected"'; } ?>>Scottish</option>
      <option value="White Northern Irish" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Northern Irish') echo ' selected="selected"'; } ?>>Northern Irish</option>
      <option value="White Irish" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Irish') echo ' selected="selected"'; } ?>>Irish</option>
      <option value="White Gypsy or Irish Traveller" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Gypsy or Irish Traveller') echo ' selected="selected"'; } ?>>Gypsy or Irish Traveller</option>
      <option value="White Other" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'White Other') echo ' selected="selected"'; } ?>>Any other White background</option>
      <option value="Mixed Multiple Ethnics" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Mixed Multiple Ethnics') echo ' selected="selected"'; } ?>>Mixed Multiple Ethnics</option>
      <option value="Asian Indian" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Indian') echo ' selected="selected"'; } ?>>Indian</option>
      <option value="Asian Pakistani" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Pakistani') echo ' selected="selected"'; } ?>>Pakistani</option>
      <option value="Asian Bangladeshi" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Bangladeshi') echo ' selected="selected"'; } ?>>Bangladeshi</option>
      <option value="Asian Chinese" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Chinese') echo ' selected="selected"'; } ?>>Chinese</option>
      <option value="Asian Other" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Asian Other') echo ' selected="selected"'; } ?>>Any other Asian background</option>
      <option value="Black African" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Black African') echo ' selected="selected"'; } ?>>African</option>
      <option value="Black African American" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Black African American') echo ' selected="selected"'; } ?>>African American</option>
      <option value="Black Caribbean" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Black Caribbean') echo ' selected="selected"'; } ?>>Caribbean</option>
      <option value="Black Other" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Black Other') echo ' selected="selected"'; } ?>>Any other Black background</option>
      <option value="Arab" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Arab') echo ' selected="selected"'; } ?>>Arab</option>
      <option value="Hispanic" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Hispanic') echo ' selected="selected"'; } ?>>Hispanic</option>
      <option value="Latino" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Latino') echo ' selected="selected"'; } ?>>Latino</option>
      <option value="Native American" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Native American') echo ' selected="selected"'; } ?>>Native American</option>
      <option value="Pacific Islander" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Pacific Islander') echo ' selected="selected"'; } ?>>Pacific Islander</option>
      <option value="Other" <?php if (isset($_POST['ethnicity'])){ if($_POST['ethnicity'] == 'Other') echo ' selected="selected"'; } ?>>Any other ethnic group</option>
  </select>
</fieldset></div>
	<?php }
	if(!empty($registerError[8])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label"><?php echo $registerError[8]; ?></label>
	<input type="radio" value="Male" id="male" name="gender" <?php if (isset($_POST['gender'])){ if($_POST['gender'] == 'Male') echo 'checked'; } ?>>
	<label for="male">Male</label>
	<input type="radio" value="Female" id="female" name="gender" <?php if (isset($_POST['gender'])){ if($_POST['gender'] == 'Female') echo 'checked'; } ?>>
	<label for="female">Female</label>
	<input type="radio" value="refused-answer" id="no-answer" name="gender" <?php if (isset($_POST['gender'])){ if($_POST['gender'] == 'refused-answer') echo 'checked'; } ?>>
	<label for="no-answer">Prefer Not to Answer</label>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="radio" value="Male" id="male" name="gender" <?php if (isset($_POST['gender'])){ if($_POST['gender'] == 'Male') echo 'checked'; } ?>>
						<label for="male">Male</label>
						<input type="radio" value="Female" id="female" name="gender" <?php if (isset($_POST['gender'])){ if($_POST['gender'] == 'Female') echo 'checked'; } ?>>
						<label for="female">Female</label>
						<input type="radio" value="refused-answer" id="no-answer" name="gender" <?php if (isset($_POST['gender'])){ if($_POST['gender'] == 'refused-answer') echo 'checked'; } ?>>
						<label for="no-answer">Prefer Not to Answer</label></div>
	<?php }
	if(!empty($registerError[9])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="usern"><?php echo $registerError[9]; ?></label>
	<input type="text" name="usern" id="usern" value="<?php echo $username;?>" placeholder="Username" class="form-control" autocomplete="false"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="text" name="usern" id="usern" value="<?php echo $username;?>" placeholder="Username" class="form-control" autocomplete="false"/></div>
	<?php }
	if(!empty($registerError[10])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="ver-username"><?php echo $registerError[10]; ?></label>
	<input type="text" name="ver-username" id="ver-username" value="<?php echo $verusername;?>" placeholder="Verify Username" class="form-control" autocomplete="off"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="text" name="ver-username" id="ver-username" value="<?php echo $verusername;?>" placeholder="Verify Username" class="form-control" autocomplete="off"/></div>
	<?php }
	if(!empty($registerError[11])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="passw"><?php echo $registerError[11]; ?></label>
	<input type="password" name="passw" id="passw" placeholder="Password" autocomplete="false" class="form-control"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="password" name="passw" id="passw" placeholder="Password" autocomplete="false" class="form-control"/></div>
	<?php }
	if(!empty($registerError[12])){ ?>
	<div class="form-group has-error has-feedback">
	<label class="control-label" for="ver-password"><?php echo $registerError[12]; ?></label>
	<input type="password" name="ver-password" id="ver-password" placeholder="Verify Password" autocomplete="off" class="form-control"/>
	<span class="glyphicon glyphicon-remove form-control-feedback"></span>
	</div>
	<?php } else { ?>
<div class="form-group"><input type="password" name="ver-password" id="ver-password" placeholder="Verify Password" autocomplete="off" class="form-control"/></div>
	<?php } ?>

		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" type="submit">Submit</button>
		                      </div>
		                  </div>
		              </div>
		          </div>
				</form>

		          <!-- Password Modal -->
				  <form class="form-login" method="post" action="php/forgot.php?code=pass">
				  <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="passModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Forgot Password ?</h4>
		                      </div>
		                      <div class="modal-body">
		                          <p>Enter your e-mail address below to reset your password.</p>
		                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
								  <input type="text" name="dob" id="dob" placeholder="Date of Birth" class="form-control placeholder-no-fix">
								  <input type="hidden" id="pass" name="pass" value="pass">
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" type="submit">Submit</button>

		                      </div>
		                  </div>
		              </div>
					  </form>
		          </div>

				  <!-- Username Modal -->
				  <form class="form-login" method="post" action="php/forgot.php?code=user">
		          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="userModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Forgot Username ?</h4>
		                      </div>
		                      <div class="modal-body">
		                          <p>Enter your e-mail address below to reset your password.</p>
		                          <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
								  <input type="text" name="dob1" id="dob1" placeholder="Date of Birth" class="form-control placeholder-no-fix">
								  <input type="hidden" id="user" name="user" value="user">
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
		                          <button class="btn btn-theme" type="submit">Submit</button>
		                      </div>
		                  </div>
		              </div>
					  </form>
		          </div>
	  	</div>
	  </div>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery.backstretch.min.js"></script>
    <script>
        $.backstretch("assets/img/login-bg.jpeg", {speed: 500});
    </script>
	<script>
      var placeSearch, autocomplete;
      var componentForm = {
          street_number: 'short_name',
          route: 'long_name',
          locality: 'long_name',
          administrative_area_level_1: 'short_name',
          country: 'long_name',
          administrative_area_level_2: 'long_name',
          postal_code: 'short_name'
      };

      function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(
            /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
            {types: ['geocode']});

        autocomplete.addListener('place_changed', fillInAddress);
      }

      function fillInAddress() {
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
        }

        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
          }
        }
      }

      function geolocate() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
      }
    </script>
		<script>$(document).ready(function() {
			$(window).keydown(function(event){
				if(event.keyCode == 13) {
				event.preventDefault();
				return false;
					}
				});
			});</script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDm-ORzY1om0BTWAwxeBWvKa7u6AF3KIYA&libraries=places&callback=initAutocomplete" async defer></script>
		<script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
		<script type="text/javascript" src="assets/js/gritter-conf.js"></script>
  </body>
</html>
