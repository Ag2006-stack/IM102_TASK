
<?php
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);
$result = $conn->query("SELECT * FROM students WHERE id = $id");
$student = $result->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $course = $conn->real_escape_string($_POST['course']);
    $year = (int)$_POST['year'];
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    
    if (empty($name) || empty($course)) {
        $message = '<p style="color:red;">Name and course are required.</p>';
    } else {
        $sql = "UPDATE students SET 
                name='$name', course='$course', year=$year,
                email='$email', phone='$phone', address='$address'
                WHERE id=$id";
        
        if ($conn->query($sql)) {
            header('Location: index.php');
            exit;
        } else {
            $message = '<p style="color:red;">Error: ' . $conn->error . '</p>';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <div class="container">
        <h1>Edit Student #<?= $student['id'] ?></h1>
        
        <?= $message ?>
        
        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
            
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($student['email'] ?? '') ?>">
            
            <label>Course</label>
            <select name="course" required>
                <option value="BSIT" <?= $student['course'] == 'BSIT' ? 'selected' : '' ?>>BSIT</option>
                <option value="BSCS" <?= $student['course'] == 'BSCS' ? 'selected' : '' ?>>BSCS</option>
            </select>
            
            <label>Year Level</label>
            <input type="number" name="year" value="<?= $student['year'] ?>" min="1" max="5" required>
            
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($student['phone'] ?? '') ?>">
            
            <label>Address</label>
            <textarea name="address" rows="3"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
            
            <button type="submit">Update Student</button>
            <a href="index.php" class="cancel">Cancel</a>
        </form>
    </div>
</body>
</html>
