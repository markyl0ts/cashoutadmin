<?php
session_start();
include('include/functions.php');

if(isset($_GET['a'])){
    session_unset();
    session_destroy();
    header("Location: ".get_base_url()."/login.php");
}

?>