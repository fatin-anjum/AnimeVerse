<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debates - AnimeVerse</title>
    <link rel="stylesheet" href="css/debate.css">
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
            <a href="?page=polldebate"><i class="fas fa-chart-bar"></i> Polls</a>
            <a href="?page=debate" class="active"><i class="fas fa-balance-scale"></i> Debates</a>
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

    <div class="debate-container">
        <!-- Page Header -->
        <header class="page-header">
            <h1><i class="fas fa-balance-scale"></i> Anime Debates</h1>
            <p>Engage in thoughtful debates and share your perspective on anime topics</p>
        </header>

        <?php if(!empty($message)): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <!-- Create Debate Sidebar -->
            <aside class="create-sidebar">
                <section class="create-section">
                    <h2><i class="fas fa-plus-circle"></i> Create Debate</h2>
                    <form method="post" class="create-form">
                        <div class="form-group">
                            <label for="debate_title"><i class="fas fa-heading"></i> Debate Topic</label>
                            <input type="text" name="debate_title" id="debate_title" placeholder="Enter debate topic" required>
                        </div>
                        <div class="form-group">
                            <label for="debate_content"><i class="fas fa-align-left"></i> Your Argument</label>
                            <textarea name="debate_content" id="debate_content" placeholder="Present your argument" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Debate
                        </button>
                    </form>
                </section>

                <!-- Quick Links -->
                <section class="quick-links">
                    <h3><i class="fas fa-link"></i> Quick Links</h3>
                    <div class="links-list">
                        <a href="?page=polldebate" class="link-item">
                            <i class="fas fa-chart-bar"></i> View Polls
                        </a>
                        <a href="?page=discussion" class="link-item">
                            <i class="fas fa-comments"></i> Discussions
                        </a>
                        <a href="?page=fanart" class="link-item">
                            <i class="fas fa-palette"></i> Fan Art
                        </a>
                    </div>
                </section>
            </aside>

            <!-- Debates List -->
            <main class="main-content">
                <section class="debates-section">
                    <h2><i class="fas fa-balance-scale"></i> Active Debates</h2>
                    <?php if (!empty($debates)): ?>
                        <div class="debates-list">
                            <?php foreach($debates as $debate): ?>
                                <article class="debate-card">
                                    <div class="debate-header">
                                        <h3><?= htmlspecialchars($debate['title']) ?></h3>
                                        <span class="content-id">Debate ID: <?= $debate['debate_id'] ?></span>
                                    </div>
                                    
                                    <div class="original-argument">
                                        <div class="argument-author">
                                            <i class="fas fa-user-circle"></i>
                                            <strong><?= htmlspecialchars($debate['username']) ?></strong>
                                            <span class="argument-label">Original Argument</span>
                                        </div>
                                        <p><?= nl2br(htmlspecialchars($debate['content'])) ?></p>
                                    </div>
                                    
                                    <?php $replies = $model->getDebateReplies($debate['debate_id']); ?>
                                    <?php if (!empty($replies)): ?>
                                        <div class="debate-replies">
                                            <h4><i class="fas fa-comments"></i> Arguments & Counter-arguments</h4>
                                            <div class="replies-list">
                                                <?php foreach($replies as $rep): ?>
                                                    <div class="reply-item">
                                                        <div class="reply-header">
                                                            <div class="reply-author">
                                                                <i class="fas fa-user-circle"></i>
                                                                <strong><?= htmlspecialchars($rep['username']) ?></strong>
                                                            </div>
                                                            <div class="reply-votes">
                                                                <span class="vote-count"><?= $rep['votes'] ?> votes</span>
                                                                <form method="post" class="vote-form">
                                                                    <input type="hidden" name="vote_reply_id" value="<?= $rep['reply_id'] ?>">
                                                                    <button type="submit" name="vote_type" value="up" class="btn btn-vote-up">
                                                                        <i class="fas fa-thumbs-up"></i>
                                                                    </button>
                                                                    <button type="submit" name="vote_type" value="down" class="btn btn-vote-down">
                                                                        <i class="fas fa-thumbs-down"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <p class="reply-content"><?= nl2br(htmlspecialchars($rep['content'])) ?></p>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="no-replies">
                                            <i class="fas fa-balance-scale"></i>
                                            <p>No counter-arguments yet. Be the first to respond!</p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="add-argument">
                                        <h4><i class="fas fa-plus"></i> Add Your Argument</h4>
                                        <form method="post" class="argument-form">
                                            <input type="hidden" name="debate_id" value="<?= $debate['debate_id'] ?>">
                                            <div class="argument-input">
                                                <textarea name="debate_reply_content" placeholder="Present your argument or counter-argument..." rows="3" required></textarea>
                                                <button type="submit" class="btn btn-argument">
                                                    <i class="fas fa-paper-plane"></i> Submit Argument
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-content">
                            <i class="fas fa-balance-scale"></i>
                            <h3>No Debates Yet</h3>
                            <p>Be the first to start a debate!</p>
                        </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>