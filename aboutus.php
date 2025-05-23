<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Student Info System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 600;
        }

        .nav-links a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .container {
            background: #fff;
            margin: 2rem auto;
            padding: 2rem;
            width: 90%;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 0.3rem;
        }

        .subject-description {
            text-align: center;
            font-size: 16px;
            color: #555;
            margin-bottom: 2rem;
            font-style: italic;
        }

        .team {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .member {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 12px;
            width: 280px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .member:hover {
            transform: translateY(-5px);
        }

        .member img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #3498db;
            margin-bottom: 15px;
        }

        .member h3 {
            margin: 10px 0 5px;
            color: #2c3e50;
        }

        .member p {
            margin: 0;
            font-size: 15px;
            color: #555;
        }

        .footer {
            margin-top:281px;
            text-align: center;
            padding: 1rem;
            background-color: #2c3e50;
            color: white;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">Student Info System</div>
    <div class="nav-links">
        <a href="Home.php">Home</a>
        <a href="aboutus.php">About Us</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Meet the Developers</h2>
    <p class="subject-description">
        This project is a Student Information System created for the subject <strong>Web Systems and Technologies 2</strong>.<br>
        It will be submitted to <strong>Mr. Sheldon Arenas</strong> as part of the final requirements.
    </p>

    <div class="team">
        <div class="member">
            <img src="Images/Andrei New 1x1.jpg" alt="Jan Andrei Caballero">
            <h3>Jan Andrei Caballero</h3>
            <p>BSIT 3B-G2<br>Frontend & XML Integration</p>
        </div>
        <div class="member">
            <img src="Images/Ian.jpg" alt="Ian Lawrence Juan">
            <h3>Groupmate Name</h3>
            <p>BSIT 3B-G2<br>Frontend & Backend</p>
        </div>
    </div>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Student Info System. All rights reserved.
</div>

</body>
</html>
