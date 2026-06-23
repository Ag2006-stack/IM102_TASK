<?php
require_once 'config.php';

$errors = [];
$success_message = '';

$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All configuration fields are required.";
    }

    if (!empty($username) && strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters long.";
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid email address.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Password confirmation strings do not match.";
    }

    if (!empty($password) && strlen($password) < 6) {
        $errors[] = "Password must contain a minimum of 6 characters.";
    }

    if (empty($errors)) {
        $safe_username = $conn->real_escape_string($username);
        $safe_email = $conn->real_escape_string($email);

        $check_query = "SELECT username, email FROM users WHERE username = '$safe_username' OR email = '$safe_email' LIMIT 1";
        $check_result = $conn->query($check_query);

        if ($check_result && $check_result->num_rows > 0) {
            $existing_user = $check_result->fetch_assoc();

            if (strcasecmp($existing_user['username'], $username) === 0) {
                $errors[] = "That username has already been taken.";
            } 

            if (strcasecmp($existing_user['email'], $email) === 0) {
                $errors[] = "That email address is already registered.";
            }
        }
    }
    
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $safe_username = $conn->real_escape_string($username);
        $safe_email = $conn->real_escape_string($email);

        $insert_sql = "INSERT INTO users (username, email, password_hash) VALUES ('$safe_username', '$safe_email', '$hashed_password')";
        
        if ($conn->query($insert_sql)) {
            $success_message = "User registration processed successfully! New user created.";
            $username = $email = '';
        } else {
            $errors[] = "System SQL deployment failure: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Account Registration</title>
    <link rel="stylesheet" href="add.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Create User Account</h1>

        <?php if (!empty($errors)): ?>
            <div style="background-color: #ffebee; border: 1px solid #f44336; padding: 12px; border-radius: 4px; margin-bottom: 15px;">
                <?php foreach ($errors as $error): ?>
                    <p style="color: #c62828; margin: 5px 0; font-size: 14px; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <p style="color: #2e7d32; background-color: #e8f5e9; border: 1px solid #4CAF50; padding: 12px; border-radius: 4px; font-weight: bold; font-size: 14px; margin-bottom: 15px;">
             <?= htmlspecialchars($success_message) ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <label>Username</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($username) ?>" placeholder="e.g. jsmith">

            <label>Email Address</label>
            <input type="text" name="email" required value="<?= htmlspecialchars($email) ?>" placeholder="e.g. john@example.com">

            <label>Password</label>
            <input type="password" name="password" required placeholder="Minimum 6 characters">

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required placeholder="Re-type your password confirmation">

            <button type="submit" style="background-color: #2196F3;">Register Account</button>
            <a href="index.php" class="cancel">Cancel</a>
        </form>
    </div>
</body>
</html>