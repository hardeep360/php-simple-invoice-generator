# Simple PHP Invoice Generator

[Demo](https://invoicegenerator.360plustechnologies.com/ "Demo Site")

**UserName: abc**

**Password: 123456**

## Features
### Signup/Login/Forgot Password
### Add Your Company Info
### Add/List Customers
### Create/Update/Delete Invoices
### PDF download of invoice
### Mark as paid
### Currency availability
### Invoice for both hourly/qnty. prices

--------------------------
## Database
#### Mysql Database: database folder

## App Settings
----
### File: appcode/confignormal.php
### Set Contants For Email:

'ADMIN_EMAIL, ADMIN_NAME, FROM_EMAIL, FROM_EMAIL_NAME, NO_REPLY_EMAIL, NO_REPLY_NAME'

'SERVER_PROTOCOL'

### Database Login Vars:
'$host, $dbname, $username, $pass'

### Send Mail Account Settings
### File: appcode/functions.php
Set email host, username and password to enable send mail functionality.
Below is the function where you need to add the required information.

'''
function sendMail( $toEmail, $toName, $body, $subject, $sendFromEmail, $sendFromName, $addReplyToEmail, $addReplyToName   )
{
	$mail =  new \PHPMailer\PHPMailer\PHPMailer(true);
	try {
		//Server settings
		//$mail->SMTPDebug = 2;                                 // Enable verbose debug output
		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = '';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = '';                 // SMTP username
		$mail->Password = '';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
''' 


---



