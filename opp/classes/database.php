<?php

//creating a database class connecting database with opp and pdo
class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db_name = 'sumit';

    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password
            );
        } catch (PDOException $e) {
            die("Connection failed") . $e->getmessage();
        }

        return $this->conn;
    }
}
