<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
include 'config.php';

if (!isset($_GET["id"])) {
    echo "Invalid Project ID";
    exit();
}

$project_id = $_GET["id"];
$user_id = $_SESSION["user_id"];

// Fetch project details
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $project_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();
if (!$project) {
    echo "Project not found.";
    exit();
}

// Handle project update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_project"])) {
    $status = $_POST["status"];
    $employees = $_POST["employees"];
    $budget = $_POST["budget"];
    $expenses = $_POST["expenses"];
    
    $stmt = $conn->prepare("UPDATE projects SET status=?, employees=?, budget=?, expenses=? WHERE id=? AND user_id=?");
    $stmt->bind_param("siddii", $status, $employees, $budget, $expenses, $project_id, $user_id);
    $stmt->execute();
    header("Location: project_details.php?id=$project_id");
    exit();
}

// Handle project deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_project"])) {
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $project_id, $user_id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($project["name"] ?? "Project Details"); ?></h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Back to Dashboard</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="project-details-container">
        <h2>Project Information</h2>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($project["status"] ?? "N/A"); ?></p>
        <p><strong>Employees Working:</strong> <?php echo htmlspecialchars($project["employees"] ?? "0"); ?></p>
        <p><strong>Budget:</strong> $<?php echo htmlspecialchars($project["budget"] ?? "0.00"); ?></p>
        <p><strong>Expenses:</strong> $<?php echo htmlspecialchars($project["expenses"] ?? "0.00"); ?></p>
        
        <h2>Update Project</h2>
        <form method="POST" action="">
            <input type="text" name="status" value="<?php echo htmlspecialchars($project["status"] ?? ""); ?>" required>
            <input type="number" name="employees" value="<?php echo htmlspecialchars($project["employees"] ?? "0"); ?>" required>
            <input type="number" name="budget" value="<?php echo htmlspecialchars($project["budget"] ?? "0.00"); ?>" step="0.01" required>
            <input type="number" name="expenses" value="<?php echo htmlspecialchars($project["expenses"] ?? "0.00"); ?>" step="0.01" required>
            <button type="submit" name="update_project">Update Project</button>
        </form>
        
        <h2>Delete Project</h2>
        <form method="POST" action="">
            <button type="submit" name="delete_project" onclick="return confirm('Are you sure you want to delete this project?');">Delete Project</button>
        </form>
    </div>
    
    <canvas id="budgetChart"></canvas>
    
    <script>
        const ctx = document.getElementById('budgetChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Budget', 'Expenses'],
                datasets: [{
                    label: 'Amount ($)',
                    data: [<?php echo $project['budget']; ?>, <?php echo $project['expenses']; ?>],
                    backgroundColor: ['#1abc9c', '#e74c3c'],
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
