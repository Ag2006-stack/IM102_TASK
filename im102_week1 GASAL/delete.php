<?php
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM students WHERE id = $id");
    header('Location: index.php');
    exit;
}

// Show confirmation (GET)
$result = $conn->query("SELECT name, course, year FROM students WHERE id = $id");
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Student</title>
    <link rel="stylesheet" href="delete.css">
</head>
<body>
    <div class="container">
        <h1>Delete Student</h1>
        
        <p>Are you sure you want to delete:</p>
        <p class="name"><?= htmlspecialchars($student['name']) ?></p>
        <p class="details"><?= $student['course'] ?> — Year <?= $student['year'] ?></p>
        <p class="warning">This action cannot be undone.</p>
        
        <form method="POST" style="display: inline;">
            <button type="submit" class="btn-delete">Yes, Delete</button>
        </form>
        <a href="index.php" class="btn-cancel">Cancel</a>
    </div>
</body>
</html>
