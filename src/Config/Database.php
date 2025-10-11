<?php
namespace Src\Config;

use PDO;
use PDOException;
use Exception;

class Database {
    private $host = 'localhost';
    private $db_name = 'crm_inmoweb';
    private $username = 'crmuser';
    private $password = 'password_crmuser';
    private $conn;

    public function getConnection(): ?PDO {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $message = "Error de conexión: " . $e->getMessage();
            throw new Exception($message, 500, $e);
        }
        return $this->conn;
    }
}
