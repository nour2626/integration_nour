<?php
require __DIR__ . "/../config.php";

class categoriesController
{
    // select all categorie list
    public function categoriesList()
    {
        $sql = "SELECT * FROM categories";
        $conn = config::getConnexion();

        try {
            $liste = $conn->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    //select one categorie by id
    function getcategoriesById($id)
    {
        $sql = "SELECT * from categories where id = $id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();

            $product = $query->fetch();
            return $product;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // add new categorie
    public function addcategorie($categorie)
    {
        $sql = "INSERT INTO categories (nom_categorie, nb_sites, description)
                VALUES (:nom_categorie, :nb_sites, :description)";
        $conn = config::getConnexion();
    
        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'nom_categorie' => $categorie->getnom_categorie(),
                'nb_sites' => $categorie->getnb_sites(),
                'description' => $categorie->getDescription(),
            ]);
            echo "Categorie inserted successfully";
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    
// update one categorie by id
    function updatecategories($categorie, $id)
    {
        $db = config::getConnexion();

        $query = $db->prepare(
            'UPDATE categories SET 
                nom_categorie = :nom_categorie,
                nb_sites = :nb_sites,
                description = :description
            WHERE id = :id'
        );
        try {
            $query->execute([
                'id' => $id,
                'nom_categorie' => $categorie->getnom_categorie(),
                'nb_sites' => $categorie->getnb_sites(),
                'description' => $categorie->getdescription()

            
            ]);

            echo $query->rowCount() . " records UPDATED successfully <br>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }




    public function deletecategories($id)
    {
        $sql = "DELETE FROM categories WHERE id=:id";
        $conn = config::getConnexion();
        $req = $conn->prepare($sql);
        $req->bindValue(':id', $id);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
