<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $task_id = $_POST['task_id'];
    $message = $_POST['message'];

    try {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, task_id, message) VALUES (:sender_id, :receiver_id, :task_id, :message)");
        $stmt->execute([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'task_id' => $task_id,
            'message' => $message
        ]);
        echo "<script>window.location.href = 'messages.php?task_id=$task_id&receiver_id=$receiver_id';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
