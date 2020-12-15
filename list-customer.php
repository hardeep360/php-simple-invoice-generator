<?php

require_once 'session-check.php';


require_once 'appcode/confignormal.php';

require_once 'formr/class.formr.php';


$form = new Formr('bootstrap');

$msg = '';

$isError = false;
$userId = $_SESSION['id'];


if (isset($_POST['Delete'])) {

	$dlt = $_POST['dlt_id'];

	$sql = "DELETE FROM tblcustomers WHERE id = '$dlt'";

	if ($db->query($sql)) {

		$msg = "Record deleted successfully";

	}

	else{

		$isError = true;
		$msg = "Problem to delete record";

	}


}




// list of companies
$sqlCompany = ' select id from tblcompany where userid = ? ';
$db->query( $sqlCompany,  $userId );
$rowCompany = $db->fetchRow();
$companyId = $rowCompany['id'];


$sql1 = 'select * from tblcustomers where  company_id = ? order by customer_name asc ';

$db->query($sql1, $companyId );

$results = $db->fetchRows();
?>

<!doctype html>

<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>

	<meta charset="UTF-8">

	<meta name="viewport"

	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>List of Customers | <?php echo SITE_TITLE; ?></title>

	<link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<?php include_once 'bin/menu.php'; ?>

<div class="container " >

	<div class="col-8 offset-2"  >

		 <br /><br />
        <h3 class="text-center pad-bottom-20 " >List of Customers</h3>
        <br />

		<?php include_once 'messages.php'; ?>


	</div>


	<!-- list of companies -->

	<table class="table table-light ">

		<thead>

		<tr>

			<th>Customer Name</th>

			<th>Company Name</th>

			<th>Addres 1</th>

			<th>Addres 2</th>

			<th>Email</th>

			<th>Phone</th>

			<th>Website</th>

			<th>Add Date</th>

			<th>Action</th>

		</tr>

		</thead>

		<tbody>

		<?php foreach ( (array) $results as $row ) { ?>

			<tr>

				<td><?php echo $row['customer_name']; ?></td>

				<td><?php echo $row['company_name']; ?></td>

				<td><?php echo $row['address1']; ?></td>

				<td><?php echo $row['address2']; ?></td>

				<td><?php echo $row['email']; ?></td>

				<td><?php echo $row['phone']; ?></td>

				<td><?php echo $row['website']; ?></td>

				<td>
					<div style="width: 100px;">
					<?php echo date("M d Y", strtotime($row['adddate'])); ?>
					</div>
				</td>


				<td>

					<div style="width: 150px;">

						<a href="edit-customer.php?id=<?php echo $row['id']; ?>" name="edit" class="btn btn-success d-inline-block" >Edit</a>

						&nbsp;&nbsp;

						<form action="" method="post" class="d-inline-block">

							<input type="hidden" name="dlt_id" value="<?php echo $row['id']; ?>">

							<input type="submit" value="Delete" name="Delete" class="btn btn-danger d-inline-block" onclick="return confirm('Are you sure to delete?');">


						</form>

					</div>

				</td>

			</tr>

		<?php } ?>

		</tbody>

	</table>

</div>

</div>

<script src="js/jquery-3.4.1.js"></script>

<script src="js/bootstrap.js"></script>

</body>

</html>
