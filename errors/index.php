<?php
if(isset($_GET['code'])){
$errorcode = $_GET['code'];
}

if(isset($_SERVER['HTTP_REFERER'])){
$pagename = $_SERVER['HTTP_REFERER'];
} else {
	$pagename = "No File";
}

require '../assets/include/db.php';
global $path;
?>
<!DOCTYPE HTML>
<html>
	<head>
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-107492978-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments)};
  gtag('js', new Date());

  gtag('config', 'UA-107492978-1');
</script>
		<title>Error Code <?php echo $errorcode; ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="<?php echo $path;?>errors/assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="<?php echo $path;?>errors/assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="<?php echo $path;?>errors/assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="<?php echo $path;?>errors/assets/css/ie9.css" /><![endif]-->
	</head>
	<body>
		<!-- Header -->
			<header id="header">
				<h1>Error Code <?php echo $errorcode; ?></h1>
				<?php if($errorcode == "fatal"){ ?>
				<p>The page you were trying to access could not be loaded due to a server error.  The error has been sent to our team to start working on it right away.</p>
				<p>Lets get you back on track here: <a href="<?php echo $path;?>">Click Here</a></p>
				<?php }
				if($errorcode == "500"){ ?>
				<p>Oh Dear!  Looks like we have an issue on our end.  The problem has been reported to the proper authority and will be resolved fairly quickly.<br>
				Please be patient and we will have it up and running in a snap.  Lets get you back on track here: <a href="<?php echo $path;?>">Click Here</a></p>
				<?php }
				if($errorcode == "404"){ ?>
				<p>Uh... Looks like you took a wrong turn.  Lets get you back on track here: <a href="<?php echo $path;?>">Click Here</a><br>
				If you continue to have issues, please contact an administrator.</p>
				<?php logError('5', $pagename, '1','Dead link. No page found.');?>
				<?php } ?>
			</header>
		<!-- Footer -->
			<footer id="footer">
				<ul class="icons">
					<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
					<li><a href="#" class="icon fa-github"><span class="label">GitHub</span></a></li>
					<li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
				</ul>
				<ul class="copyright">
					<li>&copy; DRM Web Design</li>
				</ul>
			</footer>
		<!-- Scripts -->
			<!--[if lte IE 8]><script src="<?php echo $path;?>errors/assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="<?php echo $path;?>errors/assets/js/main.js"></script>
	</body>
</html>
