<?php

require "../config.php";

class UserController
{
    public function userList()
    {
        $sql = "SELECT * FROM Person";
        $conn = Config::getConnexion();
        try {
            $query = $conn->query($sql);
            return $query;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function addUser($user)
    {

        $sql = "INSERT INTO person(id,name,age)
        VALUES (NULL,:name,:age)";
        $conn = Config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->bindValue(':name', $user->getUserName());
            $query->bindValue(':age', $user->getAge());
            $query->execute();
            echo "user inserted succcefully";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
