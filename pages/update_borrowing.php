<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../db.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("Invalid Borrow ID");
}

$borrow_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM borrowings WHERE borrow_id = ?");
$stmt->execute([$borrow_id]);
$borrowing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$borrowing) {
    die("Borrowing record not found!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $borrow_date = $_POST['borrow_date'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $borrow_date) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $due_date)) {
        $error = "Dates must be in YYYY-MM-DD format.";
    }

    if (!in_array($status, ['Borrowed', 'Returned', 'Overdue'])) {
        $error = "Invalid status selected.";
    }

    if (!isset($error)) {
        $stmt = $pdo->prepare("UPDATE borrowings SET borrow_date = ?, due_date = ?, status = ? WHERE borrow_id = ?");
        if ($stmt->execute([$borrow_date, $due_date, $status, $borrow_id])) {
            header("Location: borrowings.php?updated=1");
            exit();
        } else {
            $error = "Failed to update record.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Borrowing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Borrowing</h2>
        <a href="borrowings.php" class="btn btn-primary mb-3">Back</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Borrow Date</label>
                <input type="date" name="borrow_date" class="form-control" value="<?= htmlspecialchars($borrowing['borrow_date']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" value="<?= htmlspecialchars($borrowing['due_date']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Borrowed" <?= $borrowing['status'] == 'Borrowed' ? 'selected' : '' ?>>Borrowed</option>
                    <option value="Returned" <?= $borrowing['status'] == 'Returned' ? 'selected' : '' ?>>Returned</option>
                    <option value="Overdue" <?= $borrowing['status'] == 'Overdue' ? 'selected' : '' ?>>Overdue</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update Borrowing</button>
        </form>
    </div>
</body>
</html>
