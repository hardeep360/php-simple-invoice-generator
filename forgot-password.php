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

if( isset($_POST['submit']))
{
	if( isset($_SESSION['_token']) && '' != trim($_SESSION['_token']) && trim($_POST['_token']) == $_SESSION['_token']  )
	{
		$email = trim ( $_POST['email'] );
		if( '' == $email || !filter_var( $email, FILTER_VALIDATE_EMAIL ) )
		{
			$isError = true;
			$msg = 'Valid email is required.';
		}
		else {

			$sql = ' select id, username, email from '.$table.' where email = ? ';
			$db->query($sql, $email );
			$numRows = $db->numRows();
			if( $numRows > 0 )
			{
			    $data = $db->fetchRow();
				$tokenOfPassword = md5(strtotime(date("YmdHiS")).$data['id']);
				$sqlUpdate = ' update '.$table.' set forgot_token = ? where id = ? ';
				$db->query( $sqlUpdate, array( $tokenOfPassword, $data['id'] ) );
				$link = SERVER_PROTOCOL.'://'.$_SERVER['HTTP_HOST'].'/update-password.php?tkn='.$tokenOfPassword;

				$body = 'Hello '.$data['username'].', '."<br /><br />";
				$body .= 'Please click on the following link to reset your password.' ."<br />";
                $body .= '<a href="'.$link.'">'.$link.'</a><br /><br /><br />';
                $body .= 'Thank you,'."<br />";
				$body .= 'Team Simple Invoice Generator'."<br />";

				sendMail( $data['email'], $data['username'], $body, 'Password Reset -> Simple Invoice Generator', FROM_EMAIL, FROM_EMAIL_NAME, NO_REPLY_EMAIL ,  NO_REPLY_NAME  );

				$msg = 'Password reset email is sent to your register email address. Please follow the instructions in the email and reset your password.';
				$_POST = array();
            }
			else
			{
				$isError = true;
				$msg = 'Sorry! This email address not found in our system.';
			}

		}

	}
	else
	{
		$isError = true;
		$msg = 'Invalid access.';
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
			<h2 class="text-center text-white p-2">Simple Invoice Generator</h2>
		</div>
	</div>

	<div class=" col-md-4 d-block mx-auto">

		<form action="" method="POST" >

			<input type="hidden" name="_token" value="<?php echo ($_SESSION['_token'] = md5(rand(11111,99999999))); ?>">
			<div>
				<h3 class="my-5 text-center border-bottom pad-bottom-20">Forgotten Password</h3>
				<div class="form-row">
					<div class="col-md-12">

						<?php include_once 'messages.php'; ?>
						<?php echo $form->input_text('email','Please enter your email address*', '', '', ' required ')?>
					</div>
				</div>

				<div class="form-row">

					<div class="col-md-12">
						<br />
						<input type="submit" name="submit" value="Send Password Reset Link" class="btn btn-primary block-100-percent">

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
