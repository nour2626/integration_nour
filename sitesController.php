<?php
require __DIR__ . "/../config.php";

class sitesController
{
    // Select all sites by category
    public function getSitesByCategory($categoryId)
    {
        $sql = "SELECT * FROM sites WHERE category = :category";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->bindValue(':category', $categoryId);
            $query->execute();

            return $query->fetchAll(); // Return the list of sites for the given category
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Add a new site to a specific category
    public function addSiteToCategory($site)
    {
        $sql = "INSERT INTO sites (nom_site, description_site, category, images)
                VALUES (:nom_site, :description_site, :category, :images)";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'nom_site' => $site->getNomSite(),
                'description_site' => $site->getDescriptionSite(),
                'category' => $site->getCategory(),
                'images' => $site->getImages(),
            ]);
            echo "Site added successfully to category.";
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // Update a site by ID
    public function updateSite($site, $id)
    {
        $sql = "UPDATE sites SET 
                    nom_site = :nom_site,
                    description_site = :description_site,
                    category = :category,
                    images = :images
                WHERE id = :id";
        $conn = config::getConnexion();

        try {
            $query = $conn->prepare($sql);
            $query->execute([
                'id' => $id,
                'nom_site' => $site->getNomSite(),
                'description_site' => $site->getDescriptionSite(),
                'category' => $site->getCategory(),
                'images' => $site->getImages(),
            ]);
            echo "Site updated successfully.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Delete a site by ID
    public function deleteSite($id)
    {
        $sql = "DELETE FROM sites WHERE id = :id";
        $conn = config::getConnexion();
        $req = $conn->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            echo "Site deleted successfully.";
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
