<?php
require "classes/user.php";

$user = new User();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $type = $_POST['type'];
    if ($type == 1) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['pass'];

        $image = time() . '_' . $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $folder = "upload/" . $image;

        if (move_uploaded_file($imageTmp, $folder)) {
            $result = $user->Create($name, $email, $password, $image);
            if ($result) {
                echo 1;
            } else {
                echo 'image errro';
            }
        } else {
            echo 'error';
        }
    } else if ($type == 2) {
        $id = $_POST['id'];
        if ($user->delete($id)) {
            echo 1;
        } else {
            echo 0;
        }
    } else if ($type == 3) { // Edit user
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = !empty($_POST['pass']) ? $_POST['pass'] : null;

        $image = null;
        if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
            $image = time() . '_' . $_FILES['image']['name'];
            $imageTmp = $_FILES['image']['tmp_name'];
            $folder = "upload/" . $image;
            move_uploaded_file($imageTmp, $folder);
        }

        if ($user->update($id, $name, $email, $password, $image)) {
            echo 1;
        } else {
            echo 0;
        }
    }
}
