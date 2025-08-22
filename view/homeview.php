<!doctype html>
<html>
<head>
    <title>Home - AnimeVerse</title>
    <link rel="stylesheet" href="/Animeverse/css/home.css">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <span>A n i m e V e r s e</span>
        </div>
        <div class="navbar-right">
            <a href="/Animeverse/controller/profilecontroller.php">Profile Management</a>
            <a href="/Animeverse/controller/fanartcontroller.php">Fan Arts</a>
            <a href="contact.php">Contact Us</a>
            <?php if ($userEmail): ?>
                <a href="/Animeverse/controller/logoutcontroller.php">Logout</a>
            <?php else: ?>
                <a href="/Animeverse/controller/logincontroller.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to AnimeVerse</h1>
            <p>AnimeVerse is the perfect place for people who love anime!</p>
            <div class="hero-buttons">
                <a href="/Animeverse/controller/discussioncontroller.php" class="primary">Discussion Channel</a>
                <a href="/Animeverse/controller/polldebatecontroller.php" class="primary">Polls & Debates</a>
            </div>
        </div>
    </div>
    <div class="content-section">
        <h2>What is AnimeVerse?</h2>
        <p>
            <strong>**AnimeVerse** is a fan-driven platform where anime enthusiasts can explore reviews, share fan art, join discussions, and trade collectibles in a vibrant community.</strong>
        </p>
    </div>
    <div class="content-section">
        <h2>Why AnimeVerse?</h2>
        <ul class="benefits-list">
            <li><strong>Easy access to the anime world</strong></li>
            <li><strong>Active 24×7</strong></li>
            <li><strong>Free to use</strong></li>
            <li><strong>All data are secured</strong></li>
        </ul>
    </div>
    <div class="content-section">
        <h2>Take a look at our Inventory (<?php echo $inventoryCount; ?> items)</h2>
        <p>
            <strong>We have a huge collection of merchandise in our inventory. Check it out.</strong>
        </p>
        <div class="hero-buttons">
            <a href="inventory.php" class="primary">Inventory</a>
        </div>   
    </div>
    <div class="qr-links-section">
        <div class="qr-code">
            <img src="Untitled 1.png" alt="Scan QR Code" width="150">
            <p>Scan to visit our mobile site</p>
        </div>
        <div class="important-links">
            <h3>Important Links:</h3>
            <div class="link-buttons">
                <a href="contact.php" class="link-button">Contact Us</a>
                <a href="donor.php" class="link-button">Reviews and Ratings</a>
                <a href="recipient.php" class="link-button">Polls</a>
                <a href="/Animeverse/controller/fanartcontroller.php" class="link-button">Fan Arts</a>
                <a href="upcoming.php" class="link-button">Upcoming Events</a>
                <a href="/Animeverse/controller/inventorycontroller.php" class="link-button">Inventory</a>
            </div>
        </div>
    </div>
</body>
</html>
