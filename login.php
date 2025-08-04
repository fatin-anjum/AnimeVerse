<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name']; // Optional if you want to show name
        header("Location: home.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - AnimeVerse</title>
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
        .login-box {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px #000;
            width: 350px;
            text-align: center;
        }
        .login-box h2 {
            margin-bottom: 20px;
        }
        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
        }
        .login-box button {
            background-color: #00adb5;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-box button:hover {
            background-color: #007a80;
        }
        .login-box a {
            color: #00adb5;
            text-decoration: none;
        }
        .login-box a:hover {
            text-decoration: underline;
        }
        .error {
            color: #ff6b6b;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login to AnimeVerse</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>
