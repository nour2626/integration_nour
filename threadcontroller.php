<?php

include_once(__DIR__ . '/../view/create_thread.php');  // Ensure this has a semicolon
include_once(__DIR__ . '/../model/forum.php');  // Ensure this has a semicolon
include_once(__DIR__ . '/../config.php');  // Correct the missing semicolon

class ThreadC {

    // Add a thread
    function ajouterThread($thread) {
        $sql = "INSERT INTO thread (id, name, location, comment, created_at, view) 
                VALUES (:id, :name, :location, :comment, :created_at, :view)";
        $db = new config();
        $conn = $db->getConnexion();
        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'id' => $thread->getId(),
                'name' => $thread->getName(),
                'location' => $thread->getLocation(),
                'comment' => $thread->getComment(),
                'created_at' => date('Y-m-d H:i:s'),
                'view' => 'private'  // Default the thread visibility to private
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Display all threads (for admin: public and private, for user: only public)
    function afficherThreads($visibility = 'public') {
        if ($visibility === 'public') {
            $sql = "SELECT * FROM thread WHERE view = 'public'";  // Fetch only public threads
        } else {
            $sql = "SELECT * FROM thread";  // Fetch all threads (public and private)
        }
        $db = new config();  // Create a new database connection
        $conn = $db->getConnexion();  // Get the database connection
        try {
            $query = $conn->prepare($sql);  // Prepare the SQL query
            $query->execute();  // Execute the query
            return $query->fetchAll(PDO::FETCH_ASSOC);  // Fetch all results as an associative array
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();  // Handle any errors
        }
    }

    // Delete a thread
    function supprimerThread($id) {
        $id = (int)$id; // Ensure $id is an integer
        $sql = "DELETE FROM thread WHERE id = :id";
        $conn = new config();
        $db = $conn->getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id, PDO::PARAM_INT); // Bind as integer
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Update a thread's details
    function modifierThread($thread, $idThread) {
        try {
            $conn = new config();
            $db = $conn->getConnexion();
            $query = $db->prepare(
                "UPDATE thread SET 
                    name = :name,
                    location = :location,
                    comment = :comment
                WHERE id = :id"
            );
            $query->execute([
                'name' => $thread->getName(),
                'location' => $thread->getLocation(),
                'comment' => $thread->getComment(),
                'id' => $idThread
            ]);
            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // Retrieve a specific thread by ID
    function recupererThread($idThread) {
        $sql = "SELECT * FROM thread WHERE id = :id";
        $conn = new config();
        $db = $conn->getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $idThread]);

            $thread = $query->fetch();
            return $thread;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Search threads by name, location, or comment
    function rechercherThreads($searchQuery) {
        $sql = "SELECT * FROM thread WHERE name LIKE :searchQuery OR location LIKE :searchQuery OR comment LIKE :searchQuery";
        $conn = new config();
        $db = $conn->getConnexion();

        try {
            $query = $db->prepare($sql);
            $searchQuery = "%$searchQuery%"; // Add wildcards for a partial match
            $query->bindParam(':searchQuery', $searchQuery);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Update a thread's visibility (public/private)
    public function updateViewStatus($id, $view) {
        // Ensure the view status is either 'public' or 'private'
        if ($view !== 'public' && $view !== 'private') {
            die("Invalid view status.");
        }

        // Prepare the SQL query to update the thread's view status
        $sql = "UPDATE thread SET view = :view WHERE id = :id";
        $db = new config();
        $conn = $db->getConnexion();  // Get the database connection

        try {
            // Prepare and execute the query
            $query = $conn->prepare($sql);
            $query->execute([
                'view' => $view,  // Set the new view status
                'id' => $id       // Specify the thread ID to update
            ]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());  // Handle any errors during the database operation
        }
    }
}
?>
