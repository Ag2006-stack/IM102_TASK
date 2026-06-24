<?php
require_once 'config.php';
require_once 'auth.php';
requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        if (empty($username) || empty($email) || empty($password)) {
            $error = "All fields are required.";
        } else {
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $check_stmt->bind_param("ss", $username, $email);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                $error = "Username or Email already exists.";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $role = 'staff'; 

                $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $email, $password_hash, $role);

                if ($stmt->execute()) {
                    $success = "Staff account created successfully!";
                } else {
                    $error = "An error occurred during account registration.";
                }
                $stmt->close();
            }
            $check_stmt->close();
        }
    }

    if ($_POST['action'] === 'update') {
        $id = (int)$_POST['id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role = $_POST['role'];

        if (empty($username) || empty($email)) {
            $error = "Username and Email cannot be empty.";
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $email, $role, $id);
            if ($stmt->execute()) {
                $success = "User account updated successfully!";
            } else {
                $error = "Failed to update user account.";
            }
            $stmt->close();
        }
    }

    if ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];

        if ($id === (int)$_SESSION['user_id']) {
            $error = "You cannot delete your own account while logged in.";
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $success = "User account removed successfully!";
            } else {
                $error = "Failed to delete user account.";
            }
            $stmt->close();
        }
    }
}

$users_result = $conn->query("SELECT id, username, email, role, created_at FROM users ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Accounts Management</title>
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
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            max-width: 500px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn-submit {
            padding: 9px 16px;
            background: #2d2d2d;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
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
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
        }
        td {
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }
        .inline-form {
            display: inline-block;
            margin: 0;
        }
        .btn-inline-delete {
            background: none;
            border: none;
            color: #f44336;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            padding: 0;
        }
        .btn-inline-save {
            background: none;
            border: none;
            color: #4caf50;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            padding: 0;
            margin-right: 10px;
        }
        .table-input {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <h2>CREATE NEW STAFF ACCOUNT</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="users.php">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-submit">Register Staff</button>
        </form>
    </div>

    <h2>MANAGE EXISTING ACCOUNTS</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email Address</th>
                <th>Role Assignment</th>
                <th>Registration Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($users_result && $users_result->num_rows > 0): ?>
                <?php while ($user = $users_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        
                        <td>
                            <form id="update-form-<?= $user['id'] ?>" method="POST" action="users.php" class="inline-form">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="table-input" required>
                        </td>
                        
                        <td>
                                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="table-input" required>
                        </td>
                        
                        <td>
                                <select name="role" class="table-input">
                                    <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>
                        </td>
                        
                        <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                        
                        <td>
                            <button type="submit" form="update-form-<?= $user['id'] ?>" class="btn-inline-save">Save</button>
                            
                            <form method="POST" action="users.php" class="inline-form" onsubmit="return confirm('Permanently delete this user account?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn-inline-delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: #999; padding: 30px;">No user profiles located.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>