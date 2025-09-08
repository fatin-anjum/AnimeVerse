<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['username']) ?> - Profile | AnimeVerse</title>
    <link rel="stylesheet" href="css/userprofile.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <span>AnimeVerse</span>
        </div>
        <div class="navbar-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?page=home">Home</a>
                <a href="?page=myprofile">My Profile</a>
                <a href="?page=badge">Badges</a>
                <a href="?page=logout">Logout</a>
            <?php else: ?>
                <a href="?page=login">Login</a>
                <a href="?page=register">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="profile-container">
        <header class="profile-header">
            <div class="profile-cover">
                <div class="profile-info">
                    <div class="profile-picture">
                        <?php if ($user['profile_picture']): ?>
                            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                                 alt="<?= htmlspecialchars($user['username']) ?>">
                        <?php else: ?>
                            <div class="default-avatar">👤</div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-details">
                        <h1><?= htmlspecialchars($user['username']) ?></h1>
                        <div class="profile-meta">
                            <span class="user-id">ID: <?= $user['user_id'] ?></span>
                            <span class="user-level">Level <?= $user['level'] ?></span>
                            <?php if ($user['badge']): ?>
                                <span class="primary-badge">🏆 <?= htmlspecialchars($user['badge']) ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($user['email']): ?>
                            <div class="profile-email">📧 <?= htmlspecialchars($user['email']) ?></div>
                        <?php endif; ?>
                        <?php if ($user['bio']): ?>
                            <div class="profile-bio"><?= htmlspecialchars($user['bio']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="profile-content">
            <!-- Follow Stats -->
            <section class="follow-stats">
                <div class="stats-cards">
                    <div class="stat-card">
                        <div class="stat-number"><?= $follow_stats['followers'] ?></div>
                        <div class="stat-label">Followers</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $follow_stats['following'] ?></div>
                        <div class="stat-label">Following</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?= $follow_stats['mutual_follows'] ?></div>
                        <div class="stat-label">Mutual</div>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user['user_id']): ?>
                    <div class="follow-actions">
                        <?php if ($is_following): ?>
                            <a href="?page=follow&action=unfollow&user_id=<?= $user['user_id'] ?>&redirect=<?= urlencode("?page=follow&action=profile&id=" . $user['user_id']) ?>" 
                               class="btn btn-secondary">Unfollow</a>
                        <?php else: ?>
                            <a href="?page=follow&action=follow&user_id=<?= $user['user_id'] ?>&redirect=<?= urlencode("?page=follow&action=profile&id=" . $user['user_id']) ?>" 
                               class="btn btn-primary">Follow</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>

            <div class="content-grid">
                <!-- User Badges -->
                <section class="user-badges">
                    <h2>🏆 Badges</h2>
                    <?php if (!empty($user_badges)): ?>
                        <div class="badges-grid">
                            <?php foreach ($user_badges as $badge): ?>
                                <div class="badge-item">
                                    <div class="badge-icon"><?php 
                                        $icon = $badge['badge_icon'] ?? '🏅';
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
                                            $icon = $fallback_icons[$badge_name] ?? '🏅';
                                        }
                                        echo $icon;
                                    ?></div>
                                    <div class="badge-info">
                                        <h4><?= htmlspecialchars($badge['badge_name']) ?></h4>
                                        <p><?= htmlspecialchars($badge['badge_description']) ?></p>
                                        <span class="badge-date">Earned <?= date('M j, Y', strtotime($badge['earned_at'])) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-badges">
                            <p>No badges earned yet.</p>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Recent Activity -->
                <section class="user-activity">
                    <h2>📊 Recent Activity</h2>
                    <?php if (!empty($user_activity)): ?>
                        <div class="activity-list">
                            <?php foreach ($user_activity as $activity): ?>
                                <div class="activity-item <?= $activity['type'] ?>">
                                    <div class="activity-meta">
                                        <span class="activity-type"><?= ucfirst($activity['type']) ?></span>
                                        <span class="activity-date"><?= date('M j, Y', strtotime($activity['created_at'])) ?></span>
                                    </div>
                                    <div class="activity-content">
                                        <h4>
                                            <?php
                                            $link = '';
                                            switch($activity['type']) {
                                                case 'fanart':
                                                    $link = "?page=fanart&action=view&id=" . $activity['content_id'];
                                                    break;
                                                case 'review':
                                                    $link = "?page=animereview&action=view&id=" . $activity['content_id'];
                                                    break;
                                                case 'discussion':
                                                    $link = "?page=discussion&action=view&id=" . $activity['content_id'];
                                                    break;
                                            }
                                            ?>
                                            <a href="<?= $link ?>"><?= htmlspecialchars($activity['title']) ?></a>
                                        </h4>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-activity">
                            <p>No recent activity.</p>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>
</body>
</html>