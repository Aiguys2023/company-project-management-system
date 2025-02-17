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
$stmt->close();

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
    
    if ($stmt->execute()) {
        echo "<script>alert('Project updated successfully!'); window.location.href='project_details.php?id=$project_id';</script>";
    } else {
        echo "<script>alert('Error updating project: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .project-container {
            background: rgba(37, 35, 35, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 650px;
            max-height: 90vh;
            overflow-y: auto;
            text-align: center;
            transition: transform 0.3s ease-in-out;
            animation: fadeIn 0.5s;
        }
        .project-container:hover {
            transform: scale(1.02);
        }
        .project-info, .ai-analysis {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #34495e;
            color: white;
        }
        .project-info p, .ai-analysis p {
            padding: 8px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.15);
        }
        .ai-analysis {
            background: #f39c12;
        }
        input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #1abc9c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
        }
        button:hover {
            background: #16a085;
        }
    </style>
</head>
<body>
    <div class="project-container">
        <h2><?php echo htmlspecialchars($project["name"] ?? "Project Details"); ?></h2>
        <div class="project-info">
            <p><strong>Status:</strong> <?php echo htmlspecialchars($project["status"] ?? "N/A"); ?></p>
            <p><strong>Employees:</strong> <?php echo htmlspecialchars($project["employees"] ?? "0"); ?></p>
            <p><strong>Budget:</strong> $<?php echo htmlspecialchars($project["budget"] ?? "0.00"); ?></p>
            <p><strong>Expenses:</strong> $<?php echo htmlspecialchars($project["expenses"] ?? "0.00"); ?></p>
        </div>
        <div class="ai-analysis">
            <h3>AI Project Analysis</h3>
            <p>Your project is being analyzed for efficiency.</p>
        </div>
        <canvas id="projectChart"></canvas>
        <h2>Update Project</h2>
        <form method="POST" action="project_details.php?id=<?php echo $project_id; ?>">
            <input type="text" name="status" value="<?php echo htmlspecialchars($project["status"] ?? ""); ?>" required>
            <input type="number" name="employees" value="<?php echo htmlspecialchars($project["employees"] ?? "0"); ?>" required>
            <input type="number" name="budget" value="<?php echo htmlspecialchars($project["budget"] ?? "0.00"); ?>" step="0.01" required>
            <input type="number" name="expenses" value="<?php echo htmlspecialchars($project["expenses"] ?? "0.00"); ?>" step="0.01" required>
            <button type="submit" name="update_project">Update Project</button>
        </form>
    </div>
    <script>
        const ctx = document.getElementById('projectChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
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
                maintainAspectRatio: false
            }
        });
    </script>
</body>
</html>
