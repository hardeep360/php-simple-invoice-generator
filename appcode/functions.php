<?php
function upload($dir,$file,$owidth,$type,$thumb,$bool=NULL,$processThumbnail=false)
	{

	require_once('class.upload.php');
	$image=array('jpg','gif','png','bmp','tif');
	if(is_array($type)) $image=$type;
	$uploadReturn=array();
	if($bool==NULL)
	{ 	$handle = new Upload($_FILES[$file]); }
	else
	{
		$handle = new Upload($file);
	}
/*	$handle->image_text            = ' Water mark text ';
	$handle->image_text_font       = 7;
	$handle->image_text_color      = '#000000';
	$handle->image_text_background = '#FFFFFF';
	$handle->image_text_background_percent = 60;
	$handle->image_text_position   = 'BL';
	$handle->image_text_direction   = 'v';*/
    if ($handle->uploaded) {
      	    $handle->Process('./'.$dir.'/');
	        if ($handle->processed) {
            	$info = getimagesize($handle->file_dst_pathname);
				$rw=$info[0];
				$rh=$info[1];
				}
		if(is_file($dir."/".$handle->file_dst_name)) unlink($dir."/".$handle->file_dst_name);

		if(in_array($handle->file_src_name_ext,$image))
			{
	        $handle->image_resize            = true;
			if($thumb!='NULL')
				{
				if($rh>$rw)
				{
					$handle->image_y              = $thumb[1];
					 $handle->image_ratio_x        = true;
				}
				else
				{
					$handle->image_x              = $thumb[0];
					$handle->image_ratio_y        = true;
				}

    	    $handle->Process('./'.$dir.'thumbs/');
			//echo($handle->file_dst_pathname);
			//================================================================
			//code to generate exact  ratio image
			if($processThumbnail)
			{
							$b = $handle->file_dst_pathname;
							$bodyResource = imagecreatefromjpeg($b);
							list($bodyWidth, $bodyHeight) = getimagesize($b);

							$desiredHeight=$thumb[1];
							$desiredWidth=$thumb[0];
							if($desiredHeight>$bodyHeight)
							{
								$hdiff= ($desiredHeight-$bodyHeight)/2;
							}
							if($desiredWidth>$bodyWidth)
							{
								$wdiff=($desiredWidth-$bodyWidth)/2;
							}

							$previewResource = imagecreatetruecolor($desiredWidth,$desiredHeight);

							//make background black
							$black = imagecolorallocate($previewResource, 0, 0, 0);
							imagefill($previewResource, 0, 0, $black);
							imagecopy($previewResource,$bodyResource,$wdiff,$hdiff,0,0,$bodyWidth,$bodyHeight);
							imagejpeg($previewResource,$handle->file_dst_pathname);
			}
			//================================================================








			}

	$width=$owidth[0];
	$height=$owidth[1];

 	$width_orig=$rw;
 	$height_orig=$rh;


	$handle->image_resize = true;
	$ratio_orig = $width_orig/$height_orig;
	if($width_orig < $width &&  $height_orig < $height)
		{
		 	$handle->image_x=$width_orig;
		 	$handle->image_y=$height_orig;
		} else
		{
				if ($width/$height > $ratio_orig) {
						   $width = $height*$ratio_orig;
					    	$handle->image_x=$width;
			    	    	$handle->image_ratio_y           = true;
			    	    	$handle->image_ratio_x           = false;
			   } else {
		   			    $height = $width/$ratio_orig;
   					    $handle->image_y  = $height;
			    	    $handle->image_ratio_x           = true;
			    	    $handle->image_ratio_y           = false;
			}

		}
/*	$handle->image_text            = ' Water mark text ';
	$handle->image_text_font       = 7;
	$handle->image_text_color      = '#000000';
	$handle->image_text_background = '#FFFFFF';
	$handle->image_text_background_percent = 70;
	$handle->image_text_position   = 'BL';
	$handle->image_text_direction   = 'v';*/

    	    $handle->Process('./'.$dir.'/');
			$uploadReturn="";
	        if ($handle->processed) {
    	        $uploadReturn[0]= ' Photo was Uploaded Successfully<br>';
        	    $uploadReturn[0].= '  <img src="'.$dir.'/' . $handle->file_dst_name . '" />';
            	$info = getimagesize($handle->file_dst_pathname);
	            $uploadReturn[0].= '  <p>' . $info['mime'] . ' &nbsp;-&nbsp; ' . $info[0] . ' x ' . $info[1] .' &nbsp;-&nbsp; ' . round	(filesize($handle->file_dst_pathname)/256)/4 . 'KB</p>';
		        } else {
        			    $uploadReturn[0]= '<fieldset>';
			            $uploadReturn[0].= '  <legend>file not uploaded to the wanted location</legend>';
				        $uploadReturn[0].= '  Error: ' . $handle->error . '';
			            $uploadReturn[0].= '</fieldset>';
        				}
		    $handle-> Clean();

			}
			else
			{
			$uploadReturn[0]="Err";
			}
		}
		$uploadReturn[1]=$handle->file_dst_name;
		return $uploadReturn;
	}

function sortarray($sort_array,$reverse)
{
  $lunghezza=count($sort_array);
  for ($i = 0; $i < $lunghezza ; $i++){

    for ($j = $i + 1; $j < $lunghezza ; $j++){
  //echo "if (".preg_replace("/[^0-9]/", '', $sort_array[$i])." > ". preg_replace("/[^0-9]/", '', $sort_array[$j]).")<BR/>";
      if($reverse){
	  if (intval(preg_replace("/[^0-9]/", '', $sort_array[$i])) < intval(preg_replace("/[^0-9]/", '', $sort_array[$j]))){
	      $tmp = $sort_array[$i];
          $sort_array[$i] = $sort_array[$j];
          $sort_array[$j] = $tmp;

        }
      }else{
	  if (intval(preg_replace("/[^0-9]/", '', $sort_array[$i])) > intval(preg_replace("/[^0-9]/", '', $sort_array[$j]))){
          $tmp = $sort_array[$i];
          $sort_array[$i] = $sort_array[$j];
          $sort_array[$j] = $tmp;

        }
      }
    }
  }
  return $sort_array;
}

function getdir($dir)
{
$winrs=array();
if (is_dir($dir)) {
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
		if($file!='.' && $file!='..' && $file!='thumbs' && $file!='Thumbs.db')
				 array_push($winrs,$dir.$file);
					}
				closedir($dh);
				}
	}
return $winrs;
}

function findextension($filename)
{
	$filename = strtolower($filename) ;
	$exts = split("[/\\.]", $filename) ;
	$n = count($exts)-1;
	$exts = $exts[$n];
	return $exts;
}

/*
function SendMail($sentto,$sub,$msg,$from,$fromname,$cc=NULL)
{
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
	$headers .= 'To:'.$to. "\r\n";
	if($cc!='')
	{
		$headers .= 'Cc: '.$cc . "\r\n";
	}
	$headers .= 'From: '.$fromname.'<'.$from.'>'. "\r\n";

	$chk=@mail($sentto,$sub,$msg,$headers);
	if($chk)
	{
		return true;
	}
	else
	{
		return false;
	}
} */

//function for calculating date difference
function date_difference ($first, $second)
{
    $month_lengths = array (31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $retval = FALSE;

    if (    checkdate($first['month'], $first['day'], $first['year']) &&
            checkdate($second['month'], $second['day'], $second['year'])
        )
    {
        $start = smoothdate ($first['year'], $first['month'], $first['day']);
        $target = smoothdate ($second['year'], $second['month'], $second['day']);

        if ($start <= $target)
        {
            $add_year = 0;
            while (smoothdate ($first['year']+ 1, $first['month'], $first['day']) <= $target)
            {
                $add_year++;
                $first['year']++;
            }

            $add_month = 0;
            while (smoothdate ($first['year'], $first['month'] + 1, $first['day']) <= $target)
            {
                $add_month++;
                $first['month']++;

                if ($first['month'] > 12)
                {
                    $first['year']++;
                    $first['month'] = 1;
                }
            }

            $add_day = 0;
            while (smoothdate ($first['year'], $first['month'], $first['day'] + 1) <= $target)
            {
                if (($first['year'] % 100 == 0) && ($first['year'] % 400 == 0))
                {
                    $month_lengths[1] = 29;
                }
                else
                {
                    if ($first['year'] % 4 == 0)
                    {
                        $month_lengths[1] = 29;
                    }
                }

                $add_day++;
                $first['day']++;
                if ($first['day'] > $month_lengths[$first['month'] - 1])
                {
                    $first['month']++;
                    $first['day'] = 1;

                    if ($first['month'] > 12)
                    {
                        $first['month'] = 1;
                    }
                }

            }

            $retval = array ('years' => $add_year, 'months' => $add_month, 'days' => $add_day);
        }
    }

    return $retval;
}
function smoothdate ($year, $month, $day)
{
    return sprintf ('%04d', $year) . sprintf ('%02d', $month) . sprintf ('%02d', $day);
}

// function for date difference ends here


function login_permanent_code( $loginUsername, $userid , $logintimeDays = 7)
{

	$cookieExpireTime = ( 60 * 60 * 24 * $logintimeDays);
	//if(!isset($_COOKIE['logkxmlkimjk']) ||  $_COOKIE['logkxmlkimjk'] == '' ){
		$arr = array();
		$arr['nemausr'] = base64_encode($loginUsername);
		$arr['lgnstdt'] = base64_encode(date("Y-m-d"));
		$arr['scrtkn'] =  strrev(md5($userid.$arr['username'])).(rand(11111111,9999999999)) ;

		//print_r($arr);


		global $db;
		$sql=sprintf(" update tbluser set logincode='%s' where id=%d ", $arr['scrtkn'], $userid );
		$db->query($sql);

		setcookie( 'logkxmlkimjk', serialize($arr), time() + $cookieExpireTime, '/' );

	//}
}
function check_already_loginlimit()
{
	//print_r($_COOKIE);
	if( $_COOKIE['logkxmlkimjk'] != '' && $_SESSION['userlogged']!='true' && $_COOKIE['logkxmlkimjk']!= NULL  )
	{

		global $db;

		$arr = array();
		//echo $_COOKIE['logkxmlkimjk'];
		$arr = unserialize(stripslashes($_COOKIE['logkxmlkimjk']));
		//print_r($arr);

		 $sql=sprintf("select id,concat(firstname,' ',lastname) as name, firstname,lastname,gender,username,email  from tbluser where (username='%s' || email='%s') and logincode='%s'", base64_decode($arr['nemausr']), base64_decode($arr['nemausr']), $arr['scrtkn']  );

		if($db->query($sql))
		{
			if($db->numRows()>0)
			{
				$userRow=$db->fetchRow();

				$userid=$userRow['id'];
				require_once("session.php");

				$ss = new SecureSession();
				session_start();

				$id=strrev(md5($userid));

				$ss->login_open($id);
				$_SESSION["initialid"]=$id;
				$_SESSION["uid"]=$id;
				$_SESSION['suid']=base64_encode($userid);
				$_SESSION["myname"]=$userRow['username'];
				$_SESSION["lastname"]=$userRow['lastname'];
				$_SESSION["gender"]=$userRow['gender'];
				$_SESSION["username"]=$userRow['username'];
				$_SESSION['LoginEmailId']=$userRow['email'];
				//$_SESSION['adminlogged']='true';
				$_SESSION['userlogged']='true';
				if(basename($_SERVER['PHP_SELF']) == 'signup.php' ){ //=============not auto login on signup page=====
				header('location:index.php');
				}
	}

}
	}
}


function clearpost()
{
	//echo get_magic_quotes_gpc().'ceck';
	if(get_magic_quotes_gpc())
	{
	if(isset($_POST))
	{

		foreach($_POST as $key=>$values)
		{
			//echo $key;
			if(is_array($_POST[$key]))
			{
				//echo $key;
				foreach($_POST[$key] as $keys=>$val)
				{
					 $_POST[$key][$keys]=stripslashes(trim($val));
				}
			}
			else
			{
				$_POST[$key]=stripslashes(trim($values));
			}
		}


		foreach($_POST as $key=>$values)
		{
			//echo $key;
			if(is_array($_POST[$key]))
			{
				//echo $key;
				foreach($_POST[$key] as $keys=>$val)
				{
					 $_POST[$key][$keys]=addslashes(trim($val));
				}
			}
			else
			{
				$_POST[$key]=addslashes(trim($values));
			}
		}



	}
	}






}


function date_month_add($dateToadd=false, $monthToAdd = 1 )
{
	if($dateToadd == false)
	return false;




$d1 = DateTime::createFromFormat('Y-m-d', $dateToadd );

$year = $d1->format('Y');
$month = $d1->format('n');
$day = $d1->format('d');

$year += floor($monthToAdd/12);
$monthToAdd = $monthToAdd%12;
$month += $monthToAdd;
if($month > 12) {
    $year ++;
    $month = $month % 12;
    if($month === 0)
        $month = 12;
}

if(!checkdate($month, $day, $year)) {
    $d2 = DateTime::createFromFormat('Y-n-j', $year.'-'.$month.'-1');
    $d2->modify('last day of');
}else {
    $d2 = DateTime::createFromFormat('Y-n-d', $year.'-'.$month.'-'.$day);
}
$d2->setTime($d1->format('H'), $d1->format('i'), $d1->format('s'));
return $d2->format('Y-m-d');

}


function escape_string($objVal = false, $mysqlEscape = false)
{
	if($objVal === false || trim($objVal) == '')
	return false;

        $objVal  = trim($objVal );
	if(function_exists('get_magic_quotes_gpc'))
	{
		if(get_magic_quotes_gpc())
		{
			$objVal = stripslashes( $objVal );
		}
	}
	if($mysqlEscape != false)
	{
		$objVal = mysql_real_escape_string($objVal);
	}
	else {
		$objVal = addslashes($objVal);
	}
	return $objVal;
}


function get_superadmin_email()
{
    global $tablePrefix;
    global $db;
    $sql = sprintf(' Select email from '.$tablePrefix.'tbladmin where issuperadmin="1" and status="1" limit 1 ');
    if( $db->query($sql)){
        if($db->numRows()>0){
            $rs = $db->fetchRow();
            return $rs['email'];
        }else{
            $msg = 'Super admin email id is not found.<br />'.$sql ;
        }

    }
    else
    {
        $msg = 'Error in Query '.$sql;
    }

    if(SITE_DEBUG == true)
    {
        echo $msg;
    }
    return false;
}

function rturl( )
{

    echo  get_rturl();
}

function get_rturl( )
{

    $dirName = dirname($_SERVER['PHP_SELF']);
    if( $dirName != '' )
    {
        $dirName = ltrim($dirName,'/').'/';
    }

    return $rturl = urlencode($dirName.basename($_SERVER['PHP_SELF']).($_SERVER['QUERY_STRING']!=''? '?'.$_SERVER['QUERY_STRING'] : ''));
}


function check_valid_date($mydate, $dformat='ymd', $sep = '-') {

    if($dformat == 'ymd')
    {
        list($yy,$mm,$dd)=explode($sep,$mydate);
    }
    elseif($dformat == 'mdy')
    {
         list($mm,$dd,$yy)=explode($sep,$mydate);
    }
    elseif($dformat == 'dmy')
    {
         list($dd,$mm,$yy)=explode($sep,$mydate);
    }

    if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd))
    {
        return checkdate($mm,$dd,$yy);
    }
    return false;
}

if( !function_exists('output_date'))
{
    function output_date( $dt, $format = "m/d/Y" )
    {
        $dt1 = strtotime($dt);

        if( $dt1 > 0 )
        {
            return date($format, $dt1 );
        }

        return '';
    }
}



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

		//Recipients
		$mail->setFrom($sendFromEmail, $sendFromName );
		$mail->addAddress($toEmail, $toName);     // Add a recipient
		//$mail->addAddress('ellen@example.com');               // Name is optional
		$mail->addReplyTo($addReplyToEmail, $addReplyToName);
		//$mail->addCC('cc@example.com');
		//$mail->addBCC('bcc@example.com');

		//Attachments
		//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		//Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $body;
		$mail->AltBody = strip_tags($body);

		return $mail->send();
		//echo 'Message has been sent';
	} catch (Exception $e) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	}
	return false;
}



?>
