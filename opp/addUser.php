<?php
require "session.php";


$user = new User();

$msg = '';
$alertType = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(isset($_FILES['image']) && $_FILES['image']['name'] != '') {
        $image = time().'_'.$_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $folder = "upload/".$image;

        if(move_uploaded_file($imageTmp, $folder)) {
            $result = $user->Create($name,$email,$password,$image);
            if($result === true){
                $msg = 'User created successfully';
                $alertType = 'success';
            } elseif($result === "duplicate") {
                $msg = 'Email already exists!';
                $alertType = 'danger';
            } else {
                $msg = 'Something went wrong!';
                $alertType = 'danger';
            }
        } else {
            $msg = 'Error uploading image!';
            $alertType = 'danger';
        }
    } else {
        $msg = 'Please select image!';
        $alertType = 'danger';
    }
}



?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Simple Signup Form</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/adduser.css">

</head>


<body>
    <main class="card" role="main">
        <h1>Add Student</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="name">Full name</label>
                <input id="name" name="name" class="input" type="text" placeholder="Your full name" required />
            </div>

            <div>
                <label for="email">Email address</label>
                <input id="email" name="email" class="input" type="email" placeholder="you@example.com" required />
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" name="password" class="input" type="password" placeholder="Choose a password" minlength="6" required />
                <div class="note">Use at least 6 characters.</div>
            </div>

            <div>
                <label for="image">Profile image</label>
                <input id="image" name="image" class="file-input" type="file" accept="image/*" />
            </div>

            <div style="margin-top:8px;display:flex;justify-content:space-between;align-items:center">
                <button type="submit" class="btn">Create account</button>
            </div>
         <?php if($msg != ""): ?>
            <div class="alert alert-<?php echo $alertType?>" role="alert">
                <?php echo $msg?>
            </div>
         <?php endif;?>   
            
        </form>
    </main>

</body>

</html>