<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'im102_lab2_gasal_db'; // Your updated database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
