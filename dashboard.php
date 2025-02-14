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
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="dashboard-container">
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
        <form method="POST" action="dashboard.php">
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
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
