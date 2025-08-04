<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, bio=? WHERE user_id=?");
    $stmt->execute([$username, $email, $bio, $user_id]);

    $message = "Profile updated successfully!";
    // Refresh user info
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    // Re-fetch only the password hash
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $userPasswordRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userPasswordRow && password_verify($current, $userPasswordRow['password_hash'])) {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash=? WHERE user_id=?");
        $stmt->execute([$hashed, $user_id]);
        $message = "Password changed successfully!";
    } else {
        $error = "Current password is incorrect.";
    }
}

// Handle account disband (DELETE permanently)
if (isset($_POST['disband'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    session_destroy();
    header("Location: goodbye.php?disbanded=1");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - AnimeVerse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1f4037, #99f2c8);
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: rgba(255,255,255,0.95);
            max-width: 600px;
            margin: 60px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0,0,0,0.2);
        }
        h2, h3 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
        }
        button {
            background-color: #003f11ff;
            color: white;
            padding: 12px;
            margin-top: 20px;
            width: 100%;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #00591b;
        }
        .danger {
            background-color: #db0016ff;
        }
        .danger:hover {
            background-color: #a71d2a;
        }
        .message {
            color: green;
            text-align: center;
            font-weight: bold;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>My Profile</h2>

    <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form action="" method="post">
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

    <form method="post" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This cannot be undone!');">
        <button class="danger" type="submit" name="disband">Disband Account</button>
    </form>
</div>

</body>
</html>
