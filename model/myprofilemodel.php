<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($user['username']) ?> - Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <img src="<?= $user['profile_picture'] ?: 'images/default.png' ?>" class="profile-pic">
        <div class="profile-info">
            <h2><?= htmlspecialchars($user['username']) ?></h2>
            <p>Badge: <?= htmlspecialchars($user['badge'] ?: 'None') ?></p>
            <p>Followers: <?= $followers ?> | Following: <?= $following ?></p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="profile-tabs">
        <a href="?tab=collectibles" class="tab-link <?= $tab=='collectibles' ? 'active' : '' ?>">Collectibles</a>
        <a href="?tab=sold" class="tab-link <?= $tab=='sold' ? 'active' : '' ?>">Sold</a>
        <a href="?tab=fanart" class="tab-link <?= $tab=='fanart' ? 'active' : '' ?>">Fanart</a>
    </div>

    <!-- Tab Content -->
    <div class="profile-content">
        <?php if ($tab == 'collectibles'): ?>
            <?php foreach ($collectibles as $item): ?>
                <div class="item">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                    <p><?= htmlspecialchars($item['title']) ?></p>
                    <p>Price: $<?= $item['price'] ?></p>
                    <p>Status: <?= $item['is_sold'] ? 'Sold' : 'Available' ?></p>
                </div>
            <?php endforeach; ?>
        <?php elseif ($tab == 'sold'): ?>
            <?php foreach ($soldCollectibles as $item): ?>
                <div class="item">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                    <p><?= htmlspecialchars($item['title']) ?></p>
                    <p>Sold for: $<?= $item['price'] ?></p>
                </div>
            <?php endforeach; ?>
        <?php elseif ($tab == 'fanart'): ?>
            <?php foreach ($fanart as $art): ?>
                <div class="item">
                    <img src="<?= htmlspecialchars($art['image_url']) ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                    <p><?= htmlspecialchars($art['title']) ?></p>
                    <p><?= htmlspecialchars($art['description']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
