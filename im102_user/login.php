<?php
require_once 'config.php';
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw_username = $_POST['username'] ?? '';
    $raw_password = $_POST['password'] ?? '';
    
    $username = preg_replace('/[\x{00A0}\x{C2A0}]/u', ' ', $raw_username);
    $username = trim($username);
    $password = preg_replace('/[\x{00A0}\x{C2A0}]/u', '', $raw_password);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $safe_username = $conn->real_escape_string($username);
        $result = $conn->query("SELECT * FROM users WHERE LOWER(username) = LOWER('$safe_username') LIMIT 1");
        $user = $result->fetch_assoc();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>System Login</title>
    <link rel="stylesheet" href="add.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container" style="margin-top: 80px;">
        <h1>Inventory System Login</h1>
        <?php if (!empty($error)): ?>
            <div style="background-color: #ffebee; border: 1px solid #f44336; padding: 12px; border-radius: 4px; margin-bottom: 15px;">
                <p style="color: #c62828; margin: 0; font-size: 14px; font-weight: bold;">⚠️ <?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label>Username</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($username) ?>" placeholder="Enter username">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter password">
            <button type="submit" style="background-color: #4CAF50; width: 100%; padding: 12px; margin-top: 15px;">Log In</button>
        </form>
    </div>
</body>
</html>