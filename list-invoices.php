<?php

require_once 'session-check.php';
require_once 'appcode/confignormal.php';
require_once 'appcode/pagination.class.php';

$msg = '';
$isError = false;
if( isset($_POST['Delete']) && trim($_POST['dlt_id']) != '' )
{
    $sql1 = ' delete from tblinvoiceitem where invoiceid = ? ';
    if( $db->query( $sql1, $_POST['dlt_id'] ) ) {

	    $sql2 = ' delete from tblinvoice where id = ? ';
	    if ( $db->query( $sql2, $_POST['dlt_id'] ) ) {
		    $msg = 'Successfully deleted.';
	    } else {
		    $msg = 'Error to delete Main Invoice.';
		    $isError = true;
	    }
    }
    else
    {
	    $msg = 'Error to delete Invoice Items';
	    $isError = true;
    }
}


//update ispaid
if( isset($_POST['update_ispaid']) && isset($_POST['invid']) &&  '' != trim($_POST['invid'] ) )
{
	$sqlIsPaid = ' update tblinvoice set ispaid = if(ispaid = 1,0,1) where id = ? ';
	$db->query($sqlIsPaid, $_POST['invid'] );
}



//list of Companies
$sql = ' select id, concat ( customer_name, " ", company_name  ) as compname from tblcustomers where company_id = ? ';
$db->query($sql, $_SESSION['company_id']);

$customerRows = $db->fetchRows();
$appQuery = '';
$arrParam = array();
$arrParam[] = $_SESSION['company_id'];

if( isset($_GET['comp_name']) && '' != trim($_GET['comp_name']) )
{
    $appQuery = ' and t1.tocompanyid = ? ';
	$arrParam[] = $_GET['comp_name'];
}


if( isset($_GET['fromdate'])  && '' != trim($_GET['fromdate']) &&  isset($_GET['todate']) && '' != trim($_GET['todate']) )
{

    $appQuery = ' and t1.invoicedate between ? and ? ';
    $toDate = date("Y-m-d H:i:s", strtotime($_GET['todate']));
    $fromDate = date("Y-m-d H:i:s", strtotime($_GET['fromdate']));
    
    $arrParam[] = $fromDate;
    $arrParam[] = $toDate; 
	

}
else if( isset($_GET['fromdate'])  && '' != trim($_GET['fromdate']) )
{
    $fromDate = date("Y-m-d H:i:s", strtotime($_GET['fromdate']));

	$appQuery = ' and datediff( t1.invoicedate, "'.$fromDate.'"  ) >= 0  ';


}
else if( isset($_GET['todate'])  && '' != trim($_GET['todate']) )
{
	$toDate = date("Y-m-d H:i:s", strtotime($_GET['todate']));
	$appQuery = ' and datediff( t1.invoicedate, "'.$toDate.'"  ) <= 0  ';


}

if( isset($_GET['paid_status']) &&  '' != trim($_GET['paid_status']) )
{
	$appQuery .= ' and t1.ispaid = ? ';
	$arrParam[] = $_GET['paid_status'];
}

// list of invoices
$itemsperpage = 50;

$sqlCount = ' select count(t1.id) as cnt  from tblinvoice  as t1 where t1.fromcompanyid = ? '.$appQuery;
$db->query( $sqlCount, $arrParam );
$totalRecords  = 0;
if( $db->numRows() > 0 ) {
	$rowCnt       = $db->fetchRow();
	$totalRecords = $rowCnt['cnt'];
}



$defaultpagernumber = isset( $_GET['page'] ) ?  $_GET['page'] : 1 ;

if ($itemsperpage > 0) {

	$defaultpagernumber = max( 1, isset($_GET['page']) ? $_GET['page'] : 1 );

	$limit = " LIMIT " . (($defaultpagernumber - 1) * $itemsperpage) . ",$itemsperpage";
} else {
	$limit = " LIMIT $itemsperpage";
}



$queryString = $_SERVER['QUERY_STRING'];
parse_str($queryString , $arr);
//print_r($arr);
unset( $arr['page'] );
$newQueryString = http_build_query( $arr );
$p = new pagination;
$p->Items($totalRecords);
$p->limit($itemsperpage);
$p->target(basename($_SERVER['PHP_SELF']).($newQueryString != '' ) ? '?'.$newQueryString : '' );
$p->currentPage($defaultpagernumber);



$sql = 'select *, t1.id as invid, t1.invoiceId, t1.ispaid, t3.name as fromname, t3.company_name as fromCompanyName, t5.name as toname
 from tblinvoice  as t1
 left join (select t2.id, t2.name, t2.company_name from tblcompany as t2 ) as t3 on t3.id = t1.fromcompanyid
 left join (select t4.id, concat(  t4.customer_name, " ", company_name) as name from tblcustomers as t4 ) as t5 on t5.id = t1.tocompanyid where t1.fromcompanyid = ? '.$appQuery.'
order by t1.adddate desc '.$limit;


$db->query($sql, $arrParam  );
$results = $db->fetchRows();

//tblcurrencies
$totalsArr = array();
$currencyIdsArr = array(); 
foreach( (array) $results as $res )
{
    $currencyIdsArr[] = $res['currencysign'];
    if( isset($totalsArr[$res['currencysign']])  )
    {
        $totalsArr[$res['currencysign']] += $res['grandtotal'];
    }
    else {
        $totalsArr[$res['currencysign']] = $res['grandtotal'];
    }
}

$resCurrencies = array();
$resCurrencyArr = array();
if( count( $currencyIdsArr ) > 0 ){
    $sqlSelectCurrency = ' select  * from tblcurrencies where id in ( '.implode(',', $currencyIdsArr).' ) ';
    $db->query( $sqlSelectCurrency );
    $resCurrencies = $db->fetchRows();
    foreach( $resCurrencies as $currency ){
        $resCurrencyArr[$currency['id']] = $currency['code'];
    }
}
?>
<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Company</title>
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/paginator.css">
	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="datepicker/css/bootstrap-datepicker.css">


</head>
<body>

<?php include_once 'bin/menu.php'; ?>


<div class="container " >


	<div class="col-8 offset-2"  >
		<br /><br />
		<h3 class="text-center pad-bottom-20 border-bottom" >List of Invoices</h3>
        <br />
		<?php include_once 'messages.php'; ?>

	</div>


<form method="get">
     <div class="row">
         <div class="col-md-6">
             Customer/Company Name<br />
                <select name="comp_name" class="form-control custom-select">
                    <option value="">Select Customer/Company</option>
                    <?php
                        foreach ( (array) $customerRows as $customer )
                        {
                            ?>
                            <option value="<?php echo $customer['id']; ?>" <?php if( isset($_GET['comp_name']) &&  $customer['id'] == $_GET['comp_name'] ) { echo 'selected';  } ?>><?php echo $customer['compname']; ?></option>
                    <?php
                        }
                    ?>

                </select>
         </div>

         <div class="col-md-2">
             Paid Status<br />
             <select name="paid_status" class="custom-select">
                 <option value="">Select</option>
                 <option value="1" <?php if( isset($_GET['paid_status']) && '1' == $_GET['paid_status']  ) { echo "selected"; } ?>>Paid</option>
                 <option value="0"  <?php if( isset($_GET['paid_status']) && '0' == $_GET['paid_status']  ) { echo "selected"; } ?>>Not Paid</option>
             </select>
         </div>
         <div class="col-md-2">
                From Invoice date<br /><input autocomplete="off" type="text" class="form-control dtpicker" name="fromdate" value="<?php if( isset($_GET['fromdate']) ) { echo $_GET['fromdate'];  } ?>">
         </div>

         <div class="col-md-2">
                To Invoice date<br /><input type="text" autocomplete="off"  class="form-control dtpicker" name="todate" value="<?php if( isset($_GET['todate']) ) { echo $_GET['todate'];  } ?>">
         </div>

         <div class="col-md-2">
                &nbsp;<br/>
    <input type="submit" class="btn btn-success" name="search" value="Search">
         </div>

     </div>
</form>
    <br />
    <div class="row">
        <div class="col-md-12" style="text-align: right;">
			<?php echo $p->show(); ?>
        </div>
    </div>
    <br />
	<!-- list of companies -->
	<table class="table table-light ">
		<thead>
		<tr>
			<th>Inv ID</th>
		    <th>To Company</th>
			<th>Subtotal</th>
			<th>Tax</th>
			<th>Grand Total</th>

			<th>Invoice Date</th>
			<th>Due Date</th>
			<th>Paid</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($results as $row ) { ?>
			<tr>
				<td><?php echo $row['invoiceId']; ?></td>

				<td><?php echo $row['toname']; ?></td>
				<td><?php echo $row['subtotal']; ?></td>
				<td><?php echo $row['taxtotal']; ?></td>
				<td><?php echo $row['grandtotal']; ?></td>

				<td><?php echo date("d M Y", strtotime($row['invoicedate'])); ?></td>
				<td><?php echo date("d M Y", strtotime($row['invoiceduedate'])); ?></td>
                <td>
                <form method="post">
                    <input type="hidden" name="invid" value="<?php echo $row['invid']; ?>" />
                    <?php if( $row['ispaid'] == '1'  ) {
                            ?>
                            <input class="btn btn-success" type="submit" name="update_ispaid" value="Yes" >
                            <?php 
                    } else { ?>
         <input class="btn btn-danger" type="submit" name="update_ispaid" value="No" >
                    <?php } ?>
                     

                </form>
                </td>
                <td>
                    <div style="width: 150px;">
                    <a href="edit-invoice.php?id=<?php echo $row['invid']; ?>" name="edit" class="btn btn-success d-inline-block">Edit</a>
                        &nbsp;&nbsp;
                    <form action="" method="post" class="d-inline-block">

                        <input type="hidden" name="dlt_id" value="<?php echo $row['invid']; ?>">

                        <input type="submit" value="Delete" name="Delete" class="btn btn-danger d-inline-block" onclick="return confirm('Are you sure to delete?');">


                    </form>
                    </div>
                </td>
			</tr>
		<?php } ?>

        <?php foreach( (array) $totalsArr as $key => $total ) {
            ?>
            <tr style="background-color: #efefef;">
                <th style="text-align: right;" colspan="4">Total</th>
                <th colspan="5" style="text-align: left;">
                    <?php echo $resCurrencyArr[$key].' '.$total; ?>
                </th>
            </tr>
            <?php 
    }
    ?>

		</tbody>
	</table>
    <br />

    
    <div class="row">
        <div class="col-md-12" style="text-align: right;">
			<?php echo $p->show(); ?>
        </div>
    </div>
    <br />
</div>
</div>

<script src="js/jquery-3.4.1.js"></script>
<script src="js/bootstrap.js"></script>
<script src="datepicker/js/bootstrap-datepicker.js"></script>
<script>
    jQuery(document).ready(function() {
        $(".dtpicker").datepicker();
    });
</script>
</body>
</html>
