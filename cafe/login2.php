<?php
session_start();
include "db_connect2.php"; 

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        $query = $conn->prepare("SELECT employee_id, password FROM EMPLOYEE WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) { 
                $_SESSION["employee_id"] = $user["employee_id"];
                header("Location: menu.php"); 
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
        $query->close();
    } else {
        $error = "Please enter both email and password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brewlane Cafe - Login</title>
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

        .login-container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 25px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            margin-top: 100px;
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .login-container input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .login-container button {
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

        .login-container button:hover {
            background-color: #3b4bbd;
        }

        .signup-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .signup-link a {
            color: #4a5cd4;
            text-decoration: none;
        }

        .signup-link a:hover {
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

    <div class="login-container">
        <h2>Login</h2>
        <?php if (!empty($error)) { echo "<p class='error-message'>$error</p>"; } ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up</a>
        </div>
    </div>

</body>
</html>
