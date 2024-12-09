<?php
// Include database connection
// Check if the report has been submitted

// report.php
require_once(__DIR__ . '/../../../../controller/ReportController.php');

// Assuming you're using PDO to connect to the databa


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reply_id = $_GET['reply_id'];
    $reason = $_POST['reason'];
    $description = $_POST['description'];

    // Validate inputs
    if (empty($reason) || ($reason == 'Other' && empty($description))) {
        $error = "Please select a reason and provide a description.";
    } else {
        // Save report to the database
        $sql = "INSERT INTO reports (reply_id, reason, description) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$reply_id, $reason, $description]);

        // Redirect to a confirmation page or back to the thread
        header("Location: report_confirmation.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Reply</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .report-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
            display: block;
        }

        select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
            margin-bottom: 20px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .other-reason {
            display: none;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group textarea {
            height: 150px;
        }

        .form-group select {
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="report-container">
    <h1>Report Reply</h1>

    <!-- Display error if there's one -->
    <?php if (!empty($error)) { echo '<p class="error">' . $error . '</p>'; } ?>

    <!-- Report Form -->
    <form method="POST" action="report.php?reply_id=<?php echo $_GET['reply_id']; ?>">

        <div class="form-group">
            <label for="reason">Select a reason for reporting:</label>
            <select name="reason" id="reason" required>
                <option value="">--Please select a reason--</option>
                <option value="Sexual content">Sexual content</option>
                <option value="Violent or repulsive content">Violent or repulsive content</option>
                <option value="Hateful or abusive content">Hateful or abusive content</option>
                <option value="Harassment or bullying">Harassment or bullying</option>
                <option value="Harmful or dangerous acts">Harmful or dangerous acts</option>
                <option value="Misinformation">Misinformation</option>
                <option value="Child abuse">Child abuse</option>
                <option value="Promotes terrorism">Promotes terrorism</option>
                <option value="Spam or misleading">Spam or misleading</option>
                <option value="Legal issue">Legal issue</option>
                <option value="Captions issue">Captions issue</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <!-- Description field (only shown if "Other" is selected) -->
        <div id="other-reason" class="form-group other-reason">
            <label for="description">Describe the issue:</label>
            <textarea name="description" id="description" placeholder="Enter additional details here"></textarea>
        </div>

        <button type="submit">Submit Report</button>
    </form>
</div>

<script>
    // JavaScript to display description box when "Other" is selected
    document.getElementById('reason').addEventListener('change', function() {
        var reason = this.value;
        if (reason === 'Other') {
            document.getElementById('other-reason').style.display = 'block';
        } else {
            document.getElementById('other-reason').style.display = 'none';
        }
    });
</script>

</body>
</html>
