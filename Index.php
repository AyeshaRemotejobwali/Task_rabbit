<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskRabbit Clone - Home</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        nav a {
            color: white;
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .hero {
            background: url('https://source.unsplash.com/random/1600x400/?services') no-repeat center;
            background-size: cover;
            color: white;
            text-align: center;
            padding: 100px 20px;
        }
        .hero h2 {
            font-size: 36px;
            margin: 0;
        }
        .hero p {
            font-size: 18px;
        }
        .services, .taskers {
            padding: 40px 20px;
            text-align: center;
        }
        .services h3, .taskers h3 {
            font-size: 28px;
            margin-bottom: 20px;
        }
        .service-grid, .tasker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .service-card, .tasker-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .service-card:hover, .tasker-card:hover {
            transform: translateY(-5px);
        }
        .service-card img, .tasker-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        @media (max-width: 768px) {
            .hero h2 {
                font-size: 28px;
            }
            .service-grid, .tasker-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>TaskRabbit Clone</h1>
        <nav>
            <a href="#" onclick="redirectTo('login.php')">Login</a>
            <a href="#" onclick="redirectTo('signup.php')">Signup</a>
            <a href="#" onclick="redirectTo('post_task.php')">Post a Task</a>
            <a href="#" onclick="redirectTo('browse_tasks.php')">Browse Tasks</a>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="#" onclick="redirectTo('logout.php')">Logout</a>
            <?php } ?>
        </nav>
    </header>
    <section class="hero">
        <h2>Find Local Services Fast</h2>
        <p>Hire skilled freelancers for cleaning, moving, handyman, and more!</p>
        <button onclick="redirectTo('post_task.php')">Get Started</button>
    </section>
    <section class="services">
        <h3>Popular Services</h3>
        <div class="service-grid">
            <div class="service-card">
                <img src="https://source.unsplash.com/random/200x150/?cleaning" alt="Cleaning">
                <h4>Cleaning</h4>
                <p>Professional cleaning services for your home or office.</p>
            </div>
            <div class="service-card">
                <img src="https://source.unsplash.com/random/200x150/?handyman" alt="Handyman">
                <h4>Handyman</h4>
                <p>Fixes and repairs done quickly and efficiently.</p>
            </div>
            <div class="service-card">
                <img src="https://source.unsplash.com/random/200x150/?moving" alt="Moving">
                <h4>Moving Help</h4>
                <p>Assistance with packing and moving your belongings.</p>
            </div>
        </div>
    </section>
    <section class="taskers">
        <h3>Top Freelancers</h3>
        <div class="tasker-grid">
            <div class="tasker-card">
                <img src="https://source.unsplash.com/random/200x150/?person" alt="Tasker">
                <h4>John Doe</h4>
                <p>5.0 ★ | Expert in Cleaning & Handyman</p>
            </div>
            <div class="tasker-card">
                <img src="https://source.unsplash.com/random/200x150/?person2" alt="Tasker">
                <h4>Jane Smith</h4>
                <p>4.8 ★ | Professional Mover</p>
            </div>
        </div>
    </section>
    <footer>
        <p>© 2025 TaskRabbit Clone. All rights reserved.</p>
    </footer>
    <script>
        function redirectTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>
