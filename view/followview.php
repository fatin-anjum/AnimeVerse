<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Follow Community - AnimeVerse</title>
    <link rel="stylesheet" href="css/follow.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-left">
            <span>AnimeVerse</span>
        </div>
        <div class="navbar-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?page=home"><i class="fas fa-home"></i> Home</a>
                <a href="?page=myprofile"><i class="fas fa-user"></i> My Profile</a>
                <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
                <a href="?page=follow" class="active"><i class="fas fa-users"></i> Follow</a>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="?page=register"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="follow-container">
        <header class="page-header">
            <h1><i class="fas fa-users"></i> Follow Community</h1>
            <p>Connect with fellow anime enthusiasts and stay updated with their activities</p>
        </header>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <!-- Main Content -->
            <main class="main-content">
                <!-- Search Section -->
                <section class="search-section">
                    <h2>Find Users</h2>
                    <form action="?page=follow&action=search" method="GET" class="search-form">
                        <input type="hidden" name="page" value="follow">
                        <input type="hidden" name="action" value="search">
                        <div class="search-input-group">
                            <input type="text" name="q" placeholder="Search for users..." 
                                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>
                            <button type="submit">🔍 Search</button>
                        </div>
                    </form>
                </section>

                <!-- Follow Stats -->
                <section class="follow-stats">
                    <h2>Your Stats</h2>
                    <div class="stats-cards">
                        <div class="stat-card">
                            <div class="stat-number"><?= $follow_stats['followers'] ?></div>
                            <div class="stat-label">
                                <a href="?page=follow&action=followers">Followers</a>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $follow_stats['following'] ?></div>
                            <div class="stat-label">
                                <a href="?page=follow&action=following">Following</a>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number"><?= $follow_stats['mutual_follows'] ?></div>
                            <div class="stat-label">
                                <a href="?page=follow&action=mutual">Mutual</a>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Recent Activity Feed -->
                <section class="activity-feed">
                    <h2>Activity from People You Follow</h2>
                    
                    <?php if (!empty($recent_activity)): ?>
                        <div class="activity-list">
                            <?php foreach ($recent_activity as $activity): ?>
                                <div class="activity-item <?= $activity['type'] ?>">
                                    <div class="activity-header">
                                        <div class="user-info">
                                            <?php if ($activity['profile_picture']): ?>
                                                <img src="<?= htmlspecialchars($activity['profile_picture']) ?>" 
                                                     alt="<?= htmlspecialchars($activity['username']) ?>" class="user-avatar">
                                            <?php else: ?>
                                                <div class="user-avatar default">👤</div>
                                            <?php endif; ?>
                                            <span class="username">
                                                <a href="?page=follow&action=profile&id=<?= $activity['user_id'] ?>">
                                                    <?= htmlspecialchars($activity['username']) ?>
                                                </a>
                                            </span>
                                        </div>
                                        <div class="activity-meta">
                                            <span class="activity-type"><?= ucfirst($activity['type']) ?></span>
                                            <span class="activity-date"><?= timeAgo($activity['created_at']) ?></span>
                                        </div>
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
                                                case 'debate':
                                                    $link = "?page=polldebate&action=view&id=" . $activity['content_id'];
                                                    break;
                                            }
                                            ?>
                                            <a href="<?= $link ?>"><?= htmlspecialchars($activity['title']) ?></a>
                                        </h4>
                                        <?php if ($activity['anime_title']): ?>
                                            <p class="anime-title">for <?= htmlspecialchars($activity['anime_title']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-activity">
                            <p>No recent activity from people you follow.</p>
                            <p>Try following some users to see their content here!</p>
                        </div>
                    <?php endif; ?>
                </section>
            </main>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Suggested Users -->
                <section class="suggested-users">
                    <h3>Suggested for You</h3>
                    <?php if (!empty($suggested_users)): ?>
                        <div class="user-list">
                            <?php foreach ($suggested_users as $user): ?>
                                <div class="user-item">
                                    <div class="user-info">
                                        <?php if ($user['profile_picture']): ?>
                                            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                                                 alt="<?= htmlspecialchars($user['username']) ?>" class="user-avatar">
                                        <?php else: ?>
                                            <div class="user-avatar default">👤</div>
                                        <?php endif; ?>
                                        <div class="user-details">
                                            <div class="username">
                                                <a href="?page=follow&action=profile&id=<?= $user['user_id'] ?>">
                                                    <?= htmlspecialchars($user['username']) ?>
                                                </a>
                                            </div>
                                            <?php if ($user['level'] > 1): ?>
                                                <div class="user-level">Level <?= $user['level'] ?></div>
                                            <?php endif; ?>
                                            <div class="user-activity">
                                                <?= $user['fanart_count'] ?> fanart, 
                                                <?= $user['review_count'] ?> reviews, 
                                                <?= $user['discussion_count'] ?> discussions
                                            </div>
                                        </div>
                                    </div>
                                    <div class="user-actions">
                                        <button class="follow-btn btn-sm" 
                                                onclick="followUser(<?= $user['user_id'] ?>, 'follow', this)">
                                            Follow
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-content">No suggestions available.</p>
                    <?php endif; ?>
                </section>

                <!-- Popular Users -->
                <section class="popular-users">
                    <h3>Popular Users</h3>
                    <?php if (!empty($popular_users)): ?>
                        <div class="user-list">
                            <?php foreach ($popular_users as $index => $user): ?>
                                <div class="user-item">
                                    <div class="user-rank">#<?= $index + 1 ?></div>
                                    <div class="user-info">
                                        <?php if ($user['profile_picture']): ?>
                                            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                                                 alt="<?= htmlspecialchars($user['username']) ?>" class="user-avatar">
                                        <?php else: ?>
                                            <div class="user-avatar default">👤</div>
                                        <?php endif; ?>
                                        <div class="user-details">
                                            <div class="username">
                                                <a href="?page=follow&action=profile&id=<?= $user['user_id'] ?>">
                                                    <?= htmlspecialchars($user['username']) ?>
                                                </a>
                                            </div>
                                            <div class="follower-count"><?= $user['follower_count'] ?> followers</div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-content">No popular users yet.</p>
                    <?php endif; ?>
                </section>
            </aside>
        </div>
    </div>

    <script>
        function followUser(userId, action, button) {
            fetch('?page=follow&action=toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    action: action
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.action === 'follow') {
                        button.textContent = 'Unfollow';
                        button.className = 'follow-btn btn-sm following';
                        button.onclick = () => followUser(userId, 'unfollow', button);
                    } else {
                        button.textContent = 'Follow';
                        button.className = 'follow-btn btn-sm';
                        button.onclick = () => followUser(userId, 'follow', button);
                    }
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error occurred');
            });
        }
    </script>

    <?php
    // Helper function for time ago
    function timeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . 'm ago';
        if ($time < 86400) return floor($time/3600) . 'h ago';
        if ($time < 2592000) return floor($time/86400) . 'd ago';
        
        return date('M j, Y', strtotime($datetime));
    }
    ?>
</body>
</html>