<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($anime['title']) ?> - Reviews</title>
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
            <a href="?page=fanart"><i class="fas fa-palette"></i> Fan Art</a>
            <a href="?page=discussion"><i class="fas fa-comments"></i> Discussions</a>
            <a href="?page=collectibles"><i class="fas fa-shopping-cart"></i> Marketplace</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow"><i class="fas fa-users"></i> Social</a>
            <a href="?page=animereview" class="active"><i class="fas fa-star"></i> Reviews</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>
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
    <div class="single-anime-container">
        <nav class="breadcrumb">
            <a href="?page=animereview">← Back to Anime List</a>
        </nav>

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

        <!-- Anime Details -->
        <section class="anime-details">
            <div class="anime-header">
                <div class="anime-cover">
                    <?php if ($anime['cover_image'] && !empty(trim($anime['cover_image']))): ?>
                        <img src="<?= htmlspecialchars($anime['cover_image']) ?>" 
                             alt="<?= htmlspecialchars($anime['title']) ?>">
                        <div class="image-fallback" style="display: none;">
                            <div class="no-image">
                                <span>📺</span>
                                <div class="placeholder-text">Image Failed to Load</div>
                            </div>
                        </div>>
                    <?php else: ?>
                        <div class="no-image">
                            <span>📺</span>
                            <div class="placeholder-text">No Image URL Available</div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="anime-info">
                    <h1><?= htmlspecialchars($anime['title']) ?></h1>
                    <div class="anime-meta">
                        <span class="year"><?= htmlspecialchars($anime['release_year']) ?></span>
                        <span class="status <?= strtolower($anime['status']) ?>"><?= htmlspecialchars($anime['status']) ?></span>
                    </div>
                    <p class="description"><?= htmlspecialchars($anime['description']) ?></p>
                    
                    <!-- Rating Statistics -->
                    <?php if ($rating_stats && $rating_stats['total_reviews'] > 0): ?>
                        <div class="rating-stats">
                            <div class="overall-rating">
                                <span class="avg-rating"><?= number_format($rating_stats['avg_rating'], 1) ?></span>
                                <span class="out-of">/10</span>
                                <div class="stars">
                                    <?php 
                                    $star_rating = round($rating_stats['avg_rating'] / 2);
                                    for ($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <span class="star <?= $i <= $star_rating ? 'filled' : '' ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <span class="total-reviews"><?= $rating_stats['total_reviews'] ?> reviews</span>
                            </div>
                            <div class="rating-breakdown">
                                <?php for ($i = 10; $i >= 1; $i--): ?>
                                    <div class="rating-bar">
                                        <span class="rating-label"><?= $i ?></span>
                                        <div class="bar">
                                            <div class="fill" style="width: <?= $rating_stats['total_reviews'] > 0 ? ($rating_stats['rating_' . $i] / $rating_stats['total_reviews']) * 100 : 0 ?>%"></div>
                                        </div>
                                        <span class="rating-count"><?= $rating_stats['rating_' . $i] ?></span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="no-ratings">
                            <p>No ratings yet. Be the first to review!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Review Form -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <section class="review-form-section">
                <h3><?= $user_review ? 'Update Your Review' : 'Write a Review' ?></h3>
                <form class="review-form" action="?page=animereview&action=submit" method="POST">
                    <input type="hidden" name="anime_id" value="<?= $anime['anime_id'] ?>">
                    
                    <div class="form-group">
                        <label for="rating">Rating (1-10):</label>
                        <div class="rating-input">
                            <input type="number" id="rating" name="rating" min="1" max="10" 
                                   value="<?= $user_review ? htmlspecialchars($user_review['rating']) : '' ?>" required>
                            <span class="rating-help">1 = Terrible, 10 = Masterpiece</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comment">Your Review:</label>
                        <textarea id="comment" name="comment" rows="6" required placeholder="Share your thoughts about this anime..."><?= $user_review ? htmlspecialchars($user_review['comment']) : '' ?></textarea>
                    </div>

                    <div class="spoiler-section">
                        <div class="checkbox-group">
                            <input type="checkbox" id="is_spoiler" name="is_spoiler" 
                                   <?= $user_review && $user_review['is_spoiler'] ? 'checked' : '' ?>>
                            <label for="is_spoiler" style="color: #856404 !important; font-weight: 700 !important; font-size: 1.1rem !important; display: inline-block !important; text-shadow: 0 1px 2px rgba(255,255,255,0.8) !important; line-height: 1.4 !important;">⚠️ This review contains spoilers</label>
                        </div>
                        <div class="spoiler-warning" style="display: <?= $user_review && $user_review['is_spoiler'] ? 'block' : 'none' ?>;">
                            <label for="spoiler_warning" style="color: #856404 !important; font-weight: 600 !important; margin-bottom: 8px !important; display: block !important; font-size: 1rem !important; text-shadow: 0 1px 2px rgba(255,255,255,0.8) !important;">Spoiler Warning (required if checked):</label>
                            <input type="text" id="spoiler_warning" name="spoiler_warning" 
                                   placeholder="e.g., 'Contains ending spoilers'"
                                   value="<?= $user_review ? htmlspecialchars($user_review['spoiler_warning']) : '' ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <?= $user_review ? 'Update Review' : 'Submit Review' ?>
                        </button>
                        <?php if ($user_review): ?>
                            <a href="?page=animereview&action=delete&anime_id=<?= $anime['anime_id'] ?>" 
                               class="btn-danger" onclick="return confirm('Are you sure you want to delete your review?')">
                                Delete Review
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </section>
        <?php else: ?>
            <section class="login-prompt">
                <p>Please <a href="?page=login">login</a> to write a review.</p>
            </section>
        <?php endif; ?>

        <!-- Reviews List -->
        <section class="reviews-section">
            <h3>User Reviews (<?= count($reviews) ?>)</h3>
            
            <?php if (!empty($reviews)): ?>
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item <?= $review['is_spoiler'] ? 'has-spoiler' : '' ?>">
                            <div class="review-header">
                                <div class="user-info">
                                    <span class="username"><?= htmlspecialchars($review['username']) ?></span>
                                    <?php if ($review['level'] > 1): ?>
                                        <span class="user-level">Level <?= $review['level'] ?></span>
                                    <?php endif; ?>
                                    <?php if ($review['badge']): ?>
                                        <span class="user-badge"><?= htmlspecialchars($review['badge']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="review-meta">
                                    <span class="rating"><?= $review['rating'] ?>/10</span>
                                    <span class="review-date"><?= date('M j, Y', strtotime($review['reviewed_at'])) ?></span>
                                </div>
                            </div>

                            <?php if ($review['is_spoiler']): ?>
                                <div class="spoiler-alert">
                                    <span class="spoiler-icon">⚠️</span>
                                    <span class="spoiler-text">Spoiler Warning: <?= htmlspecialchars($review['spoiler_warning']) ?></span>
                                    <button class="reveal-spoiler" onclick="toggleSpoiler(this)">Show Review</button>
                                </div>
                                <div class="review-content spoiler-hidden">
                                    <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                </div>
                            <?php else: ?>
                                <div class="review-content">
                                    <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-content">No reviews yet. Be the first to review this anime!</p>
            <?php endif; ?>
        </section>
    </div>

    <script>
        // Handle spoiler checkbox
        document.getElementById('is_spoiler').addEventListener('change', function() {
            const spoilerWarning = document.querySelector('.spoiler-warning');
            const warningInput = document.getElementById('spoiler_warning');
            
            if (this.checked) {
                spoilerWarning.style.display = 'block';
                warningInput.required = true;
            } else {
                spoilerWarning.style.display = 'none';
                warningInput.required = false;
                warningInput.value = '';
            }
        });

        // Handle spoiler reveal
        function toggleSpoiler(button) {
            const reviewItem = button.closest('.review-item');
            const content = reviewItem.querySelector('.review-content');
            
            if (content.classList.contains('spoiler-hidden')) {
                content.classList.remove('spoiler-hidden');
                button.textContent = 'Hide Review';
            } else {
                content.classList.add('spoiler-hidden');
                button.textContent = 'Show Review';
            }
        }
    </script>
</body>
</html>