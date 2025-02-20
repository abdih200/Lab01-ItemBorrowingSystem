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
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Item Borrowing System</h2>

        <a href="pages/borrowings.php" class="btn btn-primary mb-3">Borrowings</a>
        <a href="pages/users.php" class="btn btn-secondary mb-3">Users</a>
        <a href="pages/items.php" class="btn btn-success mb-3">Items</a>

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
                        <td><?= $row['borrow_id'] ?></td>
                        <td><?= $row['full_name'] ?></td>
                        <td><?= $row['item_name'] ?></td>
                        <td><?= $row['borrow_date'] ?></td>
                        <td><?= $row['due_date'] ?></td>
                        <td><?= $row['status'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
