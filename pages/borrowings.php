<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db.php';

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $item_id = $_POST['item_id'];
    $borrow_date = $_POST['borrow_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    if ($stmt->rowCount() == 0) {
        $error = "Error: User ID does not exist!";
    }

    $stmt = $pdo->prepare("SELECT item_id FROM items WHERE item_id = ?");
    $stmt->execute([$item_id]);
    if ($stmt->rowCount() == 0) {
        $error = "Error: Item ID does not exist!";
    }

    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $borrow_date) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $due_date)) {
        $error = "Error: Dates must be in YYYY-MM-DD format.";
    }

    if (empty($error)) {
        $stmt = $pdo->prepare("INSERT INTO borrowings (user_id, item_id, borrow_date, due_date, status) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $item_id, $borrow_date, $due_date, $status])) {
            $success = "Borrowing record added successfully!";
        } else {
            $error = "Failed to add record.";
        }
    }
}

if (isset($_GET['delete'])) {
    $borrow_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM borrowings WHERE borrow_id = ?");
    if ($stmt->execute([$borrow_id])) {
        header("Location: borrowings.php?deleted=1");
        exit();
    } else {
        $error = "Failed to delete record.";
    }
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
    <title>Manage Borrowings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Manage Borrowings</h2>
        <a href="../index.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (!empty($success)) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="row">
                <div class="col">
                    <input type="number" name="user_id" class="form-control" placeholder="User ID" required>
                </div>
                <div class="col">
                    <input type="number" name="item_id" class="form-control" placeholder="Item ID" required>
                </div>
                <div class="col">
                    <input type="date" name="borrow_date" class="form-control" required>
                </div>
                <div class="col">
                    <input type="date" name="due_date" class="form-control" required>
                </div>
                <div class="col">
                    <select name="status" class="form-control" required>
                        <option value="Borrowed">Borrowed</option>
                        <option value="Overdue">Overdue</option>
                        <option value="Returned">Returned</option>
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-success">Add Borrowing</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Borrow ID</th>
                    <th>User Name</th>
                    <th>Item Name</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
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
                        <td>
                            <a href="update_borrowing.php?id=<?= htmlspecialchars($row['borrow_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="borrowings.php?delete=<?= htmlspecialchars($row['borrow_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
