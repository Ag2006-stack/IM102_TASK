<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $course = $conn->real_escape_string($_POST['course']);
    $year = (int) $_POST['year'];
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);

    '</p>';
    if (empty($name) || empty($course) || empty($year) || empty($email) || empty($phone) || empty($address)) {
        $message = '<p style="color:red;">All fields are required.</p>';
    } else {
        $sql = "INSERT INTO students (name, course, year , email , phone , address ) VALUES ('$name', 
'$course', $year , '$email' , $phone , '$address' )";
         if ($conn->query($sql)) {
 echo '<p style="color:green; font-size:1.2em;">Student added! 
Redirecting...</p>';
 header('Refresh: 2; URL=index.php');
 exit;
 }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="add.css">
</head>

<body>
    <div class="container">
        <h1>Add New Student</h1> 

        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="name" required placeholder="e.g. Juan 
            Dela Cruz">
            <label>Course</label>
            <select name="course" required>
                <option value="">-- Select Course --</option>
                <option value="BSIT">BSIT</option>
                <option value="BSCS">BSCS</option>
            </select>

            <label>Year Level</label>
            <input type="number" name="year" min="1" max="5" required placeholder="1-5">
            <label>Email</label>
            <input type="text" name="email"  required placeholder="n@gmail.com"> 
            <label>phone</label>
            <input type="number" name="phone"  required placeholder="09+">
            <label>Address</label>
            <input type="text" name="address"  required placeholder="saray inc"> 
            <button type="submit">Add Student</button>

            <a href="index.php" class="cancel">Cancel</a>
        </form>
    </div>
</body>

</html>