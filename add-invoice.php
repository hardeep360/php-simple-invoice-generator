<?php
require_once 'session-check.php';
require_once 'appcode/confignormal.php';


$isError = false;
$msg = '';

$userId = $_SESSION['id'];
$sqlCompany = ' select * from tblcompany where userid = ? ';
$db->query( $sqlCompany,  $userId );
$rowCompany = $db->fetchRow();
$companyId = $rowCompany['id'];
$ownCompanyName =   $rowCompany['company_name'];
$address = $rowCompany['address1'].( $rowCompany['address2'] != '' ? '<br />'.$rowCompany['address2'] : '' );
$phone = $rowCompany['phone'];
//$currencyId = $rowCompany['currency_id'];

$sqlCurrency = ' select id, concat( code) as code from tblcurrencies ';
$db->query( $sqlCurrency );
$rowCurrency = $db->fetchRows();



$ownCompanyName .= '<br />'.$address.'<br />'.$phone;
if( isset($_POST['save']) && $_POST['_token'] == $_SESSION['_token'] &&  '' != trim($_SESSION['_token']) )
{
    $msg = check();
    if( '1' == $msg )
    {
        $types = '';
        if( 'qnty' == $_POST['invoice_type']  )
        {
            $types = 'Quantity';
        }
        else
        {
	        $types = 'Hourly';
        }
        $sql = ' insert into  tblinvoice (invoiceId, invoicedate, invoiceduedate, currencysign, types, fromcompanyid, tocompanyid, adddate, subtotal, taxtotal, discounttotal, discounttype, discountvalue, grandtotal ) value ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?   ) ';
        $invoiceDateArr = explode("/", $_POST['invoice_data']);
        $dueDateArr = explode("/", $_POST['invoice_due_date']);
        $arr = array();
        $arr[] = $_POST['invoice_no'];
        $arr[] = $invoiceDateArr[2].'-'.$invoiceDateArr[1].'-'.$invoiceDateArr[0]; //$_POST['invoice_data'];
        $arr[] =  $dueDateArr[2].'-'.$dueDateArr[1].'-'.$dueDateArr[0]; //$_POST['invoice_due_date'];
        $arr[] = $_POST['currency_symbol'];
	    $arr[] = $types;
	    $arr[] = $companyId;
	    $arr[] = $_POST['SelectToCompany'];
	    $arr[] = date("Y-m-d H:i:s");
	    //$arr[] = date("Y-m-d H:i:s");
	    $arr[] = $_POST['final_subtotal'];
	    $arr[] = $_POST['final_tax'];
	    $arr[] = '0';
	    $arr[] = '0';
	    $arr[] = '0';
	    $arr[] = $_POST['final_grand_total'];

	    if( $db->query($sql, $arr))
        {
            $insertId = $db->insertID();

            if(0 < $insertId)
            {
                //TODO: add invoice item
                if( $types == 'Hourly' )
                {
                    if( !addHourlyItemsInDb($db, $_POST, $insertId ) )
                    {
                        $isError = true;
                        $msg = 'Error to add items.';
                    }
                }
                else
                {
                    if( !addQuantityItemsInDb( $db, $_POST, $insertId ) )
                    {
	                    $isError = true;
	                    $msg = 'Error to add items.';
                    }
                }

                if( $msg == '1' )
                {
                    $_SESSION['msg'] = 'Successfully added';
                    header("location: edit-invoice.php?id=".$insertId);
                    exit();
                }
            }

        }
        else
        {
	        $isError = true;
	        $msg = $db->getErrorMsg();

        }
    }

    //if( $_POST[''] )
}

function addQuantityItemsInDb( $db, $dataArr, $invoiceId )
{
    $x = 0;
    foreach ( $dataArr['item_name'] as $itemName )
    {
	    $itemName = $dataArr['item_name'][$x];
        $qnty = floatval($dataArr['item_qnty'][$x]);
        $price = floatval($dataArr['item_price'][$x]);
        $tax = floatval($dataArr['item_tax_percent'][$x]);
        $grandTotal = floatval($dataArr['item_sub_total'][$x]);
        if( $qnty > 0 ) {
	     $subTotalReal = $price * $qnty;
        $taxAmount = $subTotalReal * $tax / 100;

	     $sql = ' insert into tblinvoiceitem ( itemname, invoiceid, qnty1, priceperunit, discountpercent, dicountamount, taxpercent, taxtotal, subtotal, grandtotal, adddate ) values ( ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ? ) ';
	     $arr = array();
	     $arr[] = $itemName;
	     $arr[] = $invoiceId;
	     $arr[] = $qnty;
	     $arr[] = $price;
	     $arr[] = 0;
	     $arr[] = 0;
	     $arr[] = $tax;
	     $arr[] = $taxAmount;
	     $arr[] = $subTotalReal;
	     $arr[] = $grandTotal;
	     $arr[] = date("Y-m-d H:i:s");

	     if(!$db->query($sql, $arr) )
         {

             return false;
         }

         }
        $x++;
    }

    return true;

}



function addHourlyItemsInDb( $db, $dataArr, $invoiceId )
{
	$x = 0;
	foreach ( $dataArr['h_item_name'] as $itemName )
	{
		$itemName = $dataArr['h_item_name'][$x];
		$hours = floatval($dataArr['h_item_hours'][$x]);
		$mnts = floatval($dataArr['h_item_mnts'][$x]);
		$price = floatval($dataArr['h_item_price'][$x]);
		$tax = floatval($dataArr['h_item_tax_percent'][$x] );

		$grandTotal = floatval($dataArr['h_item_sub_total'][$x]);

		if( $hours > 0 ||  $mnts > 0 ) {

			$subTotalReal = $price * ($hours  + ( $mnts / 60 ));
			$taxAmount = $subTotalReal * $tax / 100;

			 $sql = ' insert into tblinvoiceitem ( invoiceid, itemname, qnty1, qnty2, priceperunit, discountpercent, dicountamount, taxpercent, taxtotal, subtotal, grandtotal, adddate ) values ( ?, ?, ?, ?, ?,  ?, ?, ?, ?, ?, ?, ? ) ';
			$arr = array();
			$arr[] = $invoiceId;
			$arr[] = $itemName;

			$arr[] = $hours;
			$arr[] = $mnts;
			$arr[] = $price;
			$arr[] = 0;
			$arr[] = 0;
			$arr[] = $tax;
			$arr[] = $taxAmount;
			$arr[] = $subTotalReal;
			$arr[] = $grandTotal;
			$arr[] = date("Y-m-d H:i:s");

			if(!$db->query($sql, $arr) )
			{
			    print_r( $db->getErrorMsg() );
				return false;
			}

		}
		$x++;
	}

	return true;

}


function check()
{
    $msg = '';

   /* if( '' == trim($_POST['SelectFromCompany']) )
    {
        $msg = 'From Company is required. '."<br />";
    } */

	if( '' == trim($_POST['SelectToCompany']) )
	{
		$msg .= 'To Company is required. '."<br />";
	}

	if( '' == trim($_POST['invoice_no']) )
	{
		$msg .= 'Invoice No. is required. '."<br />";
	}


	if( '' == trim($_POST['invoice_data']) )
	{
		$msg .= 'Invoice Date is required. '."<br />";
	}

    if( '' == trim($_POST['invoice_due_date']) )
	{
		$msg .= 'Invoice Due Date is required. '."<br />";
	}

    if( '' == trim($_POST['invoice_type']) )
	{
		$msg .= 'Invoice Type is required. '."<br />";
	}

	/*if( trim($_POST['invoice_type']) == 'hourly' )
    {
        if( isset($_POST['h_item_name']) && is_array($_POST['h_item_name'] ) && count($_POST['h_item_name'] ) > 0 )
        {

        }
        else
        {

        }
    }
    else if( trim($_POST['invoice_type']) == 'qnty' )
    {

    } */



    if(strlen($msg) > 5)
    {
        return $msg;
    }

    return '1';
}


$rowCompanyArr = array();
$sqlSelectCompany = ' select *  from tblcompany order by name asc ';
if( $db->query($sqlSelectCompany) )
{
	$resCompany = $db->fetchRows();


	foreach ($resCompany as $row)
	{
		$rowCompanyArr[$row['id']] = $row;
	}
}

$rowCustomerArr = array();
//echo $companyId;
$sqlSelectCustomers = ' select *  from tblcustomers where company_id = ? order by company_name asc ';
$customerCurrencyStr = '';
if( $db->query($sqlSelectCustomers, $companyId ) )
{
    $resCustomers = $db->fetchRows();

    foreach ( $resCustomers as $customer )
    {
	    $customerCurrencyStr .= '{"'.$customer['id'].'" : "'.$customer['currency_id'].'"},';

    }
}

$sqlInvoiceCount = sprintf(' select id, invoiceId from tblinvoice where fromcompanyid = ?  order by id desc limit 1 ');
$db->query($sqlInvoiceCount, $_SESSION['company_id']);
$numRowsInvoice = $db->numRows();
if( $numRowsInvoice > 0) {
	$rsInvoiceCount = $db->fetchRow();
	$invNumber      = $rsInvoiceCount['invoiceId'] + 1;
}
else
{
    $invNumber = 1;
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add-Invoice</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="datepicker/css/bootstrap-datepicker.css">
    <!--<link rel="stylesheet" href="jquery/jquery-ui.css"> -->

</head>
<body>
<?php include_once 'bin/menu.php'; ?>
<form method="post" action="">
    <input type="hidden" name="_token" value="<?php echo ($_SESSION['_token'] = md5(rand(11111,999999))); ?>">
<div class="container">
<br />
    <div class="row">
        <div class="col-10 offset-1">
            <?php include_once 'messages.php'; ?>
        </div>
        <div class="col-10 offset-1">
            <div class="row">

                <div class="col-6">

                    <p>

                        FROM

                    </p>
                    <div style="border: 1px dashed #ccc; padding:10px;">
                    <?php echo $ownCompanyName; ?>
                    </div>



                    <div id="from_company_address">


                    </div>
                </div>

                <div class="col-6 ">
                    <p>

                        TO

                    </p>

                    <select required name="SelectToCompany" id="SelectToCompany" class="form-control" >
                        <option value="">Select</option>

	                    <?php
                        $jsArrCurrency = '';
	                    if( is_array($resCustomers) && count($resCustomers) > 0 )
	                    {
		                    foreach ($resCustomers as $row)
		                    {

			                    ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['customer_name']; ?></option>
			                    <?php
		                    }
	                    }
	                    ?>

                    </select>
                    <div id="to_company_address"></div>
                </div><!-- ./col -->

            </div><!-- ./row -->
            <br /><br />

        </div><!-- ./col -->

    </div> <!-- ./row -->

</div> <!-- ./container -->
<br />
<div class="container">

    <div class="row adjust">
        <div class="col-10 offset-1">
            <div class="row">

                <div class="col-6">

                    <div>Invoice No* <input type="text" id="invoice_no" name="invoice_no" value="<?php echo $invNumber; ?>"  required >
                    <div id="invoice_error_msg"  style="color: red;"></div>
                    </div><br />

                </div>
            </div>
            <div class="row">
                <div class="col-6 ">
                <div>Invoice Date* <input required class="dtpicker" type="text" name="invoice_data"  data-date-format="dd/mm/yyyy" style="width: 100px;" value="<?php echo date("d/m/Y"); ?>" ></div>
                </div>


                <div class="col-6 ">


                    <div>Due Date* <input required type="text" class="dtpicker" name="invoice_due_date" data-date-format="dd/mm/yyyy" value="<?php echo date("d/m/Y"); ?>" > </div>


                </div>

            </div>

            <div class="row">

                <div class="col-6">
                    <br />
                    Invoice Type* <select required id="invoice_type" name="invoice_type" class="form-control">
                           <option value="qnty">Quantity</option>
                        <option value="hourly">Hourly</option>
                    </select>
                </div>

                <div class="col-6">
                    <br />
                    Currency Symbol*
                    <select required id="currency_symbol" name="currency_symbol" class="form-control">
                        <option value="">Select Currency</option>
                        <?php
                            foreach( $rowCurrency as $rr ) {

	                            $jsArrCurrency .= '{ "id" : "'.$rr['id'].'", "code" : "'.$rr['code'].'" },';
                                ?>
                            <option value="<?php echo $rr['id']; ?>" ><?php echo $rr['code']; ?></option>
                        <?php
                            }
                        ?>
                        <option value="qnty">Quantity</option>
                        <option value="hourly">Hourly</option>
                    </select>
                </div>


            </div>


            <div id="quantity_type_items" class="row " style="margin-top: 60px;">
                <div class="col-12">
                    <table class="table table-hover" >
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Tax %</th>
                            <th>Sub Total</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody id="hs_tbody">

                        </tbody>

                    </table>

                    <button type="button" style="display: block; margin: 10px auto;" id="add_new_inv_item" class="btn btn-outline-dark">Add New Item</button>
                </div>
            </div>


            <div id="hour_type_items" class="row " style="margin-top: 60px; display: none;">
                <div class="col-12">
                    <table class="table table-hover" >
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Hours </th>
                            <th>Mnts. </th>
                            <th>Hourly Price</th>
                            <th>Tax %</th>
                            <th>Sub Total</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                        <tbody id="hs_tbody_hourly">

                        </tbody>

                    </table>

                    <button type="button" style="display: block; margin: 10px auto;" id="add_new_inv_item_hourly" class="btn btn-outline-dark">Add New Item</button>
                </div>
            </div>

            <div   class="row " style="margin-top: 60px;">
                    <div class="col-12">
                        <table class="table table-light">
                            <tr>
                                <td></td>
                                <td>Subtotal</td>
                                <td>
                                    <span class="hs_currency_sign_class"></span> <input type="text" name="final_subtotal" id="final_subtotal" value="" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Tax</td>
                                <td>
                                    <span class="hs_currency_sign_class"></span> <input type="text" name="final_tax" id="final_tax" value="" readonly>
                                </td>
                            </tr>

                            <tr>
                                <td><div style="width: 230px;"></div> </td>
                                <td>Grand Total</td>
                                <td>
                                   <span class="hs_currency_sign_class"></span> <input type="text" name="final_grand_total" id="final_grand_total" value="" readonly>
                                </td>
                            </tr>

                        </table>
                    </div>
            </div>

        </div>

    </div>


    <div class="clearfix"></div>
    <div class="row adjust">
        <div class="col-10 offset-1">
            <input type="submit" name="save" value="Save" class="btn btn-primary float-right " onclick="return validateData()" >


        </div>
    </div>

    <br /><br />
</div>

</form>


<script src="js/jquery-3.4.1.js"></script>
<script src="js/bootstrap.js"></script>
<script src="datepicker/js/bootstrap-datepicker.js"></script>

<!--<script src="jquery/jquery-ui.js"></script> -->
<script>

    var currencyArr = [<?php echo rtrim($jsArrCurrency,','); ?>];
    var customerCurrencyArray = [ <?php echo rtrim($customerCurrencyStr, ','); ?> ];
    var  quantityItemsContainer = document.getElementById('quantity_type_items');
    var hourlyItemsContainer = document.getElementById('hour_type_items');

    var qnty_inputs = document.getElementsByClassName("qnty_inputs");


    var tablebody = document.getElementById("hs_tbody");
    var tablebodyHourly = document.getElementById("hs_tbody_hourly");

    var buttonAddInvoiceItem = document.getElementById("add_new_inv_item");
    var btn_add_new_inv_item_hourly = document.getElementById("add_new_inv_item_hourly");

    var invoice_typeObj = document.getElementById("invoice_type");
    var final_grand_total = document.getElementById("final_grand_total");
    var final_tax = document.getElementById("final_tax");
    var final_subtotal = document.getElementById("final_subtotal");
    var invoiceNoError = false;

    function validateHourlyItems()
    {
        //validate hourly items
        var msg = '';
        var itemFound = 0;
        var rows = $("#hs_tbody_hourly").find("tr");
        if( rows.length <= 0 )
        {
            msg = "Please enter atlest one item.";

        }
        else
        {
            for( x = 0; x < rows.length; x++ )
            {
                var itemNameArr = $(rows[x]).find(".h_item_name");

                if( $.trim($(itemNameArr[0]).val())  != "" )
                {
                    itemFound = 1;
                    var itemHourArr = $(rows[x]).find(".h_item_hours");
                    var itemMntArr = $(rows[x]).find(".h_item_mnts");
                    var hourlyPriceArr = $(rows[x]).find(".h_item_price");
                    if( $.trim( $(itemHourArr[0]).val() ) == '' && $.trim($(itemMntArr).val()) == '' )
                    {
                        msg += 'Item hours or mnts are required.\n';
                    }

                    if( $.trim( $(hourlyPriceArr[0]).val() ) == '' )
                    {
                        msg += 'Item prices are required.\n';
                    }

                }


            }
        }

        if( itemFound == 0 )
        {
            msg = "Please enter atlest one item.";

        }

        return msg;
    }



    function validateQntyItems()
    {
        //validate hourly items
        var msg = '';
        var itemFound = 0;
        var rows = $("#hs_tbody").find("tr");
        if( rows.length <= 0 )
        {
            msg = "Please enter atlest one item.";

        }
        else
        {
            for( x = 0; x < rows.length; x++ )
            {
                var itemNameArr = $(rows[x]).find(".item_name");

                if( $.trim($(itemNameArr[0]).val())  != "" )
                {
                    itemFound = 1;
                    var itemQnty = $(rows[x]).find(".item_qnty");

                    var priceArr = $(rows[x]).find(".item_price");

                    if( $.trim( $(itemQnty[0]).val() ) == ''  )
                    {
                        msg += 'Item Quantities are required.\n';
                    }

                    if( $.trim( $(priceArr[0]).val() ) == '' )
                    {
                        msg += 'Item prices are required.\n';
                    }

                }


            }
        }

       if( itemFound == 0 )
        {
            msg = "Please enter atlest one item.";

        }

        return msg;
    }



    function validateData()
    {
        var msg = '';
        var itemFound = 0;
        var invoiceTypeVal = $("#invoice_type").val();

        if( invoiceNoError == true )
        {
            alert("Invoice No. already exist. Please choose another one.");
            return false;
        }

        if( invoiceTypeVal == 'qnty' )
        {
            //validate qnty items
            msg = validateQntyItems();
        }
        else
        {
            msg = validateHourlyItems();
        }


        if( msg != '' )
        {
            alert(msg);
            return false;
        }

        return true;
    }

   function calculateAll(inputObj)
   {

        var invoiceTypeVal = invoice_typeObj.value;

        if( invoiceTypeVal == 'hourly' )
        {
            var itemNode = inputObj.parentNode.parentNode.childNodes[0];
            var hoursNode = inputObj.parentNode.parentNode.childNodes[1];
            var mntsNode = inputObj.parentNode.parentNode.childNodes[2];
            var priceNode = inputObj.parentNode.parentNode.childNodes[3];
            var taxNode = inputObj.parentNode.parentNode.childNodes[4];
            var subTotalNode = inputObj.parentNode.parentNode.childNodes[5];

            var itemNodeInput = itemNode.childNodes[0];
            var hoursNodeInput = hoursNode.childNodes[0];
            var mntsNodeInput = mntsNode.childNodes[0];
            var priceNodeInput = priceNode.childNodes[0];
            var taxNodeInput = taxNode.childNodes[0];
            var subTotalNodeInput = subTotalNode.childNodes[0];


            var totalPrice = ( (hoursNodeInput.value * 1) + (mntsNodeInput.value * 1) / 60) * priceNodeInput.value;

            var totalTax = totalPrice * taxNodeInput.value / 100;

            subTotalNodeInput.value = (totalPrice + totalTax).toFixed(2);;

            calculateTotalAmounts(invoiceTypeVal);
        }
        else{

            var itemNode = inputObj.parentNode.parentNode.childNodes[0];
            var quantityNode = inputObj.parentNode.parentNode.childNodes[1];
            var priceNode = inputObj.parentNode.parentNode.childNodes[2];
            var taxNode = inputObj.parentNode.parentNode.childNodes[3];
            var subTotalNode = inputObj.parentNode.parentNode.childNodes[4];

            var itemNodeInput = itemNode.childNodes[0];
            var quantityNodeInput = quantityNode.childNodes[0];
            var priceNodeInput = priceNode.childNodes[0];
            var taxNodeInput = taxNode.childNodes[0];
            var subTotalNodeInput = subTotalNode.childNodes[0];


            var totalPrice = quantityNodeInput.value * priceNodeInput.value;

            var totalTax = totalPrice * taxNodeInput.value / 100;

            subTotalNodeInput.value = (totalPrice + totalTax).toFixed(2);

            calculateTotalAmounts(invoiceTypeVal);

        }

   }

   function calculateTotalAmounts(invoiceTypeVal)
   {
        if( invoiceTypeVal == 'hourly' )
        {
            var finalSubTotal = 0;
            var finalTotalTax = 0;
            var finalGrandTotal = 0;

            var allRows = tablebodyHourly.getElementsByTagName("tr");


            for( var x = 0;  x < allRows.length; x++ )
            {
                var trNode =  allRows[x];

                var itemNode = trNode.childNodes[0];
                var hoursNode = trNode.childNodes[1];
                var mntsNode = trNode.childNodes[2];
                var priceNode = trNode.childNodes[3];
                var taxNode = trNode.childNodes[4];
                var subTotalNode = trNode.childNodes[5];

                var itemNodeInput = itemNode.childNodes[0];
                var hoursNodeInput = hoursNode.childNodes[0];
                var mntsNodeInput = mntsNode.childNodes[0];
                var priceNodeInput = priceNode.childNodes[0];
                var taxNodeInput = taxNode.childNodes[0];
                var subTotalNodeInput = subTotalNode.childNodes[0];


                var totalPrice = ( (hoursNodeInput.value * 1 ) + (mntsNodeInput.value * 1) / 60 ) * priceNodeInput.value;

                var totalTax = totalPrice * (taxNodeInput.value * 1) / 100;

                finalSubTotal += totalPrice;
                finalTotalTax += totalTax;
                finalGrandTotal +=  totalPrice +  totalTax;

            }

            final_grand_total.value = finalGrandTotal.toFixed(2);;
            final_subtotal.value = finalSubTotal.toFixed(2);;
            final_tax.value = finalTotalTax.toFixed(2);;
        }
        else
        {
            var finalSubTotal = 0;
            var finalTotalTax = 0;
            var finalGrandTotal = 0;

            var allRows = tablebody.getElementsByTagName("tr");


            for( var x = 0;  x < allRows.length; x++ )
            {
             var trNode =  allRows[x];

                var itemNode = trNode.childNodes[0];
                var quantityNode = trNode.childNodes[1];
                var priceNode = trNode.childNodes[2];
                var taxNode = trNode.childNodes[3];
                var subTotalNode = trNode.childNodes[4];

                var itemNodeInput = itemNode.childNodes[0];
                var quantityNodeInput = quantityNode.childNodes[0];
                var priceNodeInput = priceNode.childNodes[0];
                var taxNodeInput = taxNode.childNodes[0];
                var subTotalNodeInput = subTotalNode.childNodes[0];


                var totalPrice = quantityNodeInput.value * priceNodeInput.value;

                var totalTax = (totalPrice * 1 ) * taxNodeInput.value / 100;

                finalSubTotal += totalPrice;
                finalTotalTax += totalTax;
                finalGrandTotal +=  totalPrice +  totalTax;

            }

            final_grand_total.value = finalGrandTotal.toFixed(2);;
            final_subtotal.value = finalSubTotal.toFixed(2);;
            final_tax.value = finalTotalTax.toFixed(2);;
        }
   }


    function createTdWithInput(name, width, classes, isReadOnly  )
    {
        var td1 = document.createElement('td');
        var input1 = document.createElement('input');

        input1.setAttribute('type','text');

        input1.setAttribute('name',name);
        input1.setAttribute("class", classes );

        if( typeof isReadOnly != 'undefined' )
        {
            input1.setAttribute('readonly','readonly');
        }

        input1.onblur = function(){
            calculateAll(input1);
        }

        input1.onkeyup = function(){
            calculateAll(input1);
        }

        input1.style.width = width;
        td1.appendChild(input1);

        return td1;
    }

    function deleteItem(node, tbody )
    {
        tbody.removeChild(node.parentNode.parentNode) ;

    }
    function createTdWithDeleteButton(id)
    {
        var td1 = document.createElement('td');
        var input1 = document.createElement('button');
        input1.setAttribute('type',"button");
        input1.setAttribute('id',id);
        input1.setAttribute('value','Delete');
        input1.innerHTML =  "Delete" ;

        td1.appendChild(input1);

        if( id == 'delete_item_btn_hourly' )
        {
            input1.onclick = function() {
                if( confirm("Are you sure to delete?")  ){


                deleteItem(input1, tablebodyHourly)
                calculateTotalAmounts('hourly');
                }
            };
        }
        else
        {
            input1.onclick = function() {
                if( confirm("Are you sure to delete?")  ) {
                    deleteItem(input1, tablebody);
                    calculateTotalAmounts('quantity');
                }

            };
        }




        return td1;
    }






    buttonAddInvoiceItem.onclick = function(){
        var nodes = document.createElement('tr');
        nodes.appendChild(createTdWithInput('item_name[]', "350px", "qnty_inputs item_name"));
        nodes.appendChild(createTdWithInput('item_qnty[]', "80px", "qnty_inputs item_qnty"));
        nodes.appendChild(createTdWithInput('item_price[]', "100px", "qnty_inputs item_price"));
        nodes.appendChild(createTdWithInput('item_tax_percent[]', "50px", "qnty_inputs item_tax_percent"));
        nodes.appendChild(createTdWithInput('item_sub_total[]', "150px", "qnty_inputs item_sub_total", 'readonly'));
        nodes.appendChild(createTdWithDeleteButton('delete_item_btn'));

        tablebody.appendChild(nodes);
    }


        btn_add_new_inv_item_hourly.onclick = function () {

            var nodes = document.createElement('tr');
            nodes.appendChild(createTdWithInput('h_item_name[]', "350px", "hourly_inputs h_item_name"));
            nodes.appendChild(createTdWithInput('h_item_hours[]', "80px", "hourly_inputs h_item_hours"));
            nodes.appendChild(createTdWithInput('h_item_mnts[]', "80px", "hourly_inputs h_item_mnts"));
            nodes.appendChild(createTdWithInput('h_item_price[]', "100px", "hourly_inputs h_item_price"));
            nodes.appendChild(createTdWithInput('h_item_tax_percent[]', "50px", "hourly_inputs h_item_tax_percent"));
            nodes.appendChild(createTdWithInput('h_item_sub_total[]', "150px", "hourly_inputs h_item_sub_total",  'readonly'));
            nodes.appendChild(createTdWithDeleteButton('delete_item_btn_hourly'));

            tablebodyHourly.appendChild(nodes);
        }

    invoice_typeObj.onchange = function(){

        var selectedVal = invoice_typeObj.value;
        if( selectedVal == 'hourly' )
        {
            hourlyItemsContainer.style.display = "block";
            quantityItemsContainer.style.display = "none";
        }
        else
        {
            hourlyItemsContainer.style.display = "none";
            quantityItemsContainer.style.display = "block";
        }
    }

    function updateCurrencySymbol(currencyId)
    {
        for( k = 0; k < currencyArr.length; k++ )
        {
            //console.log( currencyArr[k]["id"] + " " + currencyId );
            if( currencyArr[k]["id"] == currencyId )
            {

                var currencyCode = currencyArr[k]["code"];
                $("#currency_symbol").val( currencyId );
                $(".hs_currency_sign_class").text( currencyCode );
                break;
            }
        }
    }



    function checkExistInvoiceNo(invoiceNo)
    {

        $.post("ajax.php", { "invoice_number" : invoiceNo, 'action' : 'check_invoiceno_exist' }, function(result) {

            if( result['error'] == '1' )
            {
                invoiceNoError = true;
                $("#invoice_error_msg").html( result['msg']);
            }
            else
            {
                invoiceNoError = false;
                $("#invoice_error_msg").html( '' );
            }
        }, "json");
    }



    jQuery(document).ready(function(){
        $(".dtpicker").datepicker( );
        //$(".dtpicker").datepicker();
       // $( ".dtpicker" ).datepicker( "option", "defaultDate", +7 );

        $("#invoice_no").on("blur", function(){
            checkExistInvoiceNo($(this).val());
        });

        $("#currency_symbol").on("change", function(){

            var selectedVal = $(this).val();
            if( selectedVal != '' )
            {
                updateCurrencySymbol(selectedVal);
            }

        });

        $("#SelectToCompany").on("change", function(){

            for( x = 0; x < customerCurrencyArray.length; x++ )
            {
                var selectedComapnyId = $(this).val();
                //console.log( selectedComapnyId );
                if( typeof  customerCurrencyArray[x][selectedComapnyId] != 'undefined') {
                    //console.log( customerCurrencyArray[x][selectedComapnyId]  );
                    var currencyId = customerCurrencyArray[x][selectedComapnyId];

                    updateCurrencySymbol(currencyId);
                    break;
                }

            }
        });




    });

</script>
</body>
</html>

