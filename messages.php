<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskRabbit Clone - Messages</title>
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
        .message-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .message-list {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .message {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .form-group {
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <header>
        <h1>Messages</h1>
    </header>
    <div class="message-container">
        <div class="message-list">
            <?php
            $user_id = $_SESSION['user_id'];
            $task_id = $_GET['task_id'] ?? 0;
            $stmt = $conn->prepare("SELECT m.*, u.username FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.task_id = :task_id AND (m.sender_id = :user_id OR m.receiver_id = :user_id)");
            $stmt->execute(['task_id' => $task_id, 'user_id' => $user_id]);
            while ($message = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='message'>";
                echo "<strong>" . htmlspecialchars($message['username']) . ":</strong> " . htmlspecialchars($message['message']);
                echo "<p><small>" . $message['sent_at'] . "</small></p>";
                echo "</div>";
            }
            ?>
        </div>
        <form action="send_message.php" method="POST">
            <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
            <input type="hidden" name="receiver_id" value="<?php echo $_GET['receiver_id'] ?? 0; ?>">
            <div class="form-group">
                <textarea name="message" required></textarea>
            </div>
            <button type="submit">Send Message</button>
        </form>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
