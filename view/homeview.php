<!doctype html>
<html>
<head>
    <title>Home - AnimeVerse</title>
    <link rel="stylesheet" href="/Animeverse/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-left">
            <span>AnimeVerse</span>
        </div>
        <div class="navbar-right">
            <a href="?page=home" class="active"><i class="fas fa-home"></i> Home</a>
            <a href="?page=myprofile"><i class="fas fa-user"></i> My Profile</a>
            <a href="?page=profile"><i class="fas fa-user-edit"></i> Update Profile</a>
            <a href="?page=collectibles"><i class="fas fa-gem"></i> Collectibles</a>
            <a href="?page=fanart"><i class="fas fa-palette"></i> Fan Art</a>
            <a href="?page=discussion"><i class="fas fa-comments"></i> Discussions</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow"><i class="fas fa-users"></i> Follow</a>
            <?php if ($userEmail): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="hero">
        <div class="hero-content">
            <h1><i class="fas fa-star"></i> Welcome to AnimeVerse</h1>
            <p>AnimeVerse is the perfect place for people who love anime!</p>
            <div class="hero-buttons">
                <a href="?page=discussion" class="primary"><i class="fas fa-comments"></i> Discussion Channel</a>
                <a href="?page=polldebate" class="primary"><i class="fas fa-poll"></i> Polls</a>
                <a href="?page=debate" class="primary"><i class="fas fa-balance-scale"></i> Debates</a>
                <a href="?page=animereview" class="primary"><i class="fas fa-star"></i> Ratings/Reviews</a>
            </div>
        </div>
    </div>
    <div class="content-section">
        <h2><i class="fas fa-question-circle"></i> What is AnimeVerse?</h2>
        <p>
            <strong>**AnimeVerse** is a fan-driven platform where anime enthusiasts can explore reviews, share fan art, join discussions, and trade collectibles in a vibrant community.</strong>
        </p>
    </div>
    <div class="content-section">
        <h2><i class="fas fa-thumbs-up"></i> Why AnimeVerse?</h2>
        <ul class="benefits-list">
            <li><strong><i class="fas fa-globe"></i> Easy access to the anime world</strong></li>
            <li><strong><i class="fas fa-clock"></i> Active 24×7</strong></li>
            <li><strong><i class="fas fa-free-code-camp"></i> Free to use</strong></li>
            <li><strong><i class="fas fa-shield-alt"></i> All data are secured</strong></li>
        </ul>
    </div>
    <div class="content-section">
        <h2><i class="fas fa-box-open"></i> Take a look at our Inventory (<?php echo $inventoryCount; ?> items)</h2>
        <p>
            <strong>We have a huge collection of merchandise in our inventory. Check it out.</strong>
        </p>
        <div class="hero-buttons">
            <a href="/Animeverse/index.php?page=collectibles" class="primary"><i class="fas fa-shopping-cart"></i> Inventory</a>
        </div>   
    </div>
    <div class="qr-links-section">
        <div class="qr-code">
            <img src="Untitled 1.png" alt="Scan QR Code" width="150">
            <p><i class="fas fa-mobile-alt"></i> Scan to visit our mobile site</p>
        </div>
        <div class="important-links">
            <h3><i class="fas fa-link"></i> Important Links:</h3>
            <div class="link-buttons">
                <a href="contact.php" class="link-button"><i class="fas fa-envelope"></i> Contact Us</a>
                <a href="/Animeverse/index.php?page=review" class="link-button"><i class="fas fa-star"></i> Reviews and Ratings</a>
                <a href="/Animeverse/index.php?page=polldebate" class="link-button"><i class="fas fa-poll"></i> Polls</a>
                <a href="/Animeverse/index.php?page=fanart" class="link-button"><i class="fas fa-palette"></i> Fan Arts</a>
                <a href="/Animeverse/index.php?page=spoiler" class="link-button"><i class="fas fa-eye-slash"></i> Spoiler Management</a>
                <a href="/Animeverse/index.php?page=collectibles" class="link-button"><i class="fas fa-box"></i> Inventory</a>
            </div>
        </div>
    </div>
</body>
</html>
