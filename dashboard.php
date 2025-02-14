<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}
include 'config.php';

$user_id = $_SESSION["user_id"];
$result = $conn->query("SELECT * FROM projects WHERE user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Status</title>
    <link rel="stylesheet" href="dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Company Project Status Management</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="dashboard-container">
        <h2>Ongoing Projects</h2>
        
        <div class="project-card">
            <h3>Project Alpha</h3>
            <p><strong>Status:</strong> In Progress</p>
            <p><strong>Employees Working:</strong> 10</p>
            <p><strong>Deadline:</strong> 2025-06-30</p>
            <p><strong>Working Hours:</strong> 9 AM - 6 PM</p>
            <p><strong>Budget:</strong> $50,000</p>
            <p><strong>Expenses:</strong> $30,000</p>
        </div>
        
        <div class="project-card">
            <h3>Project Beta</h3>
            <p><strong>Status:</strong> Pending</p>
            <p><strong>Employees Working:</strong> 8</p>
            <p><strong>Deadline:</strong> 2025-07-15</p>
            <p><strong>Working Hours:</strong> 10 AM - 7 PM</p>
            <p><strong>Budget:</strong> $40,000</p>
            <p><strong>Expenses:</strong> $20,000</p>
        </div>
        
        <canvas id="budgetChart"></canvas>
    </div>
    
    <footer>
        <p>&copy; 2025 Company Project Management. All rights reserved.</p>
    </footer>
    
    <script>
        const ctx = document.getElementById('budgetChart').getContext('2d');
        const budgetChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Project Alpha', 'Project Beta'],
                datasets: [{
                    label: 'Budget vs Expenses',
                    data: [50000, 40000],
                    backgroundColor: ['#1abc9c', '#f1c40f'],
                    borderColor: ['#16a085', '#d4ac0d'],
                    borderWidth: 1
                }, {
                    label: 'Expenses',
                    data: [30000, 20000],
                    backgroundColor: ['#e74c3c', '#e67e22'],
                    borderColor: ['#c0392b', '#d35400'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>
