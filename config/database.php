<?php
class Database {
    private $host = "localhost";
    private $db_name = "sgrhbmkh_db";
    private $username = "root";
    private $password = "";  // Mettre à jour si un mot de passe est défini pour root
    public $db;

    public function getConnection() {
        $this->db = null;

        try {
            error_log("Attempting database connection to {$this->host}/{$this->db_name}");
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            
            $this->db = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ]);
            
            // Test the connection
            $this->db->query("SELECT 1");
            error_log("Database connection successful");
            
        } catch(PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            error_log("DSN used: " . $dsn);
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new Exception("Une erreur est survenue lors de la connexion à la base de données: " . $e->getMessage());
        }

        return $this->db;
    }
}
?>
