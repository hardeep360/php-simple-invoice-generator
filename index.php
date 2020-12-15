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

if( isset($_SESSION['id']) && trim($_SESSION['id']) != '' )
{
    header("location:add_company.php");
}


function validation($username,$password){

    $msg = '';

    if( '' == trim($username) ){
        $msg = 'Username is required'."<br />";
    }

    if( '' == trim($password ) ){
      $msg .= 'Password is required'."<br />";
  }

  if( strlen($msg) > 5 ) {
    return $msg;
  }

  return '1' ;

}


if (isset($_GET['msg'])) {
   $msg = str_replace('-', ' ', ucfirst($_GET['msg']));
}

if( isset($_POST['resend_activation_email']) &&  $_POST['_token'] == $_SESSION['_token2'] && trim($_SESSION['_token2']) != '' && '' != trim($_POST['_email'] ) )
{


	$sqlSelect = 'select * from '.$table.' where username = ? or email = ? ';
	$db->query($sqlSelect, array($_POST['_email'] , $_POST['_email'] ));
	$numRows = $db->numRows();
	$row = $db->fetchRow();

	if( $numRows > 0 )
	{

		$verificationToken = md5($row['email'].'-'.date("Y-m-d H:i:s"));

        $sqlUpdate = ' update '.$table.' set email_verify_token = ? where id = ? ';
        $db->query( $sqlUpdate, array( $verificationToken, $row['id'] ) );

		$toEmail  = $row['email'];
		$toName   = $row['username'];

		$body     = 'Hello '.$toName.', <br /><br />You have succesfully registered with us. Please activate your account by clicking on link below.<br />';
		$siteLink = SERVER_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . '/activate-account.php?activate=' . $verificationToken;
		$body     .= '<a href="' . $siteLink . '">' . $siteLink . '</a>';
		$subject  = ' Activation email on ' . $_SERVER['HTTP_HOST'];
		sendMail( $toEmail, $toName, $body, $subject, FROM_EMAIL, FROM_EMAIL_NAME, NO_REPLY_EMAIL, NO_REPLY_NAME );
		$msg = 'Thank you! Activation email sent successfully.';
	}
	else
    {
        $msg = 'Technical problem. Please try again later.';
        $isError = true;
    }

}



if (isset($_POST['submit'])) {
 $username = $_POST['username'];
 $password = $_POST['password'];

    $msg = validation($username,$password);
    if( $msg == '1' ){

    $sql = "SELECT * FROM $table WHERE ( username = ? or email = ? ) and password = ?";

   $arr =array();
   $arr[] = $username;
   $arr[] = $username;
   $arr[] = md5($password);

  $db->query($sql,$arr);

  $rows = $db->numRows();



  if ( $rows > 0 ) {



    $user = $db->fetchRow();

    if( $user['is_email_verified'] == '1' ) {
	    $sqlCompany = ' select id from tblcompany where userid = ? ';
	    $db->query( $sqlCompany, $user['id'] );
	    $compRow = $db->fetchRow();
	    session_start();
	    $_SESSION['user']       = $user['username'];
	    $_SESSION['id']         = $user['id'];
	    $_SESSION['company_id'] = $compRow['id'];

	    header( "location:add_company.php" );
	    exit();
    }
    else
    {
	    $isError = true;
	    $_SESSION['_token2'] = md5(rand(11111,9999999));
	    $msg = '<form method="post">Look like your email address is not verified. Please resend activation email by clicking the link below.<br/><input type="hidden" name="_email" value="'.$user["email"].'"> <input type="hidden" name="_token" value="'.$_SESSION['_token2'].'"><input type="submit" class="hs-btn-primary" name="resend_activation_email" value="Resend">
</form>';
    }
  }
  else{
    $msg = "Invalid Username or Password.";
    $isError = true;
  }

}
else {
    $isError = true;
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


                <div>
                    <h3 class="my-5 text-center border-bottom pad-bottom-20">Login</h3>
                    <div class="form-row">
                        <div class="col-md-12">

                            <?php include_once 'messages.php'; ?>
                                <?php echo $form->input_text('username','Username/Email')?>
                        </div>
                    </div>
                    <div class="form-row">
                       <div class="col-md-12">
                        <?php echo $form->input_password('password','Password')?>
                    </div>
                </div>
                <div class="form-row">

                   <div class="col-md-12">
                       <br />
                    <input type="submit" name="submit" value="Log In" class="btn btn-primary block-100-percent">


                </div>
            </div>

                    <div class="row">

                        <div class="col-md-8 offset-md-2">
                            <br />
                            <a class="d-block m-auto text-center" href="forgot-password.php">Forgotten Password </a>
                            <br />
                        </div>
                    </div>

                    <div class="row border-top">

                        <div class="col-md-8 offset-md-2">
                            <br />
                            <a class="btn btn-success d-block m-auto" href="registration-form.php">Create New Account </a>
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
