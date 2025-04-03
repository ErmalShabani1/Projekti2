<?php
class Database {
    private static $instance = null;
    private $conn;
    
    // Konstruktor privat qe ndalon krijimin e instancave te reja
    private function __construct() {
        $host = "localhost";
        $username = "root";
        $pass = "";
        $db = "userdb";
        
        $this->conn = new mysqli($host, $username, $pass, $db);
        if ($this->conn->connect_error) {
            die("Failed to connect to DB: " . $this->conn->connect_error);
        }
    }
    
    // Kthen instancen e vetme te kesaj klase
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    // Kthen lidhjen me databazen
    public function getConnection() {
        return $this->conn;
    }
    
    // Pergatit nje statement per ekzekutim
    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }
    
    // Ekzekuton nje query te SQL
    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    // Mbyll lidhjen me databazen
    public function close() {
        $this->conn->close();
    }
}