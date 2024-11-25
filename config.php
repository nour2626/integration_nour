<?php
class Config {
    private $host = "localhost"; // Your database host
    private $db_name = "web project"; // Your database name
    private $username = "root"; // Your database username
    private $password = ""; // Your database password

    public function getConnexion() {
        $conn = null;
        try {
            $conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $conn;
    }
}
?>
