<!DOCTYPE html>
<html>
<head>
    <title>Goodbye - AnimeVerse</title>
    <link rel="stylesheet" href="/Animeverse/css/goodbye.css">
</head>
<body>
    <div class="message-box">
        <h1>Goodbye from AnimeVerse 💔</h1>
        <?php if ($isDisbanded): ?>
            <p>Your account has been permanently deleted. We're sad to see you go.</p>
        <?php else: ?>
            <p>You have been logged out.</p>
        <?php endif; ?>
        <a href="?page=register">Rejoin AnimeVerse</a>
    </div>
</body>
</html>
