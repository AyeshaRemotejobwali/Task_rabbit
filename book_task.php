<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

if (isset($_GET['task_id']) && isset($_GET['tasker_id'])) {
    $task_id = $_GET['task_id'];
    $tasker_id = $_GET['tasker_id'];
    $client_id = $_SESSION['user_id'];
    $booking_date = $_POST['booking_date'] ?? date('Y-m-d H:i:s');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $stmt = $conn->prepare("INSERT INTO bookings (task_id, tasker_id, client_id, booking_date) VALUES (:task_id, :tasker_id, :client_id, :booking_date)");
            $stmt->execute([
                'task_id' => $task_id,
                'tasker_id' => $tasker_id,
                'client_id' => $client_id,
                'booking_date' => $booking_date
            ]);
            $stmt = $conn->prepare("UPDATE tasks SET status = 'in_progress' WHERE id = :task_id");
            $stmt->execute(['task_id' => $task_id]);
            echo "<script>window.location.href = 'index.php';</script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Tasker</title>
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
        .form-group input {
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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Book Tasker</h2>
        <form method="POST">
            <div class="form-group">
                <label for="booking_date">Booking Date</label>
                <input type="datetime-local" id="booking_date" name="booking_date" required>
            </div>
            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</body>
</html>
