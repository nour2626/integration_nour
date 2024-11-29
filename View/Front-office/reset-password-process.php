<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];

    if ($code == $_SESSION['confirmation_code']) {
        // Code is correct, allow the user to reset their password
        $form = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file if you have one -->
        </head>
        <body>
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h2>Reset Password</h2>
                            </div>
                            <div class="card-body">
                                <form action="update-password.php" method="post">
                                    <div class="form-group">
                                        <label for="new_password">New Password:</label>
                                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
        echo $form;
    } else {
        echo '<div class="alert alert-danger" role="alert">Invalid confirmation code.</div>';
    }
}
?>