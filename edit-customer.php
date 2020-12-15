<?php

require_once 'session-check.php';


require_once 'appcode/confignormal.php';

require_once 'formr/class.formr.php';


$form = new Formr('bootstrap');

$msg = '';
$isError = false;

$id = '';
if( isset($_GET['id'])){
	$id = $_GET['id'];
}

$uid = $_SESSION['id'];
$sql = "SELECT id FROM tblcompany WHERE  userid = ?";
$arr =array();
$arr[] = $uid;
$db->query($sql,$arr);
$companyRow = $db->fetchRow();
$compid = $companyRow['id'];


if ( isset($_POST['Update']) && $_POST['_token'] == $_SESSION['_token'] &&  '' != trim($_SESSION['_token']) ) {

	$val = array();

	$val[] = $_POST['customer_name'];
	$val[] = $_POST['company_name'];

	$val[] = $_POST['address1'];

	$val[] = $_POST['address2'];

	$val[] = $_POST['email'];

	$val[] = $_POST['phone'];

	$val[] = $_POST['website'];

	$val[] = date("Y-m-d H:i:s");

	$val[] = $_POST["currency"];




	$val[] = $id;
	$val[] = $_SESSION['company_id'];


	$msg =  checkInsert();

	if($msg == '1')
	{


		$sql = " UPDATE tblcustomers SET customer_name = ?, company_name = ?,  address1 = ? , address2 = ?, email = ?, phone = ?, website = ? , adddate = ?,currency_id = ?  WHERE id = ? and company_id = ? ";


		if ($db->query($sql, $val ))
		{
			$msg = 'Successfully Updated';

		}

		else

		{

			// print_r( $db->getErrorMsg() );

			$isError = true;

			$msg = 'Problem to update company.';

		}
	}
	else{
		$isError = true;
	}

}



if(isset($_GET['msg'])){

	$msg = str_replace('-', ' ',$_GET['msg']);
}



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

$customer_name = $company_name = $address1 = $address2 = $email = $phone = $website = $currency = '';
if( isset($_GET['id'])){


	$sql = "select * from tblcustomers where id = ? and company_id = ? ";

	$arr = array();

	$arr[] = $id;
	$arr[] = $compid;

	$db->query($sql,$arr);

	$row = $db->fetchRow();
	$customer_name = $row['customer_name'];
	$company_name = $row['company_name'];
	$address1 = $row['address1'];
	$address2 = $row['address2'];
	$email = $row['email'];
	$phone = $row['phone'];
	$website = $row['website'];
	$currencyId = $row['currency_id'];

}

$sql  = "SELECT id,country,symbol FROM tblcurrencies";
$db->query($sql);
$resultCurrency = $db->fetchRows();


?>

<!doctype html>

<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>

	<meta charset="UTF-8">

	<meta name="viewport"

	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<meta http-equiv="X-UA-Compatible" content="ie=edge">

	<title>Update Customer | <?php echo SITE_TITLE; ?></title>

	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">

</head>

<body>

<?php include_once 'bin/menu.php'; ?>

<div class="container " >

	<div class="col-8 offset-2"  >

		<br />
        <br />
		<h3 class="text-center pad-bottom-20 border-bottom" >Update Customer</h3>
<br />
		<?php include_once 'messages.php'; ?>

		<?php require_once 'form-customer-update.php'; ?>

	</div>

</div>

</div>

<script src="js/jquery-3.4.1.js"></script>

<script src="js/bootstrap.js"></script>

</body>

</html>
