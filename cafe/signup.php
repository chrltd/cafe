<?php
session_start();
include "db_connect2.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["confirm_password"])) {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $confirm_password = trim($_POST["confirm_password"]);

        if ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = $conn->prepare("INSERT INTO EMPLOYEE (email, password) VALUES (?, ?)");
            $query->bind_param("ss", $email, $hashed_password);
            
            if ($query->execute()) {
                $_SESSION["employee_id"] = $conn->insert_id;
                header("Location: login2.php");
                exit();
            } else {
                $error = "Error registering user.";
            }
            $query->close();
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brewlane Cafe - Sign Up</title>
    <style>
    
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: orange;
            position: fixed;
            top: 0;
            left: 0;
        }

        .navbar h1 {
            margin: 0;
            color: white;
            font-size: 22px;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar ul li {
            margin-left: 15px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .signup-container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 25px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            margin-top: 100px;
        }

        .signup-container h2 {
            margin-bottom: 20px;
        }

        .signup-container input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .signup-container button {
            width: 100%;
            background-color: #4a5cd4;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .signup-container button:hover {
            background-color: #3b4bbd;
        }

        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .login-link a {
            color: #4a5cd4;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
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

    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php if (!empty($error)) { echo "<p class='error-message'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login2.php">Log in</a>
        </div>
    </div>

</body>
</html>
