<?php
require_once 'session-check.php';
require_once 'appcode/confignormal.php';

$userId = $_SESSION['id'];

if( $_REQUEST['action'] == 'check_invoiceno_exist' && '' != trim($_REQUEST['invoice_number'] ) )
{
	$sqlCompany = ' select id from tblcompany where userid = ? ';
	$db->query( $sqlCompany, $userId );
	$rowCompany = $db->fetchRow();
	$sql = 'select id from tblinvoice where invoiceId = ? and fromcompanyid = ? ';
	$db->query( $sql,  array( $_REQUEST['invoice_number'], $rowCompany['id'] );
	$numRows = $db->numRows();
	$arr = array();
	if( $numRows > 0 )
	{
		$arr['error'] = '1';
		$arr['msg'] = 'Invoice no. already exist.';
	}
	else {
		$arr['error'] = '0';
	}

	echo json_encode($arr);
	exit();
}

if( $_REQUEST['action'] == 'check_invoiceno_exist_for_update' && '' != trim($_REQUEST['invoice_number'] ) && '' != trim($_REQUEST['invoiceid'] )  )
{

	$sqlCompany = ' select id from tblcompany where userid = ? ';
	$db->query( $sqlCompany, $userId );
	$rowCompany = $db->fetchRow();

	$sql = 'select id from tblinvoice where invoiceId = ? and id != ? and  fromcompanyid = ? ';
	$db->query( $sql,  array( $_REQUEST['invoice_number'], $_REQUEST['invoiceid'], $rowCompany['id'] ) );
	$numRows = $db->numRows();
	$arr = array();

	if( $numRows > 0 )
	{
		$arr['error'] = '1';
		$arr['msg'] = 'Invoice no. already exist.';
	}
	else {
		$arr['error'] = '0';
	}

	echo json_encode($arr);
	exit();
}

