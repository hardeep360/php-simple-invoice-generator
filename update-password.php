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


if( isset($_GET['tkn']) && trim($_GET['tkn']) )
{
	$sql = ' select id from tbluser where  forgot_token = ? ';
	$db->query( $sql, $_GET['tkn'] );
	$numRows = $db->numRows();
	if( $numRows > 0 )
	{
		$data = $db->fetchRow();
	}
	else
	{
		header("location: index.php");
		exit(0);
	}
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
			<h2 class="text-center text-white p-2">Simple Invcoie Generator</h2>
		</div>
	</div>

	<div class=" col-md-4 d-block mx-auto">

		<form action="" method="POST" >

			<input type="hidden" name="_token" value="<?php echo ($_SESSION['_token'] = md5(rand(11111,99999999))); ?>">
			<div>
				<h3 class="my-5 text-center border-bottom pad-bottom-20">Reset Password</h3>

				<div class="row">
					<div class="col-md-12">
						<?php include_once 'messages.php'; ?>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-12">


						<?php echo $form->input_password('password','Password', '', '', ' required ')?>
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-12">


						<?php echo $form->input_password('rpassword', 'Retype Password', '', '', ' required ')?>
					</div>
				</div>

				<div class="form-row">

					<div class="col-md-12">
						<br />
						<input type="submit" name="submit" value="Update Password" class="btn btn-primary block-100-percent">

						<br /><br />
					</div>
				</div>



				<div class="row border-top">

					<div class="col-md-8 offset-md-2">
						<br /><br />
						<a class="btn btn-success d-block m-auto" href="index.php">Go to Log In Page</a>
					</div>
				</div>


			</div>
		</form>

	</div>
</div>

<script src="js/jquery-3.4.1.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
