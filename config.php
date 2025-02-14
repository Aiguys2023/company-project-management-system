<?php
$host = "localhost";
$user = "root";  // Default XAMPP MySQL user
$pass = "";      // Default XAMPP MySQL password
$db = "project_manager";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
