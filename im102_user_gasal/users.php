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
                $stmt->execute();
                $stmt->close();
                $success = "Staff registered successfully!";
            }
            $check_stmt->close();
        }
    }

    if ($_POST['action'] === 'update') {
        $id = (int)$_POST['id'];
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $role, $id);
        if ($stmt->execute()) {
            $success = "User account modified!";
        }
        $stmt->close();
    }

    if ($_POST['action'] === 'delete') {
        $id = (int)$_POST['id'];
        if ($id === (int)$_SESSION['user_id']) {
            $error = "You cannot delete your active session profile.";
        } else {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
            $success = "User deleted successfully.";
        }
    }
}

// Lab requirement query implementation
$users_result = $conn->query("
    SELECT u.id, u.username, u.email, u.role, u.created_at, COUNT(p.id) AS product_count 
    FROM users u 
    LEFT JOIN products p ON u.id = p.added_by 
    GROUP BY u.id 
    ORDER BY u.id ASC
");
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
            margin-top: 30px;
            margin-bottom: 20px;
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
            font-weight: bold;
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
            padding: 9px 16px;
            background: #2d2d2d;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
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

        .table-input {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Colored role badges */
        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 12px;
            color: #fff;
        }

        .badge-admin {
            background-color: #7952b3;
        }

        .badge-staff {
            background-color: #17a2b8;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <h2>CREATE NEW STAFF ACCOUNT</h2>

    <?php if (!empty($error)): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
    <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

    <div class="form-container">
        <form method="POST" action="users.php">
            <input type="hidden" name="action" value="create">
            <div class="form-group"><label>Username</label><input type="text" name="username" required></div>
            <div class="form-group"><label>Email Address</label><input type="email" name="email" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
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
                <th>Products Added</th>
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
                            <form id="update-form-<?= $user['id'] ?>" method="POST" action="users.php" style="display:inline;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="table-input" required>
                        </td>
                        <td>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="table-input" required>
                        </td>
                        <td>
                            <span class="role-badge <?= $user['role'] === 'admin' ? 'badge-admin' : 'badge-staff' ?>"><?= $user['role'] ?></span>
                            </form>
                        </td>
                        <td><strong><?= $user['product_count'] ?></strong> items</td>
                        <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                        <td>
                            <button type="submit" form="update-form-<?= $user['id'] ?>" style="color:#4caf50; background:none; border:none; font-weight:bold; cursor:pointer;">Save</button>
                            <form method="POST" action="users.php" style="display:inline;" onsubmit="return confirm('Delete account?')">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" style="color:#f44336; background:none; border:none; cursor:pointer; margin-left:10px;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>