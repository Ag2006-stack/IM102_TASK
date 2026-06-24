<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getUsername()
{
    return $_SESSION['username'] ?? 'Guest';
}

function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function requireAdmin()
{
    requireLogin();
    if (!isAdmin()) {
    echo '<div style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, sans-serif; text-align: center; margin-top: 100px;">';
    echo '<h2 style="color: #dc3545; margin-bottom: 20px;">Access Denied</h2>';
    
    
    echo '<img src="no!!.jpg" alt="Local Photo" style="display: block; margin: 0 auto 30px auto; max-width: 100%; height: auto;">';
    
    echo '<a href="index.php" style="display: inline-block; padding: 10px 20px; background: #2d2d2d; color: #fff; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px; transition: background 0.2s;">← Return to Dashboard</a>';
    echo '</div>';
    exit();
}
    }
