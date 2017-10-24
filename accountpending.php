<?php
session_start();
require 'assets/include/db.php';
$errMessage = array();
$registerError = array();
$status = isset($_GET['code']) ? $_GET['code'] : '';
if($status == ""){
$status = isset($_SESSION['status']) ? $_SESSION['status'] : '';
} ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Coming Soon">
    <meta name="author" content="DRM Web Design">
    <meta name="keyword" content="Coming Soon">
    <title>Go Price Shop - Account Pending</title>
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <section id="container" >
      <!--header start-->
      <header class="header black-bg">
	          <div class="sidebar-toggle-box">
                  <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
              </div>
            <!--logo start-->
            <a href="index.html" class="logo"><b>Go. Price. Shop.</b></a>
            <!--logo end-->
        </header>
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">

				  <li class="mt">
                      <a href="login.php">
                          <i class="fa fa-sign-in"></i>
                          <span>Login</span>
                      </a>
                  </li>

                  <li class="mt">
                      <a href="dashboard.php">
                          <i class="fa fa-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>

              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      <section id="main-content">
          <section class="wrapper site-min-height">
          	<div class="row mt">
			<img src="assets/img/accpend-bg.jpg" alt="" width="100%" height="300px" style="margin-top:-25px;"/>
          		<div class="col-lg-12">
						<?php
			if($status == "user"){
			$_SESSION['status'] = "user"; ?>
			<h3>Username has been sent.</h3>
						<p>At this time, please check your email and look for your username originally assigned during initial registration.  There is no other steps you have to take at this time.</p>
						<center><a href="login.php"><button type="button" class="btn btn-primary btn-lg">Login</button></a></center>
			<?php }
			elseif($status == "pass"){
			$_SESSION['status'] = "pass";
			if ($_SERVER['REQUEST_METHOD'] == "POST") {
					if (empty($_POST['temppass'])) {
						$errMessage[] = "You need to put in the temporary password.";
					} else {
						$temppass = test_input($_POST['temppass']);
						$encrypted = encryptIt( $temppass );
					}
					if (empty($_POST['password'])) {
						$errMessage[] = "Please type in a new password.";
					} else {
						$password = test_input($_POST['password']);
					}
					if (empty($_POST['verpass'])) {
						$errMessage[] = "Please type in the same password, just to make sure you typed in what you wanted to.";
					} else {
						$verpass = test_input($_POST['verpass']);
					}
					if($password != $verpass){
						$errMessage[] = "The two new passwords you typed do not match.  Please try again.";
					}
			if(count($errMessage) == 0){
					$checktemp = "SELECT * FROM cust_data WHERE passwd = '$encrypted'";
					$result = $test_db->query($checktemp);
					$hash_rows = mysqli_num_rows($result);
					$result = $result->fetch_assoc();
					$custid = $result['id'];
					if ($hash_rows == 1){
						$encrypted = encryptIt( $password );
						$verify_user = "UPDATE cust_data SET passwd = '$encrypted', passreset = 'FALSE' WHERE id = '$custid'";
						$result = $test_db->query($verify_user);
						if($result){
							$_SESSION['status'] = "passreset";
							header("Location:login.php");
						}
					} else {
						$errMessage[] = "Temporary password is incorrect.  Please check your email.";
					}
			}
			if(count($errMessage) > 0){ ?>
				<div class="nwp-notification nwp-error nwp-auto-width">
                    <span></span>
                    <p><strong>Error!</strong>
					<?php foreach($errMessage as $e){
                            echo " * " . $e . "<br />";
                    } ?></p>
                </div><!-- /notification-->
			<?php }
			} ?>
          		<div class="col-lg-12">
					<div class="form-panel">
					<h4 class="mb"><i class="fa fa-angle-right"></i> Password Reset</h4>
					<p>At this time, please check your email and look for the temporary password sent.  Type in the temporary password, then type in a new password and verify the new password.
					Your new password needs to be at least 8 characters long.</p>
					<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-horizontal tasi-form">
                              <div class="form-group">
							  <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Temporary Password</label>
                                  <div class="col-lg-10">
                                      <input type="text" name="temppass" id="temppass" class="form-control" autocomplete="off">
                                  </div>
                              </div>
                              <div class="form-group">
							  <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">New Password</label>
                                  <div class="col-lg-10">
                                      <input type="password" name="password" id="password" class="form-control" autocomplete="off">
                                  </div>
                              </div>
                              <div class="form-group">
							  <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Verify New Password</label>
                                  <div class="col-lg-10">
                                      <input type="password" name="verpass" id="verpass" class="form-control" autocomplete="off">
                                  </div>
                              </div>
							  <div class="form-group">
                                  <div class="col-lg-10">
						<center><button type="submit" class="btn btn-primary btn-lg">Submit</button></center>
						</div>
                              </div></form>
          		</div>
			</div>
			<?php } else {
				if ($_SERVER['REQUEST_METHOD'] == "POST") {
					if (empty($_POST['user'])) {
						$registerError[0] = "Username is required";
					} else {
						$username = test_input($_POST['user']);
						$query = "SELECT * FROM cust_data WHERE username = '$username'";
						$result = $test_db->query($query);
						$result = $result->fetch_assoc();
						if($result == ''){
							$registerError[0] = "This username does not exist.";
						}
					}

					if (empty($_POST['hashcode'])) {
						$registerError[1] = "Hashcode is required";
					} else {
						$hashcode = $_POST['hashcode'];
						$checkhash = "SELECT * FROM cust_data WHERE username = '$username'";
						$result = $test_db->query($checkhash);
						$hash_rows = mysqli_num_rows($result);
						$result = $result->fetch_assoc();
						if ($hash_rows == 1 && $hashcode == $result['emailhash']){
							$verify_user = "UPDATE cust_data SET emailval='TRUE', emailhash='1' WHERE emailhash = '$hashcode'";
							$result = $test_db->query($verify_user);
							if($result){
								$_SESSION['status'] = "validated";
								header("Location:login.php");
								exit();
							}
						} else {
							$registerError[1] = "Hashcode is incorrect.";
						}
					}

				} ?>
				<div class="row mt">
          		<div class="col-lg-12">
                  <div class="form-panel">
						<h3>Account Verification</h3>
						<p>At this time, please check your email and click the link inside.  If you have not received the email verification, please click below to request another email to be sent.
						Once you verify your account, you'll be able to have full access to the website.</p>
						<center><a href="#" class="btn btn-theme">Resend Email Verification</a></center><br>
						<p>Have the code from your email?  Past the code here:</p>
						<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="form-horizontal style-form">
	<?php if(!empty($registerError[0])){
	unset($_SESSION['showregister']);?>
	<div class="form-group has-error has-feedback">
	<label class="col-sm-2 col-sm-2 control-label" for="inputError0"><?php echo $registerError[0]; ?></label>
	<div class="col-sm-10"><input type="text" name="user" id="inputError0" placeholder="Username" class="form-control" autocomplete="off">
	<span class="glyphicon glyphicon-remove form-control-feedback"></span></div>
	</div>
	<?php } else { ?>
		<div class="form-group">
			<label class="col-sm-2 col-sm-2 control-label">Username</label>
			<div class="col-sm-10">
				<input type="text" name="user" placeholder="Username" class="form-control" autocomplete="off">
			</div>
		</div>
	<?php }
	if(!empty($registerError[1])){
	unset($_SESSION['showregister']);?>
	<div class="form-group has-error has-feedback">
	<label class="col-sm-2 col-sm-2 control-label" for="inputError1"><?php echo $registerError[1]; ?></label>
	<div class="col-sm-10"><input type="text" name="hashcode" placeholder="Verification Code" class="form-control" autocomplete="off">
	<span class="glyphicon glyphicon-remove form-control-feedback"></span></div>
	</div>
	<?php } else { ?>
			<div class="form-group">
				<label class="col-sm-2 col-sm-2 control-label">Verification Code</label>
				<div class="col-sm-10">
					<input type="text" name="hashcode" placeholder="Verification Code" class="form-control" autocomplete="off">
				</div>
			</div>
	<?php } ?>
						<center><button class="btn btn-theme" type="submit">Submit</button></center></form>
			<?php } ?>
			 </div>
          		</div><!-- col-lg-12-->
          	</div>
          		</div>
          	</div>
		</section>
      </section>
      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              2014 - Alvarez.is
              <a href="accountpending.php#" class="go-top">
                  <i class="fa fa-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
    <!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>
    <!--script for this page-->
  <script>
      //custom select box
      $(function(){
          $('select.styled').customSelect();
      });
  </script>
  </body>
</html>
