<?php
require_once 'appcode/confignormal.php';

require_once 'formr/class.formr.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';



$form = new Formr('bootstrap');
$table = "tbluser";

$msg  = '';
$isError = false;

function  validation($arr){

    foreach($arr as $val => $values) {
        if (trim($values) == '') {
      return "All the fields are required";
        }
    }
    return '1';


}

function isEmailUsernameExist( $userName, $email, $db, $table )
{
  $sql1 = "SELECT * FROM $table WHERE username = ? or email = ?";
  $db->query($sql1, array( $userName, $email ));
   $rows = $db->numRows();
     if($rows > 0){
      return true;

     }

     return false;
}

if (isset($_POST['submit'])) {

  $msg = validation($_POST);

  if( '1' == $msg )
  {
     if ( $_POST['password'] != $_POST['retypepassword'] ) {
        $msg = "Password not matched";
    }
  }

  if( '1' == $msg )
  {

    $userName = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

	  if( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
	      $msg = 'Please enter valid email address.'.'<br />';
	      $isError = true;
	  }
	  if( '1' == $msg ) {
		  $emailUserExist = isEmailUsernameExist( $userName, $email, $db, $table );

		  if ( $emailUserExist == true ) {

			  $msg     = "This username or email is already in use. Please choose another one.";
			  $isError = true;
		  }
	  }
     if( '1' == $msg )
     {
	     $verificationToken = md5($email.'-'.date("Y-m-d H:i:s"));

         $sql = "INSERT INTO $table ( username, password, email, email_verify_token ) VALUES ( ?, ?, ?, ? )";
         $arr = array( $userName, md5($password), $email, $verificationToken );
         $query = $db->query($sql,$arr);
         if ($query) {
                $ID = $db->insertID();
             $sqlInsertCompany = 'insert into tblcompany (name, adddate, userid ) values ( ?, ?, ? )  ';
             $db->query( $sqlInsertCompany, array( '', date("Y-m-d H:i:s"), $ID  ) );

            // header("location:index.php?msg=User-Successfully-Added");

	         $msg = 'Your account is successfully created. We have sent you confirmation email. Please active your account by follow email instructions.';

	         $toEmail  = $email;
	         $toName   = $userName;
	         $body     = 'Hello '.$toName.', <br /><br />You have succesfully registered with us. Please activate your account by clicking on link below.<br />';
	         $siteLink = SERVER_PROTOCOL . '://' . $_SERVER['HTTP_HOST'] . '/activate-account.php?activate=' . $verificationToken;
	         $body     .= '<a href="' . $siteLink . '">' . $siteLink . '</a>';
	         $subject  = ' Activation email on ' . $_SERVER['HTTP_HOST'];
	         sendMail( $toEmail, $toName, $body, $subject, FROM_EMAIL, FROM_EMAIL_NAME, NO_REPLY_EMAIL, NO_REPLY_NAME );


            // exit();
           }
          else{
              $msg = "Technical problem. Please try again later.";
              $isError = true;
          }
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
    <title>Register | <?php echo SITE_TITLE; ?></title>
       <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css?ver=1">
    <body>
    <div class="container  bg-light py-3">
        <br />
        <div class="row">
            <div class="col-md-12 bg-primary">
                <h2 class="text-center text-white p-2">Simple Invoice Generator</h2>
            </div>
        </div>

        <div class=" col-md-4 d-block mx-auto">

            <form action="" method="POST" >


                <div>

                    <h3 class="my-5 text-center border-bottom pad-bottom-20">Sign Up</h3>
                    <div class="form-row">
                        <div class="col-md-12">
                            <?php include_once './messages.php' ; ?>
                            <?php echo $form->input_text('username','Username*')?>

                        </div>
                    </div>
                    <div class="form-row">
                       <div class="col-md-12">
                        <?php echo $form->input_password('password','Password*')?>

                    </div>
                </div>
                  <div class="form-row">
                       <div class="col-md-12">
                        <?php echo $form->input_password('retypepassword','Retype-password*')?>

                    </div>
                </div>
                  <div class="form-row">
                       <div class="col-md-12">
                        <?php echo $form->input_email('email','Email*')?>

                    </div>
                </div>
                <div class="form-row">

                   <div class="col-md-12" >
                       <br />
                       <input class="btn btn-primary block-100-percent" type="submit" name="submit" value="Sign Up">
                       <br />
                </div>
                </div>

                    <div class="row border-top">
                        <div class="col-md-12 text-center">
                            <br />
                            <a href="index.php" class="d-block text-center m-auto block-100-percent">Click here to Login</a>
                        </div>
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
