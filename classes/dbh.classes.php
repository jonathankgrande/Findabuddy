<?php

class Dbh {
    private $host = "localhost";
    private $dbname = "findabuddy";
    private $username = "csc350";
    private $password = "xampp";

    protected function connect() {
        try {
            $pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}
