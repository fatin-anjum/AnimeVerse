<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Polls - AnimeVerse</title>
    <link rel="stylesheet" href="css/poll.css">
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
            <a href="?page=polldebate" class="active"><i class="fas fa-chart-bar"></i> Polls</a>
            <a href="?page=debate"><i class="fas fa-balance-scale"></i> Debates</a>
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

    <div class="poll-container">
        <!-- Page Header -->
        <header class="page-header">
            <h1><i class="fas fa-chart-bar"></i> Anime Polls</h1>
            <p>Vote on anime-related polls and see what the community thinks</p>
        </header>

        <?php if(!empty($message)): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="content-grid">
            <!-- Create Poll Sidebar -->
            <aside class="create-sidebar">
                <section class="create-section">
                    <h2><i class="fas fa-plus-circle"></i> Create Poll</h2>
                    <form method="post" class="create-form">
                        <div class="form-group">
                            <label for="poll_title"><i class="fas fa-heading"></i> Poll Title</label>
                            <input type="text" name="poll_title" id="poll_title" placeholder="Enter poll title" required>
                        </div>
                        <div class="form-group">
                            <label for="poll_desc"><i class="fas fa-align-left"></i> Description</label>
                            <textarea name="poll_desc" id="poll_desc" placeholder="Describe your poll" rows="3"></textarea>
                        </div>
                        <div class="options-group">
                            <label><i class="fas fa-list"></i> Options</label>
                            <?php for($i=1;$i<=$model->getMaxOptions();$i++): ?>
                                <input type="text" name="poll_options[]" placeholder="Option <?= $i ?>" <?= $i<=2?'required':'' ?>>
                            <?php endfor; ?>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Poll
                        </button>
                    </form>
                </section>

                <!-- Quick Links -->
                <section class="quick-links">
                    <h3><i class="fas fa-link"></i> Quick Links</h3>
                    <div class="links-list">
                        <a href="?page=debate" class="link-item">
                            <i class="fas fa-balance-scale"></i> View Debates
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

            <!-- Polls List -->
            <main class="main-content">
                <section class="polls-section">
                    <h2><i class="fas fa-chart-pie"></i> Active Polls</h2>
                    <?php if (!empty($polls)): ?>
                        <div class="polls-grid">
                            <?php foreach($polls as $poll): ?>
                                <article class="poll-card">
                                    <div class="poll-header">
                                        <h3><?= htmlspecialchars($poll['title']) ?></h3>
                                        <span class="content-id">Poll ID: <?= $poll['poll_id'] ?></span>
                                    </div>
                                    
                                    <?php if(!empty($poll['description'])): ?>
                                        <div class="poll-description">
                                            <p><?= htmlspecialchars($poll['description']) ?></p>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="poll-options">
                                        <form method="post" class="vote-form">
                                            <?php foreach($model->getPollOptions($poll['poll_id']) as $opt): ?>
                                                <button type="submit" name="vote_option" value="<?= $opt['option_id'] ?>" class="option-btn">
                                                    <span class="option-text"><?= htmlspecialchars($opt['option_text']) ?></span>
                                                    <span class="vote-count"><?= $model->getPollVotes($opt['option_id']) ?> votes</span>
                                                </button>
                                            <?php endforeach; ?>
                                        </form>
                                    </div>
                                    
                                    <div class="poll-footer">
                                        <span class="creator">
                                            <i class="fas fa-user"></i> <?= htmlspecialchars($poll['username']) ?>
                                        </span>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-content">
                            <i class="fas fa-poll"></i>
                            <h3>No Polls Yet</h3>
                            <p>Be the first to create a poll!</p>
                        </div>
                    <?php endif; ?>
                </section>
            </main>
        </div>
    </div>
</body>
</html>