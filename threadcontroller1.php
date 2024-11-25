<?php
require('config.php'); // Include the database connection class

class ThreadC {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct() {
        $this->conn = (new Config())->getConnexion();
    }

    // Add a thread
    public function ajouterThread($name, $location, $comment) {
        $sql = "INSERT INTO thread (name, location, comment) VALUES (:name, :location, :comment)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();
    }

    // Get all threads
    public function afficherThreads() {
        $sql = "SELECT * FROM thread";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a thread
    public function modifierThread($id, $name, $location, $comment) {
        $sql = "UPDATE thread SET name = :name, location = :location, comment = :comment WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();
    }

    // Delete a thread
    public function supprimerThread($id) {
        $sql = "DELETE FROM thread WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
}
?>
