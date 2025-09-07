<!DOCTYPE html>
<html>
<head>
    <title>Login - AnimeVerse</title>
    <link rel="stylesheet" href="/Animeverse/css/login.css">
</head>

<body>
    <div class="login-box">
        <h2>Login to AnimeVerse</h2>
        <?php if (isset($_GET['registered']) && $_GET['registered'] == '1'): ?>
            <p class='success'>Registration successful! Please login with your credentials.</p>
        <?php endif; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
        <p>Don't have an account? <a href="?page=register">Register here</a>.</p>
    </div>
</body>
</html>
