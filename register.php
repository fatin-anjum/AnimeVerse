<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = "Username already taken.";
        } else {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $error = "Email already registered.";
            } else {
                // Proceed with registration
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hash]);

                // Redirect after success
                header("Location: login.php?registered=1");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - AnimeVerse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #141e30, #243b55);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-box {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px #000;
            width: 350px;
            text-align: center;
        }
        .register-box h2 {
            margin-bottom: 20px;
        }
        .register-box input[type="text"],
        .register-box input[type="email"],
        .register-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .register-box button {
            background-color: #00adb5;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .register-box button:hover {
            background-color: #007a80;
        }
        .register-box a {
            color:  #00c7d3;
            text-decoration: none;
        }
        .register-box a:hover {
            text-decoration: underline;
        }
        .error {
            color: #ff6b6b;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Create Your AnimeVerse Account</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <p>Already registered? <a href="login.php">Log in here</a>.</p>
    </div>
</body>
</html>

