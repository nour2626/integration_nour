<?php
require_once(__DIR__ . '/../../../../controller/ReportController.php');

$reportController = new ReportController();
$reports = $reportController->getReports();  // Fetch all reports
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Reports Table</h4>
        <p class="card-description">Manage all reports in this table.</p>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reported Item</th>
                        
                        <th>Reason</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?= htmlspecialchars($report['id']) ?></td>
                            <td><?= htmlspecialchars($report['reply_id']) ?></td>  <!-- Assuming 'reply_id' represents the reported item -->
                    
                            <td><?= htmlspecialchars($report['reason']) ?></td>
                            <td><?= htmlspecialchars($report['created_at']) ?></td>
                            <td>
                                <a href="view_report.php?id=<?= $report['id'] ?>" class="btn btn-primary btn-sm">View</a>
                                <a href="delete_report.php?id=<?= $report['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this report?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
