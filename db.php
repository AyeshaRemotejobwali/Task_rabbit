<?php
$host = 'localhost'; 
$username = 'uxgukysg8xcbd'; 
$password = '6imcip8yfmic'; 
$database = 'dbhr2x4u3b9smy'; 
try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Log error to a file instead of displaying it to users in production
    error_log("Connection failed: " . $e->getMessage(), 3, 'database_errors.log');
    die("Database connection failed. Please try again later.");
}
?>
