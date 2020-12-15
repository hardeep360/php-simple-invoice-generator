<?php
/*
  SecureSession class
  Written by Vagharshak Tozalakyan <vagh@armdex.com>
  Released under GNU Public License
*/
namespace root\appcode;
class SecureSession
{
    // Include browser name in fingerprint?
    var $check_browser = true;

    // How many numbers from IP use in fingerprint?
    var $check_ip_blocks = 0;

    // Control word - any word you want.
    var $secure_word = 'SECURESTAFF';

    // Regenerate session ID to prevent fixation attacks?
    var $regenerate_id = true;

    // Call this when init session.
    function Open()
    {
        $_SESSION['ss_fprint1'] = $this->_Fingerprint();
//        $this->_RegenerateId();
    }

    // Call this to check session.
    function Check()
    {
        $this->_RegenerateId();
        return (isset($_SESSION['ss_fprint1']) && $_SESSION['ss_fprint1'] == $this->_Fingerprint());
    }

    // Internal function. Returns MD5 from fingerprint.
    function _Fingerprint()
    {
        $fingerprint = $this->secure_word;
        if ($this->check_browser) {
            $fingerprint .= $_SERVER['HTTP_USER_AGENT'];
        }
        if ($this->check_ip_blocks) {
            $num_blocks = abs(intval($this->check_ip_blocks));
            if ($num_blocks > 4) {
                $num_blocks = 4;
            }
            $blocks = explode('.', $_SERVER['REMOTE_ADDR']);
            for ($i = 0; $i < $num_blocks; $i++) {
                if(isset($blocks[$i]))
                $fingerprint .= $blocks[$i] . '.';
            }
        }
        return md5($fingerprint);
    }

    // Internal function. Regenerates session ID if possible.
    function _RegenerateId()
    {
        if ($this->regenerate_id && function_exists('session_regenerate_id')) {
            if (version_compare('5.1.0', phpversion(), '>=')) {
                //session_regenerate_id(true);
            } else {
                	//session_regenerate_id();
            }
        }
    }
function sessionStat($id='novalue')
{
		@session_start();
		$id=$_SESSION['uid_student'];
		$this->check_browser = true;
		$this->check_ip_blocks = 4;
		$this->secure_word =substr($id,0,5);
		
		if($_SERVER['QUERY_STRING']!="")
		$rt=basename($_SERVER['PHP_SELF']).'?'.urlencode($_SERVER['QUERY_STRING']);
		else
		$rt=basename($_SERVER['PHP_SELF']);
		
		$rt=urlencode($rt);
		if($rt!=""){$rturl="?rturl=".$rt;}
		if(!$this->Check() || $_SESSION['studentlogged']!="true" || $_SESSION['initialid_student']!=$id)
		{
		      session_destroy();
             
			@header('Location: index.php'.$rturl);
			die("You seems to be logged out. Please <a href='./index.php'>click here</a> to go back to signin | signup page.");
		}	
		if(!isset($_SESSION['studentlogged'])|| $_SESSION['initialid_student']!=$id || $id=='')
				{
				
					$_SESSION['studentlogged']="false";
					session_destroy();
					header('Location:index.php'.$rturl);
					die();
				}
			
}

function sessionBool($id)
{
		@session_start();
		$this->check_browser = true;
		$this->check_ip_blocks = 4;
		$this->secure_word =substr($id,0,5);
		
		if($_SERVER['QUERY_STRING']!="")
		$rt=basename($_SERVER['PHP_SELF']).'?'.$_SERVER['QUERY_STRING'];
		else
		$rt=basename($_SERVER['PHP_SELF']);
		
		$rt=urlencode($rt);
		if($rt!=""){$rturl="?rturl=".$rt;}
		if(!$this->Check() || $_SESSION['studentlogged']!="true" || $_SESSION['initialid_student']!=$id)
		{
		
			return false;
		}	
		if(!isset($_SESSION['studentlogged'])|| $_SESSION['initialid_student']!=$id || $id=='')
				{
					$_SESSION['studentlogged']="false";
					session_destroy();
					return false;
					die();
				}

return true;			
}
//sunny code starts
function sessionValidReturn($id)
{
		@session_start();
		$this->check_browser = true;
		$this->check_ip_blocks = 4;
		$this->secure_word =substr($id,0,5);
		
		if($_SERVER['QUERY_STRING']!="")
		$rt=basename($_SERVER['PHP_SELF']).'?'.$_SERVER['QUERY_STRING'];
		else
		$rt=basename($_SERVER['PHP_SELF']);
		
		$rt=urlencode($rt);
		if($rt!=""){$rturl="?rturl=".$rt;}
		if(!$this->Check() || $_SESSION['studentlogged']!="true" || $_SESSION['initialid_student']!=$id)
		{
			return "0";
		}
		else
			return "1";	
}
// sunny code ends

function login_open($id)
	{
		
		$this->check_browser = true;
		$this->check_ip_blocks = 4;
		$this->secure_word = substr($id,0,5);
		$this->regenerate_id = true;
		$this->Open();
		$_SESSION["studentlogged"]="true";
	}
}
?>