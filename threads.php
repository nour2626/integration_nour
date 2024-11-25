<?php
require('../controller/threadcontroller.php');

$threadController = new ThreadC();
$threads = $threadController->afficherThreads();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Threads</title>
</head>
<body>
    <h1>Manage Threads</h1>
    <a href="create_thread.php">Add New Thread</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($threads as $thread): ?>
                <tr>
                    <td><?= htmlspecialchars($thread['id']) ?></td>
                    <td><?= htmlspecialchars($thread['name']) ?></td>
                    <td><?= htmlspecialchars($thread['location']) ?></td>
                    <td><?= htmlspecialchars($thread['comment']) ?></td>
                    <td>
                        <a href="edit_thread.php?id=<?= $thread['id'] ?>">Edit</a>
                        <a href="delete_thread.php?id=<?= $thread['id'] ?>" onclick="return confirm('Are you sure you want to delete this thread?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
