<?php
require_once 'auth.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="navbar-container" style="background: #1e1e24; padding: 16px 40px; display: flex; justify-content: space-between; align-items: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; box-shadow: 0 2px 8px rgba(0,0,0,0.15); margin: -40px -40px 35px -40px; border-bottom: 1px solid #2d2d35;">
    <div style="display: flex; gap: 28px; align-items: center;">
        <?php if ($current_page === 'login.php' || $current_page === 'register.php'): ?>
            <a href="register.php" style="color: #fff; text-decoration: none; font-size: 14px; font-weight: 600; letter-spacing: 0.3px;">
                Register User
            </a>
        <?php else: ?>
            <a href="index.php" style="color: <?= $current_page === 'index.php' ? '#fff' : '#a0a0b0' ?>; text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='<?= $current_page === 'index.php' ? '#fff' : '#a0a0b0' ?>'">
                Dashboard
            </a>
            <a href="report.php" style="color: <?= $current_page === 'report.php' ? '#fff' : '#a0a0b0' ?>; text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='<?= $current_page === 'report.php' ? '#fff' : '#a0a0b0' ?>'">
                Summary Reports
            </a>
            
            <?php if (isAdmin()): ?>
                <a href="add.php" style="color: <?= $current_page === 'add.php' ? '#fff' : '#a0a0b0' ?>; text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='<?= $current_page === 'add.php' ? '#fff' : '#a0a0b0' ?>'">
                    + Add Product
                </a>
                <a href="users.php" style="color: <?= $current_page === 'register.php' ? '#fff' : '#a0a0b0' ?>; text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='<?= $current_page === 'register.php' ? '#fff' : '#a0a0b0' ?>'">
                    Users
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <div style="color: #e2e2e8; font-size: 14px; display: flex; align-items: center; gap: 20px;">
        <?php if (isLoggedIn()): ?>
            <span style="letter-spacing: 0.2px;">
                Welcome, <strong style="color: #fff; font-weight: 600;"><?= htmlspecialchars(getUsername()) ?></strong> 
                <span style="background: #3a3a44; color: #cbd5e1; font-size: 11px; padding: 3px 8px; border-radius: 12px; margin-left: 6px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                    <?= htmlspecialchars($_SESSION['role'] ?? 'staff') ?>
                </span>
            </span>
            <span style="color: #4a4a5a;">|</span>
            <a href="logout.php" style="color: #f97316; text-decoration: none; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#ea580c'" onmouseout="this.style.color='#f97316'">
                Logout
            </a>
        <?php else: ?>
            <a href="login.php" style="color: #a0a0b0; text-decoration: none; font-size: 14px; font-weight: 600; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#a0a0b0'">Login</a>
        <?php endif; ?>
    </div>
</div>