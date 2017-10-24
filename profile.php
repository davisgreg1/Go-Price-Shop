<?php
session_start();
require 'assets/include/db.php';
verifyUser();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard for Go Price Shop.">
    <meta name="author" content="DRM Web Design">
    <meta name="keyword" content="Go Price Shop, Dashboard">
    <title>Go. Price. Shop. - My Profile</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">
	<script src="https://use.fontawesome.com/f8c6e01c59.js"></script>
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <script src="assets/js/chart-master/Chart.js"></script>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <script src="assets/js/fb.js"></script>
  <section id="container" >
	  <?php require 'assets/include/header.php';?>
	  <?php require 'assets/include/sidebar.php';?>
      <section id="main-content">
          <section class="wrapper">
              <div class="row">
                  <div class="col-lg-9 main-chart">
                  	<div class="row mtbox">
                      <div class="col-md-4">
                                          <img src="assets/img/users/<?php echo $userinfo['id'];?>.jpg" class="img-rounded img-responsive" />
                                      </div>
                                      <div class="col-md-8">
                                          <div class="alert alert-info">
                                              <h2>Bio for <?php echo $userinfo['fname'] . " " . $userinfo['lname'] ;?> </h2>
                                              <p>Update the information below to update your public profile.</p>
                                          </div>
                                          <div class="form-group col-md-8">
                                            <h3>Personal Information</h3><br>
                                          <label>Registered Username</label>
                                          <input type="text" class="form-control" placeholder="<?php echo $userinfo['username'];?>">
                                          <label>Registered Email</label>
                                          <input type="text" class="form-control" placeholder="<?php echo $userinfo['email'];?>">
                                          <label>Registered Phone Number</label>
                                          <input type="text" class="form-control" placeholder="<?php echo $userinfo['phonenum'];?>">
                                          <br>
                                          <a href="#" class="btn btn-success">Update Details</a>
                                        </div>
                                          <div class="form-group col-md-8">
                                              <h3>Change Password</h3><br>
                                              <label>Enter Old Password</label>
                                              <input type="password" class="form-control">
                                              <label>Enter New Password</label>
                                              <input type="password" class="form-control">
                                              <label>Confirm New Password</label>
                                              <input type="password" class="form-control" />
                                              <br>
                                              <a href="#" class="btn btn-warning">Change Password</a>
                                          </div>
                                      </div>
                  	</div>



                  </div>
                  <div class="col-lg-3 ds">
                    <!--COMPLETED ACTIONS DONUTS CHART-->
						<h3>NOTIFICATIONS</h3>
                        <?php require 'assets/include/activity.php';?>

                       <!-- USERS ONLINE SECTION -->
						<h3>USERS ONLINE</h3>
						<?php require 'assets/include/users.php';?>
                        <div id="calendar" class="mb">
                            <div class="panel green-panel no-margin">
                                <div class="panel-body">
                                    <div id="date-popover" class="popover top" style="cursor: pointer; disadding: block; margin-left: 33%; margin-top: -50px; width: 175px;">
                                        <div class="arrow"></div>
                                        <h3 class="popover-title" style="disadding: none;"></h3>
                                        <div id="date-popover-content" class="popover-content"></div>
                                    </div>
                                    <div id="my-calendar"></div>
                                </div>
                            </div>
                        </div><!-- / calendar -->

                  </div><!-- /col-lg-3 -->
              </div><! --/row -->
          </section>
      </section>
      <?php require 'assets/include/footer.php';?>
    <script src="assets/js/sparkline-chart.js"></script>
	<script src="assets/js/zabuto_calendar.js"></script>
	<script type="application/javascript">
        $(document).ready(function () {
			timeago().render($('.need_to_be_rendered'));
            $("#date-popover").popover({html: true, trigger: "manual"});
            $("#date-popover").hide();
            $("#date-popover").click(function (e) {
                $(this).hide();
            });

            $("#my-calendar").zabuto_calendar({
                action: function () {
                    return myDateFunction(this.id, false);
                },
                action_nav: function () {
                    return myNavFunction(this.id);
                },
                ajax: {
                    url: "index.html",
                    modal: true
                },
                legend: [
                    {type: "text", label: "Special event", badge: "00"},
                    {type: "block", label: "Regular event", }
                ]
            });
        });

        function myNavFunction(id) {
            $("#date-popover").hide();
            var nav = $("#" + id).data("navigation");
            var to = $("#" + id).data("to");
            console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
        }
    </script>
  </body>
</html>
