<?php
include 'db.php';

$sql = "SELECT b.borrow_id, u.full_name, i.item_name, b.borrow_date, b.due_date, b.status
        FROM borrowings b
        JOIN users u ON b.user_id = u.user_id
        JOIN items i ON b.item_id = i.item_id";
$stmt = $pdo->query($sql);
$borrowings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Item Borrowing System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<script src="scripts.js"></script>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Item Borrowing System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="pages/borrowings.php">Manage Borrowings</a></li>
                    <li class="nav-item"><a class="nav-link" href="pages/users.php">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="pages/items.php">Manage Items</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Borrowing Records</h2>

        <!-- Success Message -->
        <?php if (isset($_GET['deleted'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Borrowing record deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Borrowings Table -->
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Borrow ID</th>
                    <th>User Name</th>
                    <th>Item Name</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($borrowings as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['borrow_id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                        <td><?= htmlspecialchars($row['borrow_date']) ?></td>
                        <td><?= htmlspecialchars($row['due_date']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
