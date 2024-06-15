<?php
//initialize the session 
session_start();

//unset all the session variables
$_SESSION = array();

//destroy the session 

session_destroy();

// redirect to the home page 
header("location: /index.php");
?>