<?php

require_once 'session-check.php';

require_once 'appcode/confignormal.php';

require_once 'formr/class.formr.php';




$form = new Formr('bootstrap');
$table = "tblcompany";
$msg = '';
$uid = $_SESSION['id'];
$isError = false;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function validation($arr){
    foreach ($arr as $key => $value) {

        if( '' == trim($value) ) {
	        return "All the fields are required";
        }
     }
      return 1;
}

if (isset($_GET['msg'])) {
  $msg = str_replace('-', ' ', $_GET['msg']);
}

if (isset($_POST['submit'])) {

    $uid = $_SESSION['id'];

	$validCheckArr = array();
	$validCheckArr[] = $_POST['name'];
	$validCheckArr[] = $_POST['company_name'];
	$validCheckArr[] = $_POST['address1'];
	$validCheckArr[] = $_POST['email'];
	$validCheckArr[] = $_POST['phone'];
	//$validCheckArr[] = $_POST['website'];
	$msg = validation($validCheckArr);

	if( '1' == $msg ) {
		$sql = " select * from $table where  userid = ? ";
		$db->query( $sql, $uid );

		$rows = $db->numRows();

		$arr   = array();
		$arr[] = $_POST['name'];
		$arr[] = $_POST['company_name'];
		$arr[] = $_POST['address1'];
		$arr[] = $_POST['address2'];

		$arr[] = $_POST['email'];
		$arr[] = $_POST['phone'];
		$arr[] = $_POST['website'];
		$arr[] = date( "Y-m-d H:i:s" );
		$arr[] = $_SESSION['id'];

		if ( $rows > 0 ) {

			  $sql = "Update  $table set name = ?, company_name = ?, address1 = ?,address2 = ?, email = ?, phone = ?, website = ? ,adddate = ? where userid = ?";

		} else {
			$sql = "INSERT INTO  $table ( name, company_name, address1, address2, email, phone, website, adddate, userid  ) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
		}

		$query = $db->query($sql, $arr );
		if ($query) {
			$msg = "Company updated";
		} else{
			$isError = true;
			$msg = "Problem to update company";
		}

	}
}



$name = $company_name = $address1 = $address2 = $email = $phone = $website = '';

$sql = "select * from $table where  userid = ?";

$db->query($sql, $uid);

$rows = $db->numRows();

if ($rows > 0) {

 $company = $db->fetchRow();
 $name = $company['name'];
 $company_name =  $company['company_name'];
 $address1 =  $company['address1'];
 $address2 =  $company['address2'];
 $email =  $company['email'];
 $phone =  $company['phone'];
 $website =  $company['website'];

}


?>
<html>
    <head>

    <meta charset="UTF-8">

    <meta name="viewport"

    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="css/bootstrap.css">

    </head>

    <title>Update Company Info | <?php echo SITE_TITLE; ?></title>
    <link rel="stylesheet" href="css/style.css?ver=1">
    <body>
     <?php include_once 'bin/menu.php'; ?>


    <div class="container  " >

        <div class="col-8 offset-2"  >

            <br />

            <h3 class="text-center pad-bottom-20 border-bottom" >Update Company Info</h3>
<br />

          <?php echo $form->form_open('','','','post');?>
            <div class="form-row">


                <div class="col-md-12">
	        <?php include_once 'messages.php'; ?>
                </div>
            </div>

            <div class="form-row">


                <div class="col-md-12">

			        <?php echo $form->input_text('name', 'Name*',$name); ?>

                </div>

            </div>


            <div class="form-row">


	<div class="col-md-12">

		<?php echo $form->input_text('company_name', 'Company Name*',$company_name); ?>

	</div>

</div>
<div class="form-row">
	<div class=" col-md-12">
		<?php echo $form->input_text('address1', 'Address 1*',$address1); ?>

	</div>


</div>

<div class="form-row">
	<div class=" col-md-12">
		<?php echo $form->input_text('address2', 'Address 2',$address2); ?>

	</div>


</div>


<div class="form-row">
	<div class="col-md-6">

		<?php echo $form->input_text('email', 'Email*',$email); ?>

	</div>

	<div class="form-group col-md-6">

		<?php echo $form->input_text('phone', 'Phone*',$phone); ?>

	</div>


</div>
<div class="form-row">
	<div class="form-group col-md-12">

		<?php echo $form->input_text('website', 'Website',$website); ?>

	</div>
</div>




<div class="col-md-12">



	<?php
    echo $form->input_submit('submit','', 'Update Company Info', '', ' class="btn btn-primary float-right" ' );
?>


</div>

<?php echo $form->form_close(); ?>


        </div>
    </div>


     <script src="js/jquery-3.4.1.js"></script>
     <script src="js/bootstrap.js"></script>


    </body>

</html>
