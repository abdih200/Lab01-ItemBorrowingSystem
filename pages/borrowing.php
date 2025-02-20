<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db.php';

if (isset($_GET['delete'])) {
    $borrow_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM borrowings WHERE borrow_id = ?");
    $stmt->execute([$borrow_id]);
    header("Location: borrowings.php");
    exit();
}

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
    <title>Borrowings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Borrowings</h2>
        <a href="../index.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Borrow ID</th>
                    <th>User Name</th>
                    <th>Item Name</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($borrowings)) : ?>
                    <tr><td colspan="7" class="text-center">No Borrowings Found</td></tr>
                <?php else: ?>
                    <?php foreach ($borrowings as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['borrow_id']) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['item_name']) ?></td>
                            <td><?= htmlspecialchars($row['borrow_date']) ?></td>
                            <td><?= htmlspecialchars($row['due_date']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <a href="borrowings.php?delete=<?= htmlspecialchars($row['borrow_id']) ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this record?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
