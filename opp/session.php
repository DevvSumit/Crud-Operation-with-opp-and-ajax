<?php 
session_start();
require "classes/user.php";
$user = new User();

if(!$user->isLoggedIN()){
    header("location:login.php");
}

?>