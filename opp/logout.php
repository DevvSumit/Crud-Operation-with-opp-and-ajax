<?php 
session_start();
require "classes/user.php";
$user = new User();
$user->logout();
header("location:login.php");

?>