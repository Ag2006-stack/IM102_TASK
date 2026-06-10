<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $course = $_POST['course'];
    $year = $_POST['year'];

    $stmt = $conn->prepare("INSERT INTO students (name, course, year) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $course, $year);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT * FROM students";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student List</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <p>
        <a href="add.php" style="
 display: inline-block;
 padding: 10px 20px;
 background: #4CAF50;
 color: white;
 text-decoration: none;
 border-radius: 4px;
 ">+ Add Student</a>
    </p>
    <table>
        <br>

        <h2>NPA ENLISTMENT</h2>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Course</th>
            <th>Year</th>
            <th>Date Added</th>
            <th>email</th>
            <th>phone</th>
            <th>address</th>

        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['course']) ?></td>
                <td><?= htmlspecialchars($row['year']) ?></td>
                <td><?= htmlspecialchars($row['Date_Added']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>