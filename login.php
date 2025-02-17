<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        echo "<script>alert('Login successful! Redirecting to dashboard...'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Invalid username or password! Please try again.'); window.location.href='login.html';</script>";
    }
}
?>
