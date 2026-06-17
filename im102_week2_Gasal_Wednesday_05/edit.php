<?php
require_once 'config.php';

$id = (int) ($_GET['id'] ?? 0);

$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

$categories_result = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$suppliers_result = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = (float) $_POST['price'];
    $stock = (int) $_POST['stock'];
    $category_id = $_POST['category_id'] === '' ? 'NULL' : (int) $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'] === '' ? 'NULL' : (int) $_POST['supplier_id'];

    if (empty($name) || $price < 0 || $stock < 0) {
        $message = '<p style="color:red;">Valid Name, Price, and Stock values are required.</p>';
    } else {
        $sql = "UPDATE products SET 
                name='$name', 
                price=$price, 
                stock=$stock,
                category_id=$category_id, 
                supplier_id=$supplier_id
                WHERE id=$id";

        if ($conn->query($sql)) {
            header('Location: index.php');
            exit;
        } else {
            $message = '<p style="color:red;">Error: ' . $conn->error . '</p>';
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="edit.css">
</head>

<body>
    <div class="container">
        <h1>Edit Product #<?= $product['id'] ?></h1>

        <?= $message ?>

        <form method="POST">
            <label>Product Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

            <label>Price (₱)</label>
            <input type="number" name="price" value="<?= $product['price'] ?>" step="0.01" min="0" required>

            <label>Stock Quantity</label>
            <input type="number" name="stock" value="<?= $product['stock'] ?>" min="0" required>

            <label>Category</label>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($cat = $categories_result->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $product['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Supplier</label>
                <select name="supplier_id" required>
                    <option value="">-- Select Supplier --</option>
                    <?php while ($sup = $suppliers_result->fetch_assoc()): ?>
                        <option value="<?= $sup['id'] ?>" <?= ($sup['id'] == $product['supplier_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sup['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit">Update Product</button>
                <a href="index.php" class="cancel">Cancel</a>
        </form>
    </div>
</body>

</html>