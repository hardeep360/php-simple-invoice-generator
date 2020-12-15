<?php

require_once 'session-check.php';


require_once 'appcode/confignormal.php';

require_once 'formr/class.formr.php';

$form = new Formr('bootstrap');

$msg = '';

$isError = false;

$mode = 'add';



function validation($arr){

    foreach ($arr as $val => $value)
    {

         if ($val == 2) {
           continue;
         }

        if(trim($value) == ''){
            return "All the fields are required";
        }

    }
    return '1';
}



$uid = $_SESSION['id'];
$sql = "SELECT id FROM tblcompany WHERE  userid = ?";
$arr =array();
$arr[] = $uid;
$db->query($sql,$arr);
$companyRow = $db->fetchRow();

$compid = $companyRow['id'];

if ( isset( $_POST['addcustomer'] ) && $_POST['_token'] == $_SESSION['_token'] &&  '' != trim($_SESSION['_token']) ) {

	$msg = checkInsert( $arr );


	if ( $msg == '1' ) {

		$sql = 'insert into tblcustomers ( customer_name, company_name, address1, address2, email, phone, website, adddate, currency_id , company_id ) values ( ?, ? , ?, ?,  ? , ?, ?, ?, ?,? ) ';

		$arr = array();

		$arr[] = $_POST['customer_name'];
		$arr[] = $_POST['company_name'];

		$arr[] = $_POST['address1'];

		$arr[] = $_POST['address2'];

		$arr[] = $_POST['email'];

		$arr[] = $_POST['phone'];

		$arr[] = $_POST['website'];

		$arr[] = date( "Y-m-d H:i:s" );

		$arr[] = intval($_POST['currency']);



		$arr[] = $compid;


		$query = $db->query( $sql, $arr );

		if ( $query ) {
			$msg   = "Data Inserted Successfully";
			$_POST = array();


		} else {


			//print_r( $db->getErrorMsg() );
			$isError = true;

			$msg = 'Problem to save company.';

		}

	} else {
		$isError = true;


	}

}


$id = '';
function checkInsert()

{

    global $form;

    $msg = '';

    if( '' == trim($form->post('customer_name'))  )

    {

        $msg = 'Customer Name is required.<br />';

    }

    if( '' == trim($_POST['currency']) )
    {
	    $msg .= 'Currency is required.<br />';
    }

    if( strlen($msg) > 5 )

    {

        return $msg;

    }

    return '1';

}


$sql  = "SELECT id,country,symbol FROM tblcurrencies";

$db->query($sql);

$result = $db->fetchRows();

//$customer_name = $company_name = $name = $address1 = $website = $address2 = $email = $phone = '';


?>

<!doctype html>

<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"

    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Add Customer | <?php echo SITE_TITLE; ?></title>

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include_once 'bin/menu.php'; ?>

    <div class="container " >

        <div class="col-8 offset-2"  >

            <br /><br />
            <h3 class="text-center pad-bottom-20 border-bottom" >Add Customer</h3>
            <br />


            <?php include_once 'messages.php'; ?>

            <?php require_once 'form-customer-add.php'; ?>

        </div>



    </div>

</div>

<script src="js/jquery-3.4.1.js"></script>

<script src="js/bootstrap.js"></script>

</body>

</html>
