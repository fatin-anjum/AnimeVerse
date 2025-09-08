<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Followers - AnimeVerse</title>
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
            <h1><i class="fas fa-user-friends"></i> Your Followers</h1>
            <p>People who are following you</p>
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

        <!-- Followers List -->
        <section class="followers-section">
            <?php if (!empty($followers)): ?>
                <div class="followers-grid">
                    <?php foreach ($followers as $follower): ?>
                        <div class="user-card">
                            <div class="user-avatar-section">
                                <?php if ($follower['profile_picture']): ?>
                                    <img src="<?= htmlspecialchars($follower['profile_picture']) ?>" 
                                         alt="<?= htmlspecialchars($follower['username']) ?>" class="user-avatar">
                                <?php else: ?>
                                    <div class="user-avatar default">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="user-info">
                                <h3 class="username">
                                    <a href="?page=follow&action=profile&id=<?= $follower['user_id'] ?>">
                                        <?= htmlspecialchars($follower['username']) ?>
                                    </a>
                                </h3>
                                
                                <?php if (isset($follower['bio']) && $follower['bio']): ?>
                                    <p class="user-bio"><?= htmlspecialchars(substr($follower['bio'], 0, 100)) ?><?= strlen($follower['bio']) > 100 ? '...' : '' ?></p>
                                <?php endif; ?>
                                
                                <div class="user-meta">
                                    <span class="follow-date">
                                        <i class="fas fa-calendar"></i>
                                        Followed since <?= date('M Y', strtotime($follower['followed_at'])) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="user-actions">
                                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $follower['user_id']): ?>
                                    <?php if ($follower['is_following'] ?? false): ?>
                                        <button class="btn btn-secondary follow-btn" 
                                                onclick="followUser(<?= $follower['user_id'] ?>, 'unfollow', this)">
                                            <i class="fas fa-user-minus"></i> Unfollow
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-primary follow-btn" 
                                                onclick="followUser(<?= $follower['user_id'] ?>, 'follow', this)">
                                            <i class="fas fa-user-plus"></i> Follow Back
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                
                                <a href="?page=follow&action=profile&id=<?= $follower['user_id'] ?>" 
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
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <h3>No Followers Yet</h3>
                    <p>You don't have any followers yet. Start engaging with the community to attract followers!</p>
                    <div class="suggestion-links">
                        <a href="?page=fanart" class="btn btn-primary">
                            <i class="fas fa-palette"></i> Share Fanart
                        </a>
                        <a href="?page=discussion" class="btn btn-outline">
                            <i class="fas fa-comments"></i> Join Discussions
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <script>
        function followUser(userId, action, button) {
            // Disable button during request
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
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
                        button.innerHTML = '<i class="fas fa-user-minus"></i> Unfollow';
                        button.className = 'btn btn-secondary follow-btn';
                        button.onclick = () => followUser(userId, 'unfollow', button);
                    } else {
                        button.innerHTML = '<i class="fas fa-user-plus"></i> Follow Back';
                        button.className = 'btn btn-primary follow-btn';
                        button.onclick = () => followUser(userId, 'follow', button);
                    }
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error occurred');
                button.innerHTML = originalText;
            })
            .finally(() => {
                button.disabled = false;
            });
        }
    </script>
</body>
</html>