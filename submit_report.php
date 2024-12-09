<?php
// submit_report.php
session_start();

include_once 'controllers/ReportController.php';

// Instantiate the ReportController
$reportController = new ReportController();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Get form data
    $reply_id = $_POST['reply_id'];  // The ID of the reply being reported
    $reason = $_POST['reason'];      // The reason for reporting
    $description = $_POST['description'];  // The description of the issue
    
    // Call the controller method to save the report
    $result = $reportController->submitReport($reply_id, $reason, $description);

    if ($result) {
        // Success message
        echo "Your report has been submitted successfully.";
    } else {
        // Failure message
        echo "There was an error submitting your report. Please try again.";
    }
}
?>
