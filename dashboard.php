<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
include 'config.php';

$user_id = $_SESSION["user_id"];

// Handle new project submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_project"])) {
    $name = $_POST["name"] ?? "";
    $status = $_POST["status"] ?? "";
    $employees = $_POST["employees"] ?? 0;
    $deadline = $_POST["deadline"] ?? "";
    $budget = $_POST["budget"] ?? 0.00;
    $expenses = $_POST["expenses"] ?? 0.00;

    $stmt = $conn->prepare("INSERT INTO projects (user_id, name, status, employees, deadline, budget, expenses) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issisdd", $user_id, $name, $status, $employees, $deadline, $budget, $expenses);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT id, name, status, employees, deadline, budget, expenses FROM projects WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$project_data = [];
while ($row = $result->fetch_assoc()) {
    $project_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - User Projects</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            display: flex;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #2c3e50;
            color: white;
            padding: 20px;
            transition: transform 0.3s ease;
            position: fixed;
            transform: translateX(-100%);
        }
        .sidebar.active {
            transform: translateX(0);
        }
        .menu-toggle {
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
            background: #2c3e50;
            color: white;
            border: none;
            position: fixed;
            left: 10px;
            top: 10px;
            z-index: 1000;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 15px;
            cursor: pointer;
            transition: 0.3s;
        }
        .sidebar ul li:hover {
            background: #34495e;
        }
        .main-content {
            margin-left: 20px;
            padding: 20px;
            width: 100%;
        }
        .form-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .form-container input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            background: #1abc9c;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .form-container button:hover {
            background: #16a085;
        }
    </style>
</head>
<body>
    <button class="menu-toggle" onclick="toggleMenu()">☰</button>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li onclick="location.href='dashboard.php'">Home</li>
            <li onclick="location.href='profile.php'">Profile Details</li>
            <li onclick="location.href='forgot_password.php'">Forgot Password</li>
            <li onclick="location.href='logout.php'">Logout</li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Your Projects</h2>
        <?php foreach ($project_data as $project): ?>
            <div class="project-card">
                <h3><a href="project_details.php?id=<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project["name"] ?? "No Name"); ?></a></h3>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($project["status"] ?? "N/A"); ?></p>
                <p><strong>Employees Working:</strong> <?php echo htmlspecialchars($project["employees"] ?? "0"); ?></p>
                <p><strong>Budget:</strong> $<?php echo htmlspecialchars($project["budget"] ?? "0.00"); ?></p>
                <p><strong>Expenses:</strong> $<?php echo htmlspecialchars($project["expenses"] ?? "0.00"); ?></p>
            </div>
        <?php endforeach; ?>
    
        <h2>Add New Project</h2>
        <form method="POST" action="dashboard.php" class="form-container">
            <input type="hidden" name="add_project" value="1">
            <input type="text" name="name" placeholder="Project Name" required>
            <input type="text" name="status" placeholder="Status" required>
            <input type="number" name="employees" placeholder="Employees" required>
            <input type="date" name="deadline" required>
            <input type="number" name="budget" placeholder="Budget" step="0.01" required>
            <input type="number" name="expenses" placeholder="Expenses" step="0.01" required>
            <button type="submit">Add Project</button>
        </form>
    
        <canvas id="budgetChart"></canvas>
    </div>

    <script>
        function toggleMenu() {
            document.querySelector('.sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
