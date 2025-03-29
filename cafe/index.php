<?php
session_start();
include "db_connect2.php";

if (!isset($_SESSION["employee_id"])) {
    header("Location: login2.php");
    exit();
}

$employee_id = $_SESSION["employee_id"];
$query = $conn->prepare("SELECT email FROM EMPLOYEE WHERE employee_id = ?");
$query->bind_param("i", $employee_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
$query->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brewlane Cafe</title>
    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #333;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: orange;
        }

        .navbar h1 {
            margin: 0;
            color: white;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar ul li {
            margin: 0 15px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        button {
    display: block; 
    margin: 400px auto; 
    background-color: #ff6600; 
    color: white; 
    font-size: 16px; 
    padding: 10px 20px;
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    transition: background-color 0.3s ease-in-out; 
}

button:hover {
    background-color: #cc5500; 
}


button:hover {
    background-color: #cc5500; 
}

        .hero {
            background: url('https://source.unsplash.com/1600x900/?food,burger') no-repeat center center/cover;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            padding: 20px;
        }

        .welcome {
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

    <header>
        <div class="navbar">
            <h1>Brewlane Cafe</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="login2.php">Login</a></li>
                    <li><a href="signup.php">Sign up</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <button class="order-btn" onclick="window.location.href='menu.php'">New Order</button>
    </div>
</body>
</html>