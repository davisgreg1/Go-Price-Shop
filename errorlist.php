<?php
session_start();
require 'assets/include/db.php';
verifyAdmin(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="DRM Web Design">
    <meta name="keyword" content="Error List, Fatal, Error 404">
    <title>Go. Price. Shop. - Error List</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">
    <link href="assets/css/table-responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <section id="container" >
    <?php require 'assets/include/header.php';
    require 'assets/include/sidebar.php';?>
      <section id="main-content">
          <section class="wrapper site-min-height">
            <?php if(isset($_SESSION['deleted'])){ ?>
      			<div class="alert alert-success fade in alert-dismissable">
      				<a aria-label="close" class="close" data-dismiss="alert" href="#">&times;</a> <strong>Success!</strong> Error has been successfully deleted.
      			</div>
      			<?php unset($_SESSION['deleted']); }
      			    $query = "SELECT * FROM errorlog";
      			    $result = $test_db->query($query);
      			    $numerrors = mysqli_num_rows($result);?>
      			<h3>Most Recently Added Errors</h3>
      			<h4>Currently <?php echo $numerrors;?> error(s).<?php if($numerrors > 0){ ?> Would you like to clear error list? <a href="php/delete-error.php?id=all">Delete all errors</a><?php } ?></h4>
            <div class="row mt">
  			  		<div class="col-lg-12">
                <div class="content-panel">
                    <section id="unseen">
      			<table class="table table-bordered table-striped table-condensed">
      				<thead>
      					<tr>
      						<th>Time</th>
      						<th>File Name</th>
      						<th>Error</th>
      						<th>IP Address</th>
      						<th>Admin Controls</th>
      					</tr>
      				</thead>
      				<tbody>
      					<?php require 'assets/include/errors.php'; ?>
      				</tbody>
      			</table>
          </section>
        </div>
      </div>
    </div>
		</section>
      </section>
      <?php require 'assets/include/footer.php'; ?>
  </body>
</html>
