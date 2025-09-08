<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= htmlspecialchars($user['username'] ?? 'User') ?> - My Profile | AnimeVerse</title>
    <link rel="stylesheet" href="css/myprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <span>AnimeVerse</span>
        </div>
        <div class="navbar-right">
            <a href="?page=home"><i class="fas fa-home"></i> Home</a>
            <a href="?page=myprofile" class="active"><i class="fas fa-user"></i> My Profile</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow"><i class="fas fa-users"></i> Follow</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="profile-container">
        <!-- Profile Header -->
        <header class="profile-header">
            <div class="profile-cover">
                <div class="profile-info">
                    <div class="profile-picture">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                                 alt="<?= htmlspecialchars($user['username'] ?? 'User') ?>">
                        <?php else: ?>
                            <div class="default-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-details">
                        <h1><?= htmlspecialchars($user['username'] ?? 'Unknown User') ?></h1>
                        <p class="user-title"><?= htmlspecialchars($user['badge'] ?? 'New Member') ?></p>
                        <div class="profile-stats">
                            <div class="stat-item">
                                <span class="stat-number"><?= $followers ?></span>
                                <span class="stat-label">Followers</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?= $following ?></span>
                                <span class="stat-label">Following</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number"><?= count($user_badges) ?></span>
                                <span class="stat-label">Badges</span>
                            </div>
                            <?php if (!empty($level_progress)): ?>
                            <div class="stat-item">
                                <span class="stat-number"><?= $level_progress['current_level'] ?? 1 ?></span>
                                <span class="stat-label">Level</span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Level Progress -->
        <?php if (!empty($level_progress)): ?>
        <section class="level-progress">
            <div class="level-info">
                <h3><i class="fas fa-chart-line"></i> Level Progress</h3>
                <div class="progress-details">
                    <span class="current-level">Level <?= $level_progress['current_level'] ?? 1 ?></span>
                    <span class="xp-info"><?= $level_progress['total_experience'] ?? 0 ?> / <?= $level_progress['next_level_exp'] ?? 100 ?> XP</span>
                </div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $level_progress['progress_percentage'] ?? 0 ?>%"></div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Badges Section -->
        <?php if (!empty($user_badges)): ?>
        <section class="badges-section">
            <h3><i class="fas fa-trophy"></i> Recent Badges</h3>
            <div class="badges-grid">
                <?php foreach (array_slice($user_badges, 0, 6) as $badge): ?>
                    <div class="badge-item">
                        <div class="badge-icon"><?php 
                            $icon = $badge['badge_icon'] ?? '🏆';
                            // Fallback for emoji display issues
                            if (empty($icon) || $icon === '????' || mb_strlen($icon) > 10) {
                                $fallback_icons = [
                                    'First Reviewer' => '📝',
                                    'Review Enthusiast' => '✍️', 
                                    'Review Master' => '🏆',
                                    'First Artist' => '🎨',
                                    'Art Enthusiast' => '🖼️',
                                    'Conversation Starter' => '💬',
                                    'Discussion Leader' => '🗣️',
                                    'Popular User' => '⭐',
                                    'Community Star' => '🌟',
                                    'Active Member' => '🔥',
                                    'Veteran Member' => '🎖️',
                                    'Level 5 Achieved' => '🏅',
                                    'Level 10 Achieved' => '👑'
                                ];
                                $badge_name = $badge['badge_name'] ?? 'Achievement';
                                $icon = $fallback_icons[$badge_name] ?? '🏆';
                            }
                            echo $icon;
                        ?></div>
                        <div class="badge-info">
                            <h4><?= htmlspecialchars($badge['badge_name'] ?? 'Achievement') ?></h4>
                            <p><?= htmlspecialchars($badge['badge_description'] ?? 'Badge earned') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($user_badges) > 6): ?>
                <div class="view-all">
                    <a href="?page=badge" class="btn btn-outline">View All Badges</a>
                </div>
            <?php endif; ?>
        </section>
        <?php endif; ?>

        <!-- Content Tabs -->
        <section class="content-section">
            <div class="tab-navigation">
                <a href="?page=myprofile&tab=overview" 
                   class="tab-link <?= ($tab ?? 'overview') == 'overview' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i> Overview
                </a>
                <a href="?page=myprofile&tab=collectibles" 
                   class="tab-link <?= ($tab ?? '') == 'collectibles' ? 'active' : '' ?>">
                    <i class="fas fa-gem"></i> Collectibles
                </a>
                <a href="?page=myprofile&tab=sold" 
                   class="tab-link <?= ($tab ?? '') == 'sold' ? 'active' : '' ?>">
                    <i class="fas fa-shopping-cart"></i> Sold
                </a>
                <a href="?page=myprofile&tab=fanart" 
                   class="tab-link <?= ($tab ?? '') == 'fanart' ? 'active' : '' ?>">
                    <i class="fas fa-palette"></i> Fan Art
                </a>
            </div>

            <div class="tab-content">
                <?php if (($tab ?? 'overview') == 'overview'): ?>
                    <div class="overview-content">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-gem"></i></div>
                                <div class="stat-info">
                                    <h4>Collectibles</h4>
                                    <span class="stat-number"><?= count($collectibles) ?></span>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                                <div class="stat-info">
                                    <h4>Items Sold</h4>
                                    <span class="stat-number"><?= count($soldCollectibles) ?></span>
                                </div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-palette"></i></div>
                                <div class="stat-info">
                                    <h4>Fan Art</h4>
                                    <span class="stat-number"><?= count($fanart) ?></span>
                                </div>
                            </div>
                            <?php if (!empty($user_stats)): ?>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fas fa-star"></i></div>
                                <div class="stat-info">
                                    <h4>Total XP</h4>
                                    <span class="stat-number"><?= $user_stats['total_xp'] ?? 0 ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($user['bio'])): ?>
                        <div class="bio-section">
                            <h3><i class="fas fa-info-circle"></i> About</h3>
                            <p><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                
                <?php elseif ($tab == 'collectibles'): ?>
                    <div class="items-grid">
                        <?php if (!empty($collectibles)): ?>
                            <?php foreach ($collectibles as $item): ?>
                                <div class="item-card">
                                    <div class="item-image">
                                        <img src="<?= htmlspecialchars($item['image_url'] ?? 'images/placeholder.png') ?>" 
                                             alt="<?= htmlspecialchars($item['title'] ?? 'Item') ?>">
                                        <div class="item-status <?= $item['is_sold'] ? 'sold' : 'available' ?>">
                                            <?= $item['is_sold'] ? 'Sold' : 'Available' ?>
                                        </div>
                                    </div>
                                    <div class="item-info">
                                        <h4><?= htmlspecialchars($item['title'] ?? 'Item') ?></h4>
                                        <p class="item-price">$<?= number_format($item['price'] ?? 0, 2) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-gem"></i>
                                <h3>No Collectibles Yet</h3>
                                <p>Start collecting your favorite anime items!</p>
                                <a href="?page=collectibles" class="btn btn-primary">Browse Collectibles</a>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <?php elseif ($tab == 'sold'): ?>
                    <div class="items-grid">
                        <?php if (!empty($soldCollectibles)): ?>
                            <?php foreach ($soldCollectibles as $item): ?>
                                <div class="item-card sold">
                                    <div class="item-image">
                                        <img src="<?= htmlspecialchars($item['image_url'] ?? 'images/placeholder.png') ?>" 
                                             alt="<?= htmlspecialchars($item['title'] ?? 'Item') ?>">
                                        <div class="item-status sold">Sold</div>
                                    </div>
                                    <div class="item-info">
                                        <h4><?= htmlspecialchars($item['title'] ?? 'Item') ?></h4>
                                        <p class="item-price">Sold for $<?= number_format($item['price'] ?? 0, 2) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-shopping-cart"></i>
                                <h3>No Items Sold</h3>
                                <p>You haven't sold any items yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <?php elseif ($tab == 'fanart'): ?>
                    <div class="fanart-grid">
                        <?php if (!empty($fanart)): ?>
                            <?php foreach ($fanart as $art): ?>
                                <div class="fanart-card">
                                    <div class="fanart-image">
                                        <img src="<?= htmlspecialchars($art['image_url'] ?? 'images/placeholder.png') ?>" 
                                             alt="<?= htmlspecialchars($art['title'] ?? 'Fan Art') ?>">
                                    </div>
                                    <div class="fanart-info">
                                        <h4><?= htmlspecialchars($art['title'] ?? 'Fan Art') ?></h4>
                                        <p><?= htmlspecialchars($art['description'] ?? 'No description') ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-palette"></i>
                                <h3>No Fan Art Yet</h3>
                                <p>Share your creative anime artwork with the community!</p>
                                <a href="?page=fanart" class="btn btn-primary">Create Fan Art</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <script>
        // Add smooth scrolling and interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bar
            const progressBar = document.querySelector('.progress-fill');
            if (progressBar) {
                const width = progressBar.style.width;
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.width = width;
                }, 500);
            }
            
            // Add hover effects to cards
            const cards = document.querySelectorAll('.item-card, .fanart-card, .badge-item');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>
