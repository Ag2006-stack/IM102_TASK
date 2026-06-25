<?php
require_once 'config.php';
require_once 'auth.php';
requireAdmin();
$id = (int) ($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->query("DELETE FROM products WHERE id = $id");
    header('Location: index.php');
    exit;
}

$result = $conn->query("SELECT p.name, p.price, c.name AS category_name 
                       FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = $id");
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Delete Product</title>
    <link rel="stylesheet" href="delete.css">
</head>

<body>
    <div class="container">
        <h1>Delete Product</h1>

        <p>Are you sure you want to delete:</p>
        <p class="name"><?= htmlspecialchars($product['name']) ?></p>
        <p class="details"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?> —
            ₱<?= number_format($product['price'], 2) ?></p>
        <p class="warning">This action cannot be undone.</p>

        <form method="POST" style="display: inline;">
            <button type="submit" class="btn-delete">Yes, Delete</button>
        </form>
        <a href="index.php" class="btn-cancel">Cancel</a>
    </div>
</body>

</html>