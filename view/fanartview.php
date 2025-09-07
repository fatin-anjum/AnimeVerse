<?php

$message = $message ?? '';
$fanarts = $fanarts ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fan Art - AnimeVerse</title>
    <link rel="stylesheet" href="css/fanart.css">
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
            <a href="?page=fanart" class="active"><i class="fas fa-palette"></i> Fan Art</a>
            <a href="?page=discussion"><i class="fas fa-comments"></i> Discussions</a>
            <a href="?page=collectibles"><i class="fas fa-shopping-cart"></i> Marketplace</a>
            <a href="?page=badge"><i class="fas fa-trophy"></i> Badges</a>
            <a href="?page=follow"><i class="fas fa-users"></i> Social</a>
            <a href="?page=animereview"><i class="fas fa-star"></i> Reviews</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="fanart-container">
        <!-- Page Header -->
        <header class="page-header">
            <h1><i class="fas fa-palette"></i> Fan Art Gallery</h1>
            <p>Share your creativity and discover amazing anime fan art</p>
        </header>

        <?php if($message): ?>
            <div class="alert <?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
                <i class="fas <?= strpos($message, 'success') !== false ? 'fa-check-circle' : 'fa-exclamation-circle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <!-- Upload Section -->
            <aside class="upload-sidebar">
                <section class="upload-section">
                    <h2><i class="fas fa-cloud-upload-alt"></i> Upload Fan Art</h2>
                    <form method="post" enctype="multipart/form-data" class="upload-form">
                        <div class="form-group">
                            <label for="title"><i class="fas fa-heading"></i> Title</label>
                            <input type="text" name="title" id="title" placeholder="Enter artwork title" required>
                        </div>
                        <div class="form-group">
                            <label for="description"><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="description" id="description" placeholder="Tell us about your artwork" rows="4"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fanart_file"><i class="fas fa-image"></i> Artwork File</label>
                            <input type="file" name="fanart_file" id="fanart_file" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Artwork
                        </button>
                    </form>
                </section>
            </aside>

            <!-- Main Gallery -->
            <main class="main-gallery">
                <section class="gallery-section">
                    <h2><i class="fas fa-images"></i> All Fan Art</h2>
                    <?php if (!empty($fanarts)): ?>
                        <div class="fanart-grid">
                            <?php foreach($fanarts as $art): ?>
                                <article class="fanart-card">
                                    <div class="card-header">
                                        <div class="artwork-info">
                                            <h3><?= htmlspecialchars($art['title']) ?></h3>
                                            <span class="content-id">ID: <?= $art['fanart_id'] ?></span>
                                        </div>
                                        <div class="artist-info">
                                            <span class="artist-name">
                                                <i class="fas fa-user"></i> <?= htmlspecialchars($art['username'] ?? 'Unknown') ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="artwork-display">
                                        <?php if (!empty($art['filename']) && file_exists(__DIR__ . '/../uploads/' . $art['filename'])): ?>
                                            <img src="uploads/<?= htmlspecialchars($art['filename']) ?>" 
                                                 alt="<?= htmlspecialchars($art['title']) ?>"
                                                 loading="lazy">
                                            <div class="image-fallback" style="display: none;">
                                                <div class="no-image">
                                                    <i class="fas fa-image"></i>
                                                    <p>Image Failed to Load</p>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="no-image">
                                                <i class="fas fa-image"></i>
                                                <p>Image not available</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($art['description'])): ?>
                                        <div class="artwork-description">
                                            <p><?= htmlspecialchars($art['description']) ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="card-actions">
                                        <form method="post" class="action-form">
                                            <input type="hidden" name="heart_fanart_id" value="<?= $art['fanart_id'] ?>">
                                            <button type="submit" class="btn btn-heart">
                                                <i class="fas fa-heart"></i> <?= $art['hearts'] ?? 0 ?>
                                            </button>
                                        </form>

                                        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] === $art['user_id']): ?>
                                            <form method="post" class="action-form">
                                                <input type="hidden" name="delete_fanart_id" value="<?= $art['fanart_id'] ?>">
                                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this artwork?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Comments Section -->
                                    <div class="comments-section">
                                        <h4><i class="fas fa-comments"></i> Comments</h4>
                                        <?php if(!empty($art['comments'])): ?>
                                            <div class="comments-list">
                                                <?php foreach($art['comments'] as $comment): ?>
                                                    <div class="comment-item">
                                                        <span class="comment-author">
                                                            <i class="fas fa-user-circle"></i> <?= htmlspecialchars($comment['username'] ?? 'Unknown') ?>
                                                        </span>
                                                        <p class="comment-text"><?= htmlspecialchars($comment['comment'] ?? '') ?></p>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="no-comments">No comments yet. Be the first to comment!</p>
                                        <?php endif; ?>

                                        <form method="post" class="comment-form">
                                            <input type="hidden" name="fanart_id" value="<?= $art['fanart_id'] ?>">
                                            <div class="comment-input">
                                                <input type="text" name="comment" placeholder="Add a comment..." required>
                                                <button type="submit" class="btn btn-comment">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-content">
                            <i class="fas fa-palette"></i>
                            <h3>No Fan Art Yet</h3>
                            <p>Be the first to share your amazing artwork with the community!</p>
                        </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>

    <script>
        // Enhanced image loading with event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.artwork-display img');
            
            images.forEach(function(img) {
                // Handle successful image loading
                img.addEventListener('load', function() {
                    this.style.display = 'block';
                    const fallback = this.parentNode.querySelector('.image-fallback');
                    if (fallback) {
                        fallback.style.display = 'none';
                    }
                });
                
                // Handle image loading errors
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                    const fallback = this.parentNode.querySelector('.image-fallback');
                    if (fallback) {
                        fallback.style.display = 'flex';
                    }
                });
                
                // Check if image is already loaded (cached)
                if (img.complete && img.naturalHeight !== 0) {
                    img.dispatchEvent(new Event('load'));
                }
            });
        });
    </script>
</body>
</html>
