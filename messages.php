<?php
@session_start();
if( isset($_SESSION['msg']) && '' != trim($_SESSION['msg']) )
{
    $msg = $_SESSION['msg'];
	$_SESSION['msg'] = '';
}

if( isset($_SESSION['isError']) &&  '' != trim($_SESSION['isError']) )
{
	$isError = $_SESSION['isError'];
	$_SESSION['isError'] = '';
}
if( $isError == true )
{
	$extraClass = ' alert-danger  ';
}
else
{
	$extraClass = ' alert-success  ';
}

$msgNew = '';



if( is_array($msg) && count($msg) > 0 ) {
	$msgNew = implode( "<br />" , $msg );
}
else if( $msg != '' )
{
	$msgNew = $msg;
}

if( $msgNew != '' )
{
	?>
	<div class="alert <?php echo $extraClass; ?> alert-dismissible fade show" role="alert">
		 <?php echo $msgNew; ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php
}
