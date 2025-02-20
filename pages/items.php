<?php
include '../db.php';

if (isset($_GET['delete'])) {
    $item_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM items WHERE item_id = ?");
    $stmt->execute([$item_id]);
    header("Location: items.php");
    exit();
}

// Fetch All Items
$sql = "SELECT * FROM items";
$stmt = $pdo->query($sql);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Items</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Items</h2>
        <a href="../index.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <!-- Items Table -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Availability</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $row): ?>
                    <tr>
                        <td><?= $row['item_id'] ?></td>
                        <td><?= $row['item_name'] ?></td>
                        <td><?= $row['category'] ?></td>
                        <td><?= $row['availability_status'] ?></td>
                        <td>
                            <a href="items.php?delete=<?= $row['item_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
