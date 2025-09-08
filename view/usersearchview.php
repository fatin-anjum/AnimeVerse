<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Search - AnimeVerse</title>
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
            <h1><i class="fas fa-search"></i> Search Users</h1>
            <p>Find and connect with fellow anime enthusiasts</p>
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

        <!-- Search Form -->
        <section class="search-section">
            <form action="?page=follow&action=search" method="GET" class="search-form">
                <input type="hidden" name="page" value="follow">
                <input type="hidden" name="action" value="search">
                <div class="search-input-group">
                    <input type="text" name="q" placeholder="Search for users..." 
                           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" required>
                    <button type="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </form>
        </section>

        <!-- Search Results -->
        <section class="search-results">
            <?php if (isset($_GET['q']) && !empty($_GET['q'])): ?>
                <h2>Search Results for "<?= htmlspecialchars($_GET['q']) ?>"</h2>
                
                <?php if (!empty($search_results)): ?>
                    <div class="search-results-grid">
                        <?php foreach ($search_results as $user): ?>
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
                                        <?php if (isset($user['level']) && $user['level'] > 1): ?>
                                            <span class="user-level">
                                                <i class="fas fa-star"></i>
                                                Level <?= $user['level'] ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($user['follower_count'])): ?>
                                            <span class="follower-count">
                                                <i class="fas fa-users"></i>
                                                <?= $user['follower_count'] ?> followers
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (isset($user['badge']) && $user['badge']): ?>
                                        <div class="user-badge">
                                            <i class="fas fa-trophy"></i>
                                            <?= htmlspecialchars($user['badge']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="user-actions">
                                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user['user_id']): ?>
                                        <?php if ($user['is_following'] ?? false): ?>
                                            <button class="btn btn-secondary follow-btn" 
                                                    onclick="followUser(<?= $user['user_id'] ?>, 'unfollow', this)">
                                                <i class="fas fa-user-minus"></i> Unfollow
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-primary follow-btn" 
                                                    onclick="followUser(<?= $user['user_id'] ?>, 'follow', this)">
                                                <i class="fas fa-user-plus"></i> Follow
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
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
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No Users Found</h3>
                        <p>No users match your search criteria "<?= htmlspecialchars($_GET['q']) ?>"</p>
                        <div class="suggestion-text">
                            <p>Try searching with different keywords or check the spelling.</p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="search-help">
                    <div class="search-help-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3>Search Tips</h3>
                    <ul>
                        <li>Enter a username to find specific users</li>
                        <li>Search results will show matching usernames</li>
                        <li>You can follow/unfollow users directly from search results</li>
                        <li>Click on usernames to view detailed profiles</li>
                    </ul>
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
                        button.innerHTML = '<i class="fas fa-user-plus"></i> Follow';
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