<?php 
session_start();
require "classes/user.php";
$user = new User();


if($_SERVER['REQUEST_METHOD']=="POST"){
    $name = $_POST['name'];
    $password = $_POST['pass'];

    if($user->login($name,$password)){
        header("location:index.php");
        exit;
    }
    else{
        echo "<script>alert('Login Failed')</script>";
    }


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/login.css">

</head>

<body>
    <div class="login-box">
        <h2>Login</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="input-box">
                <input type="text" name="name" placeholder="Enter Name" required>
            </div>
            <div class="input-box">
                <input type="password" name="pass" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="extra">
                <p>Forgot Password? <a href="#">Click Here</a></p>
            </div>
        </form>
    </div>
</body>

</html>