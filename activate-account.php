<?php
session_start();
require_once 'appcode/confignormal.php';

require_once 'formr/class.formr.php';

$form = new Formr('bootstrap');

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';



$table = "tbluser";

$msg  = '';
$isError = false;

if( '' != trim($_GET['activate']) )
{


	$sqlSelect = ' select id, is_email_verified from   '.$table.' where email_verify_token = ? ';
	$db->query( $sqlSelect, $_GET['activate'] );
	$numRows = $db->numRows();
	$data = $db->fetchRow();

	if( $numRows > 0 )
	{

		if( $data['is_email_verified'] == '1' )
		{
			$msg = 'Your account is already verified. Please <a href="index.php">click here</a> to go to login page.';
		}
		else
		{
			$sqlUpdate = 'update '.$table.' set is_email_verified = "1", email_verify_token = "" where email_verify_token = ? ';
			if( $db->query($sqlUpdate, $_GET['activate'] ))
			{

				$msg = 'Congratulations! your account is successfully verified. Please <a href="index.php">click here</a> to go to login page.';
			}
			else
			{
				$isError = true;
				$msg = 'Technical problem. Please try again later.';
			}

		}
	}
	else
	{
		header("location:index.php");
		exit(0);
	}

}
else
{
	header("location:index.php");
	exit(0);
}

if( isset($_POST['submit']))
{
	$msg = '1';
	if( '' == trim($_POST['password']) )
	{
		$isError = true;
		$msg = 'Password is required.';
	}

	if( '1' == $msg )
	{
		if( trim($_POST['rpassword']) != trim($_POST['password']) )
		{
			$msg = 'Password and Retype Password not matched.';
			$isError = true;
		}
	}

	if( '1' == $msg )
	{
		$sqlUpdatePass = ' update '.$table.' set password = ?, forgot_token = ""  where forgot_token = ? ';
		if( $db->query($sqlUpdatePass, array( md5(trim($_POST['password'])), $_GET['tkn']) ) )
		{
			$msg = 'Password successfully changed. Log In and start your work.';
		}
	}

}


?>

<!doctype html>
<html>
<head>
	<meta charset="UTF-8">

	<meta name="viewport"

	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<meta http-equiv="X-UA-Compatible" content="ie=edge">

</head>
<title>Login | <?php echo SITE_TITLE; ?></title>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css?ver=1">
<body>

<div class="container  bg-light py-5 ">
	<div class="row">
		<div class="col-md-12 bg-primary">
			<h2 class="text-center text-white p-2">Email Verification</h2>
		</div>
	</div>

	<div class=" col-md-4 d-block mx-auto">

		<div class="row">
			<div class="col-md-12">
				<?php include_once 'messages.php'; ?>
			</div>
		</div>

	</div>
</div>

<script src="js/jquery-3.4.1.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
