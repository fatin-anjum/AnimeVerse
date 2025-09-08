<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Reviews - AnimeVerse</title>
    <link rel="stylesheet" href="css/animereview.css">
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
            <a href="?page=animereview" class="active"><i class="fas fa-star"></i> Reviews</a>
            <a href="?page=fanart"><i class="fas fa-palette"></i> Fan Art</a>
            <a href="?page=discussion"><i class="fas fa-comments"></i> Discussions</a>
            <a href="?page=collectibles"><i class="fas fa-shopping-cart"></i> Marketplace</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow"><i class="fas fa-users"></i> Social</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="anime-review-container">
        <header class="page-header">
            <h1><i class="fas fa-star"></i> Anime Reviews & Ratings</h1>
            <p>Discover, rate, and review your favorite anime series</p>
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
                <section class="anime-list-section">
                    <h2>All Anime</h2>
                    <div class="anime-grid">
                        <?php if (!empty($anime_list)): ?>
                            <?php foreach ($anime_list as $anime): ?>
                                <div class="anime-card">
                                    <div class="anime-cover">
                                        <?php if ($anime['cover_image'] && !empty(trim($anime['cover_image']))): ?>
                                            <img src="<?= htmlspecialchars($anime['cover_image']) ?>" 
                                                 alt="<?= htmlspecialchars($anime['title']) ?>"
                                                 loading="lazy">
                                            <div class="image-fallback" style="display: none;">
                                                <div class="no-image">
                                                    <span>📺</span>
                                                    <div class="placeholder-text">Image Failed to Load</div>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="no-image">
                                                <span>📺</span>
                                                <div class="placeholder-text">No Image URL Available</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="anime-info">
                                        <h3><a href="?page=animereview&action=view&id=<?= $anime['anime_id'] ?>"><?= htmlspecialchars($anime['title']) ?></a></h3>
                                        <p class="anime-year"><?= htmlspecialchars($anime['release_year']) ?> • <?= htmlspecialchars($anime['status']) ?></p>
                                        
                                        <div class="rating-info">
                                            <?php if ($anime['avg_rating']): ?>
                                                <div class="rating-display">
                                                    <span class="rating-number"><?= number_format($anime['avg_rating'], 1) ?></span>
                                                    <div class="stars">
                                                        <?php 
                                                        $rating = round($anime['avg_rating'] / 2); // Convert 10-point to 5-point scale
                                                        for ($i = 1; $i <= 5; $i++): 
                                                        ?>
                                                            <span class="star <?= $i <= $rating ? 'filled' : '' ?>">★</span>
                                                        <?php endfor; ?>
                                                    </div>
                                                    <span class="review-count">(<?= $anime['review_count'] ?> reviews)</span>
                                                </div>
                                            <?php else: ?>
                                                <p class="no-rating">No ratings yet</p>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <p class="anime-description"><?= htmlspecialchars(substr($anime['description'], 0, 100)) ?>...</p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-content">No anime found.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </main>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Recent Reviews -->
                <section class="recent-reviews">
                    <h3>Recent Reviews</h3>
                    <?php if (!empty($recent_reviews)): ?>
                        <div class="review-list">
                            <?php foreach ($recent_reviews as $review): ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <span class="username"><?= htmlspecialchars($review['username']) ?></span>
                                        <span class="rating"><?= $review['rating'] ?>/10</span>
                                    </div>
                                    <div class="anime-title">
                                        <a href="?page=animereview&action=view&id=<?= $review['anime_id'] ?>">
                                            <?= htmlspecialchars($review['anime_title']) ?>
                                        </a>
                                    </div>
                                    <div class="review-preview">
                                        <?= htmlspecialchars(substr($review['comment'], 0, 80)) ?>...
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-content">No recent reviews.</p>
                    <?php endif; ?>
                </section>

                <!-- Top Rated Anime -->
                <section class="top-rated">
                    <h3>Top Rated Anime</h3>
                    <?php if (!empty($top_rated)): ?>
                        <div class="top-rated-list">
                            <?php foreach ($top_rated as $index => $anime): ?>
                                <div class="top-rated-item">
                                    <span class="rank">#<?= $index + 1 ?></span>
                                    <div class="anime-info">
                                        <a href="?page=animereview&action=view&id=<?= $anime['anime_id'] ?>">
                                            <?= htmlspecialchars($anime['title']) ?>
                                        </a>
                                        <div class="rating">
                                            <span class="rating-number"><?= number_format($anime['avg_rating'], 1) ?></span>
                                            <span class="review-count">(<?= $anime['review_count'] ?>)</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-content">No ratings yet.</p>
                    <?php endif; ?>
                </section>
            </aside>
        </div>
    </div>

    <script>
        // Simple image error handling
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.anime-cover img');
            images.forEach(function(img) {
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                    const fallback = this.nextElementSibling;
                    if (fallback && fallback.classList.contains('image-fallback')) {
                        fallback.style.display = 'flex';
                    }
                });
                
                img.addEventListener('load', function() {
                    this.style.display = 'block';
                    const fallback = this.nextElementSibling;
                    if (fallback && fallback.classList.contains('image-fallback')) {
                        fallback.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>