<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    error_log("Apply task: User not logged in", 3, 'debug.log');
    echo "<script>alert('Please log in as a tasker to apply for tasks.'); window.location.href = 'login.php';</script>";
    exit;
}

if ($_SESSION['role'] !== 'tasker') {
    error_log("Apply task: User role is {$_SESSION['role']}, expected tasker", 3, 'debug.log');
    echo "<script>alert('Only taskers can apply for tasks. Please log in as a tasker.'); window.location.href = 'login.php';</script>";
    exit;
}

$error = '';
if (isset($_GET['task_id'])) {
    $task_id = filter_var($_GET['task_id'], FILTER_VALIDATE_INT);
    if (!$task_id) {
        $error = "Invalid task ID.";
    } else {
        $tasker_id = $_SESSION['user_id'];
        $message = $_POST['message'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $stmt = $conn->prepare("INSERT INTO task_applications (task_id, tasker_id, message) VALUES (:task_id, :tasker_id, :message)");
                $stmt->execute([
                    'task_id' => $task_id,
                    'tasker_id' => $tasker_id,
                    'message' => $message
                ]);
                echo "<script>window.location.href = 'browse_tasks.php';</script>";
                exit;
            } catch (PDOException $e) {
                error_log("Apply task error: " . $e->getMessage(), 3, 'database_errors.log');
                $error = "Error submitting application. Please try again.";
            }
        }
    }
} else {
    $error = "No task ID provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Apply for Task</h2>
        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST">
            <div class="form-group">
                <label for="message">Application Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>
</html>
