<?php
session_start();
require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Basic input validation
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($role !== 'client' && $role !== 'tasker') {
        $error = "Invalid role selected.";
    } else {
        try {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            $stmt->execute(['username' => $username, 'email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                $error = "Username or email already taken.";
            } else {
                // Insert new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
                $stmt->execute([
                    'username' => $username,
                    'email' => $email,
                    'password' => $hashed_password,
                    'role' => $role
                ]);
                echo "<script>window.location.href = 'login.php';</script>";
                exit;
            }
        } catch (PDOException $e) {
            // Log error to file
            error_log("Signup error: " . $e->getMessage(), 3, 'database_errors.log');
            $error = "An error occurred during signup. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskRabbit Clone - Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .signup-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .signup-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
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
        @media (max-width: 768px) {
            .signup-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="client" <?php echo (($_POST['role'] ?? '') === 'client') ? 'selected' : ''; ?>>Client</option>
                    <option value="tasker" <?php echo (($_POST['role'] ?? '') === 'tasker') ? 'selected' : ''; ?>>Tasker</option>
                </select>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <p style="text-align: center;">Already have an account? <a href="#" onclick="redirectTo('login.php')">Login</a></p>
        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
    </div>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
