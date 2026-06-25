<?php
require_once 'config.php';
require_once 'auth.php';
requireLogin();

$error = '';
$success = '';

$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category_id = (int)$_POST['category_id'];
    $supplier_id = (int)$_POST['supplier_id'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $added_by = $_SESSION['user_id']; // Captured directly from active session log

    if (empty($name) || empty($category_id) || empty($supplier_id) || $price <= 0 || $stock < 0) {
        $error = "Please provide valid information for all required fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, category_id, supplier_id, price, stock, added_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siidii", $name, $category_id, $supplier_id, $price, $stock, $added_by);

        if ($stmt->execute()) {
            $success = "Product added successfully!";
        } else {
            $error = "Failed to add product to database.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add New Product</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f9f9f9;
        }

        h2 {
            font-family: sans-serif;
            font-weight: bold;
            margin-bottom: 20px;
            color: #000;
        }

        .form-container {
            background: #fff;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-submit {
            padding: 10px 20px;
            background: #2d2d2d;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <h2>ADD NEW PRODUCT</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="add.php">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while ($c = $categories->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Supplier</label>
                <select name="supplier_id" required>
                    <option value="">Select Supplier</option>
                    <?php while ($s = $suppliers->fetch_assoc()): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Unit Price (₱)</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock" required>
            </div>
            <button type="submit" class="btn-submit">Save Product</button>
        </form>
    </div>

</body>

</html>