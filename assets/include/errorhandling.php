<?php
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
$dbhost = "localhost";
$dbname = "dromproj_pricecompare";
$dbuser = "dromproj";
$dbpass = "jesus321";
global $path;
global $connect_db;
$connect_db = new mysqli();
$connect_db->connect($dbhost, $dbuser, $dbpass, $dbname);
$connect_db->set_charset("utf8");
function errorHandler($error_level, $error_message, $error_file, $error_line, $error_context)
{
$error = $error_message . " on line " . $error_line;
switch ($error_level) {
    case E_ERROR:
    case E_CORE_ERROR:
    case E_COMPILE_ERROR:
    case E_PARSE:
        mylog($error, $error_file, "fatal");
        break;
    case E_USER_ERROR:
    case E_RECOVERABLE_ERROR:
        mylog($error, $error_file, "error");
        break;
    case E_WARNING:
    case E_CORE_WARNING:
    case E_COMPILE_WARNING:
    case E_USER_WARNING:
        mylog($error, $error_file, "warn");
        break;
    case E_NOTICE:
    case E_USER_NOTICE:
        mylog($error, $error_file, "info");
        break;
    case E_STRICT:
        mylog($error, $error_file, "debug");
        break;
    default:
        mylog($error, $error_file, "warn");
}
}
function shutdownHandler() {
$lasterror = error_get_last();
switch ($lasterror['type']) {
    case E_ERROR:
    case E_CORE_ERROR:
    case E_COMPILE_ERROR:
    case E_USER_ERROR:
    case E_RECOVERABLE_ERROR:
    case E_CORE_WARNING:
    case E_COMPILE_WARNING:
    case E_PARSE:
        $error = "Major Failure: " . $lasterror['message'] . " on line " . $lasterror['line'];
        mylog($error, $lasterror['file'], "fatal");
	}
}
function mylog($errormess, $pagename, $errlvl) {
	global $connect_db;	global $path;
		$remove[] = "'";
		$remove[] = '"';
		$clear = str_replace( $remove, "", $errormess );
		date_default_timezone_set("America/New_York");
		$timestamp = date("Y-m-d h:i:sa");
		$cust_id = "1";
		$query = "INSERT INTO errorlog (errormessage, filename, timestamp, cust_id) VALUES ('$clear','$pagename','$timestamp','$cust_id')";
		$result = $connect_db->query($query);
		if(!$result){
			 exit("Errormessage: " . mysqli_error($connect_db));
		}
		$to = 'webmaster@gopriceshop.com'; // Send email to our user
		$from = "noreply@gopriceshop.com";
		$subject = 'Major Failure on Website'; // Give the email a subject
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: '.$from."\r\n".
		'Reply-To: '.$from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		$message = '<html><body>';
		$message .= '<h1>Major Failure!</h1>';
		$message .= '<p>Issue happened at ' . $timestamp . ' - The problem is ' . $errormess .'</p>';
		$message .= '<p>File: ' . $pagename .'</p>';
		$message .= '</body></html>';
		if(mail($to, $subject, $message, $headers)){
			header("Location:". $path ."errors/index.php?code=fatal");
		}else{
			header("Location:". $path ."errors/index.php?code=fatal");
		}
}
?>
