<?php
require_once 'config.php';
require_once 'auth.php';
requireLogin();



$summary_sql = "SELECT 
                    COUNT(id) AS total_products,
                    SUM(stock) AS total_stock,
                    SUM(stock * price) AS total_value
                FROM products";
$summary = $conn->query($summary_sql)->fetch_assoc();


$category_sql = "SELECT c.name AS category_name, 
                        COUNT(p.id) AS products_count,
                        IFNULL(SUM(p.stock), 0) AS total_stock,
                        IFNULL(SUM(p.price * p.stock), 0) AS total_value,
                        IFNULL(AVG(p.price), 0) AS avg_price
                 FROM categories c
                 LEFT JOIN products p ON c.id = p.category_id
                 GROUP BY c.id, c.name
                 ORDER BY total_value DESC";
$category_report = $conn->query($category_sql);

$supplier_sql = "SELECT s.name AS supplier_name, 
                        COUNT(p.id) AS products_count,
                        IFNULL(SUM(p.stock), 0) AS total_stock
                 FROM suppliers s
                 LEFT JOIN products p ON s.id = p.supplier_id
                 GROUP BY s.id, s.name
                 ORDER BY total_stock DESC";
$supplier_report = $conn->query($supplier_sql);
?>
<!DOCTYPE html>
<html>

<title>Inventory Management Reports</title>
<link rel="stylesheet" href="report.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <h2>Overall System Summary</h2>
    <div class="stats-container">
        <div class="stat-card total-products-card">
            <h3>Total Distinct Products</h3>
            <p><?= number_format($summary['total_products'] ?? 0) ?></p>
        </div>
        <div class="stat-card total-stock-card">
            <h3>Total Accumulated Units</h3>
            <p><?= number_format($summary['total_stock'] ?? 0) ?> units</p>
        </div>
        <div class="stat-card total-value-card">
            <h3>Gross Inventory Value</h3>
            <p>₱<?= number_format($summary['total_value'] ?? 0, 2) ?></p>
        </div>
    </div>

    <h2>Per-Category Breakdown</h2>
    <table>
        <thead>
            <tr>
                <th>Category Name</th>
                <th class="number-col">Product Count</th>
                <th class="number-col">Total Stock</th>
                <th class="number-col">Average Item Price</th>
                <th class="number-col">Total Evaluated Value</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($category_report && $category_report->num_rows > 0): ?>
                <?php while ($cat = $category_report->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($cat['category_name']) ?></strong></td>
                        <td class="number-col"><?= $cat['products_count'] ?></td>
                        <td class="number-col"><?= number_format($cat['total_stock']) ?> units</td>
                        <td class="number-col">₱<?= number_format($cat['avg_price'], 2) ?></td>
                        <td class="number-col">₱<?= number_format($cat['total_value'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-data">No categories configured.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Per-Supplier Breakdown</h2>
    <table>
        <thead>
            <tr>
                <th>Supplier Name</th>
                <th class="number-col">Unique Products Supplied</th>
                <th class="number-col">Total Stock Contributed</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($supplier_report && $supplier_report->num_rows > 0): ?>
                <?php while ($sup = $supplier_report->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($sup['supplier_name']) ?></strong></td>
                        <td class="number-col"><?= $sup['products_count'] ?></td>
                        <td class="number-col"><?= number_format($sup['total_stock']) ?> units</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="no-data">No suppliers configured.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>