<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskRabbit Clone - Browse Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        .filter-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .filter-container input, .filter-container select {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .task-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .task-card h4 {
            margin: 0 0 10px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error, .info {
            color: red;
            text-align: center;
            margin: 20px;
        }
        .info a {
            color: #28a745;
            text-decoration: none;
        }
        .info a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .task-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Browse Tasks</h1>
    </header>
    <?php
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tasker') {
        echo "<p class='info'>Please <a href='#' onclick=\"redirectTo('login.php')\">log in</a> as a tasker to view and apply for tasks.</p>";
    } else {
        ?>
        <div class="filter-container">
            <input type="text" id="search" placeholder="Search tasks...">
            <select id="category">
                <option value="">All Categories</option>
                <option value="cleaning">Cleaning</option>
                <option value="handyman">Handyman</option>
                <option value="moving">Moving Help</option>
                <option value="furniture_assembly">Furniture Assembly</option>
            </select>
            <input type="number" id="budget" placeholder="Max Budget">
        </div>
        <div class="task-grid">
            <?php
            try {
                $query = "SELECT * FROM tasks WHERE status = 'pending'";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                if ($stmt->rowCount() === 0) {
                    echo "<p class='info'>No pending tasks available.</p>";
                } else {
                    while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<div class='task-card'>";
                        echo "<h4>" . htmlspecialchars($task['title']) . "</h4>";
                        echo "<p>" . htmlspecialchars($task['description']) . "</p>";
                        echo "<p><strong>Category:</strong> " . htmlspecialchars($task['category']) . "</p>";
                        echo "<p><strong>Budget:</strong> $" . htmlspecialchars($task['budget']) . "</p>";
                        echo "<p><strong>Location:</strong> " . htmlspecialchars($task['location']) . "</p>";
                        echo "<button onclick=\"redirectTo('apply_task.php?task_id=" . $task['id'] . "')\">Apply</button>";
                        echo "</div>";
                    }
                }
            } catch (PDOException $e) {
                error_log("Browse tasks error: " . $e->getMessage(), 3, 'database_errors.log');
                echo "<p class='error'>Error loading tasks. Please try again later.</p>";
            }
            ?>
        </div>
        <?php
    }
    ?>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
        document.getElementById('search')?.addEventListener('input', function() {
            // Add client-side filtering logic here if needed
        });
    </script>
</body>
</html>
