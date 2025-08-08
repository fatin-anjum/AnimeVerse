<!DOCTYPE html>
<html>
<head>
    <title>Register - AnimeVerse</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
<div class="register-box">
    <h2>Create Your AnimeVerse Account</h2>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already registered? <a href="/Animeverse/controller/logincontroller.php">Log in here</a>.</p>
</div>
</body>
</html>
