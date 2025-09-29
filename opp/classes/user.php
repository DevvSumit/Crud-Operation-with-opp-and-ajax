<?php
require "database.php";


//creating user class
class User
{
    private $table = 'users';
    private $conn;

    //firstly connecting database with making constructor automaticly call the constructor
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    //insert user
    public function Create($name, $email, $password, $image)
    {
        try {
            $sql = "INSERT INTO users (name,email,password,image) VALUES (:name,:email,:password,:image)";
            $stmt = $this->conn->prepare($sql);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':image', $image);

            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "duplicate"; // duplicate email
            }
            return false;
        }
    }


    //Method for fetch all the data from database
    public function readAll()
    {
        $sql = "select * from {$this->table}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //Method for delete user
    public function delete($id)
    {
        $stmt = $this->conn->prepare("delete from {$this->table} where id = :id");
        return $stmt->execute([
            ':id' => $id
        ]);
    }

    //Method for update user
    public function update($id, $name, $email, $password = null, $image = null)
    {
        $sql = "UPDATE {$this->table} SET name=:name, email=:email";
        $params = [':name' => $name, ':email' => $email, ':id' => $id];

        if (!empty($password)) {
            $sql .= ", password=:password";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (!empty($image)) {
            $sql .= ", image=:image";
            $params[':image'] = $image;
        }

        $sql .= " WHERE id=:id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }



    //Method for login
    public function login($name, $password)
    {
        $stmt = $this->conn->prepare("select * from {$this->table}  where name = :name limit 1");
        $stmt->execute([
            ':name' => $name
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_id'] = $user['name'];
            return true;
        }
        return false;
    }

    //Method for check isLoggedIN()
    public function isLoggedIN()
    {
        return isset($_SESSION['user_id']);
    }


    //Method for logout
    public function logout()
    {
        session_destroy();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
    }
}
