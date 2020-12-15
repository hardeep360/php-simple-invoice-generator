<?php

session_start();

if( $_SESSION['user'] == '' || $_SESSION['id'] == ''){

    header("location:index.php");

}
?>