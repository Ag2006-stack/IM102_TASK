<?php
require_once 'config.php';
require_once 'auth.php';
requireLogin();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_id = isset($_GET['category_id']) ? trim($_GET['category_id']) : '';


$sql = "SELECT p.id, p.name, p.price, p.stock, p.created_at, 
               c.name AS category_name, 
               s.name AS supplier_name,
               u.username AS encoder_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN suppliers s ON p.supplier_id = s.id
        LEFT JOIN users u ON p.added_by = u.id
        WHERE 1=1";

if (!empty($search)) {
    $safe_search = $conn->real_escape_string($search);
    $sql .= " AND (p.name LIKE '%$safe_search%')";
}

if (!empty($category_id)) {
    $safe_cat_id = (int)$category_id;
    $sql .= " AND p.category_id = $safe_cat_id";
}

$sql .= " ORDER BY p.id ASC";
$result = $conn->query($sql);

$stats_sql = "SELECT COUNT(p.id) AS total_products, SUM(p.stock) AS total_stock, SUM(p.stock * p.price) AS total_value, SUM(CASE WHEN p.stock < 20 THEN 1 ELSE 0 END) AS low_stock_count FROM products p WHERE 1=1";
if (!empty($search)) {
    $stats_sql .= " AND (p.name LIKE '%$safe_search%')";
}
if (!empty($category_id)) {
    $stats_sql .= " AND p.category_id = $safe_cat_id";
}

$stats_result = $conn->query($stats_sql)->fetch_assoc();
$categories_dropdown = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Product Inventory Dashboard</title>
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
            margin-top: 30px;
            margin-bottom: 20px;
            color: #000;
        }

        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            flex: 1;
            background: #fff;
            padding: 15px 20px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #2d2d2d;
        }

        .stat-card h3 {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
        }

        .stat-card p {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #2d2d2d;
        }

        .filter-form {
            background: #fff;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .filter-form input[type="text"],
        .filter-form select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-filter {
            padding: 8px 16px;
            background: #2d2d2d;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
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
        }

        td {
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }

        .row-low-stock {
            background-color: #ffebee !important;
        }

        .row-low-stock td {
            color: #c62828 !important;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <h2>PRODUCT INVENTORY</h2>
    <div class="stats-container">
        <div class="stat-card">
            <h3>Total Products</h3>
            <p><?= $stats_result['total_products'] ?? 0 ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Stock</h3>
            <p><?= number_format($stats_result['total_stock'] ?? 0) ?> units</p>
        </div>
        <div class="stat-card">
            <h3>Total Value</h3>
            <p>₱<?= number_format($stats_result['total_value'] ?? 0, 2) ?></p>
        </div>
        <div class="stat-card" style="border-left-color: #f44336;">
            <h3>Low Stock Items (&lt; 20)</h3>
            <p style="color: #f44336;"><?= $stats_result['low_stock_count'] ?? 0 ?></p>
        </div>
    </div>

    <form method="GET" action="index.php" class="filter-form">
        <input type="text" name="search" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>">
        <select name="category_id">
            <option value="">All Categories</option>
            <?php while ($cat = $categories_dropdown->fetch_assoc()): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category_id ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn-filter">Apply Filters</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Added By</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr <?= ($row['stock'] < 20) ? 'class="row-low-stock"' : '' ?>>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category_name'] ?? 'Uncategorized') ?></td>
                        <td><?= htmlspecialchars($row['supplier_name'] ?? 'No Supplier') ?></td>
                        <td>₱<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['stock'] ?> units</td>
                        <td><strong><?= htmlspecialchars($row['encoder_name'] ?? '-') ?></strong></td>
                        <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                        <td>
                            <<?php if (isAdmin()): ?>
                                <a href="edit.php?id=<?= $row['id'] ?>" style="color: #2196F3; text-decoration: none; margin-right: 10px; font-weight: bold;">Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>" style="color: #f44336; text-decoration: none; font-weight: bold;" onclick="return confirm('Delete product?')">Delete</a>
                            <?php else: ?>
                                <span style="color: #c62828; background: #ffebee; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; border: 1px solid #ffcdd2;">
                                    Access Denied
                                </span>
                            <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center; color: #999; padding: 30px;">No matching products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>