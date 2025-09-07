<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Channel - AnimeVerse</title>
    <link rel="stylesheet" href="css/discussion.css">
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
            <a href="?page=discussion" class="active"><i class="fas fa-comments"></i> Discussions</a>
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

    <div class="discussion-container">
        <!-- Page Header -->
        <header class="page-header">
            <h1><i class="fas fa-comments"></i> Discussion Channel</h1>
            <p>Join the conversation and discuss your favorite anime topics</p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="content-layout">
            <!-- Genre Selection Sidebar -->
            <aside class="genre-sidebar">
                <section class="genre-section">
                    <h2><i class="fas fa-tags"></i> Select Genre</h2>
                    <form method="get" class="genre-form">
                        <input type="hidden" name="page" value="discussion">
                        <div class="form-group">
                            <select name="genre_id" id="genre" onchange="this.form.submit()" class="genre-select">
                                <option value="0">--Choose a genre--</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= $genre['genre_id'] ?>"
                                        <?= ($selected_genre == $genre['genre_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($genre['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </section>

                <?php if ($selected_genre > 0): ?>
                    <!-- Create Thread Section -->
                    <section class="create-thread-section">
                        <h2><i class="fas fa-plus-circle"></i> Create Thread</h2>
                        <form method="post" class="thread-form">
                            <input type="hidden" name="genre_id" value="<?= $selected_genre ?>">
                            <div class="form-group">
                                <label for="title"><i class="fas fa-heading"></i> Title</label>
                                <input type="text" name="title" id="title" placeholder="Thread title" required>
                            </div>
                            <div class="form-group">
                                <label for="content"><i class="fas fa-align-left"></i> Content</label>
                                <textarea name="content" id="content" rows="4" placeholder="Start the discussion..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Post Thread
                            </button>
                        </form>
                    </section>
                <?php endif; ?>
            </aside>

            <!-- Main Discussion Area -->
            <main class="main-discussion">
                <?php if ($selected_genre > 0): ?>
                    <section class="threads-section">
                        <h2><i class="fas fa-list"></i> Discussion Threads</h2>
                        <?php if (!empty($threads)): ?>
                            <div class="threads-list">
                                <?php foreach ($threads as $thread): ?>
                                    <article class="thread-card">
                                        <div class="thread-header">
                                            <div class="thread-info">
                                                <h3><?= htmlspecialchars($thread['title']) ?></h3>
                                                <span class="content-id">ID: <?= $thread['discussion_id'] ?></span>
                                            </div>
                                            <div class="thread-meta">
                                                <span class="author">
                                                    <i class="fas fa-user"></i> <?= htmlspecialchars($thread['username']) ?>
                                                </span>
                                                <span class="date">
                                                    <i class="fas fa-clock"></i> <?= $thread['posted_at'] ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="thread-content">
                                            <p><?= nl2br(htmlspecialchars($thread['content'])) ?></p>
                                        </div>

                                        <div class="thread-actions">
                                            <form method="post" class="like-form">
                                                <input type="hidden" name="like_discussion_id" value="<?= $thread['discussion_id'] ?>">
                                                <input type="hidden" name="genre_id" value="<?= $selected_genre ?>">
                                                <button type="submit" name="is_like" value="1" class="btn btn-like">
                                                    <i class="fas fa-thumbs-up"></i> <?= $thread['likes'] ?? 0 ?>
                                                </button>
                                                <button type="submit" name="is_like" value="0" class="btn btn-dislike">
                                                    <i class="fas fa-thumbs-down"></i> <?= $thread['dislikes'] ?? 0 ?>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Replies Section -->
                                        <div class="replies-section">
                                            <h4><i class="fas fa-reply"></i> Replies</h4>
                                            <?php 
                                                $replies = $discussionModel->getReplies($thread['discussion_id']); 
                                                if (!empty($replies)):
                                            ?>
                                                <div class="replies-list">
                                                    <?php foreach ($replies as $reply): ?>
                                                        <div class="reply-item">
                                                            <div class="reply-author">
                                                                <i class="fas fa-user-circle"></i>
                                                                <strong><?= htmlspecialchars($reply['username']) ?></strong>
                                                                <span class="reply-date"><?= $reply['replied_at'] ?></span>
                                                            </div>
                                                            <p class="reply-content"><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="no-replies">No replies yet. Be the first to reply!</p>
                                            <?php endif; ?>

                                            <!-- Reply Form -->
                                            <form method="post" class="reply-form">
                                                <input type="hidden" name="reply_discussion_id" value="<?= $thread['discussion_id'] ?>">
                                                <input type="hidden" name="genre_id" value="<?= $selected_genre ?>">
                                                <div class="reply-input">
                                                    <textarea name="reply_content" rows="2" placeholder="Write a reply..." required></textarea>
                                                    <button type="submit" class="btn btn-reply">
                                                        <i class="fas fa-paper-plane"></i> Reply
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="no-content">
                                <i class="fas fa-comments"></i>
                                <h3>No Threads Yet</h3>
                                <p>Be the first to start a discussion in this genre!</p>
                            </div>
                        <?php endif; ?>
                    </section>
                <?php else: ?>
                    <div class="welcome-section">
                        <div class="welcome-content">
                            <i class="fas fa-arrow-left"></i>
                            <h2>Welcome to Discussion Channel</h2>
                            <p>Select a genre from the sidebar to start exploring discussions or create a new thread!</p>
                        </div>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</body>
</html>
