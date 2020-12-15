<?php
namespace root\appcode;
use root\appcode\MyPDO;
spl_autoload_register(function ($className) {
	$path = str_replace( '\\', '/',str_replace( 'root\\','', $className.'.php'));
	if( file_exists($path) ) {
		require_once str_replace( '\\', '/', str_replace( 'root\\', '', $className . '.php' ) );
	}

   //throw new MissingException("Unable to load $name.");
});

define('SITE_TITLE', 'Simple Invoice Generator');
define('ADMIN_EMAIL', '');
define('ADMIN_NAME','Hardeep singh');
define('FROM_EMAIL', '');
define('FROM_EMAIL_NAME', 'Team Simple Invoice Generator');
define('NO_REPLY_EMAIL', '');
define('NO_REPLY_NAME', 'No Reply');
define('SERVER_PROTOCOL', 'http');


define('MSG_QUERY_INSERT_SUCCESS', 'Insert Successfully');
define('MSG_QUERY_ERROR', 'Error: Some Technical Problem');
define('MSG_QUERY_UPDATE_SUCCESS', 'Update Successfully');
define('MSG_QUERY_DELETE_SUCCESS', 'Delete Successfully');
date_default_timezone_set( "Asia/Calcutta" );
@session_start();
$showNoOfRecordsPerPage = 20;

require_once 'appcode/functions.php';
//require_once 'appcode/sitefunctions.php';
require "appcode/gump.class.php";



$host = "localhost";
$dbname = "invoice_generator";
$username = "root";
$pass = "root";
//$db = new Dbe();


//$db->connect( HOST,USERNAME, PWD, DATABASE );
$db = new MyPDO("mysql:host=$host;dbname=$dbname", $username, $pass );
