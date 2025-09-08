<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Following - AnimeVerse</title>
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
            <a href="?page=home"><i class="fas fa-home"></i> Home</a>
            <a href="?page=myprofile"><i class="fas fa-user"></i> My Profile</a>
            <a href="?page=fanart"><i class="fas fa-palette"></i> Fan Art</a>
            <a href="?page=discussion"><i class="fas fa-comments"></i> Discussions</a>
            <a href="?page=collectibles"><i class="fas fa-shopping-cart"></i> Marketplace</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow" class="active"><i class="fas fa-users"></i> Social</a>
            <a href="?page=animereview"><i class="fas fa-star"></i> Reviews</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="follow-container">
        <!-- Page Header -->
        <header class="page-header">
            <h1><i class="fas fa-heart"></i> People You Follow</h1>
            <p>Users you are currently following</p>
        </header>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Back Navigation -->
        <div class="back-navigation">
            <a href="?page=follow" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Back to Follow Community
            </a>
        </div>

        <!-- Following List -->
        <section class="following-section">
            <?php if (!empty($following)): ?>
                <div class="following-grid">
                    <?php foreach ($following as $user): ?>
                        <div class="user-card">
                            <div class="user-avatar-section">
                                <?php if ($user['profile_picture']): ?>
                                    <img src="<?= htmlspecialchars($user['profile_picture']) ?>" 
                                         alt="<?= htmlspecialchars($user['username']) ?>" class="user-avatar">
                                <?php else: ?>
                                    <div class="user-avatar default">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="user-info">
                                <h3 class="username">
                                    <a href="?page=follow&action=profile&id=<?= $user['user_id'] ?>">
                                        <?= htmlspecialchars($user['username']) ?>
                                    </a>
                                </h3>
                                
                                <?php if (isset($user['bio']) && $user['bio']): ?>
                                    <p class="user-bio"><?= htmlspecialchars(substr($user['bio'], 0, 100)) ?><?= strlen($user['bio']) > 100 ? '...' : '' ?></p>
                                <?php endif; ?>
                                
                                <div class="user-meta">
                                    <span class="follow-date">
                                        <i class="fas fa-calendar"></i>
                                        Following since <?= date('M Y', strtotime($user['followed_at'])) ?>
                                    </span>
                                    
                                    <?php if (isset($user['level']) && $user['level'] > 1): ?>
                                        <span class="user-level">
                                            <i class="fas fa-star"></i>
                                            Level <?= $user['level'] ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if (isset($user['recent_activity'])): ?>
                                    <div class="user-activity">
                                        <small class="activity-text">
                                            <i class="fas fa-clock"></i>
                                            Last active: <?= timeAgo($user['last_activity'] ?? $user['followed_at']) ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="user-actions">
                                <button class="btn btn-danger unfollow-btn" 
                                        onclick="unfollowUser(<?= $user['user_id'] ?>, this)">
                                    <i class="fas fa-user-minus"></i> Unfollow
                                </button>
                                
                                <a href="?page=follow&action=profile&id=<?= $user['user_id'] ?>" 
                                   class="btn btn-outline">
                                    <i class="fas fa-eye"></i> View Profile
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-content">
                    <div class="no-content-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Not Following Anyone Yet</h3>
                    <p>You're not following anyone yet. Discover interesting users to follow!</p>
                    <div class="suggestion-links">
                        <a href="?page=follow" class="btn btn-primary">
                            <i class="fas fa-search"></i> Find Users to Follow
                        </a>
                        <a href="?page=follow&action=search" class="btn btn-outline">
                            <i class="fas fa-users"></i> Browse Community
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <script>
        function unfollowUser(userId, button) {
            // Show confirmation dialog
            if (!confirm('Are you sure you want to unfollow this user?')) {
                return;
            }
            
            // Disable button during request
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Unfollowing...';
            
            fetch('?page=follow&action=toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    action: 'unfollow'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the user card from the view
                    button.closest('.user-card').style.transition = 'opacity 0.3s ease';
                    button.closest('.user-card').style.opacity = '0.5';
                    setTimeout(() => {
                        button.closest('.user-card').remove();
                        
                        // Check if no more users, show no content message
                        const grid = document.querySelector('.following-grid');
                        if (grid && grid.children.length === 0) {
                            location.reload(); // Reload to show the "no content" state
                        }
                    }, 300);
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error occurred');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }
        
        // Helper function for time ago (same as in followview.php)
        function timeAgo(datetime) {
            const time = Math.floor(Date.now() / 1000) - Math.floor(new Date(datetime).getTime() / 1000);
            
            if (time < 60) return 'just now';
            if (time < 3600) return Math.floor(time/60) + 'm ago';
            if (time < 86400) return Math.floor(time/3600) + 'h ago';
            if (time < 2592000) return Math.floor(time/86400) + 'd ago';
            
            return new Date(datetime).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
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