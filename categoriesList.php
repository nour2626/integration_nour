<?php

include "../Controller/categoriesController.php";
$categoriesC = new categoriesController();
$list = $categoriesC->categoriesList();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <a href="addcategories.php">Add</a>
    <table border>
        <tr>
            <th>Id</th>
            <th>nom_categorie</th>
            <th>nb_sites</th>
            <th>description</th>
            <td>Action</td>
        </tr>
        <?php
        foreach ($list as $categories) {
        ?> <tr>
                <td><?= $categories['id']; ?></td>
                <td><?= $categories['nom_categorie']; ?></td>
                <td><?= $categories['nb_sites']; ?></td>
                <td><?= $categories['description']; ?></td>

                <td>
                    <!-- en cliquant sur le bouton update on appelle la page updateProduct.php et passe l'id du produit -->
                    <form method="POST" action="updatecategories.php">
                    <input type="submit" name="update" value="Update">
                    <input type="hidden" value=<?PHP echo $categories['id']; ?> name="id">
                    </form>
                    <!-- en cliquant sur le lien delete on appelle la page deleteProduct.php et le id du produit sera passé dans l'url -->
                    <a href="deletecategories.php?id=<?= $categories['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
</body>

</html>