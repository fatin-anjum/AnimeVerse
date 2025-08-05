<!DOCTYPE html>
<html>
<head>
    <title>Goodbye - AnimeVerse</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('background2.jpg') center/cover no-repeat fixed;;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .message-box {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px #000;
        }
        .message-box h1 {
            margin-bottom: 20px;
        }
        .message-box a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            background: #fff;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
        }
        .message-box a:hover {
            background: #ddd;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <h1>Goodbye from AnimeVerse 💔</h1>
        <?php if (isset($_GET['disbanded'])): ?>
            <p>Your account has been permanently deleted. We're sad to see you go.</p>
        <?php else: ?>
            <p>You have been logged out.</p>
        <?php endif; ?>
        <a href="register.php">Rejoin AnimeVerse</a>
    </div>
</body>
</html>
