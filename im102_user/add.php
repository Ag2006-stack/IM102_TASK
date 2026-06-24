<?php
require_once 'config.php';
require_once 'auth.php';
requireAdmin();

$categories_result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$suppliers_result = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $category_id = $_POST['category_id'] === '' ? 'NULL' : (int) $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'] === '' ? 'NULL' : (int) $_POST['supplier_id'];

    if (empty($name) || $price < 0 || $stock < 0 || $_POST['category_id'] === '' || $_POST['supplier_id'] === '') {
        $message = '<p style="color:red;">All fields are required.</p>';
    } else {
        $sql = "INSERT INTO products (name, price, stock, category_id, supplier_id) VALUES ('$name', $price, $stock, $category_id, $supplier_id)";
        if ($conn->query($sql)) {
            $message = '<p style="color:green; font-size:1.2em;">Product added! Redirecting...</p>';
            header('Refresh: 2; URL=index.php');
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="add.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Add New Product</h1> 

        <?= $message ?>

        <form method="POST">
            <label>Product Name</label>
            <input type="text" name="name" required placeholder="e.g. Wireless Mouse">
            
            <label>Price (₱)</label>
            <input type="number" name="price" step="0.01" min="0" required placeholder="0.00">

            <label>Stock Quantity</label>
            <input type="number" name="stock" min="0" required placeholder="0">

            <label>Category</label>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($cat = $categories_result->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Supplier</label>
            <select name="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                <?php while ($sup = $suppliers_result->fetch_assoc()): ?>
                    <option value="<?= $sup['id'] ?>"><?= htmlspecialchars($sup['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Add Product</button>

            <a href="index.php" class="cancel">Cancel</a>
        </form>
    </div>
</body>

</html>
