<?php
require_once 'config.php';

$sql = "SELECT p.id, p.name, p.price, p.stock, p.created_at, 
               c.name AS category_name, 
               s.name AS supplier_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN suppliers s ON p.supplier_id = s.id
        ORDER BY p.id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Product Inventory</title>
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
            letter-spacing: 0.5px;
            margin-top: 30px;
            margin-bottom: 20px;
            color: #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 20px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #2d2d2d;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            border: none;
        }
        td {
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }
        tr:hover {
            background-color: #fafafa;
        }
        .btn-add {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <p>
        <a href="add.php" class="btn-add">+ Add Product</a>
    </p>
    
    <h2>PRODUCT INVENTORY</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td>
                    <td><?= htmlspecialchars($row['supplier_name'] ?? 'No Supplier') ?></td>
                    <td>₱<?= number_format($row['price'], 2) ?></td>
                    <td><?= htmlspecialchars($row['stock']) ?> units</td>
                    <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id'] ?>" 
                           style="color: #2196F3; text-decoration: none; margin-right: 10px;">Edit</a>
                        <a href="delete.php?id=<?= $row['id'] ?>" 
                           style="color: #f44336; text-decoration: none;"
                           onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>
