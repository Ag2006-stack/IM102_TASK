<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<nav style="background: #2d2d2d; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div style="display: flex; gap: 20px; align-items: center;">
        <a href="index.php" style="color: <?= $current_page === 'index.php' ? '#fff' : '#a0a0b0' ?>; text-decoration: none; font-size: 14px; font-weight: 600;">Dashboard</a>
        <a href="report.php" style="color: <?= $current_page === 'report.php' ? '#fff' : '#a0a0b0' ?>; text-decoration: none; font-size: 14px; font-weight: 600;">Summary Reports</a>

        <a href="add.php" style="color: <?= $current_page === 'add.php' ? '#fff' : '#a0a0b0' ?>; text-decoration: none; font-size: 14px; font-weight: 600;">+ Add Product</a>

        <?php if (isAdmin()): ?>
            <a href="users.php" style="color: <?= $current_page === 'users.php' ? '#fff' : '#a0a0b0' ?>; text-decoration: none; font-size: 14px; font-weight: 600;">Users</a>
        <?php endif; ?>
    </div>

    <div style="color: #fff; font-size: 14px; display: flex; align-items: center; gap: 15px;">
        <span>Welcome, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></strong>
            <small style="background: #444; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin-left: 5px; text-transform: uppercase; color:#fff;">
                <?= htmlspecialchars($_SESSION['role'] ?? 'staff') ?>
            </small>
        </span>
        <a href="logout.php" style="color: #f44336; text-decoration: none; font-weight: bold; border-left: 1px solid #555; padding-left: 15px;">Logout</a>
    </div>
</nav>