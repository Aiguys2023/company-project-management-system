<?php
session_start();
include 'config.php';
if (!isset($_SESSION["user_id"])) { die("Unauthorized access. Please <a href='login.html'>Login</a> first."); }
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $user_id = $_SESSION["user_id"];
    $stmt = $conn->prepare("INSERT INTO projects (user_id, name, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $name, $description);
    if ($stmt->execute()) { header("Location: dashboard.php"); exit(); }
    else { echo "Failed to add project!"; }
    $stmt->close(); $conn->close();
}
?>