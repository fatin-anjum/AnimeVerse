<!DOCTYPE html>
<html>
<head>
    <title>My Profile - AnimeVerse</title>
    <link rel="stylesheet" href="/animeverse/css/profile.css">
</head>

<body>
    <div class="pnavbar">
        <div class="pnavbar-left">
            <span>A n i m e V e r s e</span>
        </div>
        <div class="pnavbar-right">
            <a href="homecontroller.php">Home</a>
            <a href="profilecontroller.php">Profile Management</a>
            <a href="faq.php">FAQ</a>
            <a href="contact.php">Contact Us</a>
            <?php if ($userEmail): ?>
                <a href="logoutcontroller.php">Logout</a>
            <?php else: ?>
                <a href="logincontroller.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

<div class="container">
    
    <h2>My Profile</h2>

    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Bio:</label>
        <textarea name="bio" rows="4"><?= htmlspecialchars($user['bio']) ?></textarea>

        <button type="submit" name="update">Update Profile</button>
    </form>

    <hr>

    <h3>Change Password</h3>
    <form method="post">
        <label>Current Password:</label>
        <input type="password" name="current_password" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <button type="submit" name="change_password">Change Password</button>
    </form>

    <hr>

    <form method="post" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone!');">
        <button class="danger" type="submit" name="disband">Disband Account</button>
    </form>
</div>

</body>
</html>
